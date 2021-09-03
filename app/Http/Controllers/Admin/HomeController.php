<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public $user;

    public function index()
    {
        $this->user = Auth::user();

        if ($this->user->is_admin) {
            $data = $this->adminDashboard();
        } elseif ($this->user->is_main_merchant) {
            $data = $this->mainMerchantDashboard();
        } elseif ($this->user->is_sub_merchant) {
            $data = $this->subMerchantDashboard();
        }

        return view('dashboard.' . $this->user->folder_name, $data);
    }

    private function adminDashboard(): array
    {
        return [];
    }

    private function mainMerchantDashboard(): array
    {
        return [];
    }

    private function subMerchantDashboard(): array
    {
        return [];
    }
}
