<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
                    'username' => 'required|unique:users,username',
                    'email' => 'required|unique:users,email|email',
                    'password' => 'required',
                    'divisi' => 'required',
                    'fakultas' => 'required',
                    'prodi' => 'required',
                    'nim' => 'required|unique:users,nim',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()
                ], 200);
            }

            $user = User::create(
                [
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password,
                    'divisi' => $request->divisi,
                    'fakultas' => $request->fakultas,
                    'prodi' => $request->prodi,
                    'nim' => $request->nim,
                ]
            );

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Account Created Successfully',
                    'token' => $user->createToken('API')->plainTextToken
                ],
                200
            );
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage()
                ],
                500
            );
        }
    }

    public function login(Request $request)
    {

        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'username' => 'required',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['username', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Username or Password Does Not Match',
                ], 401);
            }

            $user = User::where('username', $request->username)->first();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Logged In Successfully',
                    'token' => $user->createToken('API', [$user->role])->plainTextToken
                ],
                200
            );
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage()
                ],
                500
            );
        }
    }

    // public function profile()
    // {
    //     try {
    //         // $userData = auth()->user();
    //         return response()->json(
    //             [
    //                 'status' => true,
    //                 'message' => 'Get Data Success',
    //                 'data' => $userData,
    //             ],
    //             200
    //         );
    //     } catch (Throwable $err) {
    //         return response()->json(
    //             [
    //                 'status' => false,
    //                 'message' => $err->getMessage()
    //             ],
    //             500
    //         );
    //     }
    // }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'status' => true,
                'message' => 'Logged Out',
            ],
            200
        );
    }
}
