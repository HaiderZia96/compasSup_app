<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];
    public function create(Request $request)
    {
        // validation rules
        $rules = ['option' => 'required|string','question_id' => 'required|int'];

        // validation messages
        $messages = ['option.required' => 'Please enter a option.','question_id.required' => 'Please enter a question id.'];

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



        Answer::create($data);

        // fetch the newly created answer
        $ans = Answer::where('option', $request->option)->orderBy('created_at', 'desc')->first();

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            "success" =>["Record store successfully."],
            'data' => [
                $ans
            ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }


    public function index(Request $request)
    {
        $ans = Answer::get();

        if ($ans->isNotEmpty()) {

            $data = $ans;

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All questions fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = $ans;
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No questions found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }
}
