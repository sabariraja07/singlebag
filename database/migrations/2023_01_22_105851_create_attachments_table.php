<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('type')->default('default');
            $table->string('name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('disk')->nullable();
            $table->string('path')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->json('responsive_images')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
