<?php

namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ConnectedExchange;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConnectedExchangeController extends Controller
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
        $query = ConnectedExchange::select('id', 'user_id', 'exchange_id', 'api_key', 'api_key_secret', 'wallet_private_key')
            ->with([
                // 'user' => function($q) {
                //     $q->select('id', 'name', 'email');
                // },
                'exchange' => function($q) {
                    $q->select('id', 'name');
                }
            ]);

        $connectedExchanges = $query->get();

        return response($connectedExchanges, 200);
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
            'exchange_id' => 'required|exists:exchanges,id'
        ];
        if($request->wallet_secret_key) {
            $rules['wallet_private_key'] = 'required';
        }
        else {
            $rules['api_key'] = 'required';
            $rules['api_key_secret'] = 'required';
        }

        $this->validate($request, $rules, [
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange not found',
            'wallet_private_key_required' => 'Wallet private key is required',
            'api_key_required' => 'API key is required',
            'api_key_secret_required' => 'API key secret is required'
        ]);

        $connectedExchange = new ConnectedExchange();
        $connectedExchange->user_id = $request->user()->id;
        $connectedExchange->exchange_id = $request->exchange_id;
        $connectedExchange->api_key = $request->api_key ? $request->api_key : null;
        $connectedExchange->api_key_secret = $request->api_key_secret ? $request->api_key_secret : null;
        $connectedExchange->wallet_private_key = $request->wallet_private_key ? $request->wallet_private_key : null;
        $connectedExchange->save();

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
            'data' => $connectedExchange->toArray()
        ]);

        return response()->json($connectedExchange, 201);
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
        if(!$id) {
            return response()->json([
                'message' => 'Connected exchange id is required'
            ], 404);
        }

        $connectedExchange = ConnectedExchange::find($id);
        if(!$connectedExchange) {
            return response()->json([
                'message' => 'Connected exchange not found'
            ], 404);
        }

        $rules = [
            'exchange_id' => 'required|exists:connected_exchanges,id'
        ];
        if($request->wallet_secret_key) {
            $rules['wallet_private_key'] = 'required';
        }
        else {
            $rules['api_key'] = 'required';
            $rules['api_key_secret'] = 'required';
        }

        $this->validate($request, $rules, [
            'exchange_id_required' => 'Exchange id is required',
            'exchange_id_exists' => 'Exchange not found',
            'wallet_private_key_required' => 'Wallet private key is required',
            'api_key_required' => 'API key is required',
            'api_key_secret_required' => 'API key secret is required'
        ]);

        $connectedExchange->exchange_id = $request->exchange_id;
        $connectedExchange->api_key = $request->api_key ? $request->api_key : $connectedExchange->api_key;
        $connectedExchange->api_key_secret = $request->api_key_secret ? $request->api_key_secret : $connectedExchange->api_key_secret;
        $connectedExchange->wallet_private_key = $request->wallet_private_key ? $request->wallet_private_key : $connectedExchange->wallet_private_key;
        $connectedExchange->save();

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
            'data' => $connectedExchange->toArray()
        ]);

        return response()->json($connectedExchange, 200);
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
        if(!$id) {
            return response()->json([
                'message' => 'Connected exchange id is required'
            ], 404);
        }

        $connectedExchange = ConnectedExchange::find($id);
        if(!$connectedExchange) {
            return response()->json([
                'message' => 'Connected exchange not found'
            ], 404);
        }

        $connectedExchange->delete();

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
            'data' => $connectedExchange->toArray()
        ]);

        return response('Success', 200);
    }
}