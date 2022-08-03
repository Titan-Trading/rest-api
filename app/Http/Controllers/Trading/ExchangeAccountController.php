<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Exchange;
use App\Models\Trading\ExchangeAccount;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExchangeAccountController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Exchange accounts list
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = ExchangeAccount::select('id', 'user_id', 'exchange_id', 'api_key', 'api_key_secret', 'api_key_passphrase', 'api_version', 'wallet_private_key')
            ->whereUserId($request->user()->id)
            ->with([
                // 'user' => function($q) {
                //     $q->select('id', 'name', 'email');
                // },
                'exchange' => function($q) {
                    $q->select('id', 'name');
                }
            ]);

        $exchangeAccounts = $query->get();

        return response($exchangeAccounts);
    }

    /**
     * Create exchange account
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $rules = [
            'exchange_id' => ['required', 'exists:exchanges,id']
        ];

        $exchange = Exchange::find($request->exchange_id);
        if(!$exchange) {
            return response()->json([
                'message' => 'Exchange not found'
            ], 422);
        }

        // check if the exchange the account is for is a decentralized exchange, use wallet secret key instead
        if($exchange->is_dex) {
            $rules['wallet_private_key'][] = 'required';
        }
        else {
            $rules['api_key'][] = 'required|unique:exchange_accounts,api_key';
            $rules['api_key_secret'][] = 'required';
            $rules['api_key_passphrase'][] = 'required';
        }

        $this->validate($request, $rules, [
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange not found',
            'wallet_private_key_required' => 'Wallet private key is required',
            'api_key_required' => 'API key is required',
            'api_key_unique' => 'API key must be unique',
            'api_key_secret_required' => 'API key secret is required',
            'api_key_passphrase_required' => 'API key passphrase is required'
        ]);

        $exchangeAccount = new ExchangeAccount();
        $exchangeAccount->user_id = $request->user()->id;
        $exchangeAccount->exchange_id = $request->exchange_id;

        if($exchange->is_dex) {
            $exchangeAccount->wallet_private_key = $request->wallet_private_key;
        }
        else {
            $exchangeAccount->api_key = $request->api_key;
            $exchangeAccount->api_key_secret = $request->api_key_secret;
            $exchangeAccount->api_key_passphrase = $request->api_key_passphrase;
            $exchangeAccount->api_version = '2';
        }

        $exchangeAccount->save();

        /**
         * Add new exchange account onto message bus
         */

        $this->messageBus->sendMessage('exchange-accounts', [
            'topic' => 'exchange-accounts',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'CREATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchangeAccount->toArray()
        ]);

        return response()->json($exchangeAccount, 201);
    }

    /**
     * Update exchange account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $exchangeAccount = ExchangeAccount::find($id);
        if(!$exchangeAccount) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if exchange account is for the current user
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to exchange account'
            ]);
        }

        $exchange = Exchange::find($request->exchange_id);
        if(!$exchange) {
            return response()->json([
                'message' => 'Exchange not found'
            ], 422);
        }

        $rules = [
            'exchange_id' => ['required', 'exists:exchange_accounts,id']
        ];

        // check if the exchange the account is for is a decentralized exchange, use wallet secret key instead
        if($exchange->is_dex) {
            $rules['wallet_private_key'][] = 'required';
        }
        else {
            $rules['api_key'][] = 'required';
            if($exchangeAccount->api_key != $request->api_key) {
                $rules['api_key'][] = 'unique:exchange_accounts,api_key';
            }

            $rules['api_key_secret'][] = 'required';
            $rules['api_key_passphrase'][] = 'required';
        }

        $this->validate($request, $rules, [
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange not found',
            'wallet_private_key_required' => 'Wallet private key is required',
            'api_key_required' => 'API key is required',
            'api_key_unique' => 'API key must be unique',
            'api_key_secret_required' => 'API key secret is required',
            'api_key_passphrase_required' => 'API key passphrase is required'
        ]);

        $exchangeAccount->exchange_id = $request->exchange_id;

        if($exchange->is_dex) {
            $exchangeAccount->wallet_private_key = $request->wallet_private_key ? $request->wallet_private_key : $exchangeAccount->wallet_private_key;
        }
        else {
            $exchangeAccount->api_key = $request->api_key ? $request->api_key : $exchangeAccount->api_key;
            $exchangeAccount->api_key_secret = $request->api_key_secret ? $request->api_key_secret : $exchangeAccount->api_key_secret;
            $exchangeAccount->api_key_passphrase = $request->api_key_passphrase ? $request->api_key_passphrase : $exchangeAccount->api_key_passphrase;
            $exchangeAccount->api_version = '2';
        }
        
        $exchangeAccount->save();

        /**
         * Update exchange account onto message bus
         */

        $this->messageBus->sendMessage('exchange-accounts', [
            'topic' => 'exchange-accounts',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchangeAccount->toArray()
        ]);

        return response()->json($exchangeAccount);
    }

    /**
     * Delete exchange account by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $exchangeAccount = ExchangeAccount::find($id);
        if(!$exchangeAccount) {
            return response()->json([
                'message' => 'Connected exchange not found'
            ], 404);
        }

        // check if exchange account is for the current user
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to remove exchange account'
            ], 403);
        }

        $exchangeAccount->delete();

        /**
         * Delete exchange account onto message bus
         */

        $this->messageBus->sendMessage('exchange-accounts', [
            'topic' => 'exchange-accounts',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'DELETED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $exchangeAccount->toArray()
        ]);

        return response()->json($exchangeAccount);
    }
}