<?php

namespace App\Http\Controllers\API\Secman;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Secman\Secgroup;
use App\Http\Resources\Secman\SecgroupCollection;
use Validator;

class SecgroupController extends BaseController
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
            $secgroups = new SecgroupCollection(Secgroup::all());
        } else {
            $secgroups = new SecgroupCollection(Secgroup::paginate($request->get('per_page')));
        }
        
        return $secgroups;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'oid' => 'required',
            'gname' => 'required',
            'genable' => 'required'
        ]);

        $input = $request->all();

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secgroup = Secgroup::create($input);

        return $this->sendResponse($secgroup->toArray(), 'Secgroup created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $secgroup = Secgroup::find($id);

        if (is_null($secgroup)) {
            return $this->sendError('Secgroup not found.');
        }

        return $this->sendResponse($secgroup->toArray(), 'Secgroup retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secgroup $secgroup)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'oid' => 'required',
            'gname' => 'required',
            'genable' => 'required'
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secgroup->update($input);

        return $this->sendResponse($secgroup->toArray(), 'Secgroup updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $secgroup = Secgroup::find($id);
        $secgroup->delete();

        return $this->sendResponse($secgroup->toArray(), 'Secgroup deleted successfully.');
    }
}