<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('domain_id');
            $table->unsignedBigInteger('shop_id');
            $table->string('domain');
            $table->integer('status')->default(2);
            $table->timestamps();

            $table->foreign('domain_id')
            ->references('id')->on('domains')
            ->onDelete('cascade');;

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_domains');
    }
}
