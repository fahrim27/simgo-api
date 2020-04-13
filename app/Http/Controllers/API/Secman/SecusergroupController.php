<?php

namespace App\Http\Controllers\API\Secman;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Secman\Secusergroup;
use App\Http\Resources\Secman\SecgroupCollection;
use Validator;

class SecusergroupController extends BaseController
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
            $secusergroups = new SecgroupCollection(Secgroup::all());
        } else {
            $secusergroups = new SecgroupCollection(Secgroup::paginate($request->get('per_page')));
        }
        
        return $secusergroups;
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
            'user_id' => 'required',
            'group_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secusergroup = Secusergroup::create($input);

        return $this->sendResponse($secusergroup->toArray(), 'Secusergroup created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $secusergroup = Secusergroup::find($id);

        if (is_null($secusergroup)) {
            return $this->sendError('Secusergroup not found.');
        }

        return $this->sendResponse($secusergroup->toArray(), 'Secusergroup retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secusergroup $secusergroup)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'group_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secusergroup->update($input);

        return $this->sendResponse($secusergroup->toArray(), 'Secusergroup updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secusergroup $secusergroup)
    {
        $secusergroup->delete();

        return $this->sendResponse($secusergroup->toArray(), 'Secusergroup deleted successfully.');
    }
}