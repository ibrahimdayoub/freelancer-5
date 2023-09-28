<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reglist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ReglistController extends Controller
{
    /**
     * Route: http://127.0.0.1:8000/api/view-reglists
     * Method: get
     * Takes: no thing
     * Returns: all reglists
     * Accessable: by admin role
     */
    public function viewReglists()
    {
        $reglists = Reglist::all();

        return response()->json([
            'reglists' => $reglists,
            'message' => 'Reglists Fetched Successfully',
        ], 200);
    }

    /**
     * Route: http://127.0.0.1:8000/api/add-reglist
     * Method: post
     * Takes: reglist information
     * Returns: reglist
     * Accessable: by admin role
     */
    public function addReglist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'father_name' => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'max:50'],
            'governorate' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'max:100', 'email', 'unique:reglists', 'unique:admins'],
            'phone' => ['required', 'string', 'unique:reglists'],
            'is_disabled' => ['required', 'boolean'],
            'collage' => ['required', 'string'],
            'collage_id' => ['required', 'string', 'unique:reglists'],
            'year' => ['required', 'string'],
            'is_successded' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $reglist = new Reglist;
            $reglist->first_name = $request->input('first_name');
            $reglist->last_name = $request->input('last_name');
            $reglist->gender = $request->input('gender');
            $reglist->father_name = $request->input('father_name');
            $reglist->mother_name = $request->input('mother_name');
            $reglist->governorate = $request->input('governorate');
            $reglist->email = $request->input('email');
            $reglist->phone = $request->input('phone');
            $reglist->is_disabled = $request->input('is_disabled');
            $reglist->collage = $request->input('collage');
            $reglist->collage_id = $request->input('collage_id');
            $reglist->year = $request->input('year');
            $reglist->is_successded = $request->input('is_successded');
            $reglist->save();

            return response()->json([
                'reglist' => $reglist,
                'message' => 'Reglist Added Successfully',
            ], 201);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/view-reglist/{id}
     * Method: get
     * Takes: reglist id
     * Returns: reglist
     * Accessable: by admin role
     */
    public function viewReglist($id)
    {
        $reglist = Reglist::find($id);

        if ($reglist) {
            return response()->json([
                'reglist' => $reglist,
                'message' => 'Reglist Fetched Successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Reglist Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/update-reglist/{id}
     * Method: post
     * Takes: reglist information, reglist id
     * Returns: reglist
     * Accessable: by admin role
     */
    public function updateReglist(Request $request, $id)
    {
        $validationArray = [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'father_name' => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'max:50'],
            'governorate' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'unique:reglists'],
            'is_disabled' => ['required', 'boolean'],
            'collage' => ['required', 'string'],
            'collage_id' => ['required', 'string', 'unique:reglists'],
            'year' => ['required', 'string'],
            'is_successded' => ['required', 'boolean'],
        ];

        $reglist_e = Reglist::find($id);

        if ($reglist_e && $reglist_e->email == $request->input('email')) {
            $validationArray['email'] = ['required', 'string', 'max:100', 'email', 'unique:admins'];
        } else {
            $validationArray['email'] = ['required', 'string', 'max:100', 'email', 'unique:reglists', 'unique:admins'];
        }

        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $reglist = Reglist::find($id);
            if ($reglist) {
                $reglist = new Reglist;
                $reglist->first_name = $request->input('first_name');
                $reglist->last_name = $request->input('last_name');
                $reglist->gender = $request->input('gender');
                $reglist->father_name = $request->input('father_name');
                $reglist->mother_name = $request->input('mother_name');
                $reglist->governorate = $request->input('governorate');
                $reglist->email = $request->input('email');
                $reglist->phone = $request->input('phone');
                $reglist->is_disabled = $request->input('is_disabled');
                $reglist->collage = $request->input('collage');
                $reglist->collage_id = $request->input('collage_id');
                $reglist->year = $request->input('year');
                $reglist->is_successded = $request->input('is_successded');
                $reglist->save();

                return response()->json([
                    'reglist' => $reglist,
                    'message' => 'Reglist Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Reglist Is Not Found',
                ], 404);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/delete-reglist/{id}
     * Method: delete
     * Takes: reglist id
     * Returns: no thing
     * Accessable: by admin role
     */
    public function deleteReglist($id)
    {
        $reglist = Reglist::find($id);
        if ($reglist) {
            $reglist->delete();
            return response()->json([
                'message' => 'Reglist Deleted Successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Reglist Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/search_reglist/{key}
     * Method: post
     * Takes: no thing
     * Returns: matching reglists
     * Accessable: by admin role
     */
    public function searchReglist($key)
    {
        $reglists = Reglist::where('first_name', 'LIKE', '%' . $key . '%')->orWhere('last_name', 'LIKE', '%' . $key . '%')->orWhere('collage', 'LIKE', '%' . $key . '%')->get();

        return response()->json([
            'reglist' => $reglists,
            'message' => 'Reglist Fetched Successfully',
        ], 200);
    }
}
