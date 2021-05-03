<?php

namespace App\Http\Middleware;

use App\Models\CompanyUser;
use Closure;
use Illuminate\Http\Request;

class IsCompanyAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');
        $company = CompanyUser::where('user_id', auth()->user()->id)->where('company_id', $id)->where('role', 'owner')->first();

        if (!isset($company->user_id)) :
            return response()->json(['status' => 'failed', 'message' => 'This company does not exists or you do not have admin privileges.'], 400);
        endif;

        return $next($request);
    }
}
