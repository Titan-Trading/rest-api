<?php
namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\BotSession;
use App\Models\Trading\BotSessionResult;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BotSessionResultController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Create a new bot session result
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $sessionId
     * @return void
     */
    public function store(Request $request, $botId, $id)
    {
        $botSession = BotSession::find($id);
        if(!$botSession) {
            return response()->json([
                'message' => 'Bot session not found'
            ], 404);
        }

        $this->validate($request, [
            'total_pips' => ['required'],
            'total_trades' => ['required'],
            'total_wins' => ['required'],
            'total_losses' => ['required'],
            'winning_percent' => ['required'],
            'risk_percent_per_position' => ['required'],
            'max_dollar_drawdown' => ['required'],
            'total_dollar_gain' => ['required'],
            'total_dollar_percent_gain' => ['required'],
            'starting_balance' => ['required'],
            'ending_balance' => ['required']
        ]);

        $result = new BotSessionResult();
        $result->bot_session_id = $id;
        $result->total_pips = $request->total_pips;
        $result->total_trades = $request->total_trades;
        $result->total_wins = $request->total_wins;
        $result->total_losses = $request->total_losses;
        $result->winning_percent = $request->winning_percent;
        $result->risk_percent_per_position = $request->risk_percent_per_position;
        $result->max_dollar_drawdown = $request->max_dollar_drawdown;
        $result->total_dollar_gain = $request->total_dollar_gain;
        $result->total_dollar_percent_gain = $request->total_dollar_percent_gain;
        $result->starting_balance = $request->starting_balance;
        $result->ending_balance = $request->ending_balance;
        $result->save();

        /**
         * Add new bot session result onto message bus
         */

        $this->messageBus->sendMessage('bot-session-results', [
            'topic' => 'bot-session-results',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'CREATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $result->toArray()
        ]);

        return response()->json($result, 201);
    }

    /**
     * Get a bot session result by id
     *
     * @param Request $request
     * @param integer $botId
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $botId, $id)
    {
        $session = BotSessionResult::query()
            ->whereBotSessionId($id)
            ->first();
        if(!$session) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($session);
    }
}