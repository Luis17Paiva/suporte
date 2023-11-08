<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class LoginAndRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login and Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users and registering new users for the application.
    | The controller uses two traits to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, RegistersUsers;

    public function showLoginAndRegisterForm()
    {
        return view('auth.login2');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // Se o login falhar, exibe uma mensagem de erro
        return redirect()->route('login')->with('error', 'Usuário ou senha incorretos. Por favor, tente novamente.');
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */


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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'E-mail' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'Senha' => ['required', 'string', 'min:8', 'confirmed'],
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['E-mail'],
            'password' => Hash::make($data['Senha']),
        ]);
    }


    protected function guard()
    {
        return Auth::guard();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/relatorios');
    }

    public function register(Request $request)
    {
        // Valida os dados do usuário
        $this->validator($request->all())->validate();

        // Cria o novo usuário
        $user = $this->create($request->all());

        // Faz login do usuário
        Auth::login($user);

        // Redireciona o usuário para a página inicial
        return redirect()->route('central');
    }
    

}
