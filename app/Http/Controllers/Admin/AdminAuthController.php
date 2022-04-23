<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        # authenticate the user
        if (!Auth::guard('admin')->attempt($request->only(['email', 'password']))) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        # create token for admin
        $admin = Admin::where('email', $request->email)->first();
        $token = $admin->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token
        ], 200);
    }

}
