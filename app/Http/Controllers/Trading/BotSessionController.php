<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Bot;
use App\Models\Trading\BotSession;
use App\Models\Trading\ExchangeAccount;
use App\Services\MessageBus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotSessionController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Get list of all active sessions
     *
     * @param Request $request
     * @return void
     */
    public function allActive(Request $request)
    {
        $sessions = BotSession::query()
            ->whereActive(true)
            ->with(['connectedExchange', 'bot'])
            ->get();

        return response()->json($sessions);
    }

    /**
     * Get list of sessions for a bot (for the current user)
     *
     * @param Request $request
     * @param integer $botId
     * @return void
     */
    public function index(Request $request, $botId)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this bot'
            ], 403);
        }

        $sessions = BotSession::whereUserId($request->user()->id)->whereBotId($botId)->get();

        return response()->json($sessions);
    }

    /**
     * Create a new bot session
     *
     * @param Request $request
     * @param integer $botId
     * @return void
     */
    public function store(Request $request, $botId)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this bot'
            ], 403);
        }

        $this->validate($request, [
            'exchange_account_id' => ['required','exists:exchange_accounts,id'],
            'name' => ['required'],
            'parameters' => ['required'],
            'mode' => ['required'],
            'active' => ['required'],
            'started_at' => ['required']
        ], [
            'exchange_account_id_required' => 'Connected exchange id is required',
            'exchange_account_id_exists' => 'Connected exchange is not found',
            'name_required' => 'Name is required',
            'parameters_required' => 'Parameters is required',
            'mode_required' => 'Mode is required',
            'active_required' => 'Active is required',
            'started_at_required' => 'Start date is required'
        ]);

        // check if exchange account belongs to current user
        $exchangeAccount = ExchangeAccount::find($request->exchange_account_id);
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this exchange account'
            ], 403);
        }

        $session = new BotSession();
        $session->user_id = $request->user()->id;
        $session->exchange_account_id = $request->exchange_account_id;
        $session->bot_id = $botId;
        $session->name = $request->name;
        $session->parameters = $request->parameters;
        $session->algorithm_text_compiled = $bot->algorithm_text_compiled;
        $session->mode = $request->mode;
        $session->active = $request->active;
        $session->started_at = $request->started_at;
        $session->ended_at = $request->ended_at ? $request->ended_at : null;
        $session->save();

        /**
         * Add new bot session onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('bot-sessions', [
            'topic' => 'bot-sessions',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'CREATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $session->toArray()
        ]);

        return response()->json($session, 201);
    }

    /**
     * Get a bot session by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $botId, $id)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this bot'
            ], 403);
        }

        $session = BotSession::whereUserId($request->user()->id)
            ->whereBotId($botId)
            ->whereId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($session);
    }

    /**
     * Update a bot session by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $botId, $id)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this bot'
            ], 403);
        }

        $session = BotSession::whereUserId($request->user()->id)
            ->whereBotId($botId)
            ->whereId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'exchange_account_id' => ['required', 'exists:exchange_accounts,id'],
            'name' => ['required'],
            'parameters' => ['required'],
            'mode' => ['required'],
            'active' => ['required'],
            'started_at' => ['required']
        ], [
            'exchange_account_id_required' => 'Connected exchange id is required',
            'exchange_account_id_exists' => 'Connected exchange is not found',
            'name_required' => 'Name is required',
            'parameters_required' => 'Parameters is required',
            'mode_required' => 'Mode is required',
            'active_required' => 'Active is required',
            'started_at_required' => 'Start date is required'
        ]);

        // check if exchange account belongs to current user
        $exchangeAccount = ExchangeAccount::find($request->exchange_account_id);
        if($exchangeAccount->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this exchange account'
            ], 403);
        }

        $session->exchange_account_id = $request->exchange_account_id;
        $session->bot_id = $botId;
        $session->name = $request->name;
        $session->parameters = $request->parameters;
        $session->algorithm_text_compiled = $request->algorithm_text_compiled;
        $session->mode = $request->mode;
        $session->active = $request->active;
        $session->started_at = $request->started_at;
        $session->ended_at = $request->ended_at ? $request->ended_at : $session->ended_at;
        $session->save();

        /**
         * Add bot session update onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('bot-sessions', [
            'topic' => 'bot-sessions',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $session->toArray()
        ]);

        return response()->json($session);
    }

    /**
     * Resume a bot session by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function resume(Request $request, $botId, $id)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $session = BotSession::whereUserId($request->user()->id)
            ->whereBotId($botId)
            ->whereId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $session->active = true;
        $session->save();

        /**
         * Add bot session update onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('bot-sessions', [
            'topic' => 'bot-sessions',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'RESUMED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $session->toArray()
        ]);

        return response()->json($session);
    }

    /**
     * Stop a bot session by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function stop(Request $request, $botId, $id)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $session = BotSession::whereUserId($request->user()->id)
            ->whereBotId($botId)
            ->whereId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $this->validate($request, [
            'paused_at' => ['required']
        ], [
            'paused_at_required' => 'Paused date is required'
        ]);

        $session->active = false;
        $session->paused_at = $request->paused_at;
        $session->save();

        /**
         * Add bot session update onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('bot-sessions', [
            'topic' => 'bot-sessions',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'STOPPED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $session->toArray()
        ]);

        return response()->json($session);
    }

    /**
     * Delete a bot session by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $botId, $id)
    {
        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to access this bot'
            ], 403);
        }

        $session = BotSession::whereUserId($request->user()->id)
            ->whereBotId($botId)
            ->whereId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $session->delete();

        /**
         * Add bot session delete onto message bus
         * - Switch which topic the message is added to based on if the session is live trading or backtesting
         */

        $this->messageBus->sendMessage('bot-sessions', [
            'topic' => 'bot-sessions',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'DELETED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $session->toArray()
        ]);

        return response()->json($session);
    }
}