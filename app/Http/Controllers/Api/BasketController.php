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

    public function index(Request $request)
    {

        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $perPage = $request->get('per_page');
        } else {

            $perPage = "7";
        }

//        dd($perPage);
//        $perPage = $request->input('per_page', 5);

        $basket = Basket::paginate($perPage);


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
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No baskets found.'], 'data' => $data];
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
        $data['created_by'] = Auth::id();

        Basket::create($data);

        // fetch the newly created basket
        $basket = Program::where('id', $request->program_id)->first();

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program added to basket successfully.'], 'data' =>
        [
            "program_id" =>  $basket->id,
            "formation_id"=>  $basket->formation_id,
            "name_of_the_formation"=>  $basket->name_of_the_formation,
            "link_to_its_webpage"=>  $basket->link_to_its_webpage,
            "region"=>  $basket->region,
            "schooling_cost"=>  $basket->schooling_cost,
            "length_of_the_formation"=>  $basket->length_of_the_formation,
            "status"=>  $basket->status,
            "access_rate"=>  $basket->access_rate,
            "type_of_formation"=>  $basket->type_of_formation,
            "town"=>  $basket->town,
            "schooling_modalities"=>  $basket->schooling_modalities,
            "schooling_pursuit"=>  $basket->schooling_pursuit,
            "description_of_the_formation"=>  $basket->description_of_the_formation,
            "number_of_students"=>  $basket->number_of_students,
            "keywords_option"=>  $basket->keywords_option,
            "keywords_secondary"=>  $basket->keywords_secondary,
            "keywords_main"=>  $basket->keywords_main,
            "created_by"=>  $basket->created_by,
            "updated_by"=>  $basket->updated_by,
            "created_at"=>  $basket->created_at,
            "updated_at"=>  $basket->updated_at,
        ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }



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


}
