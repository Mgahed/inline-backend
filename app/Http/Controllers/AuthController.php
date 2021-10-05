<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'social']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => 'Can not login user, Check errors response',
                "errors" => $validator->errors()
            ], 422);
        } else {
            if (strpos($request->username, '@')) {
                $credentials = ['email' => $request->username, 'password' => $request->password];
            } else {
                $credentials = ['phone_number' => $request->username, 'password' => $request->password];
            }
        }


        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                "status" => false,
                'message' => 'Unauthorized user check token',
                'error' => 'Unauthorized'
            ], 401);
        }

        return $this->createNewToken($token);
    }

    public function social(Request $request)
    {
//        return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                'message' => 'Can not login user, Check errors response',
                "errors" => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "status" => false,
                'message' => 'user not exist please register'
            ], 401);
        }
        if (!$token = auth('api')->login($user)) {
            return response()->json([
                "status" => false,
                'message' => 'Unauthorized user check token',
                'error' => 'Unauthorized'
            ], 401);
        }
//        return $token;

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'phone_number' => 'required|numeric|unique:users',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                'message' => 'Error in registering user check errors response',
                "errors" => $validator->errors()
            ), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $credentials = ['phone_number' => $user->phone_number, 'password' => $request->password];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                "status" => false,
                'message' => 'Unauthorized user check token',
                'error' => 'Unauthorized'
            ], 401);
        }

        return $this->createNewToken($token);

//        return response()->json([
//            'status' => true,
//            'message' => 'User successfully registered',
//            'user' => $user
//        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => true,
            'message' => 'User successfully signed out'
        ], 201);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth('api')->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(
            [
                'status' => true,
//                'message' => 'User successfully registered',
                'user' => auth('api')->user()
            ], 201);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60 * 60 * 24,
            'user' => auth('api')->user()
        ]);
    }
}
