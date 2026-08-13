<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GBPS Urdu Phullei | {{ config('app.name', 'School Management') }}</title>

    @include('partials.login.inc_top')

    <style>
        /* Login page gradient override */
        html, body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 55%, #4338ca 100%) !important;
            min-height: 100vh !important;
        }
        body > .navbar.navbar-dark {
            background: rgba(15, 23, 42, 0.85) !important;
            backdrop-filter: blur(10px);
        }
        .content-wrapper,
        .page-content.login-cover {
            background: transparent !important;
        }
        /* Frosted glass login card */
        .login-form .card {
            border-radius: 18px !important;
            border: 1px solid rgba(255,255,255,.18) !important;
            box-shadow: 0 24px 60px rgba(0,0,0,.5), 0 0 40px rgba(99,102,241,.2) !important;
        }
        .login-form .card-body { padding: 40px 36px !important; }
        /* Icon */
        .login-form .icon-people {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: #fff !important; border-color: transparent !important;
            border-radius: 14px !important;
            box-shadow: 0 6px 18px rgba(99,102,241,.45) !important;
        }
        /* Heading */
        .login-form h5 {
            font-family: 'Poppins', sans-serif !important;
            font-size: 21px !important; font-weight: 700 !important;
            color: #0f172a !important;
        }
        /* Inputs */
        .login-form .form-control {
            height: 46px !important; font-size: 14px !important;
            border-radius: 8px !important; background: #f8fafc !important;
        }
        .login-form .form-control:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,.18) !important;
            background: #fff !important;
        }
        /* Button */
        .login-form .btn-primary {
            height: 46px !important; font-size: 14px !important;
            font-weight: 700 !important; border-radius: 8px !important;
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(99,102,241,.4) !important;
        }
        .login-form .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5, #3730a3) !important;
            box-shadow: 0 6px 20px rgba(99,102,241,.5) !important;
            transform: translateY(-1px);
        }
        /* Links */
        .login-form a { color: #6366f1 !important; font-weight: 600; }
        .login-form a:hover { color: #4f46e5 !important; }
        /* Footer navbar */
        body > .navbar.navbar-light {
            background: rgba(15,23,42,.65) !important;
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .navbar.navbar-light .navbar-text,
        .navbar.navbar-light .navbar-text a,
        .navbar.navbar-light .navbar-nav-link {
            color: rgba(255,255,255,.6) !important;
        }
        .navbar.navbar-light .navbar-text a:hover { color: #fff !important; }
    </style>
</head>

<body class="login-page">
@include('partials.login.header')
@yield('content')
@include('partials.login.footer')

</body>

</html>
