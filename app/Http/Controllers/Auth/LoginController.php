<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session as FacadesSession;
use App\Http\Controllers\ApiController;

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

    public function login(Request $request)
    {

         $credentials= $request->only('email', 'password');
        
          if (!$token = auth()->attempt($credentials)) {
             return $this->errorResponse('No Autorizado', 401);
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
