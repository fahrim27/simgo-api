<?php

namespace App\Http\Controllers\API\Secman;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Secman\Secmenu;
use App\Http\Resources\Secman\SecmenuCollection;
use Validator;

class SecmenuController extends BaseController
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
            $secmenus = new SecmenuCollection(Secmenu::all());
        } else {
            $secmenus = new SecmenuCollection(Secmenu::paginate($request->get('per_page')));
        }
        
        return $secmenus;
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
            'mname' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secmenu = Secmenu::create($input);

        return $this->sendResponse($secmenu->toArray(), 'Secmenu created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $secmenu = Secmenu::find($id);

        if (is_null($secmenu)) {
            return $this->sendError('Secmenu not found.');
        }

        return $this->sendResponse($secmenu->toArray(), 'Secmenu retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secmenu $secmenu)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'mname' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secmenu->update($input);

        return $this->sendResponse($secmenu->toArray(), 'Secmenu updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secmenu $secmenu)
    {
        $secmenu->delete();

        return $this->sendResponse($secmenu->toArray(), 'Secmenu deleted successfully.');
    }
}