<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Error;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminController extends Controller
{
    public function createAccount(Request $request)
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
                    'role' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()
                ], 200);
            }

            User::create(
                [
                    'nama' => $request->nama,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password,
                    'divisi' => $request->divisi,
                    'fakultas' => $request->fakultas,
                    'prodi' => $request->prodi,
                    'nim' => $request->nim,
                    'role' => $request->role,
                ]
            );

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Account Created Successfully',
                ],
                200
            );
        } catch (Throwable $err) {
            return response()->json(
                [
                    'status' => false,
                    'message' => $err->getMessage(),
                ],
                200
            );
        }
    }
}
