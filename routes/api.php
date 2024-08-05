<?php

use App\Http\Controllers\Api\Absensi\AbsensiController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Mentor\MentorController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\Schedule\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum', 'ability:member,admin,mentor')->group(function () {
    Route::get('userdata', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('Schedules', [ScheduleController::class, 'getAllSchedule']);
    Route::get('Schedule/{id}', function ($id) {
        return ScheduleController::getSchedule($id);
    });
});

//route atmin
Route::post('CreateAccount', [AdminController::class, 'createAccount'])->middleware('auth:sanctum', 'ability:admin');

//route mentor
Route::middleware('auth:sanctum', 'ability:mentor')->group(function () {
    Route::get('MembersByDivisi', [MentorController::class, 'showMembersByDivision']);
    Route::get('Members', [MentorController::class, 'showAllMembers']);
    Route::get('Member/{id}', function ($id) {
        return MentorController::showMember($id);
    });
    Route::post('Schedule', [ScheduleController::class, 'createSchedule']);
    Route::patch('Schedule/{id}', function (Request $request, $id) {
        return ScheduleController::updateSchedule($request, $id);
    });
    Route::delete('Schedule/{id}', function ($id) {
        return ScheduleController::deleteSchedule($id);
    });
    Route::get('Schedule/{id}/Attendance', function ($id) {
        return AbsensiController::getAttendance($id);
    });
    Route::post('Schedule/{id}/Attendance', function (Request $request, $id) {
        return AbsensiController::attend($request, $id);
    });

    //route member
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('posts', [MemberController::class, 'createPost']);
        Route::post('posts/{postId}/comments', [MemberController::class, 'commentOnPost']);
    });

});
