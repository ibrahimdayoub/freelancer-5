<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Route: http://127.0.0.1:8000/api/view-students
     * Method: get
     * Takes: no thing
     * Returns: all students
     * Accessable: by admin role
     */
    public function viewStudents()
    {
        $malesStudentsWithoutDisabled = Student::where('gender', '=', 'male')->andWhere('isDesabled', '=', false)->get();
        $malesStudentsWithDisabled = Student::where('gender', '=', 'male')->andWhere('isDesabled', '=', true)->get();
        $femalesStudentsWithoutDisabled = Student::where('gender', '=', 'female')->andWhere('isDesabled', '=', false)->get();
        $femalesStudentsWithDisabled = Student::where('gender', '=', 'female')->andWhere('isDesabled', '=', true)->get();
        $students = Student::all();

        return response()->json([
            'malesStudentsWithoutDisabled' => $malesStudentsWithoutDisabled,
            'malesStudentsWithDisabled' => $malesStudentsWithDisabled,
            'femalesStudentsWithoutDisabled' => $femalesStudentsWithoutDisabled,
            'femalesStudentsWithDisabled' => $femalesStudentsWithDisabled,
            'students' => $students,
            'message' => 'Students Fetched Successfully',
        ], 200);
    }

    /**
     * Route: http://127.0.0.1:8000/api/add-student
     * Method: post
     * Takes: student information
     * Returns: student
     * Accessable: by admin role
     */
    public function addStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'father_name' => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'max:50'],
            'governorate' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'max:100', 'email', 'unique:students', 'unique:admins'],
            'phone' => ['required', 'string', 'unique:students'],
            'password' => ['required', 'string', 'min:8'],
            'is_disabled' => ['required', 'boolean'],
            'collage' => ['required', 'string'],
            'collage_id' => ['required', 'string', 'unique:students'],
            'year' => ['required', 'string'],
            'is_successded' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $student = new Student;
            $student->first_name = $request->input('first_name');
            $student->last_name = $request->input('last_name');
            $student->gender = $request->input('gender');
            $student->father_name = $request->input('father_name');
            $student->mother_name = $request->input('mother_name');
            $student->governorate = $request->input('governorate');
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');
            $student->password = Hash::make($request->input('password'));
            $student->is_disabled = $request->input('is_disabled');
            $student->collage = $request->input('collage');
            $student->collage_id = $request->input('collage_id');
            $student->year = $request->input('year');
            $student->is_successded = $request->input('is_successded');
            $student->save();

            return response()->json([
                'student' => $student,
                'message' => 'Student Added Successfully',
            ], 201);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/view-student/{id}
     * Method: get
     * Takes: student id
     * Returns: student
     * Accessable: by admin and student roles
     */
    public function viewStudent($id)
    {
        $student = Student::find($id);

        if ($student) {
            return response()->json([
                'student' => $student,
                'message' => 'Student Fetched Successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Student Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/update-student/{id}
     * Method: post
     * Takes: student information, student id
     * Returns: student
     * Accessable: by admin and student roles
     */
    public function updateStudent(Request $request, $id)
    {
        $validationArray = [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'father_name' => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'max:50'],
            'governorate' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'unique:students'],
            'password' => ['required', 'string', 'min:8'],
            'is_disabled' => ['required', 'boolean'],
            'collage' => ['required', 'string'],
            'collage_id' => ['required', 'string', 'unique:students'],
            'year' => ['required', 'string'],
            'is_successded' => ['required', 'boolean'],
        ];

        $student_e = Student::find($id);

        if ($student_e && $student_e->email == $request->input('email')) {
            $validationArray['email'] = ['required', 'string', 'max:100', 'email', 'unique:admins'];
        } else {
            $validationArray['email'] = ['required', 'string', 'max:100', 'email', 'unique:students', 'unique:admins'];
        }

        $validator = Validator::make($request->all(), $validationArray);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $student = Student::find($id);
            if ($student) {
                $student = new Student;
                $student->first_name = $request->input('first_name');
                $student->last_name = $request->input('last_name');
                $student->gender = $request->input('gender');
                $student->father_name = $request->input('father_name');
                $student->mother_name = $request->input('mother_name');
                $student->governorate = $request->input('governorate');
                $student->email = $request->input('email');
                $student->phone = $request->input('phone');
                $student->password = Hash::make($request->input('password'));
                $student->is_disabled = $request->input('is_disabled');
                $student->collage = $request->input('collage');
                $student->collage_id = $request->input('collage_id');
                $student->year = $request->input('year');
                $student->is_successded = $request->input('is_successded');

                if (auth()->user()->id == $id && auth()->user()->tokenCan('server:student')) {
                    $student->save();

                    return response()->json([
                        'student' => $student,
                        'message' => 'Your Account Updated Successfully',
                    ], 200);
                } else if (auth()->user()->tokenCan('server:admin')) {
                    $student->save();

                    return response()->json([
                        'student' => $student,
                        'message' => 'Student Updated Successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'No Permission To Update Process',
                    ], 401);
                }
            } else {
                return response()->json([
                    'message' => 'Student Is Not Found',
                ], 404);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/delete-student/{id}
     * Method: delete
     * Takes: student id
     * Returns: no thing
     * Accessable: by admin and student roles
     */
    public function deleteStudent($id)
    {
        $student = Student::find($id);
        if ($student) {
            if (auth()->user()->id == $id && auth()->user()->tokenCan('server:student')) {
                auth()->user()->tokens()->delete();
                $student->delete();
                return response()->json([
                    'message' => 'Your Account Deleted Successfully'
                ], 200);
            } else if (auth()->user()->tokenCan('server:admin')) {
                $student->delete();
                return response()->json([
                    'message' => 'Student Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Permission To Delete Process',
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Student Is Not Found',
            ], 404);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/search_student/{key}
     * Method: post
     * Takes: no thing
     * Returns: matching students
     * Accessable: by admin role
     */
    public function searchStudent($key)
    {
        $students = Student::where('first_name', 'LIKE', '%' . $key . '%')->orWhere('last_name', 'LIKE', '%' . $key . '%')->orWhere('collage', 'LIKE', '%' . $key . '%')->get();

        return response()->json([
            'student' => $students,
            'message' => 'Student Fetched Successfully',
        ], 200);
    }
}
