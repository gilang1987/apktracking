<div class="table-responsive">
	<table class="table table-bordered">
		<tr>
			<td align="center" colspan="2">
				<strong>INFORMASI PENGGUNA</strong>
			</td>
		</tr>
		<tr>
			<th width="50%">ID</th>
			<td>{{ $target->id }}</td>
		</tr>
		<tr>
			<th>BERGABUNG</th>
			<td>
				{{ \Carbon\Carbon::parse($target->created_at)->translatedFormat('d F, Y') }}
				({{ \Carbon\Carbon::parse($target->created_at)->diffForHumans() }})
			</td>
		</tr>
		<tr>
			<th>USERNAME</th>
			<td>{{ $target->username }}</td>
		</tr>
		<tr>
			<th>NAMA LENGKAP</th>
			<td>{{ $target->full_name }}</td>
		</tr>
		<tr>
			<th>EMAIL</th>
			<td>{{ $target->email }}</td>
		</tr>
		<tr>
			<th>NO. TELEPON</th>
			<td>{{ $target->phone_number }}</td>
		</tr>
		<tr>
			<th>UPLINE</th>
			<td>{{ $target->upline }}</td>
		</tr>
		<tr>
			<th>VERIFIKASI</th>
			<td>{!! $target->is_verified == '0' ? '<i class="fa fa-times text-danger"></i> Belum' : '<i class="fa fa-check text-success"></i> Sudah' !!}</td>
		</tr>
		<tr>
			<th>STATUS</th>
			<td>{!! status($target->status) !!}</td>
		</tr>
	</table>
</div>