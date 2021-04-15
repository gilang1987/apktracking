<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="author" content="{{ website_config('main')->meta_author }}" />
    <meta name="keywords" content="{{ website_config('main')->meta_keywords }}" />
    <meta name="description" content="{{ website_config('main')->meta_description }}" />
    <title>{{ website_config('main')->website_name }}</title>
    <link rel="shortcut icon" href="{{ website_config('main')->website_favicon }}">
    <link href="{{ asset('template/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
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
                                            @if (website_config('main')->website_logo <> null)
                                            <div class="text-center mb-4 mt-3">
                                                <a href="javascript:void(0);">
                                                    <span><img src="{{ website_config('main')->website_logo }}" alt="" height="50"></span>
                                                </a>
                                            </div>
                                            @else
                                            <div class="text-center mb-3 mt-3">
                                                <a href="javascript:void(0);" class="">
                                                    <span class="text-dark text-uppercase" style="font-size: 25px; font-weight: Bold;">
                                                        {{ website_config('main')->website_name }}
                                                    </span>
                                                </a>
                                            </div>
                                            @endif
                                            @include('admin.layouts.alert')
                                            <form method="post" action="{{ request()->url() }}" id="main_form">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Username <text class="text-danger">*</text></label>
                                                    <input class="form-control" type="text" name="username" value="{{ old('username') }}">
                                                    @error('username')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                    <small class="text-danger username_error"></small>
                                                </div>
                                                <div class="form-group">
                                                    <label>Password <text class="text-danger">*</text></label>
                                                    <input class="form-control" type="password" name="password">
                                                    @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                    @enderror
                                                    <small class="text-danger password_error"></small>
                                                </div>
                                                <div class="mt-4 text-center">
                                                    <button class="btn btn-primary btn-block" type="submit">Masuk</button>
                                                </div>
                                            </form>
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
    <script src="{{ asset('template/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('template/js/theme.js') }}"></script>
    <script>
        $(function() {
              $("#main_form").on('submit', function(e){
                  e.preventDefault();
                    $.ajax({
                        url:$(this).attr('action'),
                        method:$(this).attr('method'),
                        data:new FormData(this),
                        processData:false,
                        dataType:'json',
                        contentType:false,
                        beforeSend:function(){
                            $(document).find('small.text-danger').text('');
                            $(document).find('input').removeClass('is-invalid');
                        },
                        success:function(data){
                            if (data.status == false) {
                                if (data.type == 'validation') {
                                    $.each(data.message, function(prefix, val) {
                                        $("input[name="+prefix+"]").addClass('is-invalid');
                                        $('small.'+prefix+'_error').text(val[0]);
                                    });
                                } 
                                if (data.type == 'alert') {
                                    swal.fire("Gagal!", data.message, "error");
                                }
                            } else {
                                $('#main_form')[0].reset();
                                swal.fire("Berhasil!", data.message, "success").then(function () {
                                    window.location = "<?= url('admin') ?>";
                                });
                            }
                        },
                        error:function() {
                            swal.fire("Gagal!", "Terjadi kesalahan.", "error");
                        },
                    });
              });
          });
    </script>
</body>
</html>
