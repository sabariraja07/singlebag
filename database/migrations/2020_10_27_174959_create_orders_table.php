<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->nullable();
            $table->string('transaction_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable(); //payment gateway id
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->integer('order_type')->default(0);
            $table->integer('payment_status')->default(2);
            $table->string('status')->default(0);
            $table->double('tax')->default(0);
            $table->double('shipping')->default(0);
            $table->double('total')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();            
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreign('customer_id')
            ->references('id')->on('customers')
            ->onDelete('cascade');
            
            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');
            
            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');            
        });

        Schema::create('order_metas', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->unsignedBigInteger('order_id');

            $table->foreign('order_id')
            ->references('id')->on('orders')
            ->onDelete('cascade'); 
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('term_id');
            $table->text('info')->nullable();
            $table->integer('qty')->defult(1);
            $table->double('amount');
            
            $table->foreign('order_id')
            ->references('id')->on('orders')
            ->onDelete('cascade'); 

             $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade'); 
        });

        Schema::create('order_shippings', function (Blueprint $table) {
          
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('shipping_id');
            
            $table->foreign('order_id')
            ->references('id')->on('orders')
            ->onDelete('cascade'); 

            $table->foreign('location_id')
            ->references('id')->on('categories')
            ->onDelete('cascade'); 

            $table->foreign('shipping_id')
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
        $tables = [
            'orders',
            'order_metas',
            'order_items',
            'order_shippings',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists('orders');
        }
    }
}
