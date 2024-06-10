<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ApiResponser;

    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email_address|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return $this->success([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email_address|',
            'password' => 'required|string|min:8',
        ]);

        $attr['status'] = 1;

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        if (domain_info('shop_id') != current_shop_id()) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

    public function verify_store_name(Request $request)
    {
        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name ?? "");
        $domain = str_replace("'", '', $domain);

        $shop = Shop::where('name', $domain)->first();
        if (isset($shop)) {
            $domain = env('APP_PROTOCOL') . Str::lower($shop->sub_domain) . '.' . env('APP_PROTOCOLESS_URL') . '/login';
            return $this->success($domain, 'Shop name is available.', 200);
        }


        return $this->error('Shop name is not available', 400);
    }

    public function auth(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->whereHas('shop', function ($q) {
            return $q->where('id', current_shop_id());
        })->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->success([
            'token' => $user->createToken($request->device_name)->plainTextToken
        ]);
    }

    public function logout()
    {
        $user = User::find(auth()->id());
        $user->tokens()->delete();

        return $this->success([], 'Tokens Revoked');
    }
}
