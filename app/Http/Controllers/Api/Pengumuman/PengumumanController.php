<?php

namespace App\Http\Controllers\Api\Pengumuman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PengumumanController extends Controller
{
    static public function createAnnouncement(Request $request)
    {
        $validateRequest = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required'
        ]);

        if ($validateRequest->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validateRequest->errors()
                ],
                400
            );
        }

        if (Auth::user()->role != 'mentor') {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            Pengumuman::create([
                'judul' => $request->judul,
                'isi' => $request->isi,
                'id_user' => Auth::user()->id
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Created'
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

    static public function updateAnnouncement(Request $request, $id)
    {
        $validateRequest = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required'
        ]);

        if ($validateRequest->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $validateRequest->errors()
                ],
                400
            );
        }

        if (Auth::user()->role != 'mentor' && Auth::user()->id != Pengumuman::find($id)->id_user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            Pengumuman::find($id)->update([
                'judul' => $request->judul,
                'isi' => $request->isi
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Edited'
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

    static public function deleteAnnouncement($id)
    {
        if (Auth::user()->role != 'mentor' && Auth::user()->id == Pengumuman::find($id)->id_user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Forbidden'
                ],
                403
            );
        }

        try {
            Pengumuman::find($id)->delete();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Deleted'
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

    static public function getAllAnnouncement()
    {
        try {
            $data = Pengumuman::all();

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

    static public function getAnnouncementById($id)
    {
        try {
            $data = Pengumuman::find($id)->get();

            if ($data->isEmpty()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Data not found',
                    ],
                    200
                );
            }

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
