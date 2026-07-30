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

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 128)->unique();
            $table->string('name');
            $table->string('full_name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')
                ->constrained('categories', 'id', 'fk_category_id')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->text('content')->nullable();
            $table->timestamps();
            $table->fullText('content');
        });

        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->foreignId('product_id')
                ->constrained('products', 'id', 'fk_price_lists_product_id')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->decimal('price', 15, 2);
            $table->timestamps();
            $table->unique(['product_id', 'date'], 'unq_price_list_product_date');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products', 'id', 'fk_product_images_product_id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('disk');
            $table->string('path');
            $table->string('original_filename');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->string('alt_text')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('default_image_id')
                ->nullable()
                ->after('category_id')
                ->constrained('product_images', 'id', 'fk_products_default_image_id')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('fk_products_default_image_id');
            $table->dropColumn('default_image_id');
        });

        Schema::dropIfExists('product_images');
        Schema::dropIfExists('price_lists');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
