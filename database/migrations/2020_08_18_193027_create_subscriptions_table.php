<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->nullable();
            $table->double('amount')->nullable();
            $table->decimal('discount')->default(0);
            $table->double('tax')->nullable();
            $table->string('trx')->nullable();
            $table->date('will_expire')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('category_id')->nullable();           
            $table->integer('status')->default(2);
            $table->integer('payment_status')->default(2);
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->decimal('commission')->nullable();
            $table->unsignedBigInteger('settlement_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade'); 

            $table->foreign('plan_id')
            ->references('id')->on('plans')
            ->onDelete('cascade'); 

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
