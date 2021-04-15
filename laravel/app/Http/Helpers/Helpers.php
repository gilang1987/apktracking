<?php

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\WebsiteConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

function escape_input($i = '') {
	return htmlspecialchars(strip_tags($i));
}

function website_config($i = '') {
	$check_data = WebsiteConfig::where('key', $i)->first();
	if ($check_data == false) return false;
	return json_decode($check_data->value);
}

function status($status = '') {
	if ($status == 'Waiting') {
		return '<span class="badge badge-warning">WAITING</span>';
	} elseif ($status == 'Approved') {
		return '<span class="badge badge-success">APPROVED</span>';
	} elseif ($status == 'Rejected') {
		return '<span class="badge badge-danger">REJECTED</span>';
	} else {
		return '<span class="badge badge-info">ERROR</span>';
	}
}


function fixed_amount($i = '') {
	if (preg_match("/# /i", $i)) {
		$i = str_replace('.', '', $i);
		$i = str_replace('# ', '', $i);
		if (is_numeric($i) == false) return false;
		if ($i < 0) return false;
		return $i;
	}
	if (preg_match("/Rp /i", $i)) {
		$i = str_replace('.', '', $i);
		$i = str_replace('Rp ', '', $i);
		if (is_numeric($i) == false) return false;
		if ($i < 0) return false;
		return $i;
	}
	if (is_numeric($i) == false) return false;
	if ($i < 0) return false;
	return $i;
}

function category($category = '') {
	if ($category == 'Info') {
		return '<span class="badge badge-info badge-sm">INFO</span>';
	} elseif ($category == 'Maintenance') {
		return '<span class="badge badge-danger badge-sm">MAINTENANCE</span>';
	} elseif ($category == 'Update') {
		return '<span class="badge badge-primary badge-sm">UPDATE</span>';
	} elseif ($category == 'Product') {
		return '<span class="badge badge-success badge-sm">PRODUK</span>';
	} elseif ($category == 'Service') {
		return '<span class="badge badge-success badge-sm">LAYANAN</span>';
	} elseif ($category == 'Other') {
		return '<span class="badge badge-warning badge-sm">OTHER</span>';
	} else {
		return '<span class="badge badge-danger badge-sm">ERROR</span>';
	}
}