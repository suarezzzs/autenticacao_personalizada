<x-layouts.main-layout pageTitle="Novo Usuário">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card p-5">
                    <p class="display-6 text-center">CRIAR NOVA CONTA</p>

                    <form action="{{ route("register") }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuário</label>
                            <input type="text" class="form-control" id="username" name="username">
                            @error("username")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror  {{-- Correção: @enderror em vez de @endif --}}
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            @error("email")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror {{-- Correção: Removido o ">" extra e trocado @endif por @enderror --}}
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password">
                            @error("password")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror {{-- Correção: @enderror em vez de @endif --}}
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar senha</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            @error("password_confirmation")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mt-4">
                            <div class="col">
                                <div class="mb-3">
                                    <a href="{{ route("login") }}">Já tenho uma conta</a>
                                </div>
                                {{-- <div>
                                    <a href="#">Esqueci a minha senha</a>
                                </div> --}}
                            </div>
                            <div class="col text-end align-self-center">
                                <button type="submit" class="btn btn-secondary px-5">CRIAR CONTA</button>
                            </div>
                        </div>

                    </form>
                    @if(session("server_error"))
                        <div class="alert alert-danger text-center mt-3">
                            {{ session("server_error") }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-layouts.main-layout>
