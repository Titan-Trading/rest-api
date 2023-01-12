<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\BotSessionResult;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotSessionResultController extends Controller
{
    /**
     * Get list of session results for a bot (for the current user)
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function index(Request $request, $id)
    {
        $sessions = BotSessionResult::whereUserId($request->user()->id)->whereBotSessionId($id)->get();

        return response()->json($sessions);
    }

    /**
     * Get a bot session result by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $botId, $id)
    {
        $session = BotSessionResult::whereUserId($request->user()->id)
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