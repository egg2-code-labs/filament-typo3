<?php

declare(strict_types=1);

use Egg2CodeLabs\FilamentTypo3\Database\Builders\Typo3AccessBuilder;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function makeQueryBuilder(): QueryBuilder
{
    $connection = Mockery::mock(Connection::class);
    $connection->shouldReceive('getQueryGrammar')->andReturn(new Grammar());
    $connection->shouldReceive('getPostProcessor')->andReturn(new Processor());
    $connection->shouldReceive('select')->andReturn([]);
    $connection->shouldReceive('getTablePrefix')->andReturn('');

    return new QueryBuilder($connection, new Grammar(), new Processor());
}

function makeModel(string $table = 'pages'): Model
{
    return new class($table) extends Model {
        public function __construct(private readonly string $tableName)
        {
            parent::__construct();
        }

        public function getTable(): string
        {
            return $this->tableName;
        }
    };
}

function makeBuilder(string $table = 'pages'): Typo3AccessBuilder
{
    $builder = new Typo3AccessBuilder(makeQueryBuilder());
    $builder->setModel(makeModel($table));

    return $builder;
}

// ---------------------------------------------------------------------------
// whereNotHidden
// ---------------------------------------------------------------------------

it('whereNotHidden adds a hidden = false where clause', function (): void {
    $builder = makeBuilder('pages');
    $builder->whereNotHidden();

    expect($builder->toSql())->toContain('"pages"."hidden" = ?');
    expect($builder->getBindings())->toContain(false);
});

it('whereNotHidden respects an explicit table override', function (): void {
    $builder = makeBuilder('pages');
    $builder->whereNotHidden('other_table');

    expect($builder->toSql())->toContain('"other_table"."hidden" = ?');
    expect($builder->getBindings())->toContain(false);
});

// ---------------------------------------------------------------------------
// whereWithinStarttime
// ---------------------------------------------------------------------------

it('whereWithinStarttime adds a nullable or past starttime constraint', function (): void {
    $builder = makeBuilder('pages');
    $builder->whereWithinStarttime();

    $sql = $builder->toSql();

    expect($sql)
        ->toContain('"pages"."starttime" is null')
        ->toContain('"pages"."starttime" <= ?');
});

// ---------------------------------------------------------------------------
// whereWithinEndtime
// ---------------------------------------------------------------------------

it('whereWithinEndtime adds a nullable or future endtime constraint', function (): void {
    $builder = makeBuilder('pages');
    $builder->whereWithinEndtime();

    $sql = $builder->toSql();

    expect($sql)
        ->toContain('"pages"."endtime" is null')
        ->toContain('"pages"."endtime" >= ?');
});

// ---------------------------------------------------------------------------
// orderBySorting
// ---------------------------------------------------------------------------

it('orderBySorting adds an ascending sorting order', function (): void {
    $builder = makeBuilder('pages');
    $builder->orderBySorting();

    $sql = $builder->toSql();

    expect($sql)->toContain('"pages"."sorting" asc');
});

it('orderBySorting respects an explicit table override', function (): void {
    $builder = makeBuilder('pages');
    $builder->orderBySorting('other_table');

    $sql = $builder->toSql();

    expect($sql)->toContain('"other_table"."sorting" asc');
});

// ---------------------------------------------------------------------------
// applyTypo3Access — convenience method
// ---------------------------------------------------------------------------

it('applyTypo3Access applies all constraints by default', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access();

    $sql = $builder->toSql();

    expect($sql)
        ->toContain('"pages"."hidden" = ?')
        ->toContain('"pages"."starttime" is null')
        ->toContain('"pages"."starttime" <= ?')
        ->toContain('"pages"."endtime" is null')
        ->toContain('"pages"."endtime" >= ?')
        ->toContain('"pages"."sorting" asc');
    expect($builder->getBindings())->toContain(false);
});

it('applyTypo3Access skips hidden constraint when disableHidden is true', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access(disableHidden: true);

    $sql = $builder->toSql();

    expect($sql)->not->toContain('"pages"."hidden"');
    expect($sql)->toContain('"pages"."starttime" is null');
    expect($sql)->toContain('"pages"."endtime" is null');
});

it('applyTypo3Access skips starttime constraint when disableStarttime is true', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access(disableStarttime: true);

    $sql = $builder->toSql();

    expect($sql)->not->toContain('"pages"."starttime"');
    expect($sql)->toContain('"pages"."hidden" = ?');
    expect($sql)->toContain('"pages"."endtime" is null');
});

it('applyTypo3Access skips endtime constraint when disableEndtime is true', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access(disableEndtime: true);

    $sql = $builder->toSql();

    expect($sql)->not->toContain('"pages"."endtime"');
    expect($sql)->toContain('"pages"."hidden" = ?');
    expect($sql)->toContain('"pages"."starttime" is null');
});

it('applyTypo3Access skips sorting when sorting is false', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access(sorting: false);

    $sql = $builder->toSql();

    expect($sql)->not->toContain('"pages"."sorting"');
    expect($sql)->toContain('"pages"."hidden" = ?');
});

it('applyTypo3Access can disable all constraints', function (): void {
    $builder = makeBuilder('pages');
    $builder->applyTypo3Access(
        disableHidden: true,
        disableStarttime: true,
        disableEndtime: true,
        sorting: false,
    );

    $sql = $builder->toSql();

    expect($sql)
        ->toBe('select * from "pages"');
});

it('applyTypo3Access returns the builder instance for chaining', function (): void {
    $builder = makeBuilder('pages');

    expect($builder->applyTypo3Access())->toBe($builder);
});
