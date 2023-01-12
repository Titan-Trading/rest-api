<?php

namespace App\Http\Controllers\Admin\Trading;

use App\Http\Controllers\Controller;
use App\Models\Trading\Indicator;
use App\Services\MessageBus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndicatorController extends Controller
{
    private $messageBus;
    
    public function __construct(MessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Get list of indicators
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $query = Indicator::query();

        // search by indicator name
        if($request->has('search_text')) {
            $query->whereName('like', '%' . $request->search_text . '%');
        }
        
        $indicators = $query->get();

        return response()->json($indicators);
    }

    /**
     * Create a market indicator
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        /*$this->validate($request, [
            'name' => ['required', 'unique:indicators,name'],
            'is_active' => ['required'],
            'algorithm_text' => ['required']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active_required' => 'Is active is required',
            'algorithm_text_required' => 'Algorithm text is required'
        ]);*/

        // create instance of name generator
        $generator = new \Nubs\RandomNameGenerator\Vgng();

        // starter template for a indicator
        $indicatorTemplate = view('indicator_template')->render();

        $indicator = new Indicator();
        $indicator->user_id = $request->user()->id;
        $indicator->name = $request->name ? $request->name : $generator->getName();
        $indicator->is_active = true;
        $indicator->algorithm_text = $request->algorithm_text ? $request->algorithm_text : $indicatorTemplate;
        $indicator->algorithm_version = 1;
        $indicator->parameter_options = '{}';
        $indicator->save();

        return response()->json($indicator, 201);
    }

    /**
     * Get a market indicator by id
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        return response()->json($indicator);
    }

    /**
     * Update a market indicator by id
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $nameRules = ['required'];
        if($request->name != $indicator->name) {
            $nameRules[] = 'unique:indicators,name';
        }

        $this->validate($request, [
            'name' => $nameRules,
            'is_active' => ['required'],
            'algorithm_text' => ['required']
        ], [
            'name_required' => 'Name is required',
            'name_unique' => 'Name is not unique',
            'is_active_required' => 'Is active is required',
            'algorithm_text_required' => 'Algorithm text is required'
        ]);

        $algorithmVersion = $indicator->algorithm_version;
        if(md5($indicator->algorithm_text) != md5($request->algorithm_text)) {
            $algorithmVersion += 1;
        }

        $indicator->name = $request->name;
        $indicator->is_active = $request->is_active;
        $indicator->algorithm_text = $request->algorithm_text;
        $indicator->algorithm_text_compiled = $request->algorithm_text_compiled ? $request->algorithm_text_compiled : $indicator->algorithm_text_compiled;
        $indicator->algorithm_version = $algorithmVersion;
        $indicator->parameter_options = $request->parameter_options;
        $indicator->save();

        /**
         * Add indicator update onto message bus
         * - compile typescript into js
         * - do trial instantiation to pull out what parameters are used and what indicators are used
         * - validate the use of indicators
         * - output any errors to browser through websocket api
         */

        $this->messageBus->sendMessage('indicators', [
            'topic' => 'indicators',
            'messageType' => 'EVENT',
            'messageId' => Str::uuid()->toString(),
            'eventId' => 'UPDATED',
            'serviceId' => 'simple-trader-api',
            'instanceId' => env('INSTANCE_ID'),
            'data' => $indicator->toArray()
        ]);

        return response()->json($indicator);
    }

    /**
     * Delete a market indicator by id
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request, $id)
    {
        $indicator = Indicator::find($id);
        if(!$indicator) {
            return response()->json([
                'message' => 'Not found'
            ], 404);
        }

        $indicator->delete();

        return response()->json($indicator);
    }
}