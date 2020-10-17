<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;

class JWTAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed|string|min:6',
            'password_confirmation' => 'min:6',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'registration failed',
                'errors' => $validator->errors()
            ], 401);

        }else{

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($request->password)]
            ));

            return response()->json([
                'status' => true,
                'message' => 'Successfully registered',
                'user' => $user
            ], 201);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

         if (! $token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

         return response()->json([
            'status' => true,
            'message' => 'Login successfully',
            'token' => $this->createNewToken($token),
        ], 201);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
