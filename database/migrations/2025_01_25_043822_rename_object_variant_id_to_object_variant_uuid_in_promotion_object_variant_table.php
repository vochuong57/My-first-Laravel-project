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
            // Thêm cột mới object_variant_uuid
            $table->string('object_variant_uuid', 36)->nullable()->unique();
        });

        // Sao chép dữ liệu từ cột object_variant_id sang cột object_variant_uuid
        DB::statement('UPDATE promotion_object_variant SET object_variant_uuid = object_variant_id');

        Schema::table('promotion_object_variant', function (Blueprint $table) {
            // Xóa cột object_variant_id
            $table->dropColumn('object_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_object_variant', function (Blueprint $table) {
            // Thêm lại cột object_variant_id
            $table->bigInteger('object_variant_id')->unsigned()->nullable();
        });

        // Sao chép dữ liệu từ cột object_variant_uuid sang cột object_variant_id
        DB::statement('UPDATE promotion_object_variant SET object_variant_id = object_variant_uuid');

        Schema::table('promotion_object_variant', function (Blueprint $table) {
            // Xóa cột object_variant_uuid
            $table->dropColumn('object_variant_uuid');
        });
    }
};
