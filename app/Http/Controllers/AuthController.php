<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(): View
    {
        return view("auth.login");
    }

    public function authenticate(Request $request): RedirectResponse
    {
        // form validation

        $credentials = $request->validate(
            [
                "username" => "required|min:3|max:30",
                "password" => "required|min:8|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/"
            ],
            [
                "username.required" => "O usuario é obrigatorio",
                "username.min" => "O usuario deve ter no minimo  :min caracteres",
                "username.max" => "O usuario deve ter no maximo  :max caracteres",
                "password.required" => "A senha é obrigatoria",
                "password.min" => "A senha deve ter no minimo  :min caracteres",
                "password.max" => "A senha deve ter no maximo  :max caracteres",
                "password.regex" => "A senha deve conter letras maiusculas, minusculas e numeros"
            ]
        );

        // // login tradicional do Laravel
        // if(Auth::attempt($credentials)){
        //     $request->session()->regenerate();
        //     redirect()->route("home");
        // }; // só usar se tem eial e password


        // verificar se o user existe
        $user = User::where("username", $credentials["username"])
        ->where("active", true)
        ->where(function($query){
            $query->whereNull("blocked_until")
            ->orWhere("blocked_until", "<=", now());
        })
        ->whereNotNull("email_verified_at")
        ->whereNull("deleted_at")
        ->first();

        // verificar se o userr existe
        if(!$user){
            return back()->withInput()->with([
                "invalid_login" => "Login invalido"
            ]);
        }

        // verificar se a password e valida
        if(!Hash::check($credentials["password"], $user->password)){
            return back()->withInput()->with([
                "invalid_login" => "Login invalido"
            ]);
        }


        // atualizar o ultimo login (las_login)

        $user->last_login_at = now();
        $user->blocked_until = null;
        $user->save();

        // login propriamente dito!

        $request->session()->regenerate();
        Auth::login($user);

        // redirecionar
        return redirect()->intended(route("home"));
    }

    public function logout(): RedirectResponse
    {
        // logout
        Auth::logout();
        return redirect()->route("login");

    }

    public function register(): view
    {
        return view("auth.register");
    }

    public function store_user(Request $request): void
    {
        // form validation
        $request->validate(
            [
                "username" => "required|min:3|max:30|unique:users,username",
                "email" => "required|email|unique:users,email",
                "password" => "required|min:8|max:32|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",
            ],
            [
                "password.required" => "A senha é obrigatória",
                "password.min" => "A senha deve ter no mínimo :min caracteres",
                "password.max" => "A senha deve ter no máximo :max caracteres",
                "password.confirmed" => "A confirmação da senha não corresponde.",
                "password.regex" => "A senha deve conter letras maiúsculas, minúsculas e números"
        ]
        );

        // VAMOS CRIAR UM NOVO USUÁRIO DEFINIDO UM TOKEN DE VERIFICAÇÃO DE EMAIL

        $user = new User();
        $user->username = $request->input("username");
        $user->email = $request->input("email");
        $user->password = Hash::make($request->input("password"));
        $user->toke = Str::random(64); // cria um toke com 64 strings
        dd($user);


    }
}
