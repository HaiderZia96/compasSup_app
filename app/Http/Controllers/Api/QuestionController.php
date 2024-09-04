<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];
    public function create(Request $request)
    {
        // validation rules
        $rules = ['question' => 'required|string','possible_answer' => 'required|string'];

        // validation messages
        $messages = ['question.required' => 'Please enter a question.','possible_answer.required' => 'Please enter a possible answer.'];

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
        $data['possible_answer'] = $request->possible_answer;
        $array = explode(',',$data['possible_answer']);
        $possible_answer = $array; // assign the entire array to $possible_answer
        $data['possible_answer'] = implode(',',$possible_answer);

        $data['created_by'] = auth('sanctum')->id(); // authenticated user



        Question::create($data);

        // fetch the newly created room
        $ques = Question::where('question', $request->question)->first();

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


//    public function index(Request $request)
//    {
////        if ($request->has('per_page') && $request->get('per_page') !== null) {
////
////            $perPage = $request->get('per_page');
////        }else{
////
////            $perPage = "7" ;
////        }
//
////        $perPage = $request->input('per_page', 5);
//
////        $ques = Question::select('questions.*','answers.option','answers.id as answer_id')->leftJoin('answers','questions.id','answers.question_id')->get();
////        $ques = Question::with('answers')->get();
//        // Apply filters based on the request
//
//
//
//        $questionQuery = Question::query();
//
//
//        if ($request->has('type_of_baccalaureate') && $request->get('type_of_baccalaureate') !== null) {
//            $type_of_baccalaureate = $request->get('type_of_baccalaureate');
//            $questionQuery->where(function($query) use ($type_of_baccalaureate) {
//                $query->where('type_of_baccalaureate', 'LIKE', "%$type_of_baccalaureate%")
//                    ->orWhereNull('type_of_baccalaureate');
//            });
//
//            if ($request->has('learning_a_language') && $request->get('learning_a_language') !== null) {
//
//                $learning_a_language = $request->get('learning_a_language');
//                $interested_in_region = $request->get('interested_in_region');
//                if ($learning_a_language == 'Yes' && $interested_in_region == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//                elseif ($interested_in_region == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->where('id', '<>', 10)
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//                elseif ($learning_a_language == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->where('id', '<>', 13)
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//
//            }
//            elseif ($request->has('interested_in_region') && $request->get('interested_in_region') !== null) {
//
//                $learning_a_language = $request->get('learning_a_language');
//                $interested_in_region = $request->get('interested_in_region');
//                if ($learning_a_language == 'Yes' && $interested_in_region == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//                elseif ($interested_in_region == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->where('id', '<>', 10)
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//                elseif ($learning_a_language == 'Yes') {
//
//                    $ques = $questionQuery->with(['answers' => function($query) {
//                        $query->select('id', 'option', 'question_id');
//                    }])
//                        ->where('id', '<>', 13)
//                        ->orderBy('id', 'asc')
//                        ->get();
//                }
//
//            }
//        else{
//            $ques = $questionQuery->with(['answers' => function($query) {
//                $query->select('id', 'option', 'question_id');
//            }])->where('id','<>',10)
//                ->where('id','<>', 13)->orderBy('id','asc')->get();
//
//        }
//        }
//
//        else{
//            $ques = $questionQuery->with(['answers' => function($query) {
//                $query->select('id', 'option', 'question_id');
//            }])->where('id','=',2)->orderBy('id','asc')->get();
//
//        }
//
//
//
//
//
//
//
//
////        dd($ques);
//        if ($ques->isNotEmpty()) {
////            dd('123');
//            $data = $ques;
////                [
////                'current_page' => $ques->currentPage(),
////                'data' => $ques->items(),
////                'first_page_url' => $ques->url(1),
////                'from' => $ques->firstItem(),
////                'last_page' => $ques->lastPage(),
////                'last_page_url' => $ques->url($ques->lastPage()),
////                'links' => [
////                    [
////                        'url' => $ques->previousPageUrl(),
////                        'label' => '&laquo; Previous',
////                        'active' => $ques->onFirstPage() ? false : true,
////                    ],
////                    [
////                        'url' => $ques->url($ques->currentPage()),
////                        'label' => $ques->currentPage(),
////                        'active' => true,
////                    ],
////                    [
////                        'url' => $ques->nextPageUrl(),
////                        'label' => 'Next &raquo;',
////                        'active' => $ques->hasMorePages() ? true : false,
////                    ],
////                ],
////                'next_page_url' => $ques->nextPageUrl(),
////                'path' => $ques->path(),
////                'per_page' => $perPage,
////                'prev_page_url' => $ques->previousPageUrl(),
////                'to' => $ques->lastItem(),
////                'total' => $ques->total(),
////            ];
//            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All questions fetched.'], 'data' => $data];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        } else {
//
//            $data = $ques;
////                [
////                'current_page' => $ques->currentPage(),
////                'data' => [],
////                'first_page_url' => null,
////                'from' => null,
////                'last_page' => null,
////                'last_page_url' => null,
////                'links' => [],
////                'next_page_url' => null,
////                'path' => $request->url(),
////                'per_page' => $perPage,
////                'prev_page_url' => null,
////                'to' => null,
////                'total' => 0,
////
////            ];
//            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No questions found.'], 'data' => $data];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }
//    }


        public function index(Request $request)
    {
        // Start with the base query for questions
        $questionQuery = Question::query();

        // Check if 'question_id' is present and not null
        if ($request->has('question_id') && $request->get('question_id') !== null) {
            $question_id = $request->get('question_id');
            $answerType = $request->get('answer');
            $questionQuery->where('id', $question_id)
            ->orWhere('type_of_baccalaureate', $answerType)
            ;
//            dd('123');
            if($question_id == 4 || $question_id == 11 || $question_id == 17 || $question_id == 19 || $question_id == 20 && $answerType == 'Yes'){

                $ques = $questionQuery->with(['answers' => function ($query) {
                    $query->select('id', 'option', 'question_id');
                }])->where('id','<>',10)->where('id','<>',13)
                    ->orderBy('id', 'asc')
                    ->get();
            }
            if($question_id == 9 && $answerType == 'Yes'){

                $ques = $questionQuery->with(['answers' => function ($query) {
                    $query->select('id', 'option', 'question_id');
                }])->where('id','<>',13)
                    ->orderBy('id', 'asc')
                    ->get();
            }
            if($question_id == 12 && $answerType == 'Yes'){

                $ques = $questionQuery->with(['answers' => function ($query) {
                    $query->select('id', 'option', 'question_id');
                }])->where('id','<>',10)
                    ->orderBy('id', 'asc')
                    ->get();
            }

            $ques = $questionQuery->with(['answers' => function ($query) {
                $query->select('id', 'option', 'question_id');
            }])
                ->orderBy('id', 'asc')
                ->get();
        }else{
        $questionQuery->where('id', '<>', 3)
        ->where('id', '<>', 4)->where('id', '<>', 5)->where('id', '<>', 6)
            ->where('id', '<>', 10)->where('id', '<>', 13);
            $ques = $questionQuery->with(['answers' => function ($query) {
                $query->select('id', 'option', 'question_id');
            }])
                ->orderBy('id', 'asc')
                ->get();
    }

        // Check if 'answer' is present
//        if ($request->has('answer') && $request->get('answer') !== null) {
//            $answerType = $request->get('answer');
//            // Filter questions based on the answer type
//            // Assuming `answer_type` is a column in your answers table
//            $questionQuery->whereHas('answers', function ($query) use ($answerType) {
//                $query->where('type_of_baccalaureate', $answerType); // Adjust this as needed
//            });
//        }

        // Always include question no 2

        // Load related answers


        // Return response based on whether questions are found
        if ($ques->isNotEmpty()) {
            $this->data = [
                'status_code' => 200,
                'code' => 100200,
                'response' => '',
                'success' => ['All questions fetched.'],
                'data' => $ques
            ];
        } else {
            $this->data = [
                'status_code' => 200,
                'code' => 100402,
                'response' => '',
                'success' => ['No questions found.'],
                'data' => $ques
            ];
        }

        $this->setResponse($this->data);
        return $this->getResponse();
    }


//    public function index(Request $request)
//    {
//
//        // Start with the base query for questions
////        $ques = Question::with(['answers' => function ($query) {
////            $query->select('id', 'option', 'question_id');
////    }])->with('subQuestions')->get();
//        // Retrieve query parameters
//        $questionId = $request->query('question_id');
//        $answerOption = $request->query('answer');
//
//        // Start the query on the Question model
//        $query = Question::query();
//
//        // Apply filters if they are present
//        if ($questionId) {
//            $query->where('id', $questionId);
//        }
//
//        // Eager load relationships and apply filters to answers
//        $ques = $query->with([
//            'answers' => function ($query) use ($answerOption) {
//                $query->select('id', 'option', 'question_id');
//                if ($answerOption) {
//                    $query->where('option', $answerOption);
//                }
//            },
//            'subQuestions' => function ($query) {
//                $query->select('id', 'question', 'status', 'type', 'min_answer_count', 'question_id');
//                if ($query) {
//                    $query->where('status', '1');
//                };
//            },
//            'subQuestions.subAnswers' => function ($query) use ($answerOption) {
//                $query->select('id', 'option', 'sub_question_id');
//                if ($answerOption) {
//                    $query->where('option', $answerOption);
//                }
//            }
//        ])->get();
//
//
//        // Return response based on whether questions are found
//        if ($ques->isNotEmpty()) {
//            $this->data = [
//                'status_code' => 200,
//                'code' => 100200,
//                'response' => '',
//                'success' => ['All questions fetched.'],
//                'data' => $ques
//            ];
//        } else {
//            $this->data = [
//                'status_code' => 200,
//                'code' => 100402,
//                'response' => '',
//                'success' => ['No questions found.'],
//                'data' => $ques
//            ];
//        }
//
//        $this->setResponse($this->data);
//        return $this->getResponse();
//    }

    public function edit(Request $request, string $id)
    {
        // find the question to update
        $ques = Question::find($id);

        if (!$ques) {
            // ques not found
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['Question not found.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // Validation rules
        // validation rules
        $rules = ['question' => 'required|string','possible_answer' => 'required|string'];

        // validation messages
        $messages = ['question.required' => 'Please enter a question.','possible_answer.required' => 'Please enter a possible answer.'];

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
        $data['possible_answer'] = $request->possible_answer;
        $array = explode(',',$data['possible_answer']);
        $possible_answer = $array; // assign the entire array to $possible_answer
        $data['possible_answer'] = implode(',',$possible_answer);

        $data['created_by'] = auth('sanctum')->id(); // authenticated user
        // Update the room data
        $data = $request->all();
        $data['updated_by'] = auth('sanctum')->id(); // Use the ID of the currently authenticated user


        $ques->update($data);

        // fetch the updated question
        $updatedQues = Question::find($id);

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Record updated successfully'], 'data' => $updatedQues];
        $this->setResponse($this->data);
        return $this->getResponse();


    }

}
