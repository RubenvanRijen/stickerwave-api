<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;


class JwtAuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validate incoming login request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            // Return validation error if validation fails
            return response()->json(['error' => 'Validation failed', 'data' => $validator->errors()], 422);
        }

        // Attempt to authenticate user
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            // Return error for invalid credentials
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = auth()->user();

        if (!$user->email_verified_at) {
            // Return error if user's email is not verified
            return response()->json(['error' => 'Email not verified'], 403);
        }

        // create the token and return a response with token and cookie
        $token = $this->createToken($request);
        return $this->createTokenResponse($token, "Successfully logged in", 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        // Validate incoming registration request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            // Return validation error if validation fails
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new user with hashed password
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->verification_token = Str::random(40);
        // set the users email straight to verified, should not be done.
        // But to speed up the progress of this project this has been set.
        $user->email_verified_at = now();
        $user->verification_token = null;

        $user->save();

        //for now add this code so that the user is automatically logged in
        // create the token and return a response with token and cookie
        $token = $this->createToken($request);
        // Return success response with registered user information
        return $this->createTokenResponse($token, "User successfully made", 201);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        // Logout the authenticated user
        auth()->logout();
        return response()->json(['message' => 'User logged out successfully']);
    }

    /**
     * @return JsonResponse|String
     */
    private function createToken(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return $token;
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        // Refresh the token and return a new token
        $token = auth()->refresh();

        return $this->createTokenResponse($token, "Refreshed the token successfully", 200);
    }

    /**
     * @return JsonResponse
     */
    public function getCurrentUser(): JsonResponse
    {
        // Get the authenticated user's information
        $user = auth()->user();

        if (!$user) {
            // Return error if user is not authenticated
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Return the user's information
        return response()->json($user);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendEmailVerification(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = new User();
        $user = $user->where('email', $request->input('email'))->first();

        if ($user->hasVerifiedEmail()) {
            // Return message if email is already verified
            return response()->json(['message' => 'Email is already verified'], 200);
        }

        // Generate the verification link
        $verificationUrl = $this->generateVerificationUrl($user);

        // Return success response
        return response()->json(['url' => $verificationUrl]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createNewVerificationLink(Request $request): JsonResponse
    {
        $user = new User();
        $user = $user->where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->verification_token = Str::random(40);
        $user->save();

        // generate a new verification url
        $verificationUrl = $this->generateVerificationUrl($user);

        return response()->json(['url' => $verificationUrl]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyEmail(Request $request): JsonResponse
    {
        // Find the user by the verification token
        $user = new User();
        $user = $user->where('verification_token', $request->verification_token)->first();

        if (!$user) {
            // Return error if the token is invalid
            return response()->json(['message' => 'Invalid verification token'], 400);
        }

        // Mark the user's email as verified and clear the verification token
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        // Return success response
        return response()->json(['message' => 'Email verified successfully']);
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    protected function createTokenResponse(string $token, string $message = "", int $responseCode): JsonResponse
    {
        // Set a cookie
        $cookie = Cookie::make('jwt_token', $token, 60); // 60 minutes
        // Create a new token response with necessary metadata
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
            'message' => $message
        ], $responseCode)->withCookie($cookie);
    }

    /**
     * @param User $user
     * @return string
     */
    protected function generateVerificationUrl(User $user): string
    {
        // Generate a signed route URL for email verification
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['verification_token' => $user->verification_token]
        );
    }
}
