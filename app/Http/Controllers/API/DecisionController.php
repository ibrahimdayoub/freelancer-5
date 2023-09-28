<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Decision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DecisionController extends Controller
{
    /**
     * Route: http://127.0.0.1:8000/api/view-decisions
     * Method: get
     * Takes: no thing
     * Returns: all decisions
     * Accessable: by admin and user roles
     */
    public function viewDecisions()
    {
        $decisions = Decision::all();

        return response()->json([
            'decisions' => $decisions,
            'message' => 'Decisions Fetched Successfully',
        ], 200);
    }

    /**
     * Route: http://127.0.0.1:8000/api/add-decision
     * Method: post
     * Takes: decision information
     * Returns: decision
     * Accessable: by admin role
     */
    public function addDecision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $decision = new Decision;
            $decision->title = $request->input('title');
            $decision->content = $request->input('content');

            $decision->save();
            return response()->json([
                'decision' => $decision,
                'message' => 'Decision Added Successfully',
            ], 201);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/view-decision/{id}
     * Method: get
     * Takes: decision id
     * Returns: decision
     * Accessable: by admin and user roles
     */
    public function viewDecision($id)
    {
        $decision = Decision::find($id);

        if ($decision) {
            return response()->json([
                'decision' => $decision,
                'message' => 'Decision Fetched Successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Decision Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/update-decision/{id}
     * Method: post
     * Takes: decision information, decision id
     * Returns: decision
     * Accessable: by admin role
     */
    public function updateDecision(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $decision = Decision::find($id);
            if ($decision) {
                $decision->title = $request->input('title');
                $decision->content = $request->input('content');

                $decision->save();
                return response()->json([
                    'decision' => $decision,
                    'message' => 'Decision Updated Successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Decision Id Found',
                ], 404);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/delete-decision/{id}
     * Method: delete
     * Takes: decision id
     * Returns: no thing
     * Accessable: by admin role
     */
    public function deleteDecision($id)
    {
        $decision = Decision::find($id);
        if ($decision) {
            $decision->delete();

            return response()->json([
                'message' => 'Decision Deleted Successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Decision Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/search_decision/{key}
     * Method: post
     * Takes: no thing
     * Returns: matching decisions
     * Accessable: by admin and user roles
     */
    public function searchDecision($key)
    {
        $decisions = Decision::where('title', 'LIKE', '%' . $key . '%')->orWhere('content', 'LIKE', '%' . $key . '%')->get();
        return response()->json([
            'decision' => $decisions,
            'message' => 'Decisions Fetched Successfully',
        ], 200);
    }
}
