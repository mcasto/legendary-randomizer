<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends ResController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return 'fail';
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']); // Enkripsi password
        $input['role'] = 'user';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role admin
        ]);

        event(new Registered($user));

        $success['token'] =  $user->createToken('AuthToken')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse(200, 'User register successfully.', $success);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();

            return response()->json([
                'status' => 'success',
                'token' => $user->createToken('AuthToken')->plainTextToken,
                'user' => [
                    'default_view' => $user->default_view,
                    'name' => $user->name
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password'
            ]);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json(['status' => 'success']);
    }
}
