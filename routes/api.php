<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ReglistController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\DecisionController;
use App\Http\Controllers\API\ComplaintController;
use App\Http\Controllers\API\HouseController;


Route::post('sign-up', [AuthController::class, 'signUp']);
Route::post('sign-in', [AuthController::class, 'signIn']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('sign-out', [AuthController::class, 'signOut']);
    //Student
    Route::get('view-student/{id}', [StudentController::class, 'viewStudent']);
    Route::put('update-student/{id}', [StudentController::class, 'updateStudent']);
    Route::delete('delete-student/{id}', [StudentController::class, 'deleteStudent']);
    //Complaints
    Route::get('view-complaints', [ComplaintController::class, 'viewComplaints']);
    Route::get('view-complaint/{id}', [ComplaintController::class, 'viewComplaint']);
    Route::post('search_complaint/{key}', [ComplaintController::class, 'searchComplaint']);
    //Houses
    Route::post('passive-house', [HouseController::class, 'passiveHouse']);
});
//#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    //Reglists
    Route::get('view-reglists', [ReglistController::class, 'viewReglists']);
    Route::post('add-reglist', [ReglistController::class, 'addReglist']);
    Route::get('view-reglist/{id}', [ReglistController::class, 'viewReglist']);
    Route::put('update-reglist/{id}', [ReglistController::class, 'updateReglist']);
    Route::delete('delete-reglist/{id}', [ReglistController::class, 'deleteReglist']);
    //Admins
    Route::get('view-admins', [AdminController::class, 'viewAdmins']);
    Route::post('add-admin', [AdminController::class, 'addAdmin']);
    Route::get('view-admin/{id}', [AdminController::class, 'viewAdmin']);
    Route::put('update-admin/{id}', [AdminController::class, 'updateAdmin']);
    Route::delete('delete-admin/{id}', [AdminController::class, 'deleteAdmin']);
    //Students
    Route::get('view-students', [StudentController::class, 'viewStudents']);
    Route::post('add-student', [StudentController::class, 'addStudent']);
    Route::post('search_student/{key}', [StudentController::class, 'searchStudent']);
    //Decisions
    Route::get('view-decisions', [DecisionController::class, 'viewDecisions']);
    Route::post('add-decision', [DecisionController::class, 'addDecision']);
    Route::get('view-decision/{id}', [DecisionController::class, 'viewDecision']);
    Route::put('update-decision/{id}', [DecisionController::class, 'updateDecision']);
    Route::delete('delete-decision/{id}', [DecisionController::class, 'deleteDecision']);
    Route::post('search_decision/{key}', [DecisionController::class, 'searchDecision']);
    //Houses
    Route::post('view-houses', [HouseController::class, 'viewHouses']);
});

//#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#-

Route::middleware(['auth:sanctum', 'isStudent'])->group(function () {
    //Complaints
    Route::post('add-complaint', [ComplaintController::class, 'addComplaint']);
    Route::put('update-complaint/{id}', [ComplaintController::class, 'updateComplaint']);
    Route::delete('delete-complaint/{id}', [ComplaintController::class, 'deleteComplaint']);

    //Houses
    Route::post('active-house', [HouseController::class, 'activeHouse']);
});
