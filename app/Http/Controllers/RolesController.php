<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Cache::remember('cacheroles',15/60,function() {
			return Role::simplePaginate(10);  // Paginamos cada 10 elementos.
		});
		
        return response()->json([
            'status'=>'ok', 
            'siguiente'=>$roles->nextPageUrl(),
            'anterior'=>$roles->previousPageUrl(),
            'data'=>$roles->items()
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
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        return response()->json(['status' => 'ok', 'data' => $role, 201]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Role $role, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);
        
        $role->update($request->only('name'));
        
        $role->syncPermissions($request->get('permission'));
        
        return response()->json(['status' => 'ok', 'data' => $role, 200]);
        // return redirect()->route('roles.index')
        //                 ->with('success','Role updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        
        return response()->json(['status' => 'ok', 'data' => $role, 'deleted' => true, 200]);
        // return redirect()->route('roles.index')
        //                 ->with('success','Role deleted successfully');
    }
}
