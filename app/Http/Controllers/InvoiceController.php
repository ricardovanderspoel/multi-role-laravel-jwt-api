<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice as LDInvoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

use App\Models\Invoice;

class InvoiceController extends Controller
{

    public function list()
    {
        $company = auth()->user()->activeCompany();
        $invoices = Invoice::where('company_id', $company->id)->get();
        return response()->json(['status' => 'success', 'data' => $invoices]);
    }

    public function create(Request $request)
    {
        $company = auth()->user()->activeCompany();

        $client = new Party([
            'name'          => $company->name,
            'phone'         => $company->phone,
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => $company->kvk,
            ],
        ]);

        $customer = new Party([
            'name'          => $request->name,
            'address'       => $request->address,
            'code'          => $request->code,
            'custom_fields' => [
                'order number' => '> ' . $request->ordernr . ' <',
            ],
        ]);

        foreach ($request->products as $key => $product) :
            $object = new InvoiceItem;
            if (isset($product['title'])) : $object->title($product['title']);
            endif;
            if (isset($product['quantity'])) : $object->quantity($product['quantity']);
            endif;
            if (isset($product['discount'])) : $object->discount($product['discount']);
            endif;
            if (isset($product['discountbypercent'])) : $object->discountByPercent($product['discountbypercent']);
            endif;
            if (isset($product['priceperunit'])) : $object->pricePerUnit($product['priceperunit']);
            endif;
            if (isset($product['units'])) : $object->units($product['units']);
            endif;
            $items[] = $object;
        endforeach;

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $ld_invoice = LDInvoice::make('receipt')
            ->series('BIG')
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename(Carbon::now()->format('YmdHs'))
            ->addItems($items)
            ->notes($notes)
            //->logo(public_path('vendor\laraveldaily\laravel-invoices\public\sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('local');

        $link = $ld_invoice->url();
        // Then send email to party with link

        $invoice = new Invoice;
        $invoice->company_id = $company->id;
        $invoice->client_id = 1;
        $invoice->link = $link;
        $invoice->is_mailed = false;
        $invoice->save();

        // And return invoice itself to browser or have a different view
        //return $ld_invoice->stream();

        return response()->json(['status' => 'success', 'data' => $invoice]);
    }
}
