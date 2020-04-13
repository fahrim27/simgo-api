<?php

namespace app\Http\Controllers\API\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use App\Models\Role\Role;
use App\Models\Role\User_role;
use App\Models\Role\Permission;
use App\Models\Role\Role_permission;
use App\Http\Resources\Role\RoleCollection;
use App\Http\Resources\Role\User_roleCollection;
use App\Http\Resources\Role\PermissionCollection;
use App\Http\Resources\Role\Role_permissionCollection;
use Validator;

class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagination = (int)$request->header('Pagination');
        
        if($pagination === 0) {
            $roles = new RoleCollection(Role::all());
        } else {
            $roles = new RoleCollection(Role::paginate($request->get('per_page')));
        }

        return $roles;
    }

    public function getUserRole(Request $request, $user)
    {
        $pagination = (int)$request->header('Pagination');
        $input = $request->all();
        
        $user = User::find($user)->toArray();

        return $user;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
            'terkunci' => 'required',
            'nomor_lokasi' => 'required',
            'no_ba_penerimaan' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $role = Role::create($input);

        return $this->sendResponse($role->toArray(), 'Role created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);

        if (is_null($role)) {
            return $this->sendError('Role not found.');
        }

        return $this->sendResponse($role->toArray(), 'Role retrieved successfully.');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'id' => 'required',
            'terkunci' => 'required',
            'nomor_lokasi' => 'required',
            'no_ba_penerimaan' => 'required',
            'tahun_spj' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $role->update($input);

        return $this->sendResponse($role->toArray(), 'Role updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $nomor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return $this->sendResponse($role->toArray(), 'Role deleted successfully.');
    }
}