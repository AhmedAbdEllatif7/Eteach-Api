<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\ApiTrait;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiTrait;
    public function __construct()
    {
        $this->middleware('JWTMiddleware', ['except' => ['login','store','register']]);
    }

/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/



    //LOGIN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email', 'password' => 'required',],);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $emailErrors = $errors->first('email');
            // Do something with the error messages
            if ($emailErrors) {
                return $this->ApiResponse($emailErrors, 404, '');
            }
            $errors = $validator->errors();
            $passwordErrors = $errors->first('password');
            // Do something with the error messages
            if ($passwordErrors) {
                return $this->ApiResponse($passwordErrors, 404, '');
            }
        }

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return $this->ApiResponse("Credentials Not Found", 404, '');
        }

        $user = Auth::user();
        $user->token = $token;
        return $this->ApiResponse('Logged In Successful', 200, $user);
    }



/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/



    //REGISTER
    public function register(Request $request){

        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255|regex:/^[a-zA-Z ]+$/',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|min:6' ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $emailErrors = $errors->first('email');
            // Do something with the error messages
            if ($emailErrors) {
                return $this->ApiResponse($emailErrors, 404, '');
            }

            $passwordErrors = $errors->first('password');
            // Do something with the error messages
            if ($passwordErrors) {
                return $this->ApiResponse($passwordErrors, 404, '');
            }


            $nameErrors = $errors->first('name');
            // Do something with the error messages
            if ($nameErrors) {
                return $this->ApiResponse($nameErrors, 404, '');
            }
        }
    if($request->type == '')
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'student',
    ]);
        $token = Auth::login($user);
        $user = Auth::user();
        $user->token = $token;
        return $this->ApiResponse('User created successfully', 200, $user);
    }
    else {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        $token = Auth::login($user);
        $user = Auth::user();
        $user->token = $token;
        return $this->ApiResponse('User created successfully', 200, $user);
    }

    }


/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/




    //LOGOUT
    public function logout()
    {
        Auth::logout();
        return $this->ApiResponse('Successfully logged out' , 200 , '');
    }



/*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/



    //REFRESH
    public function refresh()
    {
        $token = Auth::refresh();
        $user = Auth::user();
        $user->token = $token;
        return $this->ApiResponse('Token Refreshed Successfully' , 200 , $user);
//
//        return response()->json([
//            'status' => 'success',
//            'user' => Auth::user(),
//            'authorisation' => [
//                'token' => Auth::refresh(),
//                'type' => 'bearer',
//            ]
//        ]);
    }

}
