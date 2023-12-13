<x-layout title="Register" class="register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="/" class="h1"><b>Wedding Organizer Schedule</b></a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new membership</p>

                @if (session('message'))
                    <div class="alert alert-primary" role="alert">
                        {{ session('message') }}
                        <a href="{{route('login')}}">login</a>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-warning" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form action="{{route('register')}}" class="mb-3" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="name" value="{{old('name')}}"
                        class="form-control @error('name')
                        is-invalid @enderror" placeholder="Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="username" value="{{old('username')}}"
                        class="form-control @error('username')
                        is-invalid @enderror" placeholder="Username">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control @error('email')
                        is-invalid @enderror" placeholder="Email" value="{{old('email')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control @error('password')
                        is-invalid @enderror" placeholder="Password" value="{{old('password')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Retype password" value="{{old('password_confirmation')}}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>

                <a href="{{route('login')}}" class="text-center">I already have a membership</a>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
    <!-- /.register-box -->
</x-layout>
