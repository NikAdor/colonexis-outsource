<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'client', 'staff', 'admin') NOT NULL DEFAULT 'client'");
        }

        DB::table('users')->where('role', 'user')->update(['role' => 'client']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'staff', 'admin') NOT NULL DEFAULT 'client'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'client', 'staff', 'admin') NOT NULL DEFAULT 'user'");
        }

        DB::table('users')->where('role', 'client')->update(['role' => 'user']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'staff', 'admin') NOT NULL DEFAULT 'user'");
        }
    }
};
