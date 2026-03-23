<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'bookmarks')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->json('bookmarks')->nullable()->after('remember_token');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'bookmarks')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('bookmarks');
            });
        }
    }
};
