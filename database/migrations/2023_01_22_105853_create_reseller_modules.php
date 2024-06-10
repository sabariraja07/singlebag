<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_products', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();
            $table->integer('amount_type')->default(1);// 0 = percent, 1= fixed
            $table->longText('seo')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedTinyInteger('featured')->default(0);
            $table->foreignId('brand_id')->nullable()->constrained('brands');
            $table->foreignId('category_id')->nullable()->constrained('categories'); 
            $table->foreignId('product_id')->constrained('products');
            $table->unsignedBigInteger('group_product_id')->nullable();
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('reseller_options', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->double('price')->nullable();
            $table->double('amount')->nullable();
            $table->integer('amount_type')->default(1);// 0 = percent, 1= fixed
            $table->foreignId('product_option_id')->constrained('product_options');
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reseller_products');
        Schema::dropIfExists('reseller_options');
    }
}
