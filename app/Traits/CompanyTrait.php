<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\CompanyUser;

trait CompanyTrait
{

    public function activeCompany()
    {
        $company = CompanyUser::where('user_id', auth()->user()->id)->where('active', true)->first();
        if (empty($company)) :
            return false;
        endif;

        return Company::find($company->company_id);
    }

    public function activeCompanyID()
    {
        $company = CompanyUser::where('user_id', auth()->user()->id)->where('active', true)->first();
        if (empty($company)) :
            return false;
        endif;

        return $company->company_id;
    }
}
