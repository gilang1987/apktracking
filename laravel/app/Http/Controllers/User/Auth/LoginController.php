<?php

namespace App\Http\Controllers\User\Auth;

use Exception;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\UserCookie;
use App\Mail\ActivateEmail;
use App\Models\WebsitePage;
use Illuminate\Support\Str;
use App\Models\UserLoginLog;
use Illuminate\Support\Carbon;
use App\Models\ActivateAccount;
use App\Models\WebsiteInformation;
use App\Models\UserActivateAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginController extends Controller {
	public function __construct() {
        $this->middleware('guest')->except('index', 'logout');
	}
    public function index() {
		if (Auth::check()== true) {
            return view('user.auth.index');
		} else {
            if (website_config('main')->is_landing_page_enabled == '') {
                return redirect('auth/login');
            }
			return view('landing');
		}
	}
	public function getLogin() {
        return view('user.auth.login');
	}
	public function postLogin(LoginRequest $request) {
		if ($request->ajax() == false) abort('404');
		$input_data = [
            'username' => escape_input($request->username),
            'password' => escape_input($request->password),
		];
		if (Auth::attempt($input_data) == true) {
            if (Auth::user()->status == 0) {
                Auth::logout();
                return response()->json([
                    'status'  => false, 
                    'type'    => 'alert',
                    'message' => 'Akun dinonaktifkan.'
                ]);
            }
            if (Auth::user()->is_verified == '0' AND website_config('main')->is_email_confirmation_enabled <> '') {
                $input_data['token'] = md5(Auth::user()->email.'-'.Auth::user()->username);
                $details = [
                    'name' => Auth::user()->full_name,
                    'url'  => url('auth/activate/'.$input_data['token'])
                ];
                $check_data = UserActivateAccount::where('email', Auth::user()->email)->first();
                if ($check_data == false) {
                    $insert_data = UserActivateAccount::create([
                        'email' => Auth::user()->email,
                        'token' => $input_data['token']
                    ]);
                    if ($insert_data == true) {
                        if ($this->activate_email($details, Auth::user()->email) == true) {
                            Auth::logout();
                            return response()->json([
                                'status'  => false, 
                                'type'    => 'alert',
                                'message' => 'Silahkan periksa Email anda untuk mengaktifkan akun Anda.'
                            ]);
                        } else {
                            Auth::logout();
                            return response()->json([
                                'status'  => false, 
                                'type'    => 'alert',
                                'message' => 'Terjadi kesalahan.'
                            ]);
                        }
                    } else {
                        Auth::logout();
                        return response()->json([
                            'status'  => false, 
                            'type'    => 'alert',
                            'message' => 'Terjadi kesalahan.'
                        ]);
                    }
                } else {
                    $input_data['created_at'] = now();
                    $update_data = $check_data->update([
                        'email' => Auth::user()->email,
                        'token' => $input_data['token']
                    ]);
                    if ($update_data == true) {
                        if ($this->activate_email($details, Auth::user()->email) == true) {
                            Auth::logout();
                            return response()->json([
                                'status'  => false, 
                                'type'    => 'alert',
                                'message' => 'Silahkan periksa Email anda untuk mengaktifkan akun Anda.'
                            ]);
                        } else {
                            Auth::logout();
                            return response()->json([
                                'status'  => false, 
                                'type'    => 'alert',
                                'message' => 'Terjadi kesalahan.'
                            ]);
                        }
                    } else {
                        Auth::logout();
                        return response()->json([
                            'status'  => false, 
                            'type'    => 'alert',
                            'message' => 'Terjadi kesalahan.'
                        ]);
                    }
                }
            }
			if ($request->remember  == '1') {
				$random_string = Str::random(40);
				UserCookie::create([
					'user_id' 	  => Auth::user()->id,
					'value'	  	  => $random_string,
					'expired_at'  => date('Y-m-d H:i:s', strtotime('next month'))
				]);
				Cookie::queue(Cookie::make('user', $random_string, strtotime('next month')));
			}
            UserLoginLog::create([
                'user_id'    => Auth::user()->id,
                'ip_address' => $request->ip()
            ]);
            session()->flash('result', [
                'alert'   => 'success', 
                'title'   => 'Berhasil', 
                'message' => 'Selamat datang <b>'.Auth::user()->full_name.'</b>.'
            ]);
            return response()->json([
                'status'  => true, 
                'message' => 'Login Berhasil.'
            ]);
        } else {
            return response()->json([
                'status'  => false, 
                'type'    => 'alert',
                'message' => 'Username atau Password salah.'
            ]);
        }
	}
	public function logout() {
		if (Auth::check() == false) return redirect('auth/login');
		if (Session::has('information_popup') == true) Session::forget('information_popup');
		if (Cookie::has('user') == true) {
			UserCookie::where('value', Cookie::get('user'))->delete();
			Cookie::queue(Cookie::forget('user'));
		}
		Auth::logout();
        return redirect('auth/login')->with('result', [
			'alert'   => 'success', 
			'title'   => 'Berhasil', 
			'message' => 'Sampai jumpa lagi...'
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

class LoginRequest extends FormRequest {
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
            'username' => 'required|exists:users,username',
            'password' => 'required|string'
        ];
    }
    public function attributes() {
        return [
            'username' => 'Username',
            'password' => 'Password',
        ];
    }
}