<?php
/**
 * @OA\Info(
 *     title="User API",
 *     version="1.0.0",
 *     description="API for managing users",
 *     @OA\Contact(
 *         email="mohamadtalemsi@gmail.com"
 *     ),
 *     @OA\License(
 *         name="abdellah talemsi",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController

{
    /**
     * Register api
     *
     * @return Response
     */

    /** get all users */
    public function index()
    {

    }

    /**
     * Register a new user.
     *
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration details",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "c_password"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="c_password",
     *                     type="string",
     *                     description="Confirm password, must match the 'password' field"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Access token for the registered user"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the registered user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid."
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 description="Validation errors",
     *                 example={
     *                     "email": {"The email field is required."},
     *                     "password": {"The password field is required."},
     *                     "name": {"The name field is required."}
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return JsonResponse
     */
    /**
     * Login a user.
     *
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User login credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="Access token for the logged-in user"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Name of the logged-in user"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Unauthorised"
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
                'userId' => Auth::user()->id,
            ]);

        } else {
            return response()->json(['success' => false, 'error' => 'Unauthorised'], 401);
        }
    }
    /**
     * Logs out a user and revokes the current access token.
     *
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout a user",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="status", type="boolean")
     *         )
     *     ),
     * )
     */
    public function logout(): JsonResponse
    {
        try {
            Auth::logout(); // Logout the user
            return response()->json(['success' => true, 'message' => 'Logout successful']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

}
