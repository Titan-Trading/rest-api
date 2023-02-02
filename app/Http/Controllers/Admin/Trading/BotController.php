<?php
namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Bot;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class BotController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

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
        $bot->algorithm_text_version = 1;
        $bot->algorithm_version = 1;
        $bot->parameter_options = '{}';
        $bot->event_streams = '{}';
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

        $this->validate($request, [
            'name' => 'required',
            'algorithm_text' => 'required',
            'parameter_options' => 'required'
        ], [
            'name_required' => 'Name is required',
            'algorithm_text_required' => 'Algorithm text is required',
            'parameter_options' => 'Parameter options is required'
        ]);

        // check if algorithm text has changed
        $algorithmTextVersion = $bot->algorithm_text_version;
        if(md5($bot->algorithm_text) != md5($request->algorithm_text)) {
            $algorithmTextVersion += 1;
        }

        // check if compiled algorithm has changed
        $algorithmVersion = $bot->algorithm_version;
        if(md5($bot->algorithm_text_compiled) != md5($request->algorithm_text_compiled)) {
            $algorithmVersion = $algorithmTextVersion;
        }

        $bot->name = $request->name;
        $bot->algorithm_text = $request->algorithm_text;
        $bot->algorithm_text_version = $algorithmTextVersion;
        $bot->algorithm_text_compiled = $request->algorithm_text_compiled ? $request->algorithm_text_compiled : $bot->algorithm_text_compiled;
        $bot->algorithm_version = $algorithmVersion;
        $bot->parameter_options = $request->parameter_options;
        $bot->event_streams = $request->event_streams;
        $bot->symbols = $request->symbols ? $request->symbols : $bot->symbols;
        $bot->indicators = $request->indicators ? $request->indicators : $bot->indicators;
        $bot->save();

        /**
         * Add bot update onto message bus
         * - compile typescript into js
         * - do trial instantiation to pull out what parameters are used and what bots are used
         * - validate the use of bot
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

        $bot->delete();

        return response()->json($bot);
    }
}