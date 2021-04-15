@extends('user.layouts.app')
@section('breadcrumb-first', $breadcrumb->first)
@section('breadcrumb-second', $breadcrumb->second)
@section('content')
<div class="row">
    <div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<form method="post" action="{{ request()->url() }}" id="main_form">
					@method('patch')
                    @csrf
                    <div class="form-group">
						<label>Nama Lengkap <text class="text-danger">*</text></label>
						<input type="text" class="form-control" name="full_name" value="{{ old('full_name') ?? Auth::user()->full_name }}">
						<small class="text-danger full_name_error"></small>
                    </div>
                    <div class="form-group">
						<label>Password Baru</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" placeholder="Kosongkan jika tidak dibutuhkan">
						<small class="text-danger new_password_error"></small>
					</div>
                    <div class="form-group">
						<label>Konfirmasi Password Baru</label>
						<input type="password" class="form-control" name="confirm_new_password" placeholder="Kosongkan jika tidak dibutuhkan">
						<small class="text-danger confirm_new_password_error"></small>
					</div>
                    <div class="form-group">
						<label>Password <text class="text-danger">*</text></label>
						<input type="password" class="form-control" name="password">
						<small class="text-danger password_error"></small>
                    </div>
					<button type="reset" class="btn btn-danger">Reset</button>
					<button type="submit" class="btn btn-success">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('assets/user-form.js') }}"></script>
@endsection