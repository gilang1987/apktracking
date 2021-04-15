@extends('admin.layouts.app')
@section('breadcrumb-first', 'Dasbor')
@section('breadcrumb-second', website_config('main')->website_name)
@section('content')
@if (Auth::guard('admin')->check() == true)
<div class="row">
    <div class="col-lg-12">
        <div class="alert alert-dismissable alert-info text-dark">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b><i class="fa fa-info-circle"></i> Informasi:</b> Halaman ini masih kosong.
        </div>
	</div>
</div>
@endif
@endsection