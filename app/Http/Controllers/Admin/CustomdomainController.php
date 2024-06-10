<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Domain;
use App\Models\RequestDomain;

class CustomDomainController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth()->user()->can('domain.list')) {
            return abort(401);
        }

        if ($request->type == 'email') {
            $this->email = $request->src;
            $posts = RequestDomain::whereHas('shop', function ($q) {
                return $q->where('email', $this->email);
            })->with('shop', 'parentdomain')->latest()->paginate(40);
        } elseif (!empty($request->src) && !empty($request->type)) {
            $posts = RequestDomain::with('shop', 'parentdomain')->where($request->type, $request->src)->latest()->paginate(40);
        } else {
            $posts = RequestDomain::with('shop', 'parentdomain')->latest()->paginate(40);
        }
        $type = "all";
        $all = RequestDomain::count();
        $actives = RequestDomain::where('status', 1)->count();
        $trash = RequestDomain::where('status', 0)->count();
        $requested = RequestDomain::where('status', 2)->count();

        return view('admin.domain.custom_domain_requests', compact('posts', 'request', 'type', 'all', 'actives', 'trash', 'requested'));
    }

    public function show(Request $request, $id)
    {
        if (!Auth()->user()->can('domain.list')) {
            return abort(401);
        }

        if ($request->type == 'email') {
            $this->email = $request->src;
            $posts = RequestDomain::whereHas('shop', function ($q) {
                return $q->where('email', $this->email);
            })->with('shop', 'parentdomain')->where('status', $id)->latest()->paginate(40);
        } elseif (!empty($request->src) && !empty($request->type)) {
            $posts = RequestDomain::with('shop', 'parentdomain')->where('status', $id)->where($request->type, $request->src)->latest()->paginate(40);
        } else {
            $posts = RequestDomain::with('shop', 'parentdomain')->where('status', $id)->latest()->paginate(40);
        }


        $all = RequestDomain::count();
        $actives = RequestDomain::where('status', 1)->count();
        $trash = RequestDomain::where('status', 0)->count();
        $requested = RequestDomain::where('status', 2)->count();
        $type = $id;
        return view('admin.domain.custom_domain_requests', compact('posts', 'request', 'all', 'actives', 'trash', 'type', 'requested'));
    }

    public function edit($id)
    {
        $info = RequestDomain::findorfail($id);
        return view('admin.domain.custom_domain_edit', compact('info'));
    }

    public function update(Request $request, $id)
    {

        $domain = RequestDomain::findorfail($id);
        $domain->status = $request->status;
        $domain->domain = $request->domain;
        $domain->save();

        if ($request->status == 1 && $request->reflect == 1) {
            $check = Domain::where('domain', $request->domain)->where('id', '!=', $domain->domain_id)->first();
            if (!empty($check)) {
                $error['errors']['domain'] = 'Opps this domain already taken....!!';
                return response()->json($error, 422);
            }
            $current_domain = Domain::findorfail($domain->domain_id);
            $full_domain = env('APP_PROTOCOL') . $request->domain;
            $current_domain->domain = $request->domain;
            $current_domain->full_domain = $full_domain;
            $current_domain->save();
        }

        return response()->json(['Domain Updated']);
    }

    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('domain.delete')) {
            return abort(401);
        }

        if ($request->ids) {
            if ($request->method != 'delete') {
                foreach ($request->ids as $id) {
                    $domain = RequestDomain::find($id);
                    $domain->status = $request->method;
                    $domain->save();
                }
            } else {
                foreach ($request->ids as $id) {
                    RequestDomain::destroy($id);
                }
            }
        }

        return response()->json(['Success']);
    }
}
