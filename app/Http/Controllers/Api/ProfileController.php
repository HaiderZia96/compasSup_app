<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];
    public function changePassword(Request $request){

        // validation rules
        $rules = ['old_password'=>['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],'password_confirmation' => ['required', 'string', 'min:6']];

        // validation messages
        $messages = ['old_password.required' => 'Please enter a old password.', 'password.required' => 'Please enter a password.', 'password_confirmation.required' => 'Please enter a confirm password.'];

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
        $auth_user = Auth::user();
        $oldPass = $request->get("old_password");
        if (Hash::check($oldPass, $auth_user->password) !== true) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Old Password is incorrect."],
                'data' => [

                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $user = User::where('email',$auth_user->email)->first();

        $user->update(
            [
                'password'=>Hash::make($request->password)
            ]
        );
        $user->tokens()->delete();

        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            'success' => ['Password changed successfully.'],
            'data' => $user
        ];
        $this->setResponse($this->data);
        return $this->getResponse();
    }
}
