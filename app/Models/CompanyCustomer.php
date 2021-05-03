<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyCustomer extends Pivot
{
    use HasFactory;
    protected $table = 'company_customer';
}
