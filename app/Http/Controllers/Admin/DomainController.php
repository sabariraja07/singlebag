<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\User;
use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DomainController extends Controller
{
    protected $email;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth()->user()->can('domain.list')) {
            return abort(401);
        }

        $posts = Domain::where('type', 0);

        if ($request->type == 'email') {
            $this->email = $request->src;
            $posts = $posts->whereHas('shop', function ($q) {
                return $q->where('email', $this->email);
            });
        } elseif (!empty($request->src) && !empty($request->type)) {
            $posts = $posts->where($request->type, $request->src);
        }

        $posts = $posts->with('shop')->latest()->paginate(40);

        $all = Domain::where('type', 0)->count();
        $actives = Domain::where('status', 1)->where('type', 0)->count();
        $drafts = Domain::where('status', 2)->where('type', 0)->count();
        $trash = Domain::where('status', 0)->where('type', 0)->count();
        $Requested = Domain::where('status', 3)->where('type', 0)->count();
        $type = "all";
        return view('admin.domain.index', compact('posts', 'request', 'all', 'actives', 'drafts', 'trash', 'type', 'Requested'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('domain.create')) {
            return abort(401);
        }

        return view('admin.domain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regex = "/^(?!-)[A-Za-z0-9-]+([\\-\\.]{1}[a-z0-9]+)*\\.[A-Za-z]{2,6}$/";
        $request->validate([
            'domain_name' => 'required|max:100|unique:domains,domain',
            'domain_name' => 'required|regex:' . $regex,
            // 'full_domain' => 'required|max:100|unique:domains,full_domain',
            'email' => 'required',
        ]);

        $shop = Shop::where('email', $request->email)->first();

        if (empty($shop)) {
            $data['errors']['store'] = trans("Store Not Found");
            return response()->json($data, 422);
        }

        $domain_type = Str::contains($request->domain_name, env("APP_PROTOCOLESS_URL")) ? 1 : 0;

        $domain = Domain::where('shop_id', $shop->id)->where('type', $domain_type)->first();

        if (empty($domain)) {
            $domain = new Domain();
        }

        $domain->domain = $request->domain_name;
        $domain->full_domain = env("APP_PROTOCOL" , "http://") . $request->domain_name;
        $domain->shop_id = $shop->id;
        $domain->status = $request->status;
        $domain->type = $domain_type;
        $domain->save();


        return response()->json([trans('Domain Created Successfully')]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('domain.edit')) {
            return abort(401);
        }
        $info = Domain::with('shop')->findorFail($id);
        $user_email = User::findorFail($info->shop->user_id);
        return view('admin.domain.edit', compact('info','user_email'));
    }

    public function show(Request $request, $id)
    {
        if (!Auth()->user()->can('domain.list')) {
            return abort(401);
        }

        if ($request->type == 'email') {
            $this->email = $request->src;
            $posts = Domain::whereHas('shop', function ($q) {
                return $q->where('email', $this->email);
            })->with('shop')->where('status', $id)->latest()->paginate(40);
        } elseif (!empty($request->src) && !empty($request->type)) {
            $posts = Domain::with('shop')->where('status', $id)->where($request->type, $request->src)->latest()->paginate(40);
        } else {
            $posts = Domain::with('shop')->where('status', $id)->latest()->paginate(40);
        }


        $all = Domain::count();
        $actives = Domain::where('status', 1)->count();
        $drafts = Domain::where('status', 2)->count();
        $trash = Domain::where('status', 0)->count();
        $Requested = Domain::where('status', 3)->count();
        $type = $id;
        return view('admin.domain.index', compact('posts', 'request', 'all', 'actives', 'drafts', 'trash', 'type', 'Requested'));
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
        $regex = "/^(?!-)[A-Za-z0-9-]+([\\-\\.]{1}[a-z0-9]+)*\\.[A-Za-z]{2,6}$/";
        $request->validate([
            'domain_name' => 'required|max:100|unique:domains,domain, ' . $id,
            'domain_name' => 'required|regex:' . $regex,
            // 'full_domain' => 'required|max:100|unique:domains,full_domain, ' . $id,
            'email' => 'required',
        ]);

        $shop = Shop::where('email', $request->email)->first();

        if (empty($shop)) {
            $data['errors']['shop'] = trans("Store Not Found");
            return response()->json($data, 422);
        }
        $domain_type = Str::contains($request->domain_name, env("APP_PROTOCOLESS_URL")) ? 1 : 0;

        $domain = Domain::findorFail($id);
        $domain->domain = $request->domain_name;
        $domain->full_domain = env("APP_PROTOCOL" , "http://") . $request->domain_name;
        $domain->shop_id = $shop->id;
        $domain->status = $request->status;
        $domain->type = $domain_type;
        $domain->save();

        return response()->json([trans('Domain Updated Successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('domain.delete')) {
            return abort(401);
        }

        if ($request->ids) {
            if ($request->method != 'delete') {
                foreach ($request->ids as $id) {
                    $domain = Domain::find($id);
                    $domain->status = $request->method;
                    $domain->save();
                }
            } else {
                foreach ($request->ids as $id) {
                    Domain::destroy($id);
                }
            }
        }

        return response()->json([trans('Success')]);
    }
}
