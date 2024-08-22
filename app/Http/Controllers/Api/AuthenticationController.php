<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Api\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class AuthenticationController extends Controller
{
    use Response;

    public $data;
    public $pic = [];
    public $dataArray = [];


    /**
     * Register User
     * @param Request $request
     * @return User
     */
    public function signUp(Request $request)
    {
//        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);

        // validation rules
        $rules = ['name' => ['required', 'string', 'max:255'],'surname' => ['required', 'string', 'max:255'],'high_school' => ['required', 'string'],'postal_code' => ['required', 'string'],'date_of_birth' => ['required', 'date_format:Y-m-d'], 'mobile_number' => ['required', 'int'], 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],'password' => ['required', 'confirmed', Rules\Password::defaults()],'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048']];

        // validation messages
        $messages = ['name.required' => 'Please enter name.','surname.required' => 'Please enter surname.','high_school.required' => 'Please enter high school.','postal_code.required' => 'Please enter postal code.','date_of_birth.required' => 'Please enter date of birth.','mobile_number.required' => 'Please enter mobile number.','email.required' => 'Please enter a email.', 'email.unique' => 'A user with this email already exists.','password.required' => 'Please enter a password.','image.required' => 'Please upload a image.'];

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

        $image = $request->file('image'); // Ensure you get the uploaded file

        // Get the original file name without the extension
        $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

        // Get the file extension
        $extension = $image->getClientOriginalExtension();

        // Slugify the file name
        $slugifiedName = Str::slug($originalName) . '.' . $extension;

        // Store the image in the 'public' disk, which maps to 'storage/app/public' directory
        $image_uploaded_path = $image->storeAs('users', $slugifiedName, 'public');

        // Complete URL including the base URL
        $image_url = url(Storage::url($image_uploaded_path));


        $uploadedImageResponse = array(
            "name" => basename($image_uploaded_path),
            "url" => $image_url,
            "type" => $image->getMimeType()
        );


        // Get the original file name with extension
//        $imageName = $image->getClientOriginalName();

        // Store the image in the 'public' disk, which maps to 'storage/app/public' directory
//        $image_uploaded_path = $image->storeAs('users', $imageName, 'public');

        // complete URL including the base URL
        // $image_url = url(Storage::url($image_uploaded_path));
