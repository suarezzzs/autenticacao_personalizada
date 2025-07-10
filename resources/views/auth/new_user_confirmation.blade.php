<x-layouts.main-layout pageTitle="LOGIN">

<div class="container mt-5">
        <div class="row">
            <div class="col text-center">
                <div class="card p-5 text-center">
                    <p class="display-6">A sua conta de usuário foi confirmada com sucesso.</p>
                    <p class="display-6">Bem-vindo, <strong>{{ Auth::user()->username }}</strong></p>
                    <div class="mt-5">
                        <a href="{{ route("home") }}" class="btn btn-secondary px-5">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.main-layout>
