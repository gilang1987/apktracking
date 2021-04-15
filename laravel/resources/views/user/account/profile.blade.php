@extends('user.layouts.app')
@section('breadcrumb-first', $breadcrumb->first)
@section('breadcrumb-second', $breadcrumb->second)
@section('content')
<div class="row">
    <div class="col-lg-12">
		<div class="card">
			<div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0 text-muted">
                        <tbody>
                            <tr>
                                <th scope="row" width="20%">Nama Lengkap:</th>
                                <td>{{ Auth::user()->full_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" width="20%">Email:</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">No. Handphone:</th>
                                <td>{{ Auth::user()->phone_number }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Bergabung:</th>
                                <td>{{ \Carbon\Carbon::parse(Auth::user()->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection