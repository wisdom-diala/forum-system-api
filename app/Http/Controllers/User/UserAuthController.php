<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:filter', 'max:255'],
            'name' => ['required', 'max:255'],
            'password' => ['required', 'confirmed', 'string', 'min:5'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first()
            ]);
        }

        # check if user uploads profile image
        if ($request->hasFile('profile_image')) {
            # validate profile image
            $validate_image = Validator::make($request->all(), [
                'profile_image' => ['image']
            ]);

            if ($validate_image->fails()) {
                return response()->json([
                    'errors' => $validate_image->errors()->first()
                ]);
            }
            $profile_image = $request->profile_image->store('profile_images', 'public');
            # save user records
            if (User::create(array_merge($validator->validated(), 
                [
                    'profile_image' => $profile_image,
                    'password' => Hash::make($request->password)
                ]
            ))) {
                return response()->json([
                    'message' => 'registered successfully'
                ]);
            }else{
                return response()->json([
                    'message' => 'an error occurred'
                ], 500);
            }
        }

        # save user records if no image was uploaded
        if (User::create(array_merge($validator->validated(),
        [
            'password' => Hash::make($request->password)
        ] 
        ))) {
            return response()->json([
                'message' => 'registered successfully'
            ]);
        }else{
            return response()->json([
                'message' => 'an error occurred'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:filter'],
            'password' => 'required'
        ]);
        # return first validation error if validation fails
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->first()
            ], 401);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'errors' => 'Invalid email or password'
            ], 401);
        }

        $token = Auth::user()->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'user' => Auth::user(),
            'token' => $token
        ]);
    }

    public function logout()
    {
        try {
            if (auth()->user()->currentAccessToken()->delete()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'user logged out successfully.'
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'errors' => 'an error occured while logging user out'
                ], 501);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'errors' => 'an exceptional error occurred'
            ], 501);
        } catch (\Error $e) {
            return response()->json([
                'status' => 'failed',
                'errors' => 'an error occurred'
            ], 501);
        }
    }
}
