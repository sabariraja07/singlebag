<?php

namespace App\Rules;

use App\Models\Shop;
use App\Models\Domain;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Contracts\Validation\Rule;

class StoreUser implements Rule
{

    // public $messages =[];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = request()->getHost();
        $url = $_SERVER['REQUEST_URI'];
        $request_uri = explode('/', $url)[1];
        $get_domain = Domain::where('domain', $domain)
            ->where('status', 1)
            ->first();

        $user_email = User::where('email', $value)
            ->first();
        if (empty($user_email)) {
            $this->messages[] = 'You have entered an invalid email or password.';
            return false;
        }
        $partner_data = Partner::where('user_id', $user_email->id)->first();

        if (isset($get_domain)) {
            $shop = Shop::where('status', 'active')
                ->where('id', $get_domain->shop_id)
                ->whereHas('user', function ($q) use ($value) {
                    $q->where('email', $value);
                })
                ->first();
            $this->messages[] = "Entered credentials doesn't belong to this account.";
            return $shop ? true : false;
        }
        if ($request_uri == "login") {
            if (!empty($partner_data)) {
                $this->messages[] = 'These credentials do not match our records.';
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->messages;
    }
}
