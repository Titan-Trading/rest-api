<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Bot;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * Get list of bots
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $bots = Bot::query()->get();

        return response()->json($bots, 200);
    }

    /**
     * Create a new bot
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'algorithm_text' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required'
        ]);

        $bot = new Bot();
        $bot->user_id = $request->user()->id;
        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->save();

        return response()->json($bot, 201);
    }

    /**
     * Get a bot by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot not found'
            ], 404);
        }

        return response()->json($bot, 200);
    }

    /**
     * Update a bot by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot not found'
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required',
            'algorithm_text' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required'
        ]);

        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->save();

        return response()->json($bot, 200);
    }

    /**
     * Delete a bot by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function delete(Request $request, $id)
    {
        if(!$id) {
            return response()->json([
                'message' => 'Bot id is required'
            ], 404);
        }

        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Bot not found'
            ], 404);
        }

        $bot->delete();

        return response('Success', 200);
    }
}