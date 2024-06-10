<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductVariantsAndUpgradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('online');
            $table->boolean('default')->default(0);
            $table->string('url')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('channelables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->unsignedBigInteger('variant_id');
            $table->boolean('enabled')->default(0);
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->boolean('default')->default(0);
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('user');
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('line_one');
            $table->string('line_two')->nullable();
            $table->string('line_three')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('country_code', 5)->nullable();
            $table->string('postcode')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->json('meta')->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('default')->default(0);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id');
            $table->string('taggable_type');
            $table->unsignedBigInteger('taggable_id');
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('type')->nullable();
            $table->boolean('default')->default(0);
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();
        });

        Schema::rename('product_options', 'old_product_options');

        Schema::table('old_product_options', function (Blueprint $table) {
            if (!Schema::hasColumn('old_product_options', 'value_id')) {
                $table->unsignedBigInteger('value_id')->nullable();
            }
            if (!Schema::hasColumn('old_product_options', 'price')) {
                $table->double('price')->nullable();
            }
        });

        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('type')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->timestamps();
        });

        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_option_id');
            $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('product_option_value_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('value_id');
            $table->unsignedBigInteger('variant_id');
            $table->timestamps();
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tax_class_id')->nullable();
            $table->json('options')->nullable();
            $table->unsignedInteger('unit_qty')->default(1);
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('gtin')->nullable();
            $table->string('mpn')->nullable();
            $table->decimal('length', 10, 4)->nullable()->default(0.0000);
            $table->decimal('width', 10, 4)->nullable()->default(0.0000);
            $table->decimal('height', 10, 4)->nullable()->default(0.0000);
            $table->string('package_unit')->nullable()->default("mm");
            $table->decimal('weight', 10, 4)->nullable()->default(0.0000);
            $table->string('weight_unit')->nullable()->default("mm");
            $table->decimal('volume', 10, 4)->nullable()->default(0.0000);
            $table->string('volume_unit')->nullable()->default("mm");
            $table->string('type')->default('physical');
            $table->boolean('shippable')->default(true)->index();
            $table->double('avg_rating')->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('virtual')->default(0);
            $table->boolean('in_stock')->default(0);
            $table->unsignedBigInteger('dropshipper_variant_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::rename('prices', 'old_prices');

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variant_id');
            $table->string('currency_code')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('compare_price')->nullable();
            $table->unsignedInteger('offer_price')->nullable();
            $table->unsignedInteger('selling_price')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamps();
        });

        Schema::create('product_associations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_target_id');
            $table->string('type');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->default('physical')->change();
            if (Schema::hasColumn('products', 'supplier_product_id')) {
                $table->dropColumn('supplier_product_id');
            }
            if (Schema::hasColumn('products', 'is_admin')) {
                $table->dropColumn('is_admin');
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('reviews', 'approved')) {
                $table->boolean('approved')->default(false)->after('product_id');
            }
            if (!Schema::hasColumn('reviews', 'supplier_term_id')) {
                $table->dropColumn('supplier_term_id');
            }
        });

        Schema::rename('stocks', 'old_stocks');

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->boolean('manage_stock')->default(1);
            $table->unsignedSmallInteger('status')->default(1);
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedBigInteger('variant_id')->unsigned();
            $table->unsignedInteger('alert_qty')->unsigned()->nullable();
            $table->unsignedBigInteger('location_id')->unsigned()->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->morphs('page');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('meta')->nullable();
            $table->string('page')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable();
            }
        });

        Schema::create('dropshipping_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->unsignedBigInteger('supplier_variant_id')->nullable();
            $table->string('supplier')->default('singlebg');
            $table->text('meta')->nullable();
            $table->unsignedSmallInteger('status')->default(1);
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
        Schema::dropIfExists('channels');
        Schema::dropIfExists('channelables');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('prices');
        Schema::rename('old_prices', 'prices');
        Schema::dropIfExists('product_associations');
        Schema::dropIfExists('product_options');
        Schema::dropIfExists('product_option_values');
        Schema::rename('old_product_options', 'product_options');
        Schema::dropIfExists('product_option_value_variants');
        Schema::table('product_options', function (Blueprint $table) {
            if (Schema::hasColumn('product_options', 'value_id')) {
                $table->dropColumn('value_id');
            }
            if (Schema::hasColumn('product_options', 'price')) {
                $table->dropColumn('price');
            }
        });
        Schema::dropIfExists('stocks');
        Schema::rename('old_stocks', 'stocks');
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'supplier_term_id')) {
                $table->unsignedBigInteger('supplier_term_id')->nullable()->after('product_id');
            }
            if (Schema::hasColumn('reviews', 'variant_id')) {
                $table->dropColumn('variant_id');
            }
        });
        Schema::dropIfExists('seo');
        Schema::dropIfExists('dropshipping_products');
    }
}
