<?php
namespace App\Http\Controllers\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Bot;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BotController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

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

        $bots = $query->orderBy('updated_at', 'desc')->get();

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
        /*$this->validate($request, [
            'name' => 'required',
            'algorithm_text' => 'required',
            'parameter_options' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required',
            'parameter_options' => 'Parameter options is required'
        ]);*/

        // create instance of name generator
        $generator = new \Nubs\RandomNameGenerator\Vgng();

        // starter template for a strategy
        $strategyTemplate = view('strategy_template')->render();

        $bot = new Bot();
        $bot->user_id = $request->user()->id;
        $bot->name = $request->name ? $request->name : $generator->getName();
        $bot->algorithm_text = $request->algorithm_text ? $request->algorithm_text : $strategyTemplate;
        $bot->algorithm_version = 1;
        $bot->parameter_options = '{}';
        $bot->symbols = '{}';
        $bot->indicators = '{}';
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

        $algorithmVersion = $bot->algorithm_version;
        if(md5($bot->algorithm_text) != md5($request->algorithm_text)) {
            $algorithmVersion += 1;
        }

        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->algorithm_text_compiled = $request->algorithm_text_compiled ? $request->algorithm_text_compiled : $bot->algorithm_text_compiled;
        $bot->algorithm_version = $algorithmVersion;
        $bot->parameter_options = $request->parameter_options;
        $bot->symbols = $request->symbols ? $request->symbols : $bot->symbols;
        $bot->indicators = $request->indicators ? $request->indicators : $bot->indicators;
        $bot->save();

        /**
         * Add new bot onto message bus
         * - compile typescript into js
         * - do trial instantiation to pull out what parameters are used and what indicators are used
         * - validate the use of indicators
         * - output any errors to browser through websocket api
         */

        $this->messageBus->sendMessage('bots', [
            'topic' => 'bots',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $bot->toArray()
        ]);

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