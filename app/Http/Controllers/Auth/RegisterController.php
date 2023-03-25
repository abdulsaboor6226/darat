<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

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
            'phone' => 'unique:users,phone',
            // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        $validator = Validator::make($request->all(), [
            'phone' => 'unique:users,phone',
        ]);
        if ($validator->fails()) {
            $message = 'phone number already existed';
           return response()->json(['status' => false, 'message' => $message, 'data' => []], 200);
        }
        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
       return  response()->json(['status' => true, 'message' => 'Register Successfully'], 200);
        // return $request->wantsJson()
        // ? response()->json(['status' => true, 'message' => 'Register Successfully'], 200)
        // : redirect($this->redirectPath());
    }
    protected function create($data)
    {
        $user_type = '';
        if ($data->construction_company  || $data->real_state_agent || $data->architect) {
            $user_type = 'business';
        } else {
            $user_type = 'personal';
        }
        return User::create([
            'name' => $data->name,
            'ceo_name' => $data->ceo_name,
            'email' => $data->email,
            'phone' => $data->phone,
            'company_name' => $data->company_name,
            'device' => $data->device,
            'mobile_os' => $data->mobile_os,
            'password' => bcrypt($data->password),
            'construction_company' => $data->construction_company ? true : false,
            'real_state_agent' => $data->real_state_agent  ? true : false,
            'architect' => $data->architect  ? true : false,
            'user_type' => $user_type,
        ]);
    }
    //     DB_USERNAME=unitxsol_daratadmin
    // DB_PASSWORD=93RCfWDwHeA$
}
