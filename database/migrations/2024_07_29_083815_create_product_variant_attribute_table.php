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
        Schema::create('product_variant_attribute', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_variant_id')->unsigned();
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
            $table->bigInteger('attribute_id')->unsigned();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_attribute');
    }
};
