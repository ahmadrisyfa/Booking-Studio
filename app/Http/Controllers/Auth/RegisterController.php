<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Mail\VerivikasiLogin;
use Illuminate\Support\Facades\Mail;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'status' => 'Non Aktif',
            'password' => Hash::make($data['password']),
        ]);

        $userId = $user->id; 
        $userName = $data['name'];
        $email = $data['email'];

        $user->roles()->attach(2);

        Mail::to($data['email'])->send(new VerivikasiLogin($userName, $email, $userId));

        return $user;
    }

    
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|max:255',
    //         'email' => 'required|unique:users',
    //         'password' => 'required|min:5|max:255|confirmed'
    //     ]);
        
    //     $validatedData['password'] = Hash::make($validatedData['password']);
        
    //     $user = User::create($validatedData);
        
    //     $userId = $user->id; 
    //     $userName = $validatedData['name'];
    //     $email = $validatedData['email'];
    
    //     Mail::to($validatedData['email'])->send(new VerivikasiLogin($userName, $email, $userId));
    
    //     return redirect('/login')->with('berhasil_registrasi', 'Registrasi Berhasil! Silahkan Cek Gmail, Untuk Mengaktifkan Akun Anda');
    // }
    
}
