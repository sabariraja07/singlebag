<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTermOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('term_options', function (Blueprint $table) {
            $table->unsignedBigInteger('supplier_option_id')->nullable()->after('supplier_term_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('term_options', function (Blueprint $table) {
            $table->dropColumn(['supplier_option_id']);
        });
    }
}
