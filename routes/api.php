<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;

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


//Authentication Api
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});

/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


Route::controller(ApiController::class)->group(function (){
    Route::post('create_courses',  'create_courses');
    Route::post('upload_videos_course_id',  'upload_videos_course_id');
    Route::post('search_course',  'search_course');
    Route::POST('update_user',  'update_user');
    Route::post('create_feedback',  'create_feedback');
    Route::post('send', 'sendMessages');
    Route::post('create_room', 'createRoom');
    Route::post('join_to_room', 'joinRoom');
    Route::post('upload_file', 'uploadFile');
    Route::post('add_room_fav', 'AddRoomFav');
    Route::post('add_course_fav', 'AddCourseFav');
    Route::post('reset_password', 'reset')->name('reset2_save');
    Route::delete('remove_user',  'remove_user');
    Route::delete('remove_fav_course', 'removeFavCourse');
    Route::delete('remove_fav_room', 'removeFavRoom');
    Route::get('show_videos_course_id',  'show_videos_course_id');
    Route::get('show_all_course',  'show_all_course');
    Route::get('show_fav_room', 'showFavRoom');
    Route::get('show_fav_course', 'showFavCourse');
    Route::get('show_feedbacks_Course_id',  'show_feedbacks_Course_id');
    Route::get('get_profile', 'getProfile');
    Route::get('get_my_rooms', 'getMyRooms');
    Route::get('get_my_courses', 'getMyCourses');
});

