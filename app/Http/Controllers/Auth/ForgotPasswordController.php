<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Api\Response;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use Response;
    private $otp;
    public $data;
    public $dataArray = [];

    public function forgotPassword(Request $request){
        // validation rules
        $rules = ['email'=>['required','email','exists:users']];

        // validation messages
        $messages = ['email.required' => 'Please enter a email.'];

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


        $input=$request->only('email');

        $user =User::where('email',$input)->first();

//        if (empty($user)) {
//            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
//                "success" => ["This is not a valid user email."],
//                'data' => []
//            ];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }

//        $notify = $user->notify(new ResetPasswordNotification());

        $otp = new Otp();
        $otp_num = $otp->generate($input['email'], 'numeric', 4, 15);
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            "success" => ["Otp successfully sent."],
            'data' => [
                'otp' => $otp_num->token
            ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();

//        return response()->json($success,200);

    }
}
