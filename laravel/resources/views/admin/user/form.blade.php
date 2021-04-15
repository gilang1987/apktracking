<form method="post" action="{{ request()->url() }}" id="main_form">
	@csrf
	<div class="form-group">
		<label>Nama Lengkap <text class="text-danger">*</text></label>
		<input type="text" class="form-control" name="full_name" value="{{ old('full_name') ?? $target->full_name }}">
		<small class="text-danger full_name_error"></small>
	</div>
	<div class="form-group">
		<label>Nomor Telepon <text class="text-danger">*</text></label>
		<input class="form-control" type="number" name="phone_number" value="{{ old('phone_number') ?? $target->phone_number }}">
		<small class="text-danger phone_number_error"></small>
	</div>
	<div class="form-group">
		<label>Email <text class="text-danger">*</text></label>
		<input class="form-control" type="email" name="email" value="{{ old('email') ?? $target->email }}">
		<small class="text-danger email_error"></small>
	</div>
	<div class="form-group">
		<label>Username <text class="text-danger">*</text></label>
		<input type="text" class="form-control" name="username" value="{{ old('username') ?? $target->username }}">
		<small class="text-danger username_error"></small>
	</div>
	<div class="form-group">
		<label>Password {!! request()->segment(4) == null ? '<text class="text-danger">*</text>' : '' !!}</label>
		<input type="password" class="form-control" name="password" placeholder="{{ request()->segment(4) == true ? 'Kosongkan jika tidak dibutuhkan' : '' }}" value="{{ old('password') }}">
		<small class="text-danger password_error"></small>
	</div>
	<div class="form-group">
		<label>Level <text class="text-danger">*</text></label>
		<select class="form-control" name="level" data-toggle="select2">
			<option value="" selected>Pilih...</option>
			@foreach($levels as $item)
				<option value="{{ $item }}" {{ old('level') ? (old('level') == $item ? 'selected' : '') : ($target->level == $item ? 'selected' : '')  }}>{{ $item }}</option>
			@endforeach
		</select>
		<small class="text-danger level_error"></small>
	</div>
    <hr />
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
    </div>
</form>
<script src="{{ asset('assets/custom-form.js') }}"></script>
<script>
	new AutoNumeric.multiple('.autonumeric-currency', {
		digitGroupSeparator        : '.',
		decimalPlaces              : '0',
		decimalCharacter           : ',',
		decimalCharacterAlternative: '.',
		currencySymbol             : 'Rp ',
	});
	function create_api_key() {
		$.ajax({
			type: "GET",
			url: "{{ url('ajax/create_api_key') }}",
			success: function(data) {
				$('#api_key').val(data);
			}
		});
	}
</script>