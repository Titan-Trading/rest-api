<?php
namespace App\Http\Controllers\Admin\Trading;

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
        $query = Bot::query();

        // search by bot name
        if($request->has('search_text')) {
            $query->whereName('like', '%' . $request->search_text . '%');
        }
        // search by user id
        if($request->has('user_id')) {
            $query->whereUserId($request->user_id);
        }

        $bots = $query->get();

        return response()->json($bots);
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
        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($bot);
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
        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $bot->delete();

        return response()->json($bot);
    }
}