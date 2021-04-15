<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\BalanceLog;
use Illuminate\Support\Arr;
use App\Models\UserLoginLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;
use App\DataTables\Admin\Log\BalanceDataTable;
use App\DataTables\User\Account\LoginLogDataTable;
use App\DataTables\User\Account\BalanceLogDataTable;
use Illuminate\Http\Exceptions\HttpResponseException;

class AccountController extends Controller {
    public function profile() {
        $components['breadcrumb'] = (object) [
			'first'  => 'Profil',
			'second' => 'Akun'
        ];
        return view('user.account.profile', $components);
    }
    public function login_logs(LoginLogDataTable $dataTable) {
        $components['breadcrumb'] = (object) [
			'first' => 'Log Masuk',
			'second' => 'Akun'
		];
        $components['created_at'] = UserLoginLog::where('user_id', Auth::user()->id)->selectRaw('DATE(created_at) AS created_at')->distinct()->latest('created_at')->get();
        return $dataTable->render('user.account.login_logs', $components);
    }
    public function getSettings() {
        $components['breadcrumb'] = (object) [
			'first'  => 'Pengaturan',
			'second' => 'Akun'
        ];
        return view('user.account.settings', $components);
    }
    public function postSettings(PostRequest $request) {
		if ($request->ajax() == false) abort('404');
        if (Auth::user()->username == 'demouser') {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		$input_data = [
            'full_name' => escape_input($request->full_name),
            'password'  => escape_input($request->password),
        ];
        if (Hash::check($input_data['password'], Auth::user()->password) == true) {
            $input_data['password'] = escape_input(Hash::make($request->password));
            if ($request->new_password <> '') {
                $input_data['password'] = escape_input(Hash::make($request->new_password));
            }
            $update_data = User::where('id', Auth::user()->id)->update($input_data);
            return response()->json([
                'status'  => true, 
                'message' => 'Informasi Akun berhasil diperbarui.'
            ]);
        } else {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Password salah.'
            ]);
        }
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
        if (request('new_password') <> '' OR request('confirm_new_password') <> '') {
            return [
                'full_name'            => 'required|min:5|max:30',
                'password'             => 'required|alpha_num|min:5|max:20',
                'new_password'         => 'alpha_num|min:5|max:15',
                'confirm_new_password' => 'same:new_password',
            ];
        } else {
            return [
                'full_name'            => 'required|min:5|max:30',
                'password'             => 'required|alpha_num|min:5|max:20',
            ];
        }
    }
    public function attributes() {
        if (request('new_password') <> '' OR request('confirm_new_password') <> '') {
            return [
                'full_name'            => 'Nama Lengkap',
                'password'             => 'Password',
                'new_password'         => 'Password Baru',
                'confirm_new_password' => 'Konfirmasi Password Baru',
            ];
        } else {
            return [
                'full_name'            => 'Nama Lengkap',
                'password'             => 'Password',
                'new_password'         => 'Password Baru',
                'confirm_new_password' => 'Konfirmasi Password Baru',
            ];
        }
    }
}