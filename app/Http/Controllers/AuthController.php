<?php

namespace App\Http\Controllers;

use App\Mail\NewUserConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
// A importação do Carbon não é mais estritamente necessária se usarmos o helper now()
// mas é bom manter para outras possíveis utilizações.
use Illuminate\Support\Facades\Carbon;
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

    public function store_user(Request $request): RedirectResponse|View
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
        $user->token = Str::random(64); // cria um toke com 64 strings

        // gerar link
        $confirmation_link = route("new_user_confirmation", ["token" => $user->token]);

        // enviar email
        Mail::to($user->email)->send(new NewUserConfirmation($user->username, $confirmation_link));

        // criar o user na base de dados
        $user->save();

        // apresentar view de sucesso
        return view("auth.mail_sent", ["email" => $user->email]);
    }

    public function new_user_confirmation($token)
    {
        // verificar se o token e valido
        $user = User::where("token", $token)->first();
        if(!$user){
            return redirect()->route("login");
        }

        // confirmar o registro do usuário
        $user->email_verified_at = now(); // CORREÇÃO 1: Usando o helper now()
        $user->token = null;
        $user->active = true; // CORREÇÃO 2: Corrigido de 'action' para 'active'
        $user->save();

        //autenticação automatica (login) do usuário confirmado
        Auth::login($user);

        // apresenta uma mensagem de sucesso
        return view("auth.new_user_confirmation");
    }

    public function profile(): view
    {
        return view("auth.profile");
    }
    public function change_password(Request $request)
    {
        // FORM VALIDATION
        $request->validate(
            // Regras de Validação
            [
                'current_password' => [
                    'required',
                    'min:8',
                    'max:32',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
                ],
                'new_password' => [
                    'required',
                    'min:8',
                    'max:32',
                    'different:current_password',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
                ],
                'new_password_confirmation' => [
                    'required',
                    'same:new_password'
                ],
            ],
            // Mensagens de Erro Customizadas
            [
                'current_password.required' => 'A senha atual é obrigatória.',
                'current_password.min'      => 'A senha atual deve conter no mínimo :min caracteres.',
                'current_password.max'      => 'A senha atual deve conter no máximo :max caracteres.',
                'current_password.regex'    => 'A senha atual deve conter pelo menos uma letra maiúscula, uma minúscula e um número.',

                'new_password.required' => 'A nova senha é obrigatória.',
                'new_password.min'      => 'A nova senha deve conter no mínimo :min caracteres.',
                'new_password.max'      => 'A nova senha deve conter no máximo :max caracteres.',
                'new_password.regex'    => 'A nova senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número.',
                'new_password.different' => 'A nova senha deve ser diferente da senha atual.',

                'new_password_confirmation.required' => 'A confirmação da nova senha é obrigatória.',
                'new_password_confirmation.same'     => 'A confirmação da nova senha deve ser igual à nova senha.',
            ]
        );
     // verificar se a password atual (current_password) esta correta
     if(!password_verify($request->current_password, Auth::user()->password))
     {
        return back()->with([
            "server_error" => "A senha atual está incorreta."
        ]);
     }

     // atualizar a senha na base de dados
     $user = Auth::user();
     $user->password = bcrypt($request->new_password);
     $user->save();

     // atualizar a password na sessão
     Auth::user()->password = $request->new_password;

     // apresenta uma mensagem de sucesso
     return redirect()->route("profile")->with([
        "succes" => " A senha foi atualizada com sucesso!"
     ]);

    }
}
