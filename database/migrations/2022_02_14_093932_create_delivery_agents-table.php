<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {  
        Schema::create('delivery_agents', function (Blueprint $table) {
            $table->id();
            $table->string('avathar')->nullable();
            $table->string('agent_id')->nullable();
            $table->string('image_id')->nullable();
            $table->unsignedSmallInteger('status')->default(0);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
            ->references('id')->on('customers')
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
        Schema::dropIfExists('delivery_agents');
    }
}
