<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\DataTables\Admin\UserDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller {
    public function list(UserDataTable $dataTable) {
    	$components['breadcrumb'] = (object) [
			'first' => 'Daftar Pengguna',
			'second' => website_config('main')->website_name
		];
        $components['levels'] = ['Member', 'Agen'];
        $components['statuses'] = ['1' => 'Aktif', '0' => 'Nonaktif'];
        $components['created_at'] = User::selectRaw('DATE(created_at) AS created_at')->distinct()->latest('created_at')->get();
        return $dataTable->render('admin.user.list', $components);
    }
    public function getForm(User $target, Request $request) {
		if ($request->ajax() == false) abort('404');
        if ($target == true) $components['target'] = $target;
        $components['levels'] = ['Member', 'Agen'];
        return view('admin.user.form', $components);
    }
    public function postForm(User $target, PostRequest $request) {
		if ($request->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') {
			return response()->json([
				'status'  => false, 
				'type'    => 'alert',
                'message' => 'Aksi tidak diperbolehkan.'
			]);
        }
		$input_data = [
            'username'      => escape_input($request->username),
            'full_name'     => escape_input($request->full_name),
            'email' 	    => escape_input($request->email),
            'phone_number'  => escape_input($request->phone_number),
            'level'         => $request->level,
			'is_verified'	=> '1',
			'upline'	    => 'Admin'
        ];
        if (request()->segment(4) == null) {
            $input_data['password'] = escape_input(Hash::make($request->password));
        }
		if ($target->id <> null) {
			if ($request->password <> '') {
				$input_data['password'] = escape_input(Hash::make($request->password));
			}
			$check_data = User::where([
				['username', $input_data['username']]
			])->first();
			if ($input_data['username'] <> $target['username'] AND $check_data) {
				$validator = Validator::make($request->all(), [
					'username' => 'required|unique:users,username|max:20',
				], [], ['username' => 'Username']);
				if ($validator->fails()) {
					return response()->json([
						'status'  => false, 
						'type'    => 'validation',
						'message' => $validator->errors()->toArray()
					]);
				}
			}
			$check_data = User::where([
				['full_name', $input_data['full_name']]
			])->first();
			if ($input_data['full_name'] <> $target['full_name'] AND $check_data) {
				$validator = Validator::make($request->all(), [
					'full_name' => 'required|unique:users,full_name|max:30',
				], [], ['full_name' => 'Nama Lengkap']);
				if ($validator->fails()) {
					return response()->json([
						'status'  => false, 
						'type'    => 'validation',
						'message' => $validator->errors()->toArray()
					]);
				}
			}
			$check_data = User::where([
				['email', $input_data['email']]
			])->first();
			if ($input_data['email'] <> $target['email'] AND $check_data) {
				$validator = Validator::make($request->all(), [
					'email' => 'required|email|unique:users,email',
				], [], ['email' => 'Email']);
				if ($validator->fails()) {
					return response()->json([
						'status'  => false, 
						'type'    => 'validation',
						'message' => $validator->errors()->toArray()
					]);
				}
			}
			$check_data = User::where([['phone_number', $input_data['phone_number']]])->first();
			if ($input_data['phone_number'] <> $target['phone_number'] AND $check_data) {
				$validator = Validator::make($request->all(), [
					'phone_number' => 'required|numeric|phone:ID,mobile|unique:users,phone_number',
				], [], ['phone_number' => 'Nomor Telepon']);
				if ($validator->fails()) {
					return response()->json([
						'status'  => false, 
						'type'    => 'validation',
						'message' => $validator->errors()->toArray()
					]);
				}
			}
			$update_data = $target->update($input_data);
			return response()->json([
				'status'  => true, 
				'message' => 'Pengguna berhasil diperbarui.'
			]);
		} else {
			$insert_data = User::create($input_data);
			return response()->json([
				'status'  => true, 
				'message' => 'Pengguna berhasil ditambahkan.'
			]);
		}
	}
	public function status(User $target, $status, Request $request) {
        if ($request->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') return (json_encode(['result' => false], JSON_PRETTY_PRINT));
        if (Arr::exists(['0', '1'], $status) == false) return (json_encode(['result' => false], JSON_PRETTY_PRINT));
        $update_data = $target->update(['status' => $status]);
        if ($update_data == true) {
            return (json_encode([
                'result'  => true,
                'message' => 'Status Pengguna <b>'.$target->full_name.'</b> berhasil diperbarui.'
            ], JSON_PRETTY_PRINT));
        } else {
            return (json_encode(['result' => false], JSON_PRETTY_PRINT));
        }
	}
	public function delete(User $target, Request $request) {
		if ($request->ajax() == false) abort('404');
        if (Auth::guard('admin')->user()->level == 'Admin') return (json_encode(['result' => false], JSON_PRETTY_PRINT));
		if ($target->delete()) {
			return json_encode(['result' => true], JSON_PRETTY_PRINT);
		}
	}
	public function detail(User $target, Request $request) {
		if ($request->ajax() == false) abort('404');
        return view('admin.user.detail', compact('target'));
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
        if (request()->segment(4) == null) {
			return [
				'full_name'    => 'required|max:30|regex:/^[\pL\s\-]+$/u|unique:users,full_name',
                'phone_number' => 'required|numeric|phone:ID,mobile|unique:users,phone_number',
                'email'        => 'required|email|unique:users,email',
                'username'     => 'required|alpha_num|min:5|max:20|unique:users,username',
                'password'     => 'required|alpha_num|min:5|max:20',
                'level'        => 'required|in:Member,Agen,Reseller,Admin',
			];
		}
		return [
            'full_name'    => 'required|max:30|regex:/^[\pL\s\-]+$/u',
            'phone_number' => 'required|numeric|phone:ID,mobile',
            'email'        => 'required|email',
            'username'     => 'required|alpha_num|min:5|max:20',
            'level'        => 'required|in:Member,Agen,Reseller,Admin',
		];
    }
    public function attributes() {
		return [
            'full_name'    => 'Nama Lengkap',
            'phone_number' => 'Nomor Telepon',
            'email'        => 'Email',
            'username'     => 'Username',
            'password'     => 'Password',
            'level'        => 'Level',
		];
    }
	
}