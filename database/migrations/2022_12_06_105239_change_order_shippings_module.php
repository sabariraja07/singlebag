<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeOrderShippingsModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        DB::statement('SET SESSION sql_require_primary_key=0');
        DB::statement('SET @ORIG_SQL_REQUIRE_PRIMARY_KEY = @@SQL_REQUIRE_PRIMARY_KEY;');
        Schema::table('order_shippings', function (Blueprint $table) {
            $table->dropForeign('shipping_id');
            $table->dropForeign('location_id');
            if (!Schema::hasColumn('order_shippings', 'amount')) {
                $table->string('amount')->nullable()->after('shipping_id');
            }
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_shippings', function (Blueprint $table) {
            $table->dropColumn(['amount']);
        });
    }
}
