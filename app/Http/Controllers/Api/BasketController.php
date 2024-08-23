<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Program;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BasketController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];

    public $updatedBaskets = [];
    public function index(Request $request)
    {

        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $perPage = $request->get('per_page');
        } else {

            $perPage = "7";
        }

//        dd($perPage);
//        $perPage = $request->input('per_page', 5);

//        Basket::select('baskets.*')
//            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
//            ->paginate($perPage);

        $basket = Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
            ,'programs.region','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as program_type','programs.number_of_students')
            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
            ->paginate($perPage);


        if ($basket->isNotEmpty()) {

            $data = [
                'current_page' => $basket->currentPage(),
                'data' => $basket->items(),
                'first_page_url' => $basket->url(1),
                'from' => $basket->firstItem(),
                'last_page' => $basket->lastPage(),
                'last_page_url' => $basket->url($basket->lastPage()),
                'links' => [
                    [
                        'url' => $basket->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $basket->onFirstPage() ? false : true,
                    ],
                    [
                        'url' => $basket->url($basket->currentPage()),
                        'label' => $basket->currentPage(),
                        'active' => true,
                    ],
                    [
                        'url' => $basket->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $basket->hasMorePages() ? true : false,
                    ],
                ],
                'next_page_url' => $basket->nextPageUrl(),
                'path' => $basket->path(),
                'per_page' => $perPage,
                'prev_page_url' => $basket->previousPageUrl(),
                'to' => $basket->lastItem(),
                'total' => $basket->total(),
            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All baskets fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = [
                'current_page' => $basket->currentPage(),
                'data' => [],
                'first_page_url' => null,
                'from' => null,
                'last_page' => null,
                'last_page_url' => null,
                'links' => [],
                'next_page_url' => null,
                'path' => $request->url(),
                'per_page' => $perPage,
                'prev_page_url' => null,
                'to' => null,
                'total' => 0,

            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No baskets found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }


    public function create(Request $request)
    {

        // validation rules
        $rules = ['program_id' => 'required|int|exists:programs,id'];

        // validation messages
        $messages = [ 'program_id.required' => 'Please enter a program id.'];

        // perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            // prepare response
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => $this->dataArray, 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // store data
        $data = $request->all();
        $data['type'] = '0';

        // fetch the newly created basket
        $basket_create = Basket::where('program_id', $request->program_id)->first();

//        $basket = Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
//            ,'programs.region','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as program_type','programs.number_of_students')
//            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
//            ->where('program_id', $request->program_id)
//            ->first();

        if (isset($basket_create)) {

            $data['updated_by'] = auth('sanctum')->id();
        } else {

            $data['created_by'] = auth('sanctum')->id();
        }


        $bas =   Basket::updateOrCreate(
            [ 'program_id' => $data['program_id'] ],
            $data
        );

        // fetch the newly created basket
        $basket = Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
            ,'programs.region','programs.status','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as program_type','programs.number_of_students')
            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
            ->where('program_id', $request->program_id)
            ->first();


        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program added to basket successfully.'], 'data' =>
        [
            "id"=>  $basket->id,
            "basket_type"=>  $basket->type,
            "program_id"=>  $basket->program_id,

            "formation_id"=>  $basket->formation_id,
            "name_of_the_formation"=>  $basket->name_of_the_formation,
            "link_to_its_webpage"=>  $basket->link,
            "region"=>  $basket->region,
            "status"=>  $basket->status,
            "access_rate"=>  $basket->access_rate,
            "type_of_formation"=>  $basket->program_type,
            "town"=>  $basket->town,
            "number_of_students"=>  $basket->number_of_students,

            "created_by"=>  $basket->created_by,
            "updated_by"=>  $basket->updated_by,
            "created_at"=>  $basket->created_at,
            "updated_at"=>  $basket->updated_at,
        ]
//            $bas
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }

    public function destroy(Request $request)
    {

        // validation rules
        $rules = ['program_id' => 'required|int|exists:programs,id'];

        // validation messages
        $messages = [ 'program_id.required' => 'Please enter a program id.'];

        // perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            // prepare response
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => $this->dataArray, 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // store data
        $data = $request->all();
        $data['type'] = '0';

        // fetch the newly created basket
//        $basket = Basket::where('program_id', $request->program_id)->first();

        $basket = Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
            ,'programs.region','programs.status','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as program_type','programs.number_of_students')
            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
            ->where('program_id', $request->program_id)
            ->first();



        if(!isset($basket)){
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['Related program is not in the basket.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $bas =   Basket::where(
            'program_id' , $data['program_id'] ,
        )->delete();


        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program remove from the basket.'], 'data' =>
        [
            "id"=>  $basket->id,
            "basket_type"=>  $basket->type,
            "program_id"=>  $basket->program_id,

            "formation_id"=>  $basket->formation_id,
            "name_of_the_formation"=>  $basket->name_of_the_formation,
            "link_to_its_webpage"=>  $basket->link,
            "region"=>  $basket->region,
            "status"=>  $basket->status,
            "access_rate"=>  $basket->access_rate,
            "type_of_formation"=>  $basket->program_type,
            "town"=>  $basket->town,
            "number_of_students"=>  $basket->number_of_students,

            "created_by"=>  $basket->created_by,
            "updated_by"=>  $basket->updated_by,
            "created_at"=>  $basket->created_at,
            "updated_at"=>  $basket->updated_at,
        ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }


//    public function formationType(Request $request, string $id)
//    {
//        // find the basket to update
//        $basket = Basket::find($id);
//
//        if (!$basket) {
//            // basket not found
//            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['Basket not found.'], 'data' => []];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }
//
//        // validation rules
//        $rules = ['formation_type' => 'required|regex:/^[0-1]$/'];
//
//        // validation messages
//        $messages = ['formation_type.required' => 'Please enter a formation type.', 'formation_type.regex' => 'The enter formation value is invalid. Unchecked : 0, Checked : 1' ];
//
//        // perform validation
//        $validator = Validator::make($request->all(), $rules, $messages);
//
//        if ($validator->fails()) {
//            // gather error messages
//            $errors = $validator->messages()->all();
//            $collection = collect($this->dataArray);
//            $this->dataArray = $collection->merge($errors);
//
//            // prepare response
//            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => $this->dataArray, 'data' => []];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }
//
//        // update the task status
//        $data['type'] = $request->formation_type;
//        $data['updated_by'] = Auth::id(); // authenticated user
//        $basket->update($data);
//
//        // fetch the updated task
//        $updatedTask = Basket::find($id);
//
//        $basket = Program::where('id',$updatedTask['program_id'])->first();
//
//        $user = auth()->user();
//
//        // prepare response
//        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Formation successfully ' . (isset($data['type']) && $data['type'] == 1 ? 'checked.' : 'unchecked.')]
//            , 'data' =>
//        [
//            "basket_id"=>  $updatedTask->id,
//        "type"=>  $updatedTask->id,
//        "program_id"=>  $updatedTask->id,
//
//        "formation_id"=>  $basket->formation_id,
//        "name_of_the_formation"=>  $basket->name_of_the_formation,
//        "link_to_its_webpage"=>  $basket->link_to_its_webpage,
//        "region"=>  $basket->region,
//        "status"=>  $basket->status,
//        "access_rate"=>  $basket->access_rate,
//        "type_of_formation"=>  $basket->type_of_formation,
//        "town"=>  $basket->town,
//        "description_of_the_formation"=>  $basket->description_of_the_formation,
//        "number_of_students"=>  $basket->number_of_students,
//
//        "created_by"=>  $updatedTask->id,
//        "updated_by"=>  $updatedTask->id,
//        "created_at"=>  $updatedTask->id,
//        "updated_at"=>  $updatedTask->id,
//        ]
//        ];
//        $this->setResponse($this->data);
//        return $this->getResponse();
//
//
//    }


    public function formationType(Request $request, string $id)
    {
        // find the basket to update
        $basket = Basket::find($id);

        if (!$basket) {
            // basket not found
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['Basket not found.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // validation rules
        $rules = ['formation_type' => 'required|regex:/^[0-1]$/'];

        // validation messages
        $messages = ['formation_type.required' => 'Please enter a formation type.', 'formation_type.regex' => 'The enter formation value is invalid. Unchecked : 0, Checked : 1' ];

        // perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            // prepare response
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => $this->dataArray, 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // update the task status
        $data['type'] = $request->formation_type;
        $data['updated_by'] = Auth::id(); // authenticated user
        $basket->update($data);

        // fetch the updated task
        $updatedTask = Basket::find($id);

        $basket = Program::where('id',$updatedTask['program_id'])->first();

        $user = auth()->user();

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Formation successfully ' . (isset($data['type']) && $data['type'] == 1 ? 'checked.' : 'unchecked.')]
            , 'data' =>
                [
                    "basket_id"=>  $updatedTask->id,
                    "type"=>  $updatedTask->id,
                    "program_id"=>  $updatedTask->id,

                    "formation_id"=>  $basket->formation_id,
                    "name_of_the_formation"=>  $basket->name_of_the_formation,
                    "link_to_its_webpage"=>  $basket->link_to_its_webpage,
                    "region"=>  $basket->region,
                    "status"=>  $basket->status,
                    "access_rate"=>  $basket->access_rate,
                    "type_of_formation"=>  $basket->type_of_formation,
                    "town"=>  $basket->town,
                    "description_of_the_formation"=>  $basket->description_of_the_formation,
                    "number_of_students"=>  $basket->number_of_students,

                    "created_by"=>  $updatedTask->id,
                    "updated_by"=>  $updatedTask->id,
                    "created_at"=>  $updatedTask->id,
                    "updated_at"=>  $updatedTask->id,
                ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();


    }

    public function checkedFormation(Request $request)
    {
        // validation rules
        $rules = ['formation_type' => 'required|regex:/^[1]$/'];

        // validation messages
        $messages = ['formation_type.required' => 'Please enter a formation type.', 'formation_type.regex' => 'The enter formation value is invalid. For Checked : 1' ];

        // perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            // prepare response
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => $this->dataArray, 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // update the task status
        $data['type'] = $request->formation_type;
        $data['updated_by'] = auth('sanctum')->id();// authenticated user

        $unchecked = '0';
        // find the baskets to update
        $baskets = Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
            ,'programs.region','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as type','programs.number_of_students')
            ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
            ->where('baskets.type' ,$unchecked )
            ->get();


        if(!isset($baskets[0])){
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['Not any unchecked formation found in the basket.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
        foreach ($baskets as $basket){
            $basket->update($data);
        }

        foreach ($baskets as $basket) {
            // Find the basket by its ID
            $updatedTask = Basket::find($basket['id']);

            if ($updatedTask) {
                // Fetch the program associated with the updatedTask
                $program =  Basket::select('baskets.*','programs.id as program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage as link'
                    ,'programs.region','programs.schooling_cost','programs.length_of_the_formation as length','programs.access_rate','programs.town','programs.type_of_formation as type_formation','programs.number_of_students')
                    ->leftJoin('programs', 'programs.id', '=', 'baskets.program_id')
                    ->where('programs.id', $updatedTask->program_id)
                    ->first();
//                $program = Program::where('id', $updatedTask->program_id)->first();

                if ($program) {
                    // Add the program to the updatedBaskets array
                    array_push($this->updatedBaskets, $program);
                }
            }
        }

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Formation successfully checked.']
            , 'data' =>
                $this->updatedBaskets
        ];
        $this->setResponse($this->data);
        return $this->getResponse();


    }

}
