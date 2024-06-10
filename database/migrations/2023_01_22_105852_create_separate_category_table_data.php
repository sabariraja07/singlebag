<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparateCategoryTableData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::rename('attributes', 'attribute');
        Schema::rename('terms', 'products');
        Schema::rename('term_options', 'product_options');

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedTinyInteger('featured')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('product_attributes', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('attribute_id')->constrained('attributes');
            // $table->foreignId('variation_id')->constrained('attributes');
            $table->foreignId('product_id')->constrained('products');
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedTinyInteger('featured')->default(0);
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('code')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->decimal('amount')->default(0);
            $table->boolean('discount_type')->default(0);
            $table->text('meta')->nullable();
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('url')->nullable();
            $table->text('meta')->nullable();
            $table->string('position')->nullable();
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('btn_text')->nullable();
            $table->string('url')->nullable();
            $table->text('meta')->nullable();
            $table->string('position')->nullable();
            $table->string('type')->nullable();
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->constrained('shops');
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->longtext('content')->nullable();
            $table->text('meta')->nullable();
            $table->string('keywords', 1000)->nullable();
            $table->string('type');
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('shop_id')->nullable()->constrained('shops');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->mediumText('short_description')->nullable()->after('slug');
            $table->longText('description')->nullable()->after('short_description');
            $table->string('video_provider', 20)->nullable()->after('type');
            $table->string('video_url', 500)->nullable()->after('video_provider');
            $table->foreignId('brand_id')->nullable()->after('video_url')->constrained('brands');
            $table->double('avg_rating')->nullable()->after('brand_id');
            $table->longText('seo')->nullable()->after('brand_id');
            $table->string('affiliate', 500)->nullable()->after('brand_id');
            $table->double('tax')->nullable()->after('affiliate');
            $table->unsignedTinyInteger('tax_type')->default(0)->after('tax');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('meta')->nullable();
            $table->unsignedSmallInteger('status')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });

        Schema::table('gateways', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->index()->after('category_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->index()->after('category_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->index()->after('plan_id');
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('product_id')->constrained('products');
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign('files_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('metas', function (Blueprint $table) {
            $table->dropForeign('metas_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->dropForeign('term_options_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign('stocks_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->dropForeign('prices_term_id_foreign');
            $table->renameColumn('term_id', 'product_id');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
        // product_options	supplier_term_id
        // products	supplier_term_id
        // reviews	supplier_term_id

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
        Schema::rename('attribute', 'attributes');
        Schema::rename('products', 'terms');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('tax');
        Schema::dropIfExists('tax_id');

        Schema::table('gateways', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('short_description');
            $table->dropColumn('description');
            $table->dropColumn('video_provider');
            $table->dropColumn('video_url');
            $table->dropColumn('avg_rating');
            $table->dropColumn('affiliate');
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
        Schema::dropIfExists('product_categories');

        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign('files_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::table('metas', function (Blueprint $table) {
            $table->dropForeign('metas_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign('order_items_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->dropForeign('product_options_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign('reviews_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign('stocks_product_id_foreign');
            $table->renameColumn('product_id', 'term_id');

            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });
    }
}
