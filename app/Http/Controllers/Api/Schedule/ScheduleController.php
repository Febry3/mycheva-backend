<?php

namespace App\Http\Controllers\Api\Schedule;

use Throwable;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    private static function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'judul' => 'required',
            'deskripsi' => 'required',
            'ruangan' => 'required',
            'jenis_kegiatan' => 'required',
            'divisi' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_berakhir' => 'required',
        ]);
    }

    public static function authenticateUser($user)
    {
        if ($user->role != 'mentor') {
            throw ValidationException::withMessages(['Unauthenticated']);
        }
        return true;
    }

    static public function createSchedule(Request $request)
    {
        $validateRequest = self::validateRequest($request);
        if ($validateRequest->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validateRequest->errors()
                ],
                400
            );
        }

        if (!self::authenticateUser(Auth::User())) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            Jadwal::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'ruangan' => $request->ruangan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'divisi' => $request->divisi,
                'id_user' => Auth::user()->id
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data Created',
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

    static public function updateSchedule(Request $request, $id)
    {
        $validateRequest = self::validateRequest($request);
        if ($validateRequest->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validateRequest->errors()
                ],
                400
            );
        }

        if (!self::authenticateUser(Auth::user())) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            if (!Jadwal::find($id)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data not found',
                    ],
                    200
                );
            }

            Jadwal::where('id_jadwal', "=", $id)->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'ruangan' => $request->ruangan,
                'jenis_kegiatan' => $request->jenis_kegiatan,
                'divisi' => $request->divisi,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_berakhir' => $request->tanggal_berakhir,
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data Updated',
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

    static public function deleteSchedule($id)
    {
        if (!self::authenticateUser(Auth::user())) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            if (!Jadwal::find($id)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data not found',
                    ],
                    200
                );
            }

            Jadwal::find($id)->delete();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Data deleted',
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

    static public function getAllSchedule()
    {

        try {
            $data = Jadwal::all();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Success',
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

    static public function getSchedule($id)
    {
        try {

            if (!Jadwal::find($id)) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data not found',
                    ],
                    200
                );
            }

            $data = Jadwal::find($id);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Success',
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
}
