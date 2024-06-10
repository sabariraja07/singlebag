<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('commission')->default(0);
            $table->text('data')->nullable();
            $table->text('bank_details')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->decimal('amount');
            $table->decimal('tax')->default(0);
            $table->string('status')->default('unpaid');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->boolean('is_request')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
            ->references('id')->on('users')
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
            'partners',
            'settlements',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
