@extends('admin.layouts.app')
@section('breadcrumb-first', $breadcrumb->first)
@section('breadcrumb-second', $breadcrumb->second)
@section('button')
@include('admin.permission.filter')
@endsection
@section('content')
<div class="row">
	<div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-borderless table-hover mb-0'], false) !!}
                </div>
			</div>
		</div>
	</div>
</div>
{!! $dataTable->scripts() !!}
<script>
    document.getElementById("data-table").children[0].className = "thead-light";
    function rejectPermission(elt, id, title, url) {
        swal.fire({
            title: "Tolak Permintaan Izin",
            html: 'ID Permintaan <b style="font-weight: bold;">'+id+'</b>?',
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Ya, Tolak!",
            cancelButtonText: "Tutup",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-secondary ml-2 mt-2",
            buttonsStyling: !1,
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    error: function() {
                        swal.fire("Gagal", "Terjadi kesalahan.", "error");
                    },
                    success: function(result) {
                        if (result.result == false) {
                            swal.fire("Gagal", ""+result.message+"", "error");
                        } else {
                            swal.fire("Berhasil!", "Permintaan berhasil ditolak.", "success").then(function () {
                                window.LaravelDataTables["data-table"].draw();
                            });
                        }
                    }
                });
               
            }
        });
    }
    function approvePermission(elt, id, title, url) {
        swal.fire({
            title: "Setujui Permintaan Izin",
            html: 'ID Permintaan <b style="font-weight: bold;">'+id+'</b>?',
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Ya, Setujui!",
            cancelButtonText: "Tutup",
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonClass: "btn btn-secondary ml-2 mt-2",
            buttonsStyling: !1,
        }).then(result => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    error: function() {
                        swal.fire("Gagal", "Terjadi kesalahan.", "error");
                    },
                    success: function(result) {
                        if (result.result == false) {
                            swal.fire("Gagal", ""+result.message+"", "error");
                        } else {
                            swal.fire("Berhasil!", "Permintaan berhasil disetujui.", "success").then(function () {
                                window.LaravelDataTables["data-table"].draw();
                            });
                        }
                    }
                });
               
            }
        });
    }
</script>
@endsection