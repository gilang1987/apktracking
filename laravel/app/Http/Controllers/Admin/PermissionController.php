<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\DataTables\Admin\PermissionDataTable;

class PermissionController extends Controller {
    public function __construct() {
        $this->file_path = storage_path('app/public/files/user_permission/');
    }
    public function list(PermissionDataTable $dataTable) {
        $components['breadcrumb'] = (object) [
			'first' => 'Riwayat',
			'second' => 'Izin'
		];
        $components['statuses'] = ['Waiting', 'Approved', 'Rejected'];
        $components['users'] = UserPermission::distinct()->latest('user_id')->get(['user_id']);
        $components['created_at'] = UserPermission::selectRaw('DATE(created_at) AS created_at')->distinct()->latest('created_at')->get();
        return $dataTable->render('admin.permission.list', $components);
    }
	public function detail(UserPermission $target, Request $request) {
		if ($request->ajax() == false) abort('404');
        return view('admin.permission.detail', compact('target'));
    }
	public function delete(UserPermission $target) {
		if (request()->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') return (json_encode(['result' => false], JSON_PRETTY_PRINT));
		if ($target->delete()) {
            unlink($this->file_path.$target->photo);
            return json_encode(['result' => true], JSON_PRETTY_PRINT);
        }
	}
    public function reject(UserPermission $target) {
        if (request()->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') return (json_encode(['result' => false], JSON_PRETTY_PRINT));
        if ($target->status == 'Rejected') return response()->json([
            'result'  => false,
            'message' => 'Permintaan berstatus Rejected.'
        ], 200, [], JSON_PRETTY_PRINT);
        $update_data = $target->update([
            'status' => 'Rejected',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return response()->json([
            'result'  => true,
        ], 200, [], JSON_PRETTY_PRINT);;
    }
    public function approve(UserPermission $target) {
        if (request()->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') return (json_encode(['result' => false], JSON_PRETTY_PRINT));
        if ($target->status == 'Approved') return response()->json([
            'result'  => false,
            'message' => 'Permintaan berstatus Approved.'
        ], 200, [], JSON_PRETTY_PRINT);
        $update_data = $target->update([
            'status' => 'Approved',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return response()->json([
            'result'  => true,
        ], 200, [], JSON_PRETTY_PRINT);;
    }
}

class PostRequest extends FormRequest {
    protected function getValidatorInstance() {
		$instance = parent::getValidatorInstance();
        if ($instance->fails() == true) {
			throw new HttpResponseException(response()->json([
				'status'  => false, 
				'type'    => 'validation',
				'message' => parent::getValidatorInstance()->errors()
			]));
		}
        return parent::getValidatorInstance();
    }
    public function rules() {
        return [
			'photo'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'description' => 'required|min:5',
        ];
    }
    public function attributes() {
        return [
            'photo'       => 'Foto',
            'description' => 'Keterangan',
        ];
    }
}
