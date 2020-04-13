<?php

namespace App\Http\Controllers\API\Secman;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Secman\Secorg;
use App\Http\Resources\Secman\SecorgCollection;
use Validator;

class SecorgController extends BaseController
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
            $secorgs = new SecorgCollection(Secorg::all());
        } else {
            $secorgs = new SecorgCollection(Secorg::paginate($request->get('per_page')));
        }
        return $secorgs;
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
            'oname' => 'required',
            'oenable' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secorg = Secorg::create($input);

        return $this->sendResponse($secorg->toArray(), 'Secorg created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $secorg = Secorg::find($id);

        if (is_null($secorg)) {
            return $this->sendError('Secorg not found.');
        }

        return $this->sendResponse($secorg->toArray(), 'Secorg retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secorg $secorg)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'oname' => 'required',
            'oenable' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secorg->update($input);

        return $this->sendResponse($secorg->toArray(), 'Secorg updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secorg $secorg)
    {
        $secorg->delete();

        return $this->sendResponse($secorg->toArray(), 'Secorg deleted successfully.');
    }
}