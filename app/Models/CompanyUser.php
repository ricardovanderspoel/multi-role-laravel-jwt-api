<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyUser extends Pivot
{
    use HasFactory;
    protected $table = 'company_user';
}
