<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Mentor\MentorController;
use App\Http\Controllers\Api\Absensi\AbsensiController;
use App\Http\Controllers\Api\Schedule\ScheduleController;
use App\Http\Controllers\Api\Pengumuman\PengumumanController;
use App\Http\Controllers\Api\Bookmark\BookmarkController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum', 'ability:member,admin,mentor')->group(function () {
    Route::get('userdata', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('Schedules', [ScheduleController::class, 'getAllSchedule']);
    Route::get('Schedule/{id}', function ($id) {
        return ScheduleController::getSchedule($id);
    });
    Route::get('Announcement', [PengumumanController::class, 'getAllPengumuman']);
    Route::get('Announcement/{id}', function ($id) {
        return PengumumanController::getAnnouncementById($id);
    });
});

//route atmin
Route::post('CreateAccount', [AdminController::class, 'createAccount'])->middleware('auth:sanctum', 'ability:admin');

//route mentor
Route::middleware('auth:sanctum', 'ability:mentor')->group(function () {
    Route::get('Members/{divisi}', function (Request $request, $divisi) {
        return MentorController::showMembersByDivision($request, $divisi);
    });
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

    Route::post('announcement', [PengumumanController::class, 'createAnnouncement']);
    Route::patch('announcement/{id}', function (Request $request, $id) {
        return PengumumanController::updateAnnouncement($request, $id);
    });
    Route::delete('announcement/{id}', function ($id) {
        return PengumumanController::deleteAnnouncement($id);
    });
});

//route bookmark
Route::middleware('auth:sanctum', 'ability:member,admin,mentor')->group(function () {
    Route::post('/announcement/bookmark', [BookmarkController::class, 'bookmark']);
    Route::delete('/announcement/bookmark/{id_pengumuman}', function ($id_pengumuman) {
        return BookmarkController::unbookmark($id_pengumuman);
    });
    Route::get('/announcement/bookmark', [BookmarkController::class, 'showBookmarkByUser']);
});
