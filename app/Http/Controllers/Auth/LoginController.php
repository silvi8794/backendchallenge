<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Http\Controllers\ApiController;

/**
* @OA\Info(title="API Proyectos", version="1.0")
*
* @OA\Server(url="http://swagger.local")
*/

class LoginController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Sign in",
     * description="Login con email, password",,
     * @OA\RequestBody(
     *    required=true,
     *    description="Pasar credenciales de usuario",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="persistent", type="boolean", example="true"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="El email o password son incorrectos",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Error, el email o password son incorrectos")
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {

         $credentials= $request->only('email', 'password');
        
          if (!$token = auth()->attempt($credentials)) {
             return $this->errorResponse('Error, el email o password son incorrectos', 422);
           }

           return $this->respondWithToken($token);

    }

     protected function respondWithToken($token)
     {
       return response()->json([
         'access_token' => $token,
         'token_type' => 'bearer',
         'expires_in' => auth()->factory()->getTTL() * 60
       ]);
     }
}
