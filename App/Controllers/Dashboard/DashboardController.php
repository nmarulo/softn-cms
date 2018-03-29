<?php

namespace App\Controllers\Dashboard;

use Silver\Core\Controller;
use Silver\Http\View;

/**
 * Dashboard controller
 */
class DashboardController extends Controller {
    
    public function index() {
        return View::make('dashboard.index');
    }
}
