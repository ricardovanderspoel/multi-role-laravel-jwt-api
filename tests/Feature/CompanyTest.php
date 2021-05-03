<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Company;

use Illuminate\Support\Facades\Auth;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserCanRetrieveCompanies()
    {
        Auth::loginUsingId(101);
        print_r(auth()->user()->activeCompany()->toArray());
    }
}
