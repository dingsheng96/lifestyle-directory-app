<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Career;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Widgets
        $total_listing_careers = Career::publish()
            ->when($user->is_main_branch, function ($query) use ($user) {
                $user->load(['subBranches']);
                $query->where('branch_id', $user->id)->orWhere('branch_id', $user->subBranches->pluck('id'));
            })
            ->when($user->is_sub_branch, function ($query) use ($user) {
                $user->load(['mainBranch']);
                $query->where('branch_id', $user->id);
            })
            ->count();

        return view('merchant.dashboard', compact('total_listing_careers'));
    }
}
