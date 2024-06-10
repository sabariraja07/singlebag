<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->string('customer_id')->nullable()->after('id')->change();
            $table->tinyInteger('new_customer')->default(0)->after('customer_id');
            $table->string('channel')->nullable()->after('new_customer');
            $table->json('discount_breakdown')->nullable()->after('discount');
            $table->unsignedBigInteger('shipping_breakdown')->nullable()->after('shipping');
            $table->json('tax_breakdown')->after('tax');
            $table->text('notes')->nullable()->after('tax_breakdown');
            $table->string('currency_code', 3)->after('notes');
            $table->string('compare_currency_code', 3)->nullable()->after('currency_code');
            $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('compare_currency_code');
            $table->datetime('placed_at')->nullable()->after('exchange_rate');
            $table->json('meta')->nullable()->after('placed_at');
        });

        Schema::rename('order_items', 'order_lines');

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('option')->nullable();
            $table->unsignedBigInteger('unit_price');
            $table->unsignedSmallInteger('unit_quantity')->default(1);
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('sub_total');
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->json('tax_breakdown');
            $table->unsignedBigInteger('tax_total');
            $table->unsignedBigInteger('shipping_total')->default(0);
            $table->unsignedBigInteger('total');
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('country_code')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('line_one')->nullable();
            $table->string('line_two')->nullable();
            $table->string('line_three')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('postcode')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('type')->default('shipping');
            $table->string('shipping_option')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::dropIfExists('transactions');

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->tinyInteger('success');
            $table->enum('type', ['refund', 'intent', 'capture'])->default('capture');
            $table->string('gateway');
            $table->string('currency_code', 3);
            $table->unsignedInteger('amount');
            $table->string('reference')->nullable();
            $table->string('status');
            $table->string('notes')->nullable();
            $table->text('log')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->datetime('captured_at')->nullable();
        });

        Schema::create('settlement_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('settlement_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->tinyInteger('success');
            $table->string('currency_code', 3);
            $table->unsignedInteger('amount');
            $table->string('status');
            $table->string('notes')->nullable();
            $table->text('log')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->datetime('captured_at')->nullable();
        });

        Schema::create('tax_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('based_on')->default('shipping');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('tax_class_id')->unsigned()->index();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->string('country_code')->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('zipcode');
            $table->decimal('rate', 8, 4)->unsigned();
            $table->integer('position')->unsigned();
            $table->boolean('status')->default(0);
            $table->timestamps();

            // $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onDelete('cascade');
        });

        Schema::table('shipping_locations', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('shipping_method_id');
            $table->string('country')->after('country_code');
            $table->string('state')->after('country');
            $table->string('city')->after('state');
            $table->decimal('rate', 8, 4)->unsigned()->after('city');
            $table->integer('estimated_days')->unsigned()->after('rate');
            $table->string('estimate_info')->nullable()->after('estimated_days');
            $table->integer('position')->unsigned()->after('zipcode');

            // $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropColumn('customer_id');
            $table->dropColumn('new_customer');
            $table->dropColumn('channel');
            $table->dropColumn('discount_breakdown');
            $table->dropColumn('shipping_breakdown');
            $table->dropColumn('tax_breakdown');
            $table->dropColumn('notes');
            $table->dropColumn('currency_code');
            $table->dropColumn('compare_currency_code');
            $table->dropColumn('exchange_rate');
            $table->dropColumn('placed_at');
            $table->dropColumn('meta');
        });

        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_addresses');
        Schema::rename('order_lines', 'order_items');
        Schema::dropIfExists('settlement_items');
        Schema::dropIfExists('tax_classes');
        Schema::dropIfExists('tax_rates');

        Schema::table('shipping_locations', function (Blueprint $table) {
            if (Schema::hasColumn('shipping_locations', 'country_code'))
                $table->dropColumn('country_code');
            if (Schema::hasColumn('shipping_locations', 'country'))
                $table->dropColumn('country');
            if (Schema::hasColumn('shipping_locations', 'state'))
                $table->dropColumn('state');
            if (Schema::hasColumn('shipping_locations', 'city'))
                $table->dropColumn('city');
            if (Schema::hasColumn('shipping_locations', 'rate'))
                $table->dropColumn('rate');
            if (Schema::hasColumn('shipping_locations', 'estimated_days'))
                $table->dropColumn('estimated_days');
            if (Schema::hasColumn('shipping_locations', 'estimate_info'))
                $table->dropColumn('estimate_info');
            if (Schema::hasColumn('shipping_locations', 'position'))
                $table->dropColumn('position');
        });
    }
}
