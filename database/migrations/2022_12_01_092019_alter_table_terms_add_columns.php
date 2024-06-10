<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableTermsAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_term_id')->nullable()->after('shop_id');
        });
        Schema::table('term_options', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_term_id')->nullable()->after('term_id');
            $table->unsignedBigInteger('margin')->nullable()->after('is_required');
            $table->string('margin_type')->nullable()->after('select_type');
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedBigInteger('margin')->nullable()->after('special_price');
            $table->string('margin_type')->nullable()->after('price_type');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('reseller_order_id')->nullable()->after('customer_id');
        });
        Schema::table('settlements', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_id')->after('user_id')->nullable();
            $table->foreign(['shop_id'])->references(['id'])->on('shops')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->double('total_amount')->nullable()->after('amount');
            $table->double('charge')->nullable()->after('total_amount');
            $table->double('settlement_rate')->nullable()->after('charge');
            $table->string('interval')->nullable()->after('tax');
            $table->text('bank_details')->nullable()->after('end_date');
            $table->string('settlement_date')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn(['supplier_term_id']);
        });
        Schema::table('term_options', function (Blueprint $table) {
            $table->dropColumn(['supplier_term_id','margin','margin_type']);
        });
        Schema::table('prices', function (Blueprint $table) {
            $table->dropColumn(['margin','margin_type']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['reseller_order_id']);
        });
        Schema::table('settlements', function (Blueprint $table) {
            $table->dropColumn(['shop_id','total_amount','charge','settlement_rate','interval','bank_details','settlement_date']);
        });
    }
}
