<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Route: http://127.0.0.1:8000/api/view-complaints
     * Method: get
     * Takes: no thing
     * Returns: all complaints
     * Accessable: by admin and user roles
     */
    public function viewComplaints()
    {
        $complaints = Complaint::all();

        if (auth()->user()->tokenCan('server:admin')) {
            return response()->json([
                'complaints' => $complaints,
                'message' => 'Complaints Fetched Successfully',
            ], 200);
        } else {
            $result = [];
            for ($i = 0; $i < count($complaints); $i++) {
                if ($complaints[$i]->user_id === auth()->user()->id) {
                    array_push($result, $complaints[$i]);
                }

                $complaints = $result;

                return response()->json([
                    'complaints' => $complaints,
                    'message' => 'Complaints Fetched Successfully',
                ], 200);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/add-complaint
     * Method: post
     * Takes: complaint information
     * Returns: complaint
     * Accessable: by student role
     */
    public function addComplaint(Request $request)
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
            $complaint = new Complaint;
            $complaint->title = $request->input('title');
            $complaint->content = $request->input('content');
            $complaint->student_id = auth()->user()->id;

            $complaint->save();
            $complaint->student = Student::find($complaint->student_id);

            return response()->json([
                'complaint' => $complaint,
                'message' => 'Complaint Added Successfully',
            ], 201);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/view-complaint/{id}
     * Method: get
     * Takes: complaint id
     * Returns: complaint
     * Accessable: by admin and student roles
     */
    public function viewComplaint($id)
    {
        $complaint = Complaint::find($id);

        if ($complaint) {
            if (
                auth()->user()->tokenCan('server:admin') ||
                auth()->user()->tokenCan('server:student') && auth()->user()->id === $complaint->student_id
            ) {
                return response()->json([
                    'complaint' => $complaint,
                    'message' => 'Complaint Fetched Successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Complaint Is Not Accessable',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Complaint Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/update-complaint/{id}
     * Method: post
     * Takes: complaint information, complaint id
     * Returns: complaint
     * Accessable: by student role
     */
    public function updateComplaint(Request $request, $id)
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
            $complaint = Complaint::find($id);
            if ($complaint) {
                if (
                    auth()->user()->tokenCan('server:admin') ||
                    auth()->user()->tokenCan('server:student') && auth()->user()->id === $complaint->student_id
                ) {
                    $complaint->title = $request->input('title');
                    $complaint->content = $request->input('content');

                    $complaint->save();
                    $complaint->student = Student::find($id);

                    return response()->json([
                        'complaint' => $complaint,
                        'message' => 'Complaint Updated Successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Complaint Is Not Accessable',
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'No Complaint Id Found',
                ], 404);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/delete-complaint/{id}
     * Method: delete
     * Takes: complaint id
     * Returns: no thing
     * Accessable: by student role
     */
    public function deleteComplaint($id)
    {
        $complaint = Complaint::find($id);
        if ($complaint) {
            if (
                auth()->user()->tokenCan('server:admin') ||
                auth()->user()->tokenCan('server:student') && auth()->user()->id === $complaint->student_id
            ) {
                $complaint->delete();

                return response()->json([
                    'message' => 'Complaint Deleted Successfully'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Complaint Is Not Accessable',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Complaint Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/search_complaint/{key}
     * Method: post
     * Takes: no thing
     * Returns: matching complaints
     * Accessable: by admin and user roles
     */
    public function searchComplaint($key)
    {
        $complaints = Complaint::where('title', 'LIKE', '%' . $key . '%')->orWhere('content', 'LIKE', '%' . $key . '%')->get();

        $result = [];

        for ($i = 0; $i < count($complaints); $i++) {
            $complaints[$i]->student = Student::find($complaints[$i]->student_id);
            array_push($result, $complaints[$i]);
        }

        $complaints = $result;

        return response()->json([
            'complaint' => $complaints,
            'message' => 'Complaints Fetched Successfully',
        ], 200);
    }
}
