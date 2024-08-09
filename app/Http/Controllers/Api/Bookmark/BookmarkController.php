<?php

namespace App\Http\Controllers\Api\Bookmark;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BookmarkController extends Controller
{
    static public function bookmark(Request $request)
    {
        try {
            Bookmark::create([
                'id_pengumuman' => $request->id_pengumuman,
                'id_user' => Auth::user()->id
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Bookmarked',
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

    static public function unbookmark($id)
    {
        try {
            Bookmark::where('id_pengumuman', '=', $id, 'and')->where('id_user', '=', Auth::user()->id)->delete();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Unbookmarked',
                ],
                200
            );
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => "false",
                    'message' => $err->getMessage()
                ],
                400
            );
        }
    }

    static public function showBookmarkByUser()
    {
        try {
            $data = Bookmark::where('bookmarks.id_user', '=', Auth::user()->id)->join('pengumumen', 'pengumumen.id_pengumuman', '=', 'bookmarks.id_pengumuman')->get();

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
