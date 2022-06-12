
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>WPC</title>
    <link href="{{ asset('css/min/login.min.css') }}" rel="stylesheet">

</head>
<body class="body-content">
    <div class="content img-bg">
      <div class="container-fluid">
        <div class="row">
            <main class="py-4">
                <div class="container login-container">
                    <div class="row justify-content-center">
                        <div class="col-md-6 text-center justify-content-center align-items-center">
                        <img src="dist/img/wpc2040.jpg" alt="logo" class="header-mobile__logo-img logo-img  mb-2">
                        <img src="dist/img/wpc2040aa.jpg" alt="logo" class="header-mobile__logo-img logo-img  mb-2">
                        </div>
                        <div class="col-md-6">
                            <div class="card card-login">
                                <div class="card-header card__header">
                                    <h4>LOGIN TO YOUR ACCOUNT</h4>
                                </div>

                                <div class="card-body card__content">
                                    <form method="POST" action="{{ route('login') }}">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                        @csrf
                                        <div class="form-group">
                                            <label for="email" >Email</label>
                                                <input id="email" type="text" placeholder="Enter your email" class="form-control login-textbox" name="email" required autofocus>
                                        </div>

                                        <div class="form-group position-relative">
                                            <label for="password">Password</label>
                                                <input id="password" type="password" placeholder="Enter your password" class="form-control login-textbox" name="password" required>
                                                <img class="view-password" src="{{ asset('images/icons/eye.png') }}">
                                        </div>

                                        <div class="form-group form-group--sm">
                                            <div >
                                                <button type="submit" id="btnSignin" class="btn btn-primary btn-lg btn-block">
                                                    SIGN IN TO YOUR ACCOUNT
                                                </button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
</body>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<link href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css" rel="stylesheet">
<script>
            $("document").ready(function(){
                $("#btnLogin").off("submit");
                $('.view-password').hover(function () {
                   $('#password').attr('type', 'text'); 
                }, function () {
                   $('#password').attr('type', 'password'); 
                });
            });
        </script>
</html>


