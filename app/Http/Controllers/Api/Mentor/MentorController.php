<?php

namespace App\Http\Controllers\Api\Mentor;

use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MentorController extends Controller
{
    static public function showAllMembers()
    {
        if (Auth::user()->role != 'mentor') {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'unauthenticated'
                ],
                401
            );
        }

        $data = User::where('role', '=', 'member')->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'Data Retrieved',
                'data' => $data
            ],
            200
        );
    }

    static public function showMembersByDivision(Request $request, $divisi)
    {

        if (Auth::user()->role != 'mentor') {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'unauthenticated'
                ],
                401
            );
        }

        $data = User::where('divisi', '=', $divisi, 'and')->where('role', '=', 'member')->get();

        return response()->json(
            [
                'status' => true,
                'message' => 'Data Retrieved',
                'data' => $data
            ],
            200
        );
    }

    static public function showMember($id)
    {


        if (Auth::user()->role != 'mentor') {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'unauthenticated'
                ],
                401
            );
        }

        $data = User::find($id);

        return response()->json(
            [
                'status' => true,
                'message' => 'Data Retrieved',
                'data' => $data
            ],
            200
        );
    }
}
