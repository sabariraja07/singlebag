<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Models\Option;
use App\Models\Template;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Template::create(['name' => 'SingleBag', 'src_path' => 'frontend/singlebag', 'asset_path' => 'frontend/singlebag']);
        Template::create(['name' => 'Bigbag', 'src_path' => 'frontend/bigbag', 'asset_path' => 'frontend/bigbag']);
        // // Template::create(['name'=>'M-Cart','src_path'=>'frontend/m-cart','asset_path'=>'frontend/m-cart']);
        // Template::create(['name' => 'Arafa Cart', 'src_path' => 'frontend/arafa-cart', 'asset_path' => 'frontend/arafa-cart']);
        // Template::create(['name' => 'Saka Cart', 'src_path' => 'frontend/saka-cart', 'asset_path' => 'frontend/saka-cart']);
        // Template::create(['name' => 'Bazar', 'src_path' => 'frontend/bazar', 'asset_path' => 'frontend/bazar']);

        $super = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
        ]);


        $roleSuperAdmin = Role::create(['name' => 'superadmin']);
        $roleStoreAdmin = Role::create(['name' => 'admin']);
        //create permission
        $permissions = [
            [
                'group_name' => 'dashboard',
                'permissions' => [
                    'dashboard'
                ]
            ],
            [
                'group_name' => 'plan',
                'permissions' => [
                    'plan.create',
                    'plan.edit',
                    'plan.show',
                    'plan.update',
                    'plan.delete',
                    'plan.list',

                ]
            ],

            [
                'group_name' => 'admin',
                'permissions' => [
                    'admin.create',
                    'admin.edit',
                    'admin.update',
                    'admin.delete',
                    'admin.list',

                ]
            ],
            [
                'group_name' => 'role',
                'permissions' => [
                    'role.create',
                    'role.edit',
                    'role.update',
                    'role.delete',
                    'role.list',

                ]
            ],
            [
                'group_name' => 'Pages',
                'permissions' => [
                    'page.create',
                    'page.edit',
                    'page.delete',
                    'page.list',

                ]
            ],
            [
                'group_name' => 'Payment Gateway',
                'permissions' => [
                    'payment_gateway.config',
                ]
            ],
            [
                'group_name' => 'seo',
                'permissions' => [
                    'seo',
                ]
            ],
            [
                'group_name' => 'gallery',
                'permissions' => [
                    'gallery.list',
                    'gallery.create',
                ]
            ],
            [
                'group_name' => 'Orders',
                'permissions' => [
                    'order.create',
                    'order.edit',
                    'order.delete',
                    'order.list',
                    'order.view',
                ]
            ],
            [
                'group_name' => 'Report',
                'permissions' => [
                    'report.view',

                ]
            ],

            [
                'group_name' => 'Customer',
                'permissions' => [
                    'customer.create',
                    'customer.list',
                    'customer.view',
                    'customer.edit',
                    'customer.delete',
                    'customer.request',
                    'customer.expired_subscription',

                ]
            ],
            [
                'group_name' => 'Partner',
                'permissions' => [
                    'partner.create',
                    'partner.list',
                    'partner.view',
                    'partner.edit',
                    'partner.delete',
                    'partner.settlements',
                    'partner.edit_settlement',
                ]
            ],
            [
                'group_name' => 'Shop',
                'permissions' => [
                    'shop.create',
                    'shop.list',
                    'shop.view',
                    'shop.edit',
                    'shop.delete',
                    'shop.request',
                    'shop.expired_subscription',

                ]
            ],
            [
                'group_name' => 'Domain',
                'permissions' => [
                    'domain.create',
                    'domain.edit',
                    'domain.list',
                    'domain.delete',

                ]
            ],
            [
                'group_name' => 'Cron jobs',
                'permissions' => [
                    'cron_job.control',

                ]
            ],
            [
                'group_name' => 'menu',
                'permissions' => [
                    'menu',

                ]
            ],
            [
                'group_name' => 'Developer',
                'permissions' => [
                    'cron_job',
                    'email_template.config',
                    'template.upload',
                    'template.delete',
                    'template.list',
                    'environment.settings',
                    'payment_gateway.setup',

                ]
            ],



            [
                'group_name' => 'Settings',
                'permissions' => [
                    'site.settings',
                    'marketing.tools',
                ]
            ],
            [
                'group_name' => 'Seller Activity',
                'permissions' => [
                    'uploaded_files.control',
                    'uploaded_files_directory.control',
                    'product.control',
                    'invoices.control',
                ]
            ],

            [
                'group_name' => 'language',
                'permissions' => [
                    'language_edit',
                ]
            ]

        ];

        //assign permission

        foreach ($permissions as $key => $row) {


            foreach ($row['permissions'] as $per) {
                $permission = Permission::create(['name' => $per, 'group_name' => $row['group_name']]);
                $roleSuperAdmin->givePermissionTo($permission);
                $permission->assignRole($roleSuperAdmin);
                $super->assignRole($roleSuperAdmin);
            }
        }



        $options = array(
            array('id' => '1', 'key' => 'langlist', 'value' => '{"English":"en","Bengali":"bn"}', 'created_at' => '2020-12-12 14:49:37', 'updated_at' => '2020-12-12 14:49:37'),
            array('id' => '2', 'key' => 'order_prefix', 'value' => 'AMC', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2020-12-12 14:49:38'),
            array('id' => '3', 'key' => 'company_info', 'value' => '{"name":"singlebag","site_description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s","email1":"email@email.com","email2":"email2@email.com","phone1":"+88012456789","phone2":"+88012456789","country":"bangladesh","zip_code":"1234","state":"Chattagram","city":"Chattagram","address":"Agrabad","facebook":"#","twitter":"#","linkedin":"#","instagram":"#","youtube":"#","site_color":"#42155c"}', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2021-01-16 07:30:52'),
            array('id' => '4', 'key' => 'currency', 'value' => 'INR', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2020-12-12 14:49:38'),
            array('id' => '5', 'key' => 'cron_info', 'value' => '{"send_mail_to_will_expire_within_days":7,"alert_message":"i, your plan will expire soon.","expire_message":"our plan is expired!","trial_expired_message":"our free trial is expired!","auto_approve":"yes"}', 'created_at' => '2020-12-12 14:49:38', 'updated_at' => '2020-12-12 14:49:38'),
            array('id' => '6', 'key' => 'header', 'value' => '{"title":"SELL EVERYWHERE","highlight_title":"Increase your productivity","ytb_video":"75TGjNieK84","description":"Use one platform to sell products to anyone, anywhere\\u2014in person with Point of Sale and online through your website, social media, and online marketplaces.","preview":"uploads\\/1\\/2021\\/01\\/1610213945.webp"}', 'created_at' => '2020-12-18 17:14:36', 'updated_at' => '2021-01-09 18:05:30'),
            array('id' => '7', 'key' => 'faqs', 'value' => '{"description":"<h2>Site Audit<\\/h2>\\r\\n\\r\\n<p>Site Audit crawls all the pages it finds on your website &ndash; then provides an overall SEO health score, visualises key data in charts, flags all possible SEO issues and provides recommendations on how to fix them.<\\/p>\\r\\n\\r\\n<p>Have a huge website? Not an issue.<\\/p>\\r\\n\\r\\n<p><a href=\\"https:\\/\\/demos.creative-tim.com\\/impact-design-system\\/front\\/pages\\/about.html\\">Learn More&nbsp;<\\/a><\\/p>","preview":"uploads\\/1\\/2020\\/12\\/1608311802.svg"}', 'created_at' => '2020-12-18 17:16:42', 'updated_at' => '2020-12-18 17:19:03'),
            array('id' => '8', 'key' => 'marketing_tool', 'value' => '{"ga_measurement_id":"UA-180680025-1","analytics_view_id":"231381168","google_status":"on","fb_pixel":"","fb_pixel_status":""}', 'created_at' => '2020-12-25 17:32:48', 'updated_at' => '2020-12-25 17:32:48'),
            array('id' => '9', 'key' => 'languages', 'value' => '{"en":"English","bn":"Bangla","ar":"Arabic"}', 'created_at' => '2021-01-05 09:51:31', 'updated_at' => '2021-01-11 17:07:34'),
            array('id' => '10', 'key' => 'active_languages', 'value' => '{"en":"English","ar":"Arabic"}', 'created_at' => '2021-01-08 15:21:41', 'updated_at' => '2021-01-11 17:07:52'),
            array('id' => '11', 'key' => 'about_1', 'value' => '{"title":"Upload your product","description":"Enter the product along with other complete information such as photos, videos, variations, product descriptions, promotions and so on.","btn_link":"#pricing","btn_text":"Free Trial","preview":"icofont-cloud-upload"}', 'created_at' => '2021-01-09 18:51:25', 'updated_at' => '2021-01-16 06:22:49'),
            array('id' => '12', 'key' => 'about_2', 'value' => '{"title":"Setup your store","description":"Insert logo, banner and modify your store theme according to your own brand identity without having to create any code.","btn_link":"","btn_text":"","preview":"icofont-shopping-cart"}', 'created_at' => '2021-01-09 18:55:31', 'updated_at' => '2021-01-16 06:18:44'),
            array('id' => '13', 'key' => 'about_3', 'value' => '{"title":"The launch continues","description":"Easily, your online store goes live and you can validate your business and get market share faster than your other competitors.","btn_link":"","btn_text":"","preview":"icofont-rocket-alt-2"}', 'created_at' => '2021-01-09 18:56:22', 'updated_at' => '2021-01-16 06:56:08'),
            array('id' => '14', 'key' => 'seo', 'value' => '{"title":"singlebag","description":"test","canonical":"' . env('APP_URL') . '","tags":"test","twitterTitle":"@singlebag"}', 'created_at' => '2021-01-16 08:30:26', 'updated_at' => '2021-01-16 08:30:26'),
            array('id' => '15', 'key' => 'auto_order', 'value' => 'yes', 'created_at' => '2021-02-21 18:14:35', 'updated_at' => '2021-02-21 18:14:44'),
            array('id' => '16', 'key' => 'ecom_features', 'value' => '{"top_image":"uploads\/1\/2021\/03\/1615392340.png","center_image":"uploads\/1\/2021\/03\/1615392340.webp","bottom_image":"uploads\/1\/2021\/03\/1615392340.webp","area_title":"Market your business","description":"Take the guesswork out of marketing with built-in tools that help you create, execute, and analyze digital marketing campaigns.","btn_link":"#service","btn_text":"Service"}', 'created_at' => '2021-02-21 18:14:35', 'updated_at' => '2021-02-21 18:14:44'),
            array('id' => '17', 'key' => 'counter_area', 'value' => '{"happy_customer":"1000","total_reviews":"800","total_domain":"1200","community_member":"2000"}', 'created_at' => '2021-02-21 18:14:35', 'updated_at' => '2021-02-21 18:14:44'),
            array('id' => '18', 'key' => 'instruction', 'created_at' => '2021-02-21 18:14:35', 'updated_at' => '2021-02-21 18:14:44', 'value' => '{"dns_configure_instruction":"You\'ll need to setup a DNS record to point to your store on our server. DNS records can be setup through your domain registrars control panel. Since every registrar has a different setup, contact them for assistance if you\'re unsure.","support_instruction":"DNS changes may take up to 48-72 hours to take effect, although it\'s normally a lot faster than that. You will receive a reply when your custom domain has been activated. Please allow 1-2 business days for this process to complete."}'),
            array('id' => '19', 'key' => 'tax', 'value' => 2, 'created_at' => '2021-02-21 18:14:35', 'updated_at' => '2021-02-21 18:14:44'),
            array('id' => '20', 'key' => 'site_key', 'value' => env('SITE_KEY'), 'created_at' => now(), 'updated_at' => '2021-02-21 18:14:44')
        );

        Option::insert($options);

        $plans = array(
            array(
                'id' => '1',
                'name' => 'Free Trial',
                'description' => 'free register',
                'price' => '0',
                'days' => '7',
                'featured' => '0',
                'status' => '1',
                'data' => json_encode(
                    array(
                        'product_limit' => 50,
                        'storage' => 1024,
                        'customer_limit' => 10,
                        'custom_domain' => true,
                        'inventory' => true,
                        'pos' => true,
                        'customer_panel' => true,
                        'pwa' => true,
                        'whatsapp' => true,
                        'live_support' => true,
                        'qr_code' => true,
                        'facebook_pixel' => true,
                        'custom_css' => true,
                        'custom_js' => true,
                        'gtm' => true,
                        'location_limit' => 5,
                        'category_limit' => 5,
                        'brand_limit' => 5,
                        'variation_limit' => 1,
                        'google_analytics' => true
                    )
                ),
                'is_trial' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ),

            array(
                'id' => '2',
                'name' => 'STARTER BUSINESS',
                'description' => 'STARTER',
                'price' => '4999',
                'days' => '365',
                'featured' => '0',
                'status' => '1',
                'is_trial' => 0,
                'data' => json_encode(
                    array(
                        'product_limit' => 100,
                        'storage' => 51200,
                        'custom_domain' => true,
                        'customer_limit' => 0,
                        'inventory' => true,
                        'pos' => true,
                        'customer_panel' => true,
                        'pwa' => true,
                        'whatsapp' => true,
                        'live_support' => true,
                        'qr_code' => true,
                        'facebook_pixel' => true,
                        'custom_css' => true,
                        'custom_js' => true,
                        'gtm' => true,
                        'location_limit' => 0,
                        'category_limit' => 0,
                        'brand_limit' => 0,
                        'variation_limit' => 0,
                        'google_analytics' => true
                    )
                ),
                'created_at' => now(),
                'updated_at' => now()
            ),

            array(
                'id' => '3',
                'name' => 'ENTERPRISE',
                'description' => 'FOR ENTERPRISE BUSINESS',
                'price' => '9999',
                'days' => '365',
                'featured' => '0',
                'status' => '1',
                'is_trial' => 0,
                'data' => json_encode(
                    array(
                        'product_limit' => 0,
                        'customer_limit' => 0,
                        'storage' => 0,
                        'custom_domain' => true,
                        'inventory' => true,
                        'pos' => true,
                        'customer_panel' => true,
                        'pwa' => true,
                        'whatsapp' => true,
                        'live_support' => true,
                        'qr_code' => true,
                        'facebook_pixel' => true,
                        'custom_css' => true,
                        'custom_js' => true,
                        'gtm' => true,
                        'location_limit' => 0,
                        'category_limit' => 0,
                        'brand_limit' => 0,
                        'variation_limit' => 10,
                        'google_analytics' => true
                    )
                ),
                'created_at' => now(),
                'updated_at' => now()
            ),
        );
        Plan::insert($plans);
    }
}
