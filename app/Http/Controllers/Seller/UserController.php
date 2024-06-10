<?php

namespace App\Http\Controllers\Seller;

use App\Models\Shop;
use App\Models\User;
use App\Models\Plan;
use App\Models\Domain;
use App\Models\Option;
use App\Models\ShopUser;
use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $request;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if (!Auth()->user()->can('user.list')) {
        //     return abort(401);
        // }
        $type = $request->type ?? 'all';
        if ($type == "trash") {
            $type = 0;
        }
        $shop = Shop::findorFail(current_shop_id());
        $users = User::whereHas('shop_user', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        });

        if ($type !== 'all') {
            $users = $users->where('status', $type);
        }

        if (!empty($request->src) && !empty($request->term)) {
            $users = $users->where($request->term, 'LIKE', '%' . $request->src . '%');
            // $users = $users->where($request->term, $request->src);
        }
        $src = $request->src ?? '';

        $users = $users->where('id', '<>', auth()->id());

        $users = $users->with(['shop_user.role'])->latest()->paginate(40);

        return view('seller.users.index', compact('users', 'type', 'request', 'src'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if (!Auth()->user()->can('user.create')) {
        //     return abort(401);
        // }

        $roles = Role::whereNotIn('name', ['superadmin', 'admin'])->get();

        return view('seller.users.create', compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop = Shop::findorFail(current_shop_id());
        $request->validate([
            'first_name' => 'required',
            'role_id' => 'required',
            'password' => 'required|without_spaces',
            'email' => 'required|unique:users|email_address|max:255',
        ]);

        DB::beginTransaction();
        try {
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name ?? "";
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = $request->status ?? 1;
            $user->save();

            $shop_user = new ShopUser();
            $shop_user->shop_id = $shop->id;
            $shop_user->role_id = $request->role_id;
            $shop_user->user_id = $user->id;
            $user->status = 1;
            $shop_user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json([trans('User Created Successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (!Auth()->user()->can('user.view')) {
        //     return abort(401);
        // }

        $info = Shop::withCount('term', 'orders', 'customers')->with('domain', 'subscription', 'user')->findorFail($id);
        $users = User::withCount('orders')->where('shop_id',  $info->id)->latest()->paginate(40);
        return view('seller.users.show', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!Auth()->user()->can('user.edit')) {
        //     return abort(401);
        // }
        $shop = Shop::findorFail(current_shop_id());
        $info = User::where('id', $id)->first();
        $roles = Role::whereNotIn('name', ['superadmin', 'admin'])->get();

        $shop_user = ShopUser::where('user_id', $info->id)->where('shop_id', $shop->id)->first();

        $info->role_id = $shop_user->role_id ?? null;

        return view('seller.users.edit', compact('info', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::findorFail(current_shop_id());
        $request->validate([
            'first_name' => 'required|max:50',
            'email' => 'required|max:100|email_address|unique:users,email,' . $id,
            'status' => 'required',
            'select_role' => 'required',
        ]);

        if ($request->change_password) {
            $validatedData = $request->validate([
                'password' => 'required|without_spaces',
            ]);
        }

        $user = User::whereHas('shop_user', function ($query) use ($shop) {
            $query->where('shop_id', $shop->id);
        })->findorFail($id);
        $user->first_name = $request->first_name ?? '';
        $user->last_name = $request->last_name ?? '';
        $user->email = $request->email;

        if ($request->change_password) {
            $user->password = Hash::make($request->password);
        }

        $user->status = $request->status ?? 1;
        $user->save();

        $shop_user = ShopUser::where('user_id', $id )->first();
        if (empty($shop_user)) {
            $shop_user = new ShopUser();
        }
        $shop_user->shop_id = $shop->id;
        $shop_user->role_id = $request->select_role;
        $shop_user->user_id = $user->id;
        $shop_user->status = 1;
        $shop_user->save();

        return response()->json(['User Updated Successfully']);
    }
    public function term_index(Request $request)
    {

        $info = Menu::where('shop_id', current_shop_id())->where('name', "user_term")->first();
        return view('seller.users.user_terms', compact('info'));
    }
    public function term_store(Request $request)
    {
        $request->validate([
            'term' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $info = Menu::where('shop_id', current_shop_id())->where('name', "user_term")->first();
            if (empty($info)) {
                $info = new Menu;
                $info->name = "user_term";
                $info->position =  '';
                $info->data = $request->term;
                $info->shop_id = current_shop_id();
                $info->save();
            } else {

                $info = Menu::findorFail($info->id);
                $info->data = $request->term;
                $info->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        //return response()->json([trans('User Terms Created Successfully')]);
        Session::flash('success', trans('User Terms Created Successfully'));
        return back();
        
       
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request)
    // {
    //     if (!Auth()->user()->can('user.delete')) {
    //         return abort(401);
    //     }

    //     if ($request->ids != '') {

    //         if ($request->type == "term_delete") {
    //             foreach ($request->ids ?? [] as $key => $id) {
    //                 \App\Models\Product::destroy($id);
    //             }
    //         } elseif ($request->type == "user_delete") {
    //             foreach ($request->ids ?? [] as $key => $id) {
    //                 \App\Models\Customer::destroy($id);
    //             }
    //         } else {
    //             if (!empty($request->method)) {
    //                 if ($request->method == "delete") {
    //                     foreach ($request->ids ?? [] as $key => $id) {
    //                         \File::deleteDirectory('uploads/' . $id);
    //                         $user = User::destroy($id);
    //                     }
    //                 } else {
    //                     foreach ($request->ids ?? [] as $key => $id) {
    //                         $user = User::find($id);
    //                         if ($request->method == "trash") {
    //                             $user->status = 0;
    //                         } else {
    //                             $user->status = $request->method;
    //                         }
    //                         $user->save();
    //                     }
    //                 }
    //             }
    //         }
    //     } else {

    //         return response()->json([trans('Please Select Any Item')]);
    //     }

    //     return response()->json('Success');
    // }
}
