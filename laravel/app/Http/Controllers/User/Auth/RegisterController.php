<?php

namespace App\Http\Controllers\User\Auth;

use Exception;
use App\Models\User;
use App\Mail\ActivateEmail;
use App\Models\UserRegisterLog;
use App\Models\UserActivateAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterController extends Controller {
	public function __construct() {
        $this->middleware('guest');
	}
	public function getRegister() {
        return view('user.auth.register');
	}
	public function postRegister(RegisterRequest $request) {
		if ($request->ajax() == false) abort('404');
		$input_data = [
            'full_name'    => escape_input($request->full_name),
            'email' 	   => escape_input($request->email),
            'phone_number' => escape_input($request->phone_number),
            'username'     => escape_input($request->username),
            'password'     => escape_input(Hash::make($request->password)),
            'level'        => 'Member',
            'is_verified'  => '0',
        ];
        if (website_config('main')->is_email_confirmation_enabled == '') {
            $input_data['is_verified'] = '1';
        }
		$check_data['ip_address'] = UserRegisterLog::where([['ip_address', $request->ip()], ['upline_user_id', null]])->first();
		if ($check_data['ip_address'] == true) {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Anda sudah mendaftarkan akun sebelumnya.'
            ]);
		}
        $insert_data = User::create($input_data);
        UserRegisterLog::create([
            'user_id'    => $insert_data->id,
            'ip_address' => request()->ip()
        ]);
        if ($input_data['is_verified'] == '0') {
            $input_data['token'] = md5($input_data['email'].'-'.$input_data['username']);
            $details = [
                'name' => $input_data['full_name'],
                'url'  => url('auth/activate/'.$input_data['token'])
            ];
            $insert_data = UserActivateAccount::create([
                'email' => $input_data['email'],
                'token' => $input_data['token']
            ]);
            if ($insert_data == true) {
                if ($this->activate_email($details, $input_data['email']) == true) {
                    return response()->json([
                        'status'  => true, 
                        'message' => 'Silahkan periksa Email anda untuk mengaktifkan akun Anda.'
                    ]);
                } else {
                    return response()->json([
                        'status'  => false, 
                        'type'    => 'alert',
                        'message' => 'Terjadi Kesalahan.'
                    ]);
                }
            } else {
                return response()->json([
                    'status'  => false, 
                    'type'    => 'alert',
                    'message' => 'Terjadi Kesalahan.'
                ]);
            }
        }
        return response()->json([
            'status'  => true, 
            'message' => 'Pendaftaran berhasil, silahkan masuk.'
        ]);
	}
    public function activate_email($details = [], $to = '') {
		config(['mail.mailers.smtp.username' => website_config('smtp')->username]);
		config(['mail.mailers.smtp.password' => website_config('smtp')->password]);
		config(['mail.mailers.smtp.encryption' => website_config('smtp')->encryption]);
		config(['mail.mailers.smtp.port' => website_config('smtp')->port]);
		config(['mail.mailers.smtp.host' => website_config('smtp')->host]);
		config(['mail.from.address' => website_config('smtp')->from]);
		config(['mail.from.name' => website_config('main')->website_name]);
		try {
            Mail::send('user.mail.activate_account', $details, function($message) use ($details, $to) {
                $message
                 ->to($to, $details['name'])
                 ->from(config('mail.from.address'), config('mail.from.name'))
                 ->subject('Aktivasi Akun - '.website_config('main')->website_name.'');
             });
			return true;
		} catch (Exception $message) {
			return $message->getMessage();
		}
    }
}

class RegisterRequest extends FormRequest {
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
            'full_name'        => 'required|max:30|regex:/^[\pL\s\-]+$/u|unique:users,full_name',
            'phone_number'     => 'required|numeric|phone:ID,mobile|unique:users,phone_number',
            'email'            => 'required|email|unique:users,email',
            'username'         => 'required|alpha_num|min:5|max:20|unique:users,username',
            'password'         => 'required|alpha_num|min:5|max:20',
            'confirm_password' => 'required|same:password',
            'approval'         => 'required|in:1',
        ];
    }
    public function attributes() {
        return [
            'full_name'        => 'Nama Lengkap',
            'phone_number'     => 'Nomor Telepon',
            'email'            => 'Email',
            'username'         => 'Username',
            'password'         => 'Password',
            'confirm_password' => 'Konfirmasi Password',
            'approval'         => 'Persetujuan',
        ];
    }
}
