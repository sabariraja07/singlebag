<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderTableForSettlements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'reseller_settlement_id', 
                'reseller_id', 
                // 'is_parent', 
                'reseller_order_id', 
                'settlement_id',
            ]);
            $table->renameColumn('margin_earned', 'reseller_amount');
            $table->renameColumn('supplier_settlement', 'supplier_amount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->float('subtotal')->nullable()->after('status');
            $table->float('discount')->nullable()->after('subtotal');
            $table->float('supplier_amount')->nullable()->after('group_order_id')->change();
            $table->float('reseller_amount')->nullable()->after('supplier_amount')->change();
            $table->float('commission')->nullable()->after('reseller_amount')->change();
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
            //
        });
    }
}
