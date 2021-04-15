<?php

namespace App\DataTables\User;

use App\Models\UserPermission;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PermissionDataTable extends DataTable {
    public function dataTable($query) {
        $query = UserPermission::where('user_id', Auth::user()->id)->select('user_permissions.*');
        //dd($query);
        return datatables()
            ->eloquent($query)
            ->filter(function ($query) {
                if (request()->has('filter_created_at') AND request('filter_created_at') <> null) {
                    $query->where('user_permissions.created_at', 'like', "%".escape_input(request('filter_created_at'))."%");
                }
                if (request()->has('filter_status') AND request('filter_status') <> null) {
                    $query->where('user_permissions.status', 'like', "%".escape_input(request('filter_status'))."%");
                }
                if (request()->has('search') AND request('search') <> null) {
                    $query->where(function($query) {
                        $query
                        ->where('user_permissions.id', 'like', "%".escape_input(request('search'))."%")
                        ->orWhere('user_permissions.description', 'like', "%".escape_input(request('search'))."%")
                        ->orWhere('user_permissions.status', 'like', "%".escape_input(request('search'))."%");
                    });
                }
            })
            ->addColumn('detail', function ($query) {
                return "<a href=\"javascript:;\" onclick=\"modal('detail', 'Riwayat Izin', '".url('permission/detail/'.$query->id.'')."')\" class=\"badge badge-info\" data-toggle=\"tooltip\" title=\"Detail\">Lihat Detail</a>";
            })
            ->editColumn('description', function ($query) {
                return Str::limit($query->description, 20, '...'); 
            })
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->translatedFormat('d F Y - H:i');
            })
            ->editColumn('updated_at', function ($query) {
                return Carbon::parse($query->created_at)->translatedFormat('d F Y - H:i');
            })
            ->addColumn('status', function ($query) {
                return status($query->status);
            })
            ->setRowId(function ($query) {
                return $query->id;
            })
            ->rawColumns(['user_id', 'description', 'detail', 'photo', 'status']);
    }

    public function query(UserPermission $model) {
        return $model->newQuery();
    }
    
    public function html() {
        return $this->builder()
                    ->setTableId('data-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->parameters([
                        "responsive" => true,
                        "autoWidth" => false,
                        "pageLength" => 30,
                        "lengthMenu" => [5, 10, 30, 50, 100],
                        "pagingType" => "full_numbers", 
                        "language" => [
                            "processing" => 'Sedang memproses...',
                            "lengthMenu" => "_MENU_",
                            "zeroRecords" => "Tidak ditemukan data yang sesuai",
                            "info" => "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                            "infoEmpty" => "Menampilkan 0 sampai 0 dari 0 entri",
                            "infoFiltered" => "(disaring dari _MAX_ entri keseluruhan)",
                            "infoPostFix" => "",
                            "search" => "Cari:",
                            "paginate" => [
                                "first" => "Pertama",
                                "previous" => "<i class='mdi mdi-chevron-left'>",
                                "next" => "<i class='mdi mdi-chevron-right'>",
                                "last" =>    "Terakhir"
                            ],
                        ]
                    ])
                    ->dom('<bottom><"float-left"><"float-right">r<"row"<"col-sm-4"i><"col-sm-4"><"col-sm-4"p>>')
                    ->ajax([
                        'url' => url()->current(),
                        'data' => 'function(d) { 
                            d.filter_created_at = $("#filter_created_at option:selected").val();
                            d.filter_status = $("#filter_status option:selected").val();
                            d.search = $("input[name=search]").val();
                        }',
                    ])
                    ->orderBy(0);
    }

    protected function getColumns() {
        return [
            ['data' => 'id', 'title' => 'ID', 'width' => '50'],
            ['data' => 'created_at', 'name' => 'user_permissions.created_at', 'title' => 'DIBUAT', 'width' => '130'],
            ['data' => 'created_at', 'name' => 'user_permissions.created_at', 'title' => 'DIPERBARUI', 'width' => '130'],
            ['data' => 'detail', 'title' => 'DETAIL'],
            ['data' => 'status', 'name' => 'user_permissions.status', 'title' => 'STATUS', 'class' => 'text-center', 'width' => '50'],
        ];
    }

    protected function filename() {
        return 'Lead_' . date('YmdHis');
    }
}
