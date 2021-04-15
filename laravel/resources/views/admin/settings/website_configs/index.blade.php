@extends('admin.layouts.app')
@section('breadcrumb-first', $breadcrumb->first)
@section('breadcrumb-second', $breadcrumb->second)
@section('content')
@if ($errors->any() == true)
<div class="row">
	<div class="col-lg-12">
		<div class="alert alert-dismissable alert-danger text-dark">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b><i class="fa fa-times-circle"></i> Gagal:</b><br />
            @foreach ($errors->all() as $error)
                {!! $error.'<br />' !!}
            @endforeach
            </ul>
		</div>
	</div>
</div>
@endif
<div class="row">
	<div class="col-lg-3">
		<div class="card">
			<div class="card-body">
				<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
					<a class="nav-link active" href="#main" data-toggle="pill" role="tab"><i class="fa fa-home fa-fw"></i> Utama</a>
					<a class="nav-link" href="#smtp" data-toggle="pill" role="tab"><i class="mdi mdi-email-edit fa-fw"></i> SMTP</a>
					<a class="nav-link" href="#others" data-toggle="pill" role="tab"><i class="fa fa-folder fa-fw"></i> Lain-Lain</a>
				</div>
			</div>
		</div>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
				<form method="post" action="{{ request()->url() }}" role="form" enctype="multipart/form-data">
                    @method('patch')
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="main">
                            <div class="form-group">
                            	<label>Nama Website <text class="text-danger">*</text></label>
                                <input type="text" name="website_name" class="form-control" value="{{ old('website_name') ?? website_config('main')->website_name }}" />
                            </div>
                            <hr />
                            <div class="form-group">
                                <label>Logo Website</label>
                                <div class="form-group">
                                    <input type="file" class="dropify" accept=".png, .jpg, .jpeg, .svg" name="website_logo" id="website_logo" data-max-file-size="10M" @if (website_config('main')->website_logo <> null) data-default-file="{{ website_config('main')->website_logo }}" data-show-remove="false" @endif data-toggle="tooltip" title="Ukuran yang Disarankan: 150px x 34px" />
                                </div>
                                @if (website_config('main')->website_logo <> null)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="{{ website_config('main')->website_logo }}" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <a href="{{ url('admin/settings/website_configs/delete_logo') }}" class="text-dark">
                                                Hapus Logo
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                @endif          
                            </div>
                            <hr />
                            <div class="form-group">
                                <label>Favicon Website</label>
                                <div class="form-group">
                                    <input type="file" class="dropify" accept=".png, .jpg, .jpeg, .svg" name="website_favicon" id="website_favicon" data-max-file-size="10M" @if (website_config('main')->website_favicon <> null) data-default-file="{{ website_config('main')->website_favicon }}" data-show-remove="false" @endif data-toggle="tooltip" title="Ukuran yang Disarankan: 150px x 34px" />
                                </div>
                                @if (website_config('main')->website_favicon <> null)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" value="{{ website_config('main')->website_favicon }}" disabled>
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <a href="{{ url('admin/settings/website_configs/delete_favicon') }}" class="text-dark">
                                                Hapus Favicon
                                            </a>
                                        </span>
                                    </div>
                                </div>
                                @endif          
                            </div>
                            <hr />
                            <div class="form-group">
								<label>Tentang Kami <text class="text-danger">*</text></label>
								<textarea class="form-control custom-text-editor" name="about_us" rows="5">{{ old('about_us') ?? website_config('main')->about_us }}</textarea>
							</div>
                            <hr />
                            <div class="form-group">
								<label>Meta Keywords <text class="text-danger">*</text></label>
								<textarea class="form-control" name="meta_keywords" rows="5">{{ old('meta_keywords') ?? website_config('main')->meta_keywords }}</textarea>
							</div>
                            <hr />
                            <div class="form-group">
								<label>Meta Description <text class="text-danger">*</text></label>
								<textarea class="form-control" name="meta_description" rows="5">{{ old('meta_description') ?? website_config('main')->meta_description }}</textarea>
							</div>
                        </div>
                        <div class="tab-pane fade" id="smtp">
                            <div class="form-group">
                                <label>Host</label>
                                <input type="text" name="smtp_host" class="form-control" value="{{ old('smtp_host') ?? website_config('smtp')->host }}" />
                            </div>
                            <div class="form-group">
                                <label>Email Dari</label>
                                <input type="email" name="smtp_from" class="form-control" value="{{ old('smtp_from') ?? website_config('smtp')->from }}" />
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Enkripsi</label>
                                        <select name="smtp_encryption" class="form-control">
                                            <option value="0" @if (website_config('smtp')->encryption == null) selected="selected" @endif>Tidak ada</option>
                                            <option value="ssl" @if (website_config('smtp')->encryption == 'ssl') selected="selected" @endif>SSL</option>
                                            <option value="tls" @if (website_config('smtp')->encryption == 'tls') selected="selected" @endif>TLS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label>Port</label>
                                        <input type="text" name="smtp_port" class="form-control" value="{{ old('smtp_port') ?? website_config('smtp')->port }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="custom-control custom-switch mb-3">
                                <input id="smtp_auth" name="smtp_auth" type="checkbox" class="custom-control-input" @if (website_config('smtp')->auth == '1') checked @endif>
                                <label class="custom-control-label" for="smtp_auth">Autentikasi</label>
                            </div>
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="smtp_username" class="form-control" value="{{ old('smtp_username') ?? website_config('smtp')->username }}" @if (website_config('smtp')->auth == null) disabled @endif/>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="smtp_password" class="form-control" value="{{ old('smtp_password') ?? website_config('smtp')->password }}" @if (website_config('smtp')->auth == null) disabled @endif/>
                            </div>
                            <div class="my-3">
                                <a href="{{ url('admin/settings/website_configs/test_email') }}" class="btn btn-outline-info">Kirim Email Percobaan</a>
                                <small class="form-text text-muted">Sistem akan mengirim email ke nilai dari bidang <strong> Email Dari </strong> yang Anda tetapkan di atas. Pastikan untuk menyimpan pengaturan terlebih dahulu!</small>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="others">
                            <div class="row">
                                <div class="form-group col-lg-4 mb-0">
                                    <div class="custom-control custom-switch">
                                        <input id="is_website_under_maintenance" name="is_website_under_maintenance" type="checkbox" class="custom-control-input" @if (website_config('main')->is_website_under_maintenance == '1') checked @endif>
                                        <label class="custom-control-label" for="is_website_under_maintenance">Mode Pengembangan</label>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 mb-0">
                                    <div class="custom-control custom-switch">
                                        <input id="is_landing_page_enabled" name="is_landing_page_enabled" type="checkbox" class="custom-control-input" @if (website_config('main')->is_landing_page_enabled == '1') checked @endif>
                                        <label class="custom-control-label" for="is_landing_page_enabled">Halaman Landing</label>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="form-group col-lg-4 mb-0">
                                    <div class="custom-control custom-switch">
                                        <input id="is_email_confirmation_enabled" name="is_email_confirmation_enabled" type="checkbox" class="custom-control-input" @if (website_config('main')->is_email_confirmation_enabled == '1') checked @endif>
                                        <label class="custom-control-label" for="is_email_confirmation_enabled">Konfirmasi Email Pengguna</label>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 mb-0">
                                    <div class="custom-control custom-switch">
                                        <input id="is_register_enabled" name="is_register_enabled" type="checkbox" class="custom-control-input" @if (website_config('main')->is_register_enabled == '1') checked @endif>
                                        <label class="custom-control-label" for="is_register_enabled">Pendaftaran Pengguna Baru</label>
                                    </div>
                                </div>
                                <div class="form-group col-lg-4 mb-0">
                                    <div class="custom-control custom-switch">
                                        <input id="is_reset_password_enabled" name="is_reset_password_enabled" type="checkbox" class="custom-control-input" @if (website_config('main')->is_reset_password_enabled == '1') checked @endif>
                                        <label class="custom-control-label" for="is_reset_password_enabled">Atur Ulang Kata Sandi</label>
                                    </div>
                                </div>
                            </div>
                            <hr />
                        </div>
                        <div>
							<button type="reset" class="btn btn-danger">Reset</button>
							<button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('input[name="smtp_auth"]').on('change', (event) => {
        if($(event.currentTarget).is(':checked')) {
            $('input[name="smtp_username"],input[name="smtp_password"]').removeAttr('disabled');
        } else {
            $('input[name="smtp_username"],input[name="smtp_password"]').attr('disabled', 'true');
        }
    });
	$(document).ready(function() {
		$(".dropify").dropify({
			messages:{
				default:"Seret atau jatuhkan file disini atau klik",
				replace:"Seret atau jatuhkan atau klik untuk menggantikn",
                remove:  'Hapus',
				error:"Ooops, terjadi kesalahan."
			},
			error:{
				fileSize:"Ukuran File terlalu besar (Maksimal 10MB)."
			}
		})
	});
</script>
@endsection