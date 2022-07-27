<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Bot;
use Illuminate\Http\Request;

class BotController extends Controller
{
    /**
     * Get list of bots that either a user has created or has purchased
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = Bot::query()
            ->whereUserId($request->user()->id);

        // TODO: show bots that have been purchased (with product order status=active, buyer_id=current user id)

        // search by bot name
        if($request->has('search_text')) {
            $query->whereName('like', '%' . $request->search_text . '%');
        }

        $bots = $query->get();

        return response()->json($bots);
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
            'algorithm_text' => 'required',
            'parameter_options' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required',
            'parameter_options' => 'Parameter options is required'
        ]);

        $bot = new Bot();
        $bot->user_id = $request->user()->id;
        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->parameter_options = $request->parameter_options;
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
        $bot = Bot::find($id);
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

        return response()->json($bot);
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
        $bot = Bot::find($id);
        if(!$bot) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        // check if bot can be access by current user
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to make changes to this bot'
            ], 403);
        }

        $this->validate($request, [
            'name' => 'required',
            'algorithm_text' => 'required',
            'parameter_options' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required',
            'parameter_options' => 'Parameter options is required'
        ]);

        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->parameter_options = $request->parameter_options;
        $bot->save();

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

        // check if bot can be access by current user
        if($bot->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized to remove this bot'
            ], 403);
        }

        // TODO: check if there are any bot sessions for this bot

        $bot->delete();

        return response()->json($bot);
    }
}