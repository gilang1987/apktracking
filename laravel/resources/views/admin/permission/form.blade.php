<form method="post" action="{{ request()->url() }}" id="main_form">
    @csrf
    <div class="form-group">
        <label>Foto <text class="text-danger">*</text></label>
        <div class="form-group">
            <input type="file" class="dropify" accept=".png, .jpg, .jpeg, .svg" name="photo" id="photo" data-max-file-size="10M" data-toggle="tooltip" rows="3" />
            <small class="text-danger photo_error"></small>
        </div>         
    </div>
    <div class="form-group">
        <label>Keterangan <text class="text-danger">*</text></label>
        <textarea class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
        <small class="text-danger description_error"></small>
    </div>
    <button type="reset" class="btn btn-danger">Reset</button>
    <button type="submit" class="btn btn-success">Submit</button>
</form>
<script src="{{ asset('assets/custom-form.js') }}"></script>
<script>
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