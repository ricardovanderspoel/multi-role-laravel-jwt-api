<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;



class CustomerController extends Controller
{
    public function create(Request $request)
    {
        $company = auth()->user()->activeCompany();
        if (empty($company)) :
        // return response error: No active company
        endif;

        $user = User::where('email', $request->email)->first();
        if (empty($user)) :
            $user = new User;
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->address = $request->address;
            $user->postcode = $request->postcode;
            $user->city = $request->city;
            $user->country = $request->country;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = bcrypt(Str::random(40));
            if (!$user->save()) :
                return response()->json(['status' => 'failed', 'message' => 'failed to create new user'], 400);
            endif;
            $company_customer = CompanyCustomer::where('user_id', $user->id)->where('company_id', $company->id)->first();
            $company_customer = new CompanyCustomer;
            $company_customer->company_id = $company->id;
            $company_customer->user_id = $user->id;
            if (!$company_customer->save()) :
                return response()->json(['status' => 'failed', 'message' => 'Failed to add user as customer'], 400);
            // failed to add user as customer of company;
            endif;
        else :
            $company_customer = CompanyCustomer::where('user_id', $user->id)->where('company_id', $company->id)->first();
            if (!empty($user)) :
                return response()->json(['status' => 'failed', 'message' => 'This user is already registered a customer of this company'], 400);
            endif;
            $company_customer = new CompanyCustomer;
            $company_customer->company_id = $company->id;
            $company_customer->user_id = $user->id;
            if (!$company_customer->save()) :
                return response()->json(['status' => 'failed', 'message' => 'Failed to add user as customer'], 400);
            // failed to add user as customer of company;
            endif;
        endif;

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    public function list()
    {
        $customers = CompanyCustomer::where('company_id', auth()->user()->activeCompanyID())->get();
        foreach ($customers as $customer) :
            $users[] = User::find($customer->user_id);
        endforeach;

        return response()->json(['status' => 'success', 'data' => $users]);
    }

    public function update(Request $request)
    {
        $id = $request->route('id');

        $company_customer = CompanyCustomer::where('user_id', $request->user()->id)->where('company_id', $id)->first();

        if (empty($company_customer)) :
            return response()->json(['status' => 'failed', 'message' => 'customer not found'], 400);
        endif;

        $user = User::find($id);
        if (!$user->update($request->all())) :
            return response()->json(['status' => 'failed', 'message' => 'failed to update user'], 400);
        endif;

        return response()->json(['status' => 'success', 'data' => $user], 200);
    }
}
