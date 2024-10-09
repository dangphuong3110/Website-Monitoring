<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('assets/image/logo/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Login | WebsiteMonitoring</title>
</head>
<body>
<div class="wrapper">
    <div class="container main">
        <div class="row">
            <div class="col-md-6 side-image"></div>
            <div class="col-md-6 right">
                <div class="input-box">
                    <header class="mb-0">
                        <i class="fa-solid fa-user fa-2xl"></i>
                        <img src="{{ asset('assets/image/logo/logo.png') }}" alt="logo" class="w-100 m-3 ms-0">
                        @if($errors->any())
                            <div class="alert alert-danger" style="font-size: small">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        @if($message = Session::get('success'))
                            <div class="alert alert-success" style="font-size: small">
                                {{ $message }}
                            </div>
                        @endif
                    </header>
                    <form action="#">
                        @csrf
                        <div class="input-field">
                            <input type="text" class="input mb-0" name="email" id="email" value="{{ old('email') }}" required/>
                            <label for="email">Username or Email</label>
                        </div>
                        <div class="input-field mt-3">
                            <input type="password" class="input mb-0" name="password" id="password" required>
                            <label for="password">Password</label>
                        </div>
                        <div class="remember-forgot mt-2">
                            <a href="https://sso.inet.vn/account/forgotpassword">Forgot Password?</a>
                        </div>
                        <div class="input-field">
                            <input type="submit" class="submit btn btn-secondary p-2" value="Login">
                            <a href="{{ $sso_url }}" class="btn btn-outline-primary mt-2 p-2">Login with iNET account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>
</html>
