<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('type')->default('category');
            $table->unsignedBigInteger('p_id')->nullable();
            $table->integer('featured')->default(0);
            $table->integer('menu_status')->default(0);
            $table->integer('is_admin')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

        Schema::create('category_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('type');
            $table->text('content')->nullable();
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
        });

        Schema::create('category_relations', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('relation_id');

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade'); 

            $table->foreign('relation_id')
            ->references('id')->on('categories')
            ->onDelete('cascade'); 
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('url')->nullable();           
            $table->unsignedBigInteger('shop_id')->nullable();    
            $table->timestamps();
        });

        Schema::create('category_media', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade'); 
            
            $table->foreign('media_id')
            ->references('id')->on('media')
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
            'categories',
            'category_metas',
            'category_relations',
            'media',
            'category_media',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
