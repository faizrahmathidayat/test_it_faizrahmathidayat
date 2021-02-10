<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect,Response,DB,Config;
use Datatables;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.transactions.index');
    }

    public function transactionList()
    {
        $transactions = DB::table('transactions')
                        ->select(DB::raw("transactions.id, transactions.receiver_name, customer.email, transactions.province_id, transactions.city_id, transactions.subdistrict_id, transactions.zip_code, transactions.address, transactions.address_detail"))
                        ->join('customer', 'transactions.user_id', '=', 'customer.id')
                        ->get();
        return datatables()->of($transactions)
            ->addIndexColumn()
            ->addColumn('action', function($row){

                        $btnview = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm view">View</a>';
                        $btnupdate = $btnview.'<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success btn-sm update">Update</a>';
                        $btn = $btnupdate.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</a>';

                     return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function transactionStore(Request $request)
    {
        $client = new Client();
        $res = $client->get('http://142.93.48.44:8997/api/orderFreshbox');
        $response = json_decode($res->getBody()->getContents(), true);

        $transactions_db_internal = DB::table('transactions')->select('*')
                                    ->get();
        $arr_transactions = array();
        $arr_transactions_detail = array();
        $arr_customer = array();
        foreach ($response['data'] as $transaction) {
            $arr_transactions[] = array(
                        'id' => $transaction['id'],
                        'user_id' => $transaction['user_id'],
                        'invoice' => $transaction['invoice'],
                        'sub_total' => $transaction['sub_total'],
                        'shipping_cost' => $transaction['shipping_cost'],
                        'grand_total' => $transaction['grand_total'],
                        'status' => $transaction['status'],
                        'kurir_id' => $transaction['kurir_id'],
                        'payment_method' => $transaction['payment_method'],
                        'va_number' => $transaction['va_number'],
                        'va_bank' => $transaction['va_bank'],
                        'receiver_name' => $transaction['receiver_name'],
                        'phone_number' => $transaction['phone_number'],
                        'address' => $transaction['address'],
                        'address_detail' => $transaction['address_detail'],
                        'zip_code' => $transaction['zip_code'],
                        'request_shipping_date' => $transaction['request_shipping_date'],
                        'checkout_date' => $transaction['checkout_date'],
                        'paid_date' => $transaction['paid_date'],
                        'expired_date' => $transaction['expired_date'],
                        'admin_process_date' => $transaction['admin_process_date'],
                        'shipping_date' => $transaction['shipping_date'],
                        'finish_date' => $transaction['finish_date'],
                        'invoice_printed' => $transaction['invoice_printed'],
                        'shipping_label_printed' => $transaction['shipping_label_printed'],
                        'created_at' => $transaction['created_at'],
                        'updated_at' => $transaction['updated_at'],
                        'updated_at' => $transaction['updated_at'],
                        'province_id' => $transaction['province_id'],
                        'city_id' => $transaction['city_id'],
                        'subdistrict_id' => $transaction['subdistrict_id'],
                        'zip_code_id' => $transaction['zip_code_id'],
                        'coupon_code' => $transaction['coupon_code'],
                        'discount_ammount' => $transaction['discount_ammount'],
                        'payment_type' => $transaction['payment_type'],
                        );
            foreach ($transaction['order_details'] as $transactions_detail) {
                $arr_transactions_detail[] = array(
                                                'id' => $transactions_detail['id'],
                                                'transaction_id' => $transactions_detail['transaction_id'],
                                                'product_id' => $transactions_detail['product_id'],
                                                'product_name' => $transactions_detail['product_name'],
                                                'qty' => $transactions_detail['qty'],
                                                'unit' => $transactions_detail['unit'],
                                                'weight' => $transactions_detail['weight'],
                                                'sub_total' => $transactions_detail['sub_total'],
                                                'banner_id' => $transactions_detail['banner_id'],
                                                'price' => $transactions_detail['price'],
                                                'promo_amount' => $transactions_detail['promo_amount'],
                                                'price_promo' => $transactions_detail['price_promo'],
                                                'price_promo_quantity' => $transactions_detail['price_promo_quantity'],
                                                'voucher_amount' => $transactions_detail['voucher_amount'],
                                                'price_discount' => $transactions_detail['price_discount'],
                                                'sub_total_discount' => $transactions_detail['sub_total_discount'],
                                                ); 
            }

            $arr_customer[] = array(
                                    'id' => $transaction['user']['id'],
                                    'role_id' => $transaction['user']['role_id'],
                                    'name' => $transaction['user']['name'],
                                    'email' => $transaction['user']['email'],
                                    'phone_number' => $transaction['user']['phone_number'],
                                    'otp' => $transaction['user']['otp'],
                                    'otp_validation' => $transaction['user']['otp_validation'],
                                    'image' => $transaction['user']['image'],
                                    'status' => $transaction['user']['status'],
                                    'fb_token' => $transaction['user']['fb_token'],
                                    'google_token' => $transaction['user']['google_token'],
                                    'api_token' => $transaction['user']['api_token'],
                                    'player_id' => $transaction['user']['player_id'],
                                    'created_at' => $transaction['user']['created_at'],
                                    'updated_at' => $transaction['user']['updated_at'],
                                    'deleted_at' => $transaction['user']['deleted_at'],
                                    'apple_token' => $transaction['user']['apple_token'],
                                    'payload_token' => $transaction['user']['payload_token'],
                                    );
        }
        DB::table('transactions')->insertOrIgnore($arr_transactions);
        DB::table('transactions_detail')->insertOrIgnore($arr_transactions_detail);
        DB::table('customer')->insertOrIgnore($arr_customer);
        return response()->json(array('status' => 'success'));
    }

    public function transactionDetails(Request $request)
    {
        $transactions = DB::table('transactions')
                        ->select(DB::raw("transactions.id, transactions.receiver_name, customer.email, transactions.province_id, transactions.city_id, transactions.subdistrict_id, transactions.zip_code, transactions.address, transactions.address_detail"))
                        ->join('customer', 'transactions.user_id', '=', 'customer.id')
                        ->where('transactions.id', $request['order_id'])
                        ->get();

        $transactions_detail = DB::table('transactions_detail')
                        ->select(DB::raw("transactions_detail.transaction_id,
                                        transactions_detail.product_name,
                                        products.code as code_product,
                                        transactions_detail.qty,
                                        transactions_detail.unit,
                                        transactions_detail.weight,
                                        transactions_detail.price,
                                        transactions_detail.promo_amount,
                                        transactions_detail.price_promo,
                                        transactions_detail.sub_total"))
                        ->join('products', 'transactions_detail.product_id', '=', 'products.id')
                        ->where('transactions_detail.transaction_id', $request['order_id'])
                        ->get();
        $dataTransactions = array('transaction' => $transactions, 'transaction_detail' => $transactions_detail);
        return response()->json($dataTransactions);
    }

    public function transactionDelete(Request $request)
    {
        $deleteTransactionDetail = DB::table('transactions_detail')->where('transaction_id', '=', $request['order_id'])->delete();
        $deleteTransaction = DB::table('transactions')->where('id', '=', $request['order_id'])->delete();

        return response()->json(array('msg' => 'transaction deleted!'));
    }

    public function transactionUpdate(Request $request)
    {
        $updateTransactions = DB::table('transactions')
                                ->where('id', $request['order_id'])
                                ->update(array('receiver_name' => $request['receiver_name'], 'address' => $request['address'], 'address_detail' => $request['address_detail']));
        return response()->json(array('msg' => 'Transaction Updated!'));
    }
}
