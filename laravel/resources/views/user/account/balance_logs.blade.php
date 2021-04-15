@extends(''.website_config('template')->number.'.user.layouts.app')
@section('breadcrumb-first', $breadcrumb->first)
@section('breadcrumb-second', $breadcrumb->second)
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @include('general.user.filter.account.balance_logs')
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-borderless table-hover mb-0'], false) !!}
                </div>
			</div>
		</div>
	</div>
</div>
{!! $dataTable->scripts() !!}
<script>
    var table = document.getElementById("data-table");
    table.children[0].className = "thead-light";
    $('#search-form').on('submit', function(e) {
        window.LaravelDataTables["data-table"].draw();
        e.preventDefault();
    });
</script>
@endsection