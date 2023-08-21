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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            // $table->longText('url')->nullable();
            // $table->json('img_attributes')->nullable();
            $table->string('name');
            $table->longText('back_body')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('front')->default(1);
            $table->unsignedBigInteger('sort')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
