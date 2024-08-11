<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menu_language', function (Blueprint $table) {
            // Loại bỏ unique index trên cột canonical
            $table->dropUnique(['canonical']);
            // Đảm bảo rằng cột canonical có thể null
            $table->string('canonical')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_language', function (Blueprint $table) {
            // Đảm bảo rằng cột canonical là unique và null
            $table->string('canonical')->nullable()->unique()->change();
        });
    }
};
