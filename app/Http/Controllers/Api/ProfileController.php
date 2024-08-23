<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Api\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

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
        $auth_user = auth('sanctum')->user();
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

    public function editProfile(Request $request)
    {
        $user = auth('sanctum')->user();
        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'high_school' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'date_of_birth' => ['required', 'date_format:Y-m-d'],
            'country_code' => ['required', 'regex:/^\+\d{1,3}$/'],
            'mobile_number' => ['required', 'int'],
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];

        // Validation messages
        $messages = [
            'name.required' => 'Please enter name.',
            'surname.required' => 'Please enter surname.',
            'high_school.required' => 'Please enter high school.',
            'postal_code.required' => 'Please enter postal code.',
            'date_of_birth.required' => 'Please enter date of birth.',
            'country_code.required' => 'Please enter country code.',
            'regex' => 'The country code must start with a "+" followed by 1 to 3 digits.',
            'mobile_number.required' => 'Please enter mobile number.',
            'password.required' => 'Please enter a password.',
            'image.required' => 'Please upload a valid image.',
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            // Gather error messages
            $errors = $validator->messages()->all();
            $collection = collect($this->dataArray);
            $this->dataArray = $collection->merge($errors);

            $this->data = [
                'status_code' => 200,
                'code' => 100401,
                'response' => '',
                "success" => $this->dataArray,
                'data' => []
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }




        // Find the user to update
        $user = User::find($user->id);

        if (!$user) {
            return response()->json([
                'status_code' => 404,
                'code' => 100404,
                'response' => 'User not found',
                'success' => false,
                'data' => []
            ], 404);
        }

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $slugifiedName = Str::slug($originalName) . '.' . $extension;
            $image_uploaded_path = $image->storeAs('users', $slugifiedName, 'public');
            $image_url = url(Storage::url($image_uploaded_path));

            $uploadedImageResponse = [
//                "name" => basename($image_uploaded_path),
                "url" => $image_url,
//                "type" => $image->getMimeType()
            ];

            // Update the image URL in the user record
            $user->image = $uploadedImageResponse['url'];
        }else{
            $uploadedImageResponse = [
//                "name" => basename($image_uploaded_path),
                "url" => $user->image,
//                "type" => $image->getMimeType()
            ];
        }

        if ($request->has('email') && $request->get('email') != null) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                'success' => ['Email cannot be changed.'],
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'high_school' => $user->high_school,
                    'postal_code' => $user->postal_code,
                    'date_of_birth' => $user->date_of_birth,
                    'mobile_number' => $user->mobile_number,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'image' => $uploadedImageResponse,
                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        // Update user details
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->high_school = $request->high_school;
        $user->date_of_birth = $request->date_of_birth;
        $user->postal_code = $request->postal_code;
        $user->mobile_number = $request->country_code.$request->mobile_number;


        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Optionally update other fields
        $user->user_role = 'NA';
        $user->email_verified_at = Carbon::now()->toDateTimeString();

        // Save the updated user
        $user->save();

        // Prepare response
        $this->data = [
            'status_code' => 200,
            'code' => 100200,
            'response' => '',
            "success" => ["User profile updated successfully."],
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'high_school' => $user->high_school,
                'postal_code' => $user->postal_code,
                'date_of_birth' => $user->date_of_birth,
                'mobile_number' => $user->mobile_number,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'image' => isset($uploadedImageResponse) ? $uploadedImageResponse : null,
            ]
        ];

        $this->setResponse($this->data);
        return $this->getResponse();
    }

}
