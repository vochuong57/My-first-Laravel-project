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
        Schema::table('promotion_object_variant', function (Blueprint $table) {
            // Thay đổi cột object_variant_uuid từ unique về nullable
            $table->dropUnique(['object_variant_uuid']);
            $table->string('object_variant_uuid', 36)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_object_variant', function (Blueprint $table) {
            // Thay đổi cột object_variant_uuid về lại unique
            $table->string('object_variant_uuid', 36)->nullable(false)->unique()->change();
        });
    }
};
