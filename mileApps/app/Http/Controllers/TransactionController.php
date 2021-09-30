<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Transaction;
use App\Connote;
use App\Customer_attribute;
use App\Destination_data;
use App\Koli;
use App\Origin_data;
use Illuminate\Support\Facades\Input;
use Symfony\Component\Console\Input\Input as InputInput;

class TransactionController extends Controller
{
    //

    private function get_uuid()
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            mt_rand(0, 0xffff),

            mt_rand(0, 0x0fff) | 0x4000,

            mt_rand(0, 0x3fff) | 0x8000,

            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        return $uuid;
    }


    public function update(Request $request, $id)
    {
        # code...API Create Course
        // Rules untuk validasi laravel
        $rules = [
            // 'transaction_id' => 'required|string',
            'customer_name' => 'required|string',
            'customer_code' => 'required|integer',
            'organization_id' => 'required|integer',
            'transaction_order' => 'required|integer',
        ];

        $validator = Validator::make($request->input(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $data = $request->all();
        $transaction = Transaction::where('transaction_id', $id)->first();
        // dd($transaction);
        $transaction_data = $request->all();

        if (!$transaction) {
            return response()->json([
                'status' => 'error',
                'message' => 'transaction not found'
            ], 404);
        }

        $transaction->fill($transaction_data);
        $transaction->save();

        if (!empty($data['origin_data'])) {
            # code...
            $origin_datax = Origin_data::where('transaction_id', $id)->first();

            if (!$origin_datax) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'data not found'
                ], 404);
            }

            $origin_datax->fill($data['origin_data']);
            $origin_datax->save();
        }

        if (!empty($data['destination_data'])) {
            # code...
            $destination_datax = Destination_data::where('transaction_id', $id)->first();

            if (!$destination_datax) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'destination not found'
                ], 404);
            }

            $destination_datax->fill($data['destination_data']);
            $destination_datax->save();
        }

        if (!empty($data['connote'])) {
            # code...
            $connotex = Connote::where('transaction_id', $id)->first();

            if (!$connotex) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'destination not found'
                ], 404);
            }

            $connotex->fill($data['connote']);
            $connotex->save();
        }


        if (!empty($data['koli'])) {
            foreach ($data['koli'] as $get_koli) {
                $kolixx = Koli::where('transaction_id', $id)->where('koli_code', $get_koli['koli_code'])->first();

                if (!$kolixx) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'destination not found'
                    ], 404);
                }

                $kolixx->fill($get_koli);
                $kolixx->save();
            }
            # code...
        }

        $packages_response = Transaction::with('connote', 'origin_data', 'koli', 'destination_data')->where('transaction_id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data' =>  $packages_response
        ]);
    }

    public function delete($id)
    {
        $packages_response = Transaction::with('connote', 'origin_data', 'koli', 'destination_data')->where('transaction_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Packages Deleted'
        ]);
    }

    public function get_data($id = null)
    {
        # code...

        if (!empty($id)) {
            # code...
            $packages_response = Transaction::with('connote', 'origin_data', 'koli', 'destination_data')->where('transaction_id', $id)->first();
        } else {
            # code...
            $packages_response = Transaction::with('connote', 'origin_data', 'koli', 'destination_data')->paginate(10);
        }

        return response()->json([
            'status' => 'success',
            'data' => $packages_response
        ]);
    }

    public function submit(Request $request)
    {
        # code...
        $rules_trans = [
            'transaction_id' => 'required|string',
            'customer_name' => 'required|string',
            'customer_code' => 'required|integer',
            'organization_id' => 'required|integer',
            'transaction_order' => 'required|integer',
        ];

        // dd($request->all());

        $validator = Validator::make($request->input(), $rules_trans);

        if ($validator->fails()) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        } else {

            $transaction_id = $this->get_uuid();
            $connate_id = $this->get_uuid();
            $connote_code = 'AWB' . date('YmdHis');

            // $data = $request->all();
            $transaction_data = [
                "transaction_id" => $transaction_id,
                "customer_name" => $request->input('customer_name'),
                "customer_code" => $request->input('customer_code'),
                "transaction_amount" => $request->input('transaction_amount'),
                "transaction_discount" => $request->input('transaction_discount'),
                "transaction_additional_field" => $request->input('transaction_additional_field'),
                "transaction_payment_type" => $request->input('transaction_payment_type'),
                "transaction_state" => $request->input('transaction_state'),
                "transaction_code" => $request->input('transaction_code'),
                "transaction_order" => $request->input('transaction_order'),
                "location_id" => $request->input('location_id'),
                "organization_id" => $request->input('organization_id'),
                "created_at" => $request->input('created_at'),
                "updated_at" => $request->input('updated_at'),
                "transaction_payment_type_name" => $request->input('transaction_payment_type_name'),
                "transaction_cash_amount" => $request->input('transaction_cash_amount'),
                "transaction_cash_change" => $request->input('transaction_cash_change'),
                "customer_attribute" => $request->input('customer_attribute'),
                "custom_field" => $request->input('custom_field'),
                "currentLocation" => $request->input('currentLocation'),
                "connote_id" => $connate_id
            ];

            $connote_data = [
                "connote_id" => $connate_id,
                "connote_number" => $request->input("connote_number"),
                "connote_service" => $request->input("connote_service"),
                "connote_service_price" => $request->input("connote_service_price"),
                "connote_amount" => $request->input("connote_amount"),
                "connote_code" => $connote_code,
                "connote_booking_code" => $request->input("connote_booking_code"),
                "connote_order" => $request->input("connote_order"),
                "connote_state" => $request->input("connote_state"),
                "connote_state_id" => $request->input("connote_state_id"),
                "zone_code_from" => $request->input("zone_code_from"),
                "zone_code_to" => $request->input("zone_code_to"),
                "surcharge_amount" => $request->input("surcharge_amount"),
                "transaction_id" => $transaction_id,
                "actual_weight" => $request->input("actual_weight"),
                "volume_weight" => $request->input("volume_weight"),
                "chargeable_weight" => $request->input("chargeable_weight"),
                "created_at" => $request->input("created_at"),
                "updated_at" => $request->input("updated_at"),
                "organization_id" => $request->input("organization_id"),
                "location_id" => $request->input("location_id"),
                "connote_total_package" => $request->input("connote_total_package"),
                "connote_surcharge_amount" => $request->input("connote_surcharge_amount"),
                "connote_sla_day" => $request->input("connote_sla_day"),
                "location_name" => $request->input("location_name"),
                "location_type" => $request->input("location_type"),
                "source_tariff_db" => $request->input("source_tariff_db"),
                "id_source_tariff" => $request->input("id_source_tariff"),
                "pod" => $request->input("pod"),
                "history" => $request->input("history"),
            ];

            $origin_data = [
                "transaction_id" => $transaction_id,
                "customer_name" => $request->input("origin_data.customer_name"),
                "customer_address" => $request->input("origin_data.customer_address"),
                "customer_email" => $request->input("origin_data.customer_email"),
                "customer_phone" => $request->input("origin_data.customer_phone"),
                "customer_address_detail" => $request->input("origin_data.customer_address_detail"),
                "customer_zip_code" => $request->input("origin_data.customer_zip_code"),
                "zone_code" => $request->input("origin_data.zone_code"),
                "organization_id" => $request->input("origin_data.organization_id"),
                "location_id" => $request->input("origin_data.location_id"),
            ];

            $destination_data = [
                "transaction_id" => $transaction_id,
                "customer_name" => $request->input("destination_data.customer_name"),
                "customer_address" => $request->input("destination_data.customer_address"),
                "customer_email" => $request->input("destination_data.customer_email"),
                "customer_phone" => $request->input("destination_data.customer_phone"),
                "customer_address_detail" => $request->input("destination_data.customer_address_detail"),
                "customer_zip_code" => $request->input("destination_data.customer_zip_code"),
                "zone_code" => $request->input("destination_data.zone_code"),
                "organization_id" => $request->input("destination_data.organization_id"),
                "location_id" => $request->input("destination_data.location_id"),
            ];


            $transaction = Transaction::create($transaction_data);
            $connote = Connote::create($connote_data);
            $origin = Origin_data::create($origin_data);
            $destination = Destination_data::create($destination_data);
            $i = 1;
            foreach ($request->input('koli_data') as $get_koli) {
                $koli_data = [
                    "transaction_id" => $transaction_id,
                    "koli_length" => $get_koli["koli_length"],
                    "awb_url" => $get_koli["awb_url"],
                    "created_at" => $get_koli["created_at"],
                    "koli_chargeable_weight" => $get_koli["koli_chargeable_weight"],
                    "koli_width" => $get_koli["koli_width"],
                    "koli_surcharge" => $get_koli["koli_surcharge"],
                    "koli_height" => $get_koli["koli_height"],
                    "updated_at" => $get_koli["updated_at"],
                    "koli_description" => $get_koli["koli_description"],
                    "koli_formula_id" => $get_koli["koli_formula_id"],
                    "connote_id" => $connate_id,
                    "koli_volume" => $get_koli["koli_volume"],
                    "koli_weight" => $get_koli["koli_weight"],
                    "koli_id" => $get_koli["koli_id"],
                    "koli_custom_field" => $get_koli["koli_custom_field"],
                    "koli_code" => $connote_code . '.' . $i,
                ];
                $i++;
                $koli = Koli::create($koli_data);
            }

            $packages_response = Transaction::with('connote', 'origin_data', 'koli', 'destination_data')->where('transaction_id', $transaction_id)->first();

            return response()->json([
                'status' => 'success',
                'data' => $packages_response
            ]);
        }
    }
}
