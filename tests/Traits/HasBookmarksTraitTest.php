<?php

use Egg2CodeLabs\FilamentTypo3\Traits\HasBookmarksTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * A minimal stub User model that uses HasBookmarksTrait for testing.
 */
class BookmarksTestUser extends Model
{
    use HasBookmarksTrait;

    protected $table = 'users';

    protected $guarded = [];
}

beforeEach(function (): void {
    // Create the users table with a bookmarks column for testing
    \Illuminate\Support\Facades\Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table): void {
        $table->id();
        $table->string('name')->default('Test User');
        $table->string('email')->default('test@example.com');
        $table->string('password')->default('secret');
        $table->json('bookmarks')->nullable();
        $table->timestamps();
    });
});

afterEach(function (): void {
    \Illuminate\Support\Facades\Schema::dropIfExists('users');
});

it('initializes bookmarks cast automatically', function (): void {
    $user = new BookmarksTestUser();
    expect($user->getCasts())->toHaveKey('bookmarks');
});

it('returns empty array when no bookmarks are set', function (): void {
    $user = BookmarksTestUser::create([]);
    expect($user->getBookmarks())->toBe([]);
});

it('can add a bookmark', function (): void {
    $user = BookmarksTestUser::create([]);

    $user->addBookmark('https://example.com/posts/1', 'First Post');

    $user->refresh();
    expect($user->getBookmarks())->toBe(['https://example.com/posts/1' => 'First Post']);
});

it('can add multiple bookmarks', function (): void {
    $user = BookmarksTestUser::create([]);

    $user->addBookmark('https://example.com/posts/1', 'First Post');
    $user->addBookmark('https://example.com/posts/2', 'Second Post');

    $user->refresh();
    expect($user->getBookmarks())->toBe([
        'https://example.com/posts/1' => 'First Post',
        'https://example.com/posts/2' => 'Second Post',
    ]);
});

it('can remove a bookmark', function (): void {
    $user = BookmarksTestUser::create([
        'bookmarks' => ['https://example.com/posts/1' => 'First Post'],
    ]);

    $user->removeBookmark('https://example.com/posts/1');

    $user->refresh();
    expect($user->getBookmarks())->toBe([]);
});

it('silently ignores removing a non-existent bookmark', function (): void {
    $user = BookmarksTestUser::create([
        'bookmarks' => ['https://example.com/posts/1' => 'First Post'],
    ]);

    $user->removeBookmark('https://example.com/does-not-exist');

    $user->refresh();
    expect($user->getBookmarks())->toHaveCount(1);
});

it('can detect whether a bookmark exists', function (): void {
    $user = BookmarksTestUser::create([
        'bookmarks' => ['https://example.com/posts/1' => 'First Post'],
    ]);

    expect($user->hasBookmark('https://example.com/posts/1'))->toBeTrue();
    expect($user->hasBookmark('https://example.com/other'))->toBeFalse();
});

it('updating an existing url replaces the label', function (): void {
    $user = BookmarksTestUser::create([
        'bookmarks' => ['https://example.com/posts/1' => 'Old Label'],
    ]);

    $user->addBookmark('https://example.com/posts/1', 'New Label');

    $user->refresh();
    expect($user->getBookmarks())->toBe(['https://example.com/posts/1' => 'New Label']);
    expect($user->getBookmarks())->toHaveCount(1);
});
