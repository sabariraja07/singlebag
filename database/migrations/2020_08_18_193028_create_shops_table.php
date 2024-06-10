<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_domain')->unique();
            $table->string('email')->unique();
            $table->string('shop_type')->default('seller'); // ['seller', 'reseller', 'supplier']
            $table->string('status')->default('inactive'); // ['active', 'inactive']
            $table->unsignedBigInteger('template_id')->default(1);
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->date('will_expire')->nullable();
            $table->json('data')->nullable();
            $table->integer('is_trial')->default(1);
            $table->text('address')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')
            ->references('id')->on('users')
            ->onDelete('cascade');
            
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreign('template_id')
            ->references('id')->on('templates')
            ->onDelete('cascade'); 

            $table->foreign('subscription_id')
            ->references('id')->on('subscriptions')
            ->onDelete('cascade'); 
        });

        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('full_domain');
            $table->integer('status')->default(1);
            $table->integer('type')->default(1);//1=subdomain 2= customdomain
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');          
            $table->integer('status');
            $table->integer('featured')->default(0);
            $table->string('type')->default('product');
            $table->integer('is_admin')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->unsignedBigInteger('term_id');
         
            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade'); 
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('term_id');
          
            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade'); 
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('data')->deafult("[]");
           // $table->string('lang')->deafult("en");
           // $table->integer('status')->default(0);
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();

        });
        
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->default(1);
            $table->unsignedBigInteger('variation_id')->default(1);
            $table->unsignedBigInteger('term_id');

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');

            $table->foreign('variation_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');        

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');
        });

        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->double('regular_price');
            $table->double('special_price')->nullable();
            $table->integer('price_type')->default(1);
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();
            $table->unsignedBigInteger('term_id');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_manage')->default(1);
            $table->integer('stock_status')->default(1);
            $table->integer('stock_qty')->default(1);
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('term_id');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');
           
        });

        Schema::create('term_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('type')->default(0); //0 = parent 1= child 
            $table->double('amount')->nullable();
            $table->integer('amount_type')->default(1);// 0 = percent, 1= fixed
            $table->integer('is_required')->default(0);
            $table->integer('select_type')->default(0);
            $table->unsignedBigInteger('p_id')->nullable();            
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('shop_id');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');
            
        });       

        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',100);
            $table->string('last_name',100)->nullable();
            $table->string('email');
            $table->string('password',255);
            $table->string('mobile')->nullable();            
            $table->unsignedBigInteger('shop_id'); 
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');
        });

        Schema::create('post_media', function (Blueprint $table) {
            $table->unsignedBigInteger('media_id');
            $table->unsignedBigInteger('term_id');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade'); 
            
            $table->foreign('media_id')
            ->references('id')->on('media')
            ->onDelete('cascade'); 
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id');
            $table->integer('rating')->default(1);
            $table->string('name');
            $table->string('email');
            $table->string('comment'); 
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('order_item_id')->nullable(); 
            $table->timestamps();

            $table->foreign('customer_id')
            ->references('id')->on('customers')
            ->onDelete('cascade');

            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade');
        });

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id');
            $table->string('url');
            
            $table->foreign('term_id')
            ->references('id')->on('terms')
            ->onDelete('cascade'); 
        });        

        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('content')->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('shop_id');
            $table->timestamps();

            $table->foreign('category_id')
            ->references('id')->on('categories')
            ->onDelete('cascade');

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');
        });

        Schema::create('shop_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('shop_id')
            ->references('id')->on('shops')
            ->onDelete('cascade');

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
            'shops',
            'domains',
            'terms',
            'metas',
            'post_categories',
            'menus',
            'options',
            'attributes',
            'prices',
            'stocks',
            'term_options',
            'reviews',
            'customers',
            'post_media',
            'files',
            'gateways',
            'shop_users',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
