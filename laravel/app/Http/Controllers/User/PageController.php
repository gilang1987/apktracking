<?php

namespace App\Http\Controllers\User;

use App\DataTables\User\Page\InformationDataTable;
use App\DataTables\User\Page\MonitoringServiceDataTable;
use App\DataTables\User\Page\NotificationDataTable;
use App\DataTables\User\Page\ServiceDataTable;
use App\Models\Page;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Category;
use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Models\WebsitePage;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller {
    public function site(WebsitePage $target) {
        $components['breadcrumb'] = (object) [
			'first'  => $target->title,
			'second' => 'Halaman'
        ];
        $components['target'] = $target;
        return view('user.page.site', $components);
    }
}
