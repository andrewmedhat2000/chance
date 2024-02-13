<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SurveyController;
use App\Http\Controllers\API\ChildController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\NoteController;
use App\Http\Controllers\API\TeacherController;
use App\Http\Controllers\dashboard\AuthController as dashauth;
use App\Http\Controllers\dashboard\ChildController as dashchild;
use App\Http\Controllers\dashboard\TeacherController as dashteacher;
use App\Http\Controllers\dashboard\NoteController as dashnote;
use App\Http\Controllers\dashboard\EventController as dashevent;
use App\Http\Controllers\dashboard\SurveyController as dashsurvey;
use App\Http\Controllers\dashboard\QuestionController as dashquestion;
use App\Http\Controllers\dashboard\AnswerController as dashanswer;
use App\Http\Controllers\dashboard\DepartmentController as dashdepartment;
use App\Http\Controllers\dashboard\NoteCategoryController as dashnotecategory;
use App\Http\Controllers\dashboard\TodayChildsController;
use App\Http\Controllers\dashboard\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('auth/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->post('survey/make_survey', [SurveyController::class, 'make_survey']);
Route::middleware('auth:api')->post('survey/save_answer', [SurveyController::class, 'save_answer']);
Route::middleware('auth:api')->post('survey/get_child_report', [SurveyController::class, 'get_child_report']);
Route::middleware('auth:api')->post('survey/get_teacher_report', [SurveyController::class, 'get_teacher_report']);
Route::middleware('auth:api')->get('user/profile_details', [ChildController::class, 'profile_details']);
Route::middleware('auth:api')->get('user/get_profile_by_id/{id}', [UserController::class, 'get_profile_by_id']);
Route::middleware('auth:api')->post('user/updateProfile', [UserController::class, 'updateProfile']);
Route::middleware('auth:api')->get('event/all_events', [EventController::class, 'all_events']);
Route::middleware('auth:api')->get('notes/get_notes', [NoteController::class, 'get_notes']);
Route::middleware('auth:api')->get('teacher/my_departments', [TeacherController::class, 'my_departments']);
Route::middleware('auth:api')->get('teacher/teacher_departments/{id}', [TeacherController::class, 'teacher_departments']);
Route::middleware('auth:api')->get('teacher/get_today_childs', [TeacherController::class, 'get_today_childs']);
//dashboard

Route::post('dashboard/user/login', [dashauth::class, 'login']);

Route::middleware('auth:api')->get('dashboard/childs/index',  [dashchild::class, 'index']);
Route::middleware('auth:api')->get('dashboard/childs/show/{id}',  [dashchild::class, 'show']);
Route::middleware('auth:api')->post('dashboard/childs/update/{id}',  [dashchild::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/childs/delete/{id}',  [dashchild::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/childs/create',  [dashchild::class, 'create']);

Route::middleware('auth:api')->get('dashboard/teachers/index',  [dashteacher::class, 'index']);
Route::middleware('auth:api')->get('dashboard/teachers/show/{id}',  [dashteacher::class, 'show']);
Route::middleware('auth:api')->post('dashboard/teachers/update/{id}',  [dashteacher::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/teachers/delete/{id}',  [dashteacher::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/teachers/create',  [dashteacher::class, 'create']);

Route::middleware('auth:api')->get('dashboard/teachers/index',  [dashteacher::class, 'index']);
Route::middleware('auth:api')->get('dashboard/teachers/show/{id}',  [dashteacher::class, 'show']);
Route::middleware('auth:api')->post('dashboard/teachers/update/{id}',  [dashteacher::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/teachers/delete/{id}',  [dashteacher::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/teachers/create',  [dashteacher::class, 'create']);

Route::middleware('auth:api')->post('dashboard/notes/index',  [dashnote::class, 'index']);
Route::middleware('auth:api')->get('dashboard/notes/show/{id}',  [dashnote::class, 'show']);
Route::middleware('auth:api')->post('dashboard/notes/update/{id}',  [dashnote::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/notes/delete/{id}',  [dashnote::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/notes/create',  [dashnote::class, 'create']);

Route::middleware('auth:api')->get('dashboard/events/index',  [dashevent::class, 'index']);
Route::middleware('auth:api')->get('dashboard/events/show/{id}',  [dashevent::class, 'show']);
Route::middleware('auth:api')->post('dashboard/events/update/{id}',  [dashevent::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/events/delete/{id}',  [dashevent::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/events/create',  [dashevent::class, 'create']);

Route::middleware('auth:api')->post('dashboard/survey/index',  [dashsurvey::class, 'index']);
Route::middleware('auth:api')->get('dashboard/survey/show/{id}',  [dashsurvey::class, 'show']);
Route::middleware('auth:api')->post('dashboard/survey/update/{id}',  [dashsurvey::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/survey/delete/{id}',  [dashsurvey::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/survey/create',  [dashsurvey::class, 'create']);

Route::middleware('auth:api')->post('dashboard/questions/index',  [dashquestion::class, 'index']);
Route::middleware('auth:api')->get('dashboard/questions/show/{id}',  [dashquestion::class, 'show']);
Route::middleware('auth:api')->post('dashboard/questions/update/{id}',  [dashquestion::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/questions/delete/{id}',  [dashquestion::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/questions/create',  [dashquestion::class, 'create']);

Route::middleware('auth:api')->post('dashboard/answers/index',  [dashanswer::class, 'index']);
Route::middleware('auth:api')->get('dashboard/answers/show/{id}',  [dashanswer::class, 'show']);
Route::middleware('auth:api')->post('dashboard/answers/update/{id}',  [dashanswer::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/answers/delete/{id}',  [dashanswer::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/answers/create',  [dashanswer::class, 'create']);

Route::middleware('auth:api')->post('dashboard/departments/index',  [dashdepartment::class, 'index']);
Route::middleware('auth:api')->get('dashboard/departments/show/{id}',  [dashdepartment::class, 'show']);
Route::middleware('auth:api')->post('dashboard/departments/update/{id}',  [dashdepartment::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/departments/delete/{id}',  [dashdepartment::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/departments/create',  [dashdepartment::class, 'create']);
Route::middleware('auth:api')->post('dashboard/departments/department_teacher',  [dashdepartment::class, 'department_teacher']);



Route::middleware('auth:api')->post('dashboard/note_category/index',  [dashnotecategory::class, 'index']);
Route::middleware('auth:api')->get('dashboard/note_category/show/{id}',  [dashnotecategory::class, 'show']);
Route::middleware('auth:api')->post('dashboard/note_category/update/{id}',  [dashnotecategory::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/note_category/delete/{id}',  [dashnotecategory::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/note_category/create',  [dashnotecategory::class, 'create']);


Route::middleware('auth:api')->post('dashboard/today_childs/index',  [TodayChildsController::class, 'index']);
Route::middleware('auth:api')->get('dashboard/today_childs/show/{id}',  [TodayChildsController::class, 'show']);
Route::middleware('auth:api')->post('dashboard/today_childs/update/{id}',  [TodayChildsController::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/today_childs/delete/{id}',  [TodayChildsController::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/today_childs/create',  [TodayChildsController::class, 'create']);

Route::middleware('auth:api')->get('dashboard/admins/index',  [AdminController::class, 'index']);
Route::middleware('auth:api')->get('dashboard/admins/show/{id}',  [AdminController::class, 'show']);
Route::middleware('auth:api')->post('dashboard/admins/update/{id}',  [AdminController::class, 'update']);
Route::middleware('auth:api')->delete('dashboard/admins/delete/{id}',  [AdminController::class, 'destroy']);
Route::middleware('auth:api')->post('dashboard/admins/create',  [AdminController::class, 'create']);
