<?php

namespace App\Http\Controllers\Api\Absensi;

use App\Models\User;
use App\Models\Jadwal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\Schedule\ScheduleController;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Throwable;

class AbsensiController extends Controller
{
    static public function getAttendance($id)
    {
        if (!ScheduleController::authenticateUser(Auth::User())) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            $jadwal = Jadwal::find($id);

            if (!$jadwal) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data not found',
                    ],
                    200
                );
            }

            $isAlready = Presensi::where('id_jadwal', '=', $id)->take(1)->get()->isEmpty();

            if ($isAlready) {
                $data = User::where('divisi', '=', $jadwal->divisi, 'and')->where('role', '=', 'member')->get();
            } else {
                $data = User::where('divisi', '=', $jadwal->divisi, 'and')->where('role', '=', 'member')->join('presensis', 'presensis.id_user', '=', 'users.id')->get();
            }


            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data retrieved',
                    'data' => $data
                ],
                200
            );
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage()
                ],
                400
            );
        }
    }

    static public function attend(Request $request, $id)
    {
        if (!ScheduleController::authenticateUser(Auth::User())) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            $isAlready = Presensi::where('id_jadwal', '=', $id)->take(1)->get()->isEmpty();

            if ($isAlready) {

                foreach ($request->all() as $req) {
                    Presensi::create([
                        'id_jadwal' => $id,
                        'id_user' => $req['id_user'],
                        'status' => $req['status']
                    ]);
                }

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Data submitted',
                    ],
                    200
                );
            } else {

                foreach ($request->all() as $req) {
                    Presensi::where('id_jadwal', '=', $id, 'and')->where('id_user', '=', $req['id_user'])->update([
                        'status' => $req['status']
                    ]);
                }

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Data submitted',
                    ],
                    200
                );
            }
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage()
                ],
                400
            );
        }
    }
}
