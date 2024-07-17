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
        Schema::create('product_catalogue_language', function (Blueprint $table) {
        $table->bigInteger('product_catalogue_id')->unsigned();
        $table->foreign('product_catalogue_id')->references('id')->on('product_catalogues')->onDelete('cascade');
        $table->bigInteger('language_id')->unsigned();
        $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->string('canonical')->nullable()->unique();
        $table->longText('content')->nullable();
        $table->string('meta_title')->nullable();
        $table->string('meta_keyword')->nullable();
        $table->text('meta_description')->nullable();
        $table->timestamp('deleted_at')->nullable();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_catalogue_language');
    }
};
