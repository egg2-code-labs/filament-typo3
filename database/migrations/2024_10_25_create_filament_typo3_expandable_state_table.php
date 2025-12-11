<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public const TABLE_NAME = 'filament_typo3_expandable_state';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $keyType = config('filament-typo3.migrations.keyType');

        Schema::create(self::TABLE_NAME, function (Blueprint $table) use ($keyType): void {
            $table->{$keyType}('id')->primary;

            $table->string('user_id');
            $table->morphs('expandable');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
