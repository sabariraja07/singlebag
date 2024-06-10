<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\City;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = City::with('state')->latest()->paginate(40);
        return view('admin.cities.index', compact('posts', 'request'));
    }
    public function Create()
    {
        return view('admin.cities.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|unique:cities|max:100',
            'state' => 'required',
            'cost' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user = new City();
            $user->name = ucfirst($request->name);
            $user->state_id = $request->state;
            $user->cost = $request->cost;
            $user->status = $request->status;
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json(['City Created Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = City::find($id);
        return view('admin.cities.edit', compact('info'));
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
        $request->validate([
            'name' => 'required|max:100|unique:cities,name,' . $id,
            'state' => 'required',
            'cost' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $user = City::findorFail($id);
            $user->name = ucfirst($request->name);
            $user->state_id = $request->state;
            $user->cost = $request->cost;
            $user->status = $request->status;
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }


        return response()->json(['City Updated Successfully']);
    }
}