//        $modified_path = 'public/storage/' . $image_uploaded_path;
//        $image_url = url($modified_path);
        // dd($image_url);

        $uploadedImageResponse = array(
//            "name" => basename($image_uploaded_path),
            "url" => $image_url,
//            "type" => $image->getMimeType()
        );

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'date_of_birth' => $request->date_of_birth,
            'high_school' => $request->high_school,
            'postal_code' => $request->postal_code,
            'mobile_number' => $request->mobile_number,
            'image' => $uploadedImageResponse['url'],
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => 'NA',
            'email_verified_at'  => Carbon::now()->toDateTimeString(),
        ]);

        // fetch the newly created user
        $user = User::where('email', $request->email)->first();

        // prepare response
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '',
            "success" =>["User sign-up successfully."],
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'date_of_birth' => $user->date_of_birth,
                'high_school' => $user->high_school,
                'postal_code' => $user->postal_code,
                'mobile_number' => $user->mobile_number,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'image' => $uploadedImageResponse,
            ]
        ];
        $this->setResponse($this->data);
        return $this->getResponse();

    }


    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function login(Request $request)
    {

        $credentials = $request->all('email', 'password');


        if (empty($credentials['email'])) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Email is required."],
                'data' => [

                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        if (empty($credentials['password'])) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Password is required."],
                'data' => [

                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $userEmail = trim($request->email);
        $userPass = trim($request->password);

        $user = User::where('email', $userEmail)->first();

        if (empty($user)) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Email is incorrect."],
                'data' => [

                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


        if (Hash::check($userPass, $user->password) !== true) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Password is incorrect."],
                'data' => [

                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        //  User Role
//        if ($user->user_role != 'A') {
//            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
//                "success" => ["Your User Account Role is not Admin."],
//                'data' => [
//
//                ]
//            ];
//            $this->setResponse($this->data);
//            return $this->getResponse();
//        }
        //  Verified Email
        if ($user->email_verified_at == null) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', "success" => ["Please verify your email"], 'data' => []];

            $this->setResponse($this->data);
            return $this->getResponse();
        }

        //  Login
        if (auth()->attempt($credentials)) {

            $user = auth()->user();
            $user->last_login = Carbon::now();

            //  Create Auth token
            $auth_token = $user->createToken("API TOKEN")->plainTextToken;
            $user->m_login_token = $auth_token;

            $user->save();

            // Get the default avatar image
            $avatarPath = public_path('users/user_avatar.png');

            $avatarUrl = asset('users/user_avatar.png');

            $file = File::get($avatarPath);
            $mimeType = File::mimeType($avatarPath);

            // Base64 encode the image
            $encodedImage = base64_encode($file);


//            $pic = [
//                'image' => $user->image
//            ];

            if(isset($user->image)){
                $avatarUrl = $user->image;
//                $mimeType = File::mimeType($avatarPath);
            }

            $this->data = [
                'status_code' => 200,
                'code' => 100200,
                'response' => '',
                "success" => ["User Logged in Successfully"],
                "auth_token" => $auth_token,
                'data' =>
                    [
                        'id' => $user->id,
                        'name' => $user->name,
                        'surname' => $user->surname,
                        'date_of_birth' => $user->date_of_birth,
                        'high_school' => $user->high_school,
                        'postal_code' => $user->postal_code,
                        'mobile_number' => $user->mobile_number,
                        'email' => $user->email,
                        'image' => [
                            'url' => $avatarUrl,
                            // mime_type
//                            'type' => $mimeType,
                        ],
                        "last_login" => $user->last_login
                    ]

            ];

            $this->setResponse($this->data);
            return $this->getResponse();
        }

        //  Invalid Credentials
        $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', "success" => ["Invalid Credentials"], 'data' => []];
        $this->setResponse($this->data);
        return $this->getResponse();

    }

    public function logout(Request $request)
    {

        $userEmail = trim($request->email);

        if (empty($userEmail)) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["Logged in user email required."],
                'data' => []
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $user = User::where('email', $userEmail)->first();

        //  User not exist
        if (empty($user)) {
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '',
                "success" => ["User is not logged in."],
                'data' => [
                ]
            ];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        //  Clear Auth token
        $auth_token = null;
        $user->m_login_token = $auth_token;

        $user->save();

        $user->tokens()->delete();

        // Logout Successful
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', "success" => ["Logout Successfully."], 'data' => ['email' => $userEmail]];
        $this->setResponse($this->data);
        return $this->getResponse();
    }

    public function userDetail(Request $request)
    {
        $auth_token = $request->bearerToken();
        $user = auth()->user();
        $notification = $user->unreadNotifications()->count();


        // Get the default avatar image
        $avatarPath = public_path('users/user_avatar_default.png');
        $avatarUrl = asset('users/user_avatar_default.png');

        $file = File::get($avatarPath);
        $mimeType = File::mimeType($avatarPath);

        // Base64 encode the image
        $encodedImage = base64_encode($file);

        $this->data = [
            'status_code' => 200,
            'code' => 100200,
            'response' => '',
            "success" => ["User details."],
            "auth_token" => $auth_token,
            'data' =>
                [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    'image' => [
                        'url' => $avatarUrl,
                        // mime_type
                        'type' => $mimeType,
                    ],
                    "unread_notification_count" => $notification,
                ]

        ];

        $this->setResponse($this->data);
        return $this->getResponse();


        //  Invalid
        $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', "success" => ["User not authorized."], 'data' => []];
        $this->setResponse($this->data);
        return $this->getResponse();

    }
}
