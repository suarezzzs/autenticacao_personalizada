<x-layouts.main-layout pageTitle="Esqueci a Senha">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card p-5">
                    <p class="display-6 text-center">RECUPERAR SENHA</p>

                    <form action="{{ route("send_reset_password_link") }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Indique o seu email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            @error("email")
                                <div class="text-danger">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <a href="{{ route("login") }}">Já sei a minha senha</a>
                                <br>
                                <a href="{{ route("register") }}">Criar nova conta</a>
                            </div>
                            <button type="submit" class="btn btn-secondary px-5">RECUPERAR</button>
                        </div>

                    </form>

                    @if(session("server_error"))
                        <div class="alert alert-danger mt-4 text-center">
                            {{ session("server_error") }}
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route("login") }}" class="btn btn-primary px-5">VOLTAR</a>
                        </div>
                    @endif

                    @if(session("server_message"))
                        <div class="alert alert-success mt-4 text-center">
                            {{ session("server_message") }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

</x-layouts.main-layout>
