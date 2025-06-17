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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->integer('level')->default(0);
            $table->string('reviewable_type');
            $table->integer('reviewable_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('fullname');
            $table->string('email');
            $table->string('phone', 12);
            $table->string('gender', 10);
            $table->text('description');
            $table->integer('score');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
