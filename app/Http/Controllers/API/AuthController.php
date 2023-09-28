<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Route: http://127.0.0.1:8000/api/sign-up
     * Method: post
     * Takes: student information
     * Returns: student
     * Accessable: by any one
     */
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'], //, 'in:male,female'
            'father_name' => ['required', 'string', 'max:50'],
            'mother_name' => ['required', 'string', 'max:50'],
            'governorate' => ['required', 'string', 'max:50'], //, 'in:latakia,tartous,aleppo,damascus,daraa,deir_ezor,hama,homs,idlib,quneitra,raqqa,rif_dimashq,hasakah,swedaa'
            'email' => ['required', 'string', 'max:100', 'email', 'unique:students', 'unique:admins'],
            'phone' => ['required', 'string', 'unique:students'],
            'password' => ['required', 'string', 'min:8'],
            'is_disabled' => ['required', 'boolean'],
            'collage' => ['required', 'string'],
            'collage_id' => ['required', 'string', 'unique:students'],
            'year' => ['required', 'string'], //, 'in:first,second,third,forth,fifth,sixth'
            'is_successded' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $student = new Student;
            $student->first_name = $request->input('first_name');//
            $student->last_name = $request->input('last_name');//
            $student->gender = $request->input('gender');//
            $student->father_name = $request->input('father_name');//
            $student->mother_name = $request->input('mother_name');//
            $student->governorate = $request->input('governorate');//
            $student->email = $request->input('email');
            $student->phone = $request->input('phone');//
            $student->password = Hash::make($request->input('password'));
            $student->is_disabled = $request->input('is_disabled');//
            $student->collage_id = $request->input('collage_id');//
            $student->collage = $request->input('collage');//
            $student->year = $request->input('year');//
            $student->is_successded = $request->input('is_successded');//
            $student->save();

            $token = $student->createToken($student->email . 'Student_Token', ['server:student'])->plainTextToken;

            return response()->json([
                'token' => $token,
                'student' => $student,
                'role' => 'Student',
                'message' => 'Registered Successfully',
            ], 201);
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/sign-in
     * Method: post
     * Takes: admin or student information
     * Returns: admin or student
     * Accessable: by any one
     */
    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'max:100', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->messages(),
            ], 400);
        } else {
            $student = Student::where('email', $request->email)->first();
            $admin = Admin::where('email', $request->email)->first();

            if (
                (!$student || !Hash::check(
                    $request->password,
                    $student->password
                )
                )
                && (!$admin || !Hash::check(
                    $request->password,
                    $admin->password
                )
                )
            ) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                ], 401);
            } else if ($student) {
                $token = $student->createToken($student->email . '_Student_Token', ['server:student'])->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'student' => $student,
                    'role' => 'Student',
                    'message' => 'Logged In Successfully',
                ], 200);
            } else if ($admin) {
                $token = $admin->createToken($admin->email . '_Admin_Token', ['server:admin'])->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'name' => $admin,
                    'role' => 'Admin',
                    'message' => 'Logged In Successfully',
                ], 200);
            }
        }
    }

    /**
     * Route: http://127.0.0.1:8000/api/sign-out
     * Method: post
     * Takes: no thing
     * Returns: no thing
     * Accessable: by admin and student role
     */
    public function signOut()
    {
        auth()->student()->tokens()->delete();
        return response()->json([
            'message' => 'Logged Out Successfully'
        ], 200);
    }
}
