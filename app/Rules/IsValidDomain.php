<?php

namespace App\Rules;

use App\Models\Shop;
use Illuminate\Contracts\Validation\Rule;

class IsValidDomain implements Rule
{
    public $id;
    public $messages =[];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($id = null)
    {
        $this->id = $id;
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
        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $value);
        $domain = str_replace("'", '', $domain);

        if(strlen($domain) > 15 || strlen($domain) < 4){
            $this->messages[] = 'Invalid shop name (Only allow between 4 to 15 alphanumeric characters).';
            return false;
        }

        if(isset($this->id)){
            $shop = Shop::where('id', '<>', $this->id)->where('name', $domain)->first();
            if(!isset($shop)){
                return true;
            }

            $this->messages[] = 'Invalid shop name (Only allow between 4 to 15 alphanumeric characters).';
        }else{
            $shop = Shop::where('name', $domain)->first();
            if(!isset($shop)){
                return true;
            }

            $this->messages[] = 'Invalid shop name (Only allow between 4 to 15 alphanumeric characters).';
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
