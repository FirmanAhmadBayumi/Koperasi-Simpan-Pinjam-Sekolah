<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <body
        style="background-image: url(assets/img/kaiadmin/bg-login.jpg); background-repeat: no-repeat; background-size: cover; background-position: center;">
        <div id="layoutAuthentication" class="d-flex align-items-center justify-content-center min-vh-100">
            <div id="layoutAuthentication_content" class="w-100 position-relative">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg">
                                    <div class="card-header text-center" style="padding: 0;">
                                        <img src="assets/img/kaiadmin/sideBarLogo.png" alt="Logo" style="width: 200px;">
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" action="{{ route('login') }}">
                                            @csrf

                                            <!-- Email Address -->
                                            <div class="form-floating mb-3">
                                                <x-text-input class="form-control" id="email" type="email" 
                                                    name="email" :value="old('email')" placeholder="name@example.com" required autofocus autocomplete="username" />
                                                <label for="email">Email address</label>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>

                                            <!-- Password -->
                                            <div class="form-floating mb-3">
                                                <x-text-input id="password" class="form-control" type="password"
                                                    name="password" placeholder="Password" required autocomplete="current-password" />
                                                <label for="password">Password</label>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                            </div>

                                            <div class="d-flex justify-content-end w-100 mt-4">


                                                <x-primary-button class="btn btn-primary">
                                                    {{ __('Masuk') }}
                                                </x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>

</x-layout>