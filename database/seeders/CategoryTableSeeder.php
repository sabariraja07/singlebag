<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $categories = array(
      array('id' => '1', 'name' => 'Default', 'slug' => 'default', 'type' => 'parent_attribute', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2020-12-12 14:49:38'),
      array('id' => '2', 'name' => 'COD', 'slug' => 'cod', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '1', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2020-12-12 14:49:38'),
      array('id' => '3', 'name' => 'INSTAMOJO', 'slug' => 'instamojo', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:39', 'updated_at' => '2020-12-12 14:49:39'),
      array('id' => '4', 'name' => 'RAZORPAY', 'slug' => 'razorpay', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:39', 'updated_at' => '2020-12-12 14:49:39'),
      array('id' => '5', 'name' => 'PAYPAL', 'slug' => 'paypal', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:39', 'updated_at' => '2020-12-29 09:12:16'),
      array('id' => '6', 'name' => 'STRIPE', 'slug' => 'stripe', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:40', 'updated_at' => '2020-12-12 14:49:40'),
      array('id' => '7', 'name' => 'TOYYIBPAY', 'slug' => 'toyyibpay', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:49:40', 'updated_at' => '2020-12-12 14:49:40'),
      array('id' => '8', 'name' => 'Mollie', 'slug' => 'mollie', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:54:58', 'updated_at' => '2020-12-14 06:28:00'),

      array('id' => '9', 'name' => 'Paystack', 'slug' => 'paystack', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:54:58', 'updated_at' => '2020-12-14 06:28:00'),

      array('id' => '10', 'name' => 'Mercado', 'slug' => 'mercado', 'type' => 'payment_gateway', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '0', 'created_at' => '2020-12-12 14:54:58', 'updated_at' => '2020-12-14 06:28:00'),

      array('id' => '73', 'name' => 'James Curran', 'slug' => 'General Manager Spotify', 'type' => 'testimonial', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 17:36:54', 'updated_at' => '2020-12-18 17:36:54'),
      array('id' => '74', 'name' => 'Jose Evans', 'slug' => 'Chief Engineer Apple', 'type' => 'testimonial', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 17:37:34', 'updated_at' => '2020-12-18 17:37:34'),
      array('id' => '75', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:02:34', 'updated_at' => '2020-12-18 18:02:34'),
      array('id' => '76', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:02:43', 'updated_at' => '2020-12-18 18:02:43'),
      array('id' => '77', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:02:57', 'updated_at' => '2020-12-18 18:02:57'),
      array('id' => '78', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:03:05', 'updated_at' => '2020-12-18 18:03:05'),
      array('id' => '79', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:03:14', 'updated_at' => '2020-12-18 18:03:14'),
      array('id' => '81', 'name' => 'Start an online business', 'slug' => 'start-an-online-business', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:20:57', 'updated_at' => '2021-01-09 17:20:57'),
      array('id' => '82', 'name' => 'Move your business online', 'slug' => 'move-your-business-online', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:23:50', 'updated_at' => '2021-01-09 17:23:50'),
      array('id' => '83', 'name' => 'Switch to singlebag', 'slug' => 'switch-to-singlebag', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:27:48', 'updated_at' => '2021-01-09 17:27:48'),
      array('id' => '85', 'name' => 'Hire a singlebag expert', 'slug' => 'hire-a-singlebag-expert', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:34:21', 'updated_at' => '2021-01-09 17:34:21'),
      array('id' => '87', 'name' => '#test', 'slug' => '', 'type' => 'gallery', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 18:19:05', 'updated_at' => '2021-01-09 18:19:05'),
      array('id' => '88', 'name' => '#', 'slug' => '1', 'type' => 'gallery', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 18:19:17', 'updated_at' => '2021-01-09 18:19:17'),
      array('id' => '89', 'name' => '#', 'slug' => '1', 'type' => 'gallery', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 18:19:27', 'updated_at' => '2021-01-09 18:19:27'),
      array('id' => '90', 'name' => '#', 'slug' => '1', 'type' => 'gallery', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 18:32:18', 'updated_at' => '2021-01-09 18:32:18'),
      array('id' => '91', 'name' => 'Product Inventors', 'slug' => 'start-an-online-business', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:20:57', 'updated_at' => '2021-01-09 17:20:57'),
      array('id' => '92', 'name' => 'Easy to customization', 'slug' => 'start-an-online-business', 'type' => 'features', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2021-01-09 17:20:57', 'updated_at' => '2021-01-09 17:20:57'),
      array('id' => '93', 'name' => '#', 'slug' => NULL, 'type' => 'brand', 'p_id' => NULL, 'featured' => '0', 'menu_status' => '0', 'is_admin' => '1', 'created_at' => '2020-12-18 18:03:14', 'updated_at' => '2020-12-18 18:03:14'),

    );

    Category::insert($categories);
  }
}
