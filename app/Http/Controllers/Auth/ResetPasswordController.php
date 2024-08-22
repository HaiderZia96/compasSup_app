<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Api\Response;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];
    private $otp;
    public function __construct(){
        $this->otp = new Otp();
    }
    public function resetPassword(Request $request){

        // validation rules
//        $rules = ['email'=>['required','email','exists:users'],'otp'=>['required','max:4'],'password' => ['required', 'string', 'min:8', 'confirmed'],'password_confirmation' => ['required', 'string', 'min:8']];
        $rules = ['email'=>['required','email','exists:users'],'password' => ['required', 'string', 'min:8', 'confirmed'],'password_confirmation' => ['required', 'string', 'min:8']];

        // validation messages
        $messages = ['email.required' => 'Please enter a email.', 'password.required' => 'Please enter a password.', 'password_confirmation.required' => 'Please enter a confirm password.'];

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

//        $auth = $request->header('token') ;
//
//        $otp2= $this->otp->validate($request->email,$request->otp);
//
//        if(! $otp2->status){
//
//            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
//                "success" => [$otp2->message],
//                'data' => []
//            ];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }

        $user = User::where('email',$request->email)->first();

        $user->update(
            [
                'password'=>Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();

        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            'success' => ['Password reset successfully.'],
            'data' => $user
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }
}
