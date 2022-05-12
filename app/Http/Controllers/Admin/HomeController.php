<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Career;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // total listing all merchants, total registered members, total listing careers, pending applications
        $total_listing_careers      =   Career::publish()->count();
        $total_listing_merchants    =   User::validMerchant()->publish()->count();
        $total_registered_members   =   User::member()->active()->count();
        $total_pending_applications =   User::active()->pendingApplication()->count();

        // top 10 rated merchants
        $top_rated_merchants = User::validMerchant()->publish()->sortMerchantByRating()->limit(10)->get();

        return view('admin.dashboard', compact('total_listing_careers', 'total_listing_merchants', 'total_registered_members', 'total_pending_applications', 'top_rated_merchants'));
    }
}
