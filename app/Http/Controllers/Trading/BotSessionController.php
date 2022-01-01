<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\BotSession;
use Illuminate\Http\Request;

class BotSessionController extends Controller
{
    /**
     * Get list of all active sessions
     *
     * @param Request $request
     * @return void
     */
    public function allActive(Request $request)
    {
        $sessions = BotSession::query()->with(['connectedExchange', 'bot'])->get();

        return response()->json($sessions, 200);
    }

    /**
     * Get list of sessions for a bot
     *
     * @param Request $request
     * @param integer $botId
     * @return void
     */
    public function index(Request $request, $botId)
    {
        if(!$botId) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot is not found'
            ], 404);
        }

        $sessions = BotSession::whereBotId($botId)->get();

        return response()->json($sessions, 200);
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
        if(!$botId) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot is not found'
            ], 404);
        }

        $this->validate($request, [
            'connected_exchange_id' => 'required|exists:connected_exchanges,id',
            'bot_id' => 'required|exists:bots,id',
            'parameters' => 'required',
            'mode' => 'required'
        ], [
            'connected_exchange_id_required' => 'Connected exchange id is required',
            'connected_exchange_id_exists' => 'Connected exchange is not found',
            'bot_id_required' => 'Bot id is required',
            'bot_id_exists' => 'Bot is not found',
            'parameters_required' => 'Parameters is required',
            'mode_required' => 'Mode is required'
        ]);

        $session = new BotSession();
        $session->user_id = $request->user()->id;
        $session->connected_exchange_id = $request->connected_exchange_id;
        $session->bot_id = $request->bot_id;
        $session->parameters = $request->parameters;
        $session->mode = $request->mode;
        $session->save();

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
        if(!$botId) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        if(!$id) {
            return response()->json([
                'message' => 'Bot session id is required'
            ], 404);
        }

        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot is not found'
            ], 404);
        }

        $session = BotSession::find($id);
        if(!$session) {
            return response()->json([
                'message' => 'Bot session is not found'
            ], 404);
        }

        return response()->json($session, 200);
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
        if(!$botId) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        if(!$id) {
            return response()->json([
                'message' => 'Bot session id is required'
            ], 404);
        }

        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot is not found'
            ], 404);
        }

        $session = BotSession::find($id);
        if(!$session) {
            return response()->json([
                'message' => 'Bot session is not found'
            ], 404);
        }

        $this->validate($request, [
            'connected_exchange_id' => 'required|exists:connected_exchanges,id',
            'bot_id' => 'required|exists:bots,id',
            'parameters' => 'required',
            'mode' => 'required'
        ], [
            'connected_exchange_id_required' => 'Connected exchange id is required',
            'connected_exchange_id_exists' => 'Connected exchange is not found',
            'bot_id_required' => 'Bot id is required',
            'bot_id_exists' => 'Bot is not found',
            'parameters_required' => 'Parameters is required',
            'mode_required' => 'Mode is required'
        ]);

        $session->connected_exchange_id = $request->connected_exchange_id;
        $session->bot_id = $request->bot_id;
        $session->parameters = $request->parameters;
        $session->mode = $request->mode;
        $session->save();

        return response()->json($session, 200);
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
        if(!$botId) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        if(!$id) {
            return response()->json([
                'message' => 'Bot session id is required'
            ], 404);
        }

        $bot = Bot::find($botId);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot is not found'
            ], 404);
        }

        $session = BotSession::find($id);
        if(!$session) {
            return response()->json([
                'message' => 'Bot session is not found'
            ], 404);
        }

        $session->delete();

        return response('Success', 200);
    }
}