<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\Branches\MerchantBranchesImportFirstSheet;

class MerchantBranchesImport implements WithMultipleSheets
{
    use Importable;

    protected $merchant;

    public function __construct(User $merchant)
    {
        $this->merchant = $merchant;
    }

    public function sheets(): array
    {
        return [
            new MerchantBranchesImportFirstSheet($this->merchant)
        ];
    }
}
