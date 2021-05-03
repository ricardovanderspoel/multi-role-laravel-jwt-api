<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Company;
use App\Models\CompanyUser;

class CompanyController extends Controller
{
    public function list(Request $request)
    {
        if (empty($request->user()->companies[0])) :
            return response()->json(['status' => 'failed', 'data' => null]);
        endif;

        return response()->json(['status' => 'success', 'data' => $request->user()->companies]);
    }

    public function add(Request $request)
    {
        $company = new Company;
        $company->name = $request->name;
        $company->address = $request->address;
        $company->city = $request->city;
        $company->postcode = $request->postcode;
        $company->country = $request->country;
        $company->kvk = $request->kvk;
        $company->email = $request->email;
        $company->phone = $request->phone;

        if (!$company->save()) :
            return response()->json(['status' => 'failed', 'message' => 'company name already exists'], 400);
        endif;

        $company_user = new CompanyUser;
        $company_user->user_id = $request->user()->id;
        $company_user->company_id = $company->id;
        $company_user->role = 'owner';
        $company_user->active = false;
        $company_user->save();

        $request->id = $company->id;
        if (!$this->active($request)) :
            return response()->json(['status' => 'success', 'msg' => 'success but failed to set company as active', 'data' => $company], 200);
        else :
            return response()->json(['status' => 'success', 'data' => $company], 200);
        endif;

        return response()->json(['status' => 'success', 'data' => $company], 200);
    }

    public function delete(Request $request)
    {
        $id = $request->route('id');
        $company_user = CompanyUser::where('user_id', $request->user()->id)->where('company_id', $id)->where('role', 'owner')->first();
        if (empty($company_user)) :
            return response()->json(['status' => 'failed', 'message' => 'company not found or does not belong to user'], 400);
        endif;


        if (!$company_user->delete()) :
            return response()->json(['status' => 'failed', 'message' => 'failed to delete company'], 400);
        endif;

        $company = Company::find($id);
        if (!$company->delete()) :
            return response()->json(['status' => 'failed', 'message' => 'failed to delete company'], 400);
        endif;

        return response()->json(['status' => 'success', 'message' => 'sucessfully deleted company'], 200);
    }

    public function update(Request $request)
    {
        $id = $request->route('id');
        $company_user = CompanyUser::where('user_id', $request->user()->id)->where('company_id', $id)->where('role', 'owner')->first();

        if (empty($company_user)) :
            return response()->json(['status' => 'failed', 'message' => 'company not found or does not belong to user'], 400);
        endif;

        if (isset($request->role)) :
            if (!$company_user->update(['role' => $request->role])) :
                return response()->json(['status' => 'failed', 'message' => 'failed to update company'], 400);
            endif;
        endif;

        $company = Company::find($id);
        if (!$company->update($request->all())) :
            return response()->json(['status' => 'failed', 'message' => 'failed to update company'], 400);
        endif;

        return response()->json(['status' => 'success', 'data' => $request->user()->company($id)], 200);
    }

    public function active(Request $request)
    {
        $companies = CompanyUser::where('user_id', $request->user()->id)->get();

        if (empty($companies)) :
            return response()->json(['status' => 'failed', 'message' => 'company not found or does not belong to user'], 400);
        endif;

        if (!CompanyUser::where('user_id', $request->user()->id)->update(['active' => false])) :
            return response()->json(['status' => 'failed', 'message' => 'failed to set company as active'], 400);
        endif;

        //$company = CompanyUser::where('user_id', $request->user()->id)->where('company_id', $request->id)->get();
        if (!CompanyUser::where('user_id', $request->user()->id)->where('company_id', $request->id)->update(['active' => true])) :
            return response()->json(['status' => 'failed', 'message' => 'failed to set company as active'], 400);
        endif;

        return response()->json(['status' => 'success', 'data' => $request->user()->company($request->id)], 200);
    }

    public function join(Request $request)
    {
        $company_user = CompanyUser::where('company_id', $request->id)->where('user_id', $request->user()->id)->first();

        if (!empty($company_user)) :
            return response()->json(['status' => 'failed', 'message' => 'User already belongs to company'], 400);
        endif;

        $company_user = new CompanyUser;
        $company_user->user_id = $request->user()->id;
        $company_user->company_id = $request->id;
        $company_user->role = 'manager';
        $company_user->active = true;

        if (!$company_user->save()) :
            return response()->json(['status' => 'failed', 'message' => 'Failed to apply to company'], 400);
        endif;

        return response()->json(['status' => 'success', 'data' => $request->user()->company($request->id)], 200);
    }

    public function leave(Request $request)
    {
        $company_user = CompanyUser::where('company_id', $request->id)->where('user_id', $request->user()->id)->first();

        if (empty($company_user)) :
            return response()->json(['status' => 'failed', 'message' => 'User does not belong to company'], 400);
        endif;

        if (!$company_user->delete()) :
            return response()->json(['status' => 'failed', 'message' => 'Failed to leave company'], 400);
        endif;

        return response()->json(['status' => 'success', 'data' => $request->user()->company($request->id)], 200);
    }
}
