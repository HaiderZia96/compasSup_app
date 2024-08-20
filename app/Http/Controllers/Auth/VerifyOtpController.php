<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\Api\Response;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyOtpController extends Controller
{
    use Response;
    private $otp;
    public $data;
    public $dataArray = [];
    public function verifyOTP(Request $request)
    {
        $rules = ['email'=>['required','email','exists:users'],'otp'=>['required','max:4'],];

        // validation messages
        $messages = ['email.required' => 'Please enter a email.','otp.required' => 'Please enter a otp code.'];

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


        $otp = new Otp();
        $otp_email = $request->get('email');
        $otp_input = $request->get('otp');
        $otp2 = $otp->validate($otp_email,$otp_input);

        if ($otp2->status) {
            // OTP is valid
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
                "success" => ['Otp is verified.'],
                'data' => [
                    'email' => $otp_email,
                    'otp' => $otp_input
                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {
            // OTP is invalid
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ['Invalid Otp.'],
                'data' => [
//                    'email' => $otp_email
                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }
}
