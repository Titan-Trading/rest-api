<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\ExchangeAccount;
use App\Models\Trading\Indicator;
use App\Models\Trading\IndicatorTest;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndicatorTestController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Get list of tests for a indicator (for the current user)
     *
     * @param Request $request
     * @param integer $indicatorId
     * @return void
     */
    public function index(Request $request, $indicatorId)
    {
        $indicator = Indicator::find($indicatorId);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if indicator can be access by current user
        // TODO: show indicators that have been purchased (with product order status=active, buyer_id=current user id)
        if($indicator->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this indicator'
            ], 403);
        }

        $tests = IndicatorTest::whereUserId($request->user()->id)->whereIndicatorId($indicatorId)->get();

        return response()->json($tests);
    }

    /**
     * Create a new indicator test
     *
     * @param Request $request
     * @param integer $indicatorId
     * @return void
     */
    public function store(Request $request, $indicatorId)
    {
        $indicator = Indicator::find($indicatorId);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if indicator can be access by current user
        // TODO: show indicators that have been purchased (with product order status=active, buyer_id=current user id)
        if($indicator->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this indicator'
            ], 403);
        }

        $this->validate($request, [
            'exchange_account_id' => ['required','exists:exchange_accounts,id'],
            'bot_id' => ['required', 'exists:bots,id'],
            'name' => ['required'],
            'bot_parameters' => ['required'],
            'indicator_parameters' => ['required'],
            'active' => ['required']
        ], [
            'exchange_account_id_required' => 'Connected exchange id is required',
            'exchange_account_id_exists' => 'Connected exchange is not found',
            'bot_id_required' => 'Bot id is required',
            'bot_id_exists' => 'Bot is not found',
            'name_required' => 'Name is required',
            'bot_parameters_required' => 'Bot parameters is required',
            'indicator_parameters_required' => 'Indicator parameters is required',
            'active_required' => 'Active is required'
        ]);

        // check if exchange account belongs to current user
        $exchangeAccount = ExchangeAccount::find($request->exchange_account_id);
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this exchange account'
            ], 403);
        }

        $test = new IndicatorTest();
        $test->user_id = $request->user()->id;
        $test->exchange_account_id = $request->exchange_account_id;
        $test->indicator_id = $indicatorId;
        $test->bot_id = $request->bot_id;
        $test->name = $request->name;
        $test->bot_parameters = $request->bot_parameters;
        $test->indicator_parameters = $request->indicator_parameters;
        $test->active = $request->active;
        $test->save();

        /**
         * Add new indicator test onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('indicator-tests', [
            'topic' => 'indicator-tests',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'CREATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $test->toArray()
        ]);

        return response()->json($test, 201);
    }

    /**
     * Get a indicator test by id
     *
     * @param Request $request
     * @param integer $indicatorId
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $indicatorId, $id)
    {
        $indicator = Indicator::find($indicatorId);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if indicator can be access by current user
        // TODO: show indicators that have been purchased (with product order status=active, buyer_id=current user id)
        if($indicator->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this indicator'
            ], 403);
        }

        $test = IndicatorTest::whereUserId($request->user()->id)
            ->whereIndicatorId($indicatorId)
            ->whereId($id)
            ->first();
        if(!$test) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($test);
    }

    /**
     * Update a indicator test by id
     *
     * @param Request $request
     * @param integer $indicatorId
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $indicatorId, $id)
    {
        $indicator = Indicator::find($indicatorId);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if indicator can be access by current user
        // TODO: show indicators that have been purchased (with product order status=active, buyer_id=current user id)
        if($indicator->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this indicator'
            ], 403);
        }

        $test = IndicatorTest::whereUserId($request->user()->id)
            ->whereIndicatorId($indicatorId)
            ->whereId($id)
            ->first();
        if(!$test) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'exchange_account_id' => ['required','exists:exchange_accounts,id'],
            'bot_id' => ['required', 'exists:bots,id'],
            'name' => ['required'],
            'bot_parameters' => ['required'],
            'indicator_parameters' => ['required'],
            'active' => ['required']
        ], [
            'exchange_account_id_required' => 'Connected exchange id is required',
            'exchange_account_id_exists' => 'Connected exchange is not found',
            'bot_id_required' => 'Bot id is required',
            'bot_id_exists' => 'Bot is not found',
            'name_required' => 'Name is required',
            'bot_parameters_required' => 'Bot parameters is required',
            'indicator_parameters_required' => 'Indicator parameters is required',
            'active_required' => 'Active is required'
        ]);

        // check if exchange account belongs to current user
        $exchangeAccount = ExchangeAccount::find($request->exchange_account_id);
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this exchange account'
            ], 403);
        }

        $test->exchange_account_id = $request->exchange_account_id;
        $test->indicator_id = $indicatorId;
        $test->bot_id = $request->bot_id;
        $test->name = $request->name;
        $test->bot_parameters = $request->bot_parameters;
        $test->indicator_parameters = $request->indicator_parameters;
        $test->active = $request->active;
        $test->save();

        /**
         * Add indicator test update onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('indicator-tests', [
            'topic' => 'indicator-tests',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $test->toArray()
        ]);

        return response()->json($test);
    }

    /**
     * Delete a indicator test by id
     *
     * @param Request $request
     * @param integer $indicatorId
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $indicatorId, $id)
    {
        $indicator = Indicator::find($indicatorId);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if indicator can be access by current user
        // TODO: show indicators that have been purchased (with product order status=active, buyer_id=current user id)
        if($indicator->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this indicator'
            ], 403);
        }

        $test = IndicatorTest::whereUserId($request->user()->id)
            ->whereIndicatorId($indicatorId)
            ->whereId($id)
            ->first();
        if(!$test) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $test->delete();

        /**
         * Add indicator test delete onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('indicator-tests', [
            'topic' => 'indicator-tests',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'DELETED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $test->toArray()
        ]);

        return response()->json($test);
    }
}