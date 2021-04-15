<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="author" content="{{ website_config('main')->meta_author }}" />
    <meta name="keywords" content="{{ website_config('main')->meta_keywords }}" />
    <meta name="description" content="{{ website_config('main')->meta_description }}" />
    <title>{{ website_config('main')->website_name }}</title>
    <link rel="shortcut icon" href="{{ website_config('main')->website_favicon }}">
    <link href="{{ asset('template/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/css/theme.min.css') }}" rel="stylesheet" type="text/css" />
</head>
<body>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex align-items-center min-vh-100">
                        <div class="w-100 d-block my-5">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-lg-5">
                                     <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="mt-4 pt-3 text-center">
                                                <div class="row justify-content-center">
                                                    <div class="col-6 my-4">
                                                        <img src="{{ asset('template/images/500-error.svg') }}" title="invite.svg">
                                                    </div>
                                                </div>
                                                <h3 class="expired-title mb-4 mt-3">Terjadi Kesalahan</h3>
                                                <p class="text-muted mt-3"> Kami mengalami masalah server internal, coba lagi nanti.</p>
                                            </div>
            
                                            <div class="mb-3 mt-4 text-center">
                                                <a class="btn btn-primary mb-5 waves-effect waves-light" href="{{ request()->segment(1) == 'first-template' ? url('first-template') : url('/') }}">Kembali ke Halaman Utama</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('template/js/jquery.min.js') }}"></script>
    <script src="{{ asset('template/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('template/js/waves.js') }}"></script>
    <script src="{{ asset('template/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('template/js/theme.js') }}"></script>
</body>
</html>
