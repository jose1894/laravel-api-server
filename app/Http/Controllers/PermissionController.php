<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Cache;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   

        $permissions = Cache::remember('cachepermissions',15/60,function() {
			return Permission::simplePaginate(10);  // Paginamos cada 10 elementos.
		});
		
        return response()->json([
            'status'=>'ok', 
            'siguiente'=>$permissions->nextPageUrl(),
            'anterior'=>$permissions->previousPageUrl(),
            'data'=>$permissions->items()
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|unique:users,name'
        ]);

        $permission = Permission::create($request->only('name'));

        return response()->json(['status'=>'ok', 'data' => $permission], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$permission->id
        ]);

        $permission->update($request->only('name'));

        return response()->json(['status' => 'ok', 'data' => $permission], 200);
        // return redirect()->route('permissions.index')
        //     ->withSuccess(__('Permission updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json(['status' => 'ok', 'data' => $permission, 'deleted' => true], 200);
        // return redirect()->route('permissions.index')
        //     ->withSuccess(__('Permission deleted successfully.'));
    }
}