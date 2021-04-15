<?php

namespace App\Http\Controllers\User;

use App\DataTables\User\PermissionDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        $components['created_at'] = UserPermission::where('user_id', Auth::user()->id)->selectRaw('DATE(created_at) AS created_at')->distinct()->latest('created_at')->get();
        return $dataTable->render('user.permission.list', $components);
    }
    public function getForm(Request $request) {
		if ($request->ajax() == false) abort('404');
        return view('user.permission.form');
    }
    public function postForm(PostRequest $request) {
		if ($request->ajax() == false) abort('404');
        if (Auth::user()->username == 'demouser') {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		$input_data = [
            'user_id'     => Auth::user()->id,
            'photo'       => null,
            'description' => escape_input($request->description),
        ];
        $check_limit = UserPermission::where([['user_id', Auth::user()->id], ['status', 'Waiting']])->whereDate('created_at', date('Y-m-d'))->count();
        if ($check_limit >= 3) {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Anda masih memiliki '.$check_limit.' Permintaan Izin berstatus Waiting.'
            ]);
        } else {
            if ($request->photo) {
                $file_name = md5(time().rand()).'.'.$request->photo->extension().'';
                $request->photo->move($this->file_path, $file_name);
                $input_data['photo'] = $file_name;
            }
            $insert_data = UserPermission::create($input_data);
            return response()->json([
                'status'  => true, 
                'message' => 'Permintaan Izin berhasil dibuat, silahkan menunggu persetujuan Admin.'
            ]);
        }
    }
	public function detail(UserPermission $target, Request $request) {
		if ($request->ajax() == false) abort('404');
        if ($target->user_id <> Auth::user()->id) return false;
        return view('user.permission.detail', compact('target'));
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
