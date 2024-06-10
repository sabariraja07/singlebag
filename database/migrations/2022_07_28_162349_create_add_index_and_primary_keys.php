<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddIndexAndPrimaryKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function(Blueprint $table) {
            if (!Schema::hasColumn('password_resets', 'id')) {
                $table->id()->first();
            }
        });

        Schema::table('domains', function(Blueprint $table) {
            $table->index(['domain', 'status']);
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->index(['type', 'featured', 'p_id', 'menu_status', 'is_admin']);
        });
        
        Schema::table('category_metas', function(Blueprint $table) {
            $table->index(['type']);
        });

        Schema::table('options', function(Blueprint $table) {
            $table->index(['key']);
        });

        Schema::table('order_metas', function(Blueprint $table) {
            $table->index(['key']);
        });

        Schema::table('terms', function(Blueprint $table) {
            $table->index(['type', 'featured', 'status', 'is_admin']);
        });

        Schema::table('term_options', function(Blueprint $table) {
            $table->index(['amount_type', 'select_type', 'p_id']);
        });

        Schema::table('prices', function(Blueprint $table) {
            $table->index(['price_type', 'starting_date', 'ending_date']);
        });

        Schema::table('shop_options', function(Blueprint $table) {
            $table->index(['key']);
        });

        Schema::table('stocks', function(Blueprint $table) {
            $table->index(['stock_manage', 'stock_status']);
        });

        Schema::table('metas', function(Blueprint $table) {
            $table->index(['key']);
        });
        
        Schema::table('menus', function(Blueprint $table) {
            $table->index(['position']);
        });

        // Schema::table('shops', function(Blueprint $table) {
        //     $table->index('status');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domains', function(Blueprint $table) {
            $table->dropIndex(['domain', 'status']);
        });

        Schema::table('categories', function(Blueprint $table) {
            $table->dropIndex(['type', 'featured', 'p_id', 'menu_status', 'is_admin']);
        });

        Schema::table('category_metas', function(Blueprint $table) {
            $table->dropIndex(['type']);
        });

        Schema::table('options', function(Blueprint $table) {
            $table->dropIndex(['key']);
        });

        Schema::table('order_metas', function(Blueprint $table) {
            $table->dropIndex(['key']);
        });

        Schema::table('terms', function(Blueprint $table) {
            $table->dropIndex(['type', 'featured', 'status', 'is_admin']);
        });

        Schema::table('term_options', function(Blueprint $table) {
            $table->dropIndex(['amount_type', 'select_type', 'p_id']);
        });

        Schema::table('prices', function(Blueprint $table) {
            $table->dropIndex(['price_type', 'starting_date', 'ending_date']);
        });

        Schema::table('shop_options', function(Blueprint $table) {
            $table->dropIndex(['key']);
        });

        Schema::table('stocks', function(Blueprint $table) {
            $table->dropIndex(['stock_manage', 'stock_status']);
        });
        
        Schema::table('metas', function(Blueprint $table) {
            $table->dropIndex(['key']);
        });

        Schema::table('menus', function(Blueprint $table) {
            $table->dropIndex(['position']);
        });

        // Schema::table('shops', function(Blueprint $table) {
        //     $table->dropIndex('status');
        // });
    }
}
