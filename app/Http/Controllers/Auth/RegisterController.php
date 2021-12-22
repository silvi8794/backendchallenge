<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\ApiController;

use App\User as ModelsUser;
/**
* [@OA\Info(title="API Proyectos", version="1.0")]
*
* @OA\Server(url="http://localhost:8000/")
*/
class RegisterController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    public $loginAfterSignUp = true;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

       /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Sign up",
     *     description="Registra un usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del usuario",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="name", type="string", format="text", example="Marina"),
     *       @OA\Property(property="email", type="string", format="email", example="marina@gmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="El email ya existe",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Error, el email ya existe")
     *        )
     *     )
     * )
     */ 

    public function register(Request $request)
    {
        try {
            
            $userExist = User::where('email', $request->email)->first();

             if (is_null($userExist)){
                
                 
                 $user = ModelsUser::create([
                     'name'                 => $request->name,
                     'email'                 =>  $request->email,
                     'password'              =>  Hash::make($request->password),
                     
                ]);             
               
                
                 return $this->showOne($user, 201);
          }
        else{
                 return $this->errorResponse('El mail ya existe', 401);
         }
    }
        catch (\Exception $e) {
            return response()-> json($e);
        }
    }

}
