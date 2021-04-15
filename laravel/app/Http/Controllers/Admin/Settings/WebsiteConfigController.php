<?php

namespace App\Http\Controllers\Admin\Settings;

use Exception;
use Illuminate\Http\Request;
use App\Models\WebsiteConfig;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Http\FormRequest;

class WebsiteConfigController extends Controller {
	public function __construct() {
		$this->image_path = '/public/assets/';
		$this->breadcrumb = (object) [
			'first' => 'Konfigurasi Website',
			'second' => 'Pengaturan'
		];
	}
    public function getIndex(Request $request) {
    	$components['image_path'] = $this->image_path;
    	$components['breadcrumb'] = $this->breadcrumb;
        return view('admin.settings.website_configs.index', $components);
	}
	public function postIndex(PostRequest $request) {
        if (Auth::guard('admin')->user()->level == 'Admin') {
            return redirect()->back()->with('result', [
                'alert'   => 'danger', 
                'title'   => 'Gagal', 
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		$input_data = [
			'main' => [
				'website_name'    				=> $request->website_name,
				'website_logo'     				=> optional(website_config('main'))->website_logo,
				'website_favicon'     		    => optional(website_config('main'))->website_favicon,
				'about_us'  	   				=> $request->about_us,
				'meta_author'    				=> 'jhonroot',
				'meta_keywords'    				=> $request->meta_keywords,
				'meta_description' 				=> $request->meta_description,
				'is_email_confirmation_enabled' => is_null($request->is_email_confirmation_enabled) ? '' : '1',
				'is_register_enabled' 			=> is_null($request->is_register_enabled) ? '' : '1',
				'is_reset_password_enabled' 	=> is_null($request->is_reset_password_enabled) ? '' : '1',
				'is_website_under_maintenance'  => is_null($request->is_website_under_maintenance) ? '' : '1',
				'is_landing_page_enabled'  	 	=> is_null($request->is_landing_page_enabled) ? '' : '1',
			],
			'smtp' => [
				'auth' 		 => is_null($request->smtp_auth) ? '' : '1',
				'host'		 => is_null($request->smtp_host) ? '' : $request->smtp_host,
				'port'		 => is_null($request->smtp_port) ? '' : $request->smtp_port,
				'from' 		 => is_null($request->smtp_from) ? '' : $request->smtp_from,
				'encryption' => is_null($request->smtp_encryption) ? '' : $request->smtp_encryption,
				'username' 	 => is_null($request->smtp_username) ? '' : $request->smtp_username,
				'password' 	 => is_null($request->smtp_password) ? '' : $request->smtp_password,
			],
		];
		if ($request->website_logo) {
			//$image_name = md5(time().rand()).'.'.$request->website_logo->extension().'';
			$image_name = 'website-logo.'.$request->website_logo->extension().'';
			$request->website_logo->move(getcwd().$this->image_path, $image_name);
			$input_data['main']['website_logo'] = url('public/assets/'.$image_name.'');
		}
		if ($request->website_favicon) {
			//$image_name = md5(time().rand()).'.'.$request->website_logo->extension().'';
			$image_name = 'website-favicon.'.$request->website_favicon->extension().'';
			$request->website_favicon->move(getcwd().$this->image_path, $image_name);
			$input_data['main']['website_favicon'] = url('public/assets/'.$image_name.'');
		}
		if ($request->smtp_host == '' || $request->smtp_port == '' || $request->smtp_from == '' || $request->smtp_encryption == '') {
			$input_data['main']['is_email_confirmation_enabled'] = '';
		}
		try {
			$update_data = WebsiteConfig::where('key', 'main')->update(['value' => json_encode($input_data['main'])]);
			$update_data = WebsiteConfig::where('key', 'smtp')->update(['value' => json_encode($input_data['smtp'])]);
			return redirect()->back()->with('result', [
				'alert'   => 'success', 
				'title'   => 'Berhasil', 
				'message' => 'Konfigurasi Website berhasil diperbarui.'
			]);
		} catch (Exception $exception) {
			return redirect()->back()->withInput()->with('result', [
				'alert'   => 'danger', 
				'title'   => 'Gagal', 
				'message' => $exception->getMessage()
			]);
			return dd($exception->getMessage());
		}
	}
	public function delete_logo() {
        if (Auth::guard('admin')->user()->level == 'Admin') {
            return redirect()->back()->with('result', [
                'alert'   => 'danger', 
                'title'   => 'Gagal', 
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		$check_data = WebsiteConfig::where('key', 'main')->first();
		foreach (json_decode($check_data->value, true) as $key => $item) {
			$array[$key] = $item;
			if ($key == 'website_logo') {
				$array[$key] = '';
			}
		}
		$check_data->update([
			'value' => json_encode($array)
		]);
		return redirect('admin/settings/website_configs')->with('result', [
			'alert'   => 'success', 
			'title'   => 'Berhasil', 
			'message' => 'Logo berhasil dihapus.'
		]);
	}
	public function delete_favicon() {
        if (Auth::guard('admin')->user()->level == 'Admin') {
            return redirect()->back()->with('result', [
                'alert'   => 'danger', 
                'title'   => 'Gagal', 
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		$check_data = WebsiteConfig::where('key', 'main')->first();
		foreach (json_decode($check_data->value, true) as $key => $item) {
			$array[$key] = $item;
			if ($key == 'website_favicon') {
				$array[$key] = '';
			}
		}
		$check_data->update([
			'value' => json_encode($array)
		]);
		return redirect('admin/settings/website_configs')->with('result', [
			'alert'   => 'success', 
			'title'   => 'Berhasil', 
			'message' => 'Favicon berhasil dihapus.'
		]);
	}
	public function test_email() {
		if (Auth::guard('admin')->user()->level == 'Admin') {
            return redirect()->back()->with('result', [
                'alert'   => 'danger', 
                'title'   => 'Gagal', 
                'message' => 'Aksi tidak diperbolehkan.'
            ]);
        }
		if (website_config('smtp')->host == '' || website_config('smtp')->port == '' || website_config('smtp')->from == '' || website_config('smtp')->encryption == '') {
			return redirect()->back()->with('result', [
				'alert'   => 'danger', 
				'title'   => 'Gagal', 
				'message' => 'Mohon untuk bidang melengkapi SMTP.'
			]);
		}
		config(['mail.mailers.smtp.username' => website_config('smtp')->username]);
		config(['mail.mailers.smtp.password' => website_config('smtp')->password]);
		config(['mail.mailers.smtp.encryption' => website_config('smtp')->encryption]);
		config(['mail.mailers.smtp.port' => website_config('smtp')->port]);
		config(['mail.mailers.smtp.host' => website_config('smtp')->host]);
		config(['mail.from.address' => website_config('smtp')->from]);
		config(['mail.from.name' => website_config('main')->website_name]);
		try {
			Mail::raw(website_config('main')->website_name, function ($message) {
			$message
				->to(website_config('smtp')->from)
				->from(config('mail.from.address'), config('mail.from.name'))
				->subject('Tes SMTP Email');
			});
			return redirect()->back()->with('result', [
				'alert'   => 'success', 
				'title'   => 'Berhasil', 
				'message' => 'Silahkan periksa Email: '.website_config('smtp')->from.'.'
			]);
		} catch (Exception $message) {
			return redirect()->back()->with('result', [
				'alert'   => 'danger', 
				'title'   => 'Gagal', 
				'message' => $message->getMessage()
			]);
		}
	}
}

class PostRequest extends FormRequest {
	public function rules() {
        return [
			'website_name'  	=> 'required|string',
			'website_logo' 		=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			'website_favicon' 	=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about_us'  		=> 'required',
            'meta_keywords'  	=> 'required',
            'meta_description'	=> 'required',
        ];
    }
    public function attributes() {
        return [
            'website_name'  	=> 'Nama Website',
            'website_logo'  	=> 'Logo Website',
            'website_favicon'  	=> 'Logo Favicon',
            'about_us'  		=> 'Tentang Kami',
            'meta_keywords'  	=> 'Meta Keywords',
            'meta_description'	=> 'Meta Description',
        ];
    }
}
