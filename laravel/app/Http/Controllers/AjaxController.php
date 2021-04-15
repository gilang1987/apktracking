<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AjaxController extends Controller {
    public function __construct(Request $request) {
		if ($request->ajax() == false) abort('404');
    }
}
