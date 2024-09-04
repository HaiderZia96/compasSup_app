<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubQuestion;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubQuestionController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];
    public function create(Request $request)
    {
        // validation rules
        $rules = ['question' => 'required|string','type' => 'required|string'];

        // validation messages
        $messages = ['question.required' => 'Please enter a question.','type.required' => 'Please enter a type.'];

        // perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => $this->dataArray,
                'data' => []
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
        $data = $request->all();
        $data['created_by'] = auth('sanctum')->id(); // authenticated user



        SubQuestion::create($data);

        // fetch the newly created room
        $ques = SubQuestion::where('question', $request->question)->first();

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            "success" =>["Record store successfully."],
            'data' => [
                $ques
            ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }
}
