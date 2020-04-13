<?php

namespace App\Http\Controllers\API\Secman;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Secman\Secmenugroup;
use App\Http\Resources\Secman\SecmenugroupCollection;
use Validator;

class SecmenugroupController extends BaseController
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
            $secmenugroups = new SecmenugroupCollection(Secmenugroup::all());
        } else {
            $secmenugroups = new SecmenugroupCollection(Secmenugroup::paginate($request->get('per_page')));
        }
        
        return $secmenugroups;
        // $secmenugroups = Secmenugroup::all();
        // return $this->sendResponse($secmenugroups->toArray(), 'Secmenugroups retrieved successfully.');
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
            'menu_id' => 'required',
            'group_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secmenugroup = Secmenugroup::create($input);

        return $this->sendResponse($secmenugroup->toArray(), 'Secmenugroup created successfully.');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $secmenugroup = Secmenugroup::find($id);

        if (is_null($secmenugroup)) {
            return $this->sendError('Secmenugroup not found.');
        }

        return $this->sendResponse($secmenugroup->toArray(), 'Secmenugroup retrieved successfully.');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secmenugroup $secmenugroup)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'menu_id' => 'required',
            'group_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $secmenugroup->update($input);

        return $this->sendResponse($secmenugroup->toArray(), 'Secmenugroup updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secmenugroup $secmenugroup)
    {
        $secmenugroup->delete();

        return $this->sendResponse($secmenugroup->toArray(), 'Secmenugroup deleted successfully.');
    }
}