<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\User;
use App\Models\UserProgramApplication;
use App\Models\UserProgramView;
use App\Traits\Api\Response;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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

    public function changePassword(Request $request)
    {

        // validation rules
        $rules = ['old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:6', 'confirmed'], 'password_confirmation' => ['required', 'string', 'min:6']];

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

        $user = User::where('email', $auth_user->email)->first();

        $user->update(
            [
                'password' => Hash::make($request->password)
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
            'mobile_number' => ['required', 'numeric'],
//            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
//            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
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
//            'password.required' => 'Please enter a password.',
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
        if ($request->hasFile('image')) {
            $image = $request->file('image'); // Ensure you get the uploaded file

            // Get the original file name without the extension
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            // Get the file extension
            $extension = $image->getClientOriginalExtension();

            // Slugify the file name
            $slugifiedName = Str::slug($originalName) . '.' . $extension;

            // Store the image in the 'public' disk, which maps to 'storage/app/public' directory
            $image_uploaded_path = $image->storeAs('users', $slugifiedName, 'public');

            // Complete URL including the base live URL
//        $image_url = url(Storage::url($image_uploaded_path));

            $modified_path = 'public/storage/' . $image_uploaded_path;
            $image_url = url($modified_path);
            $uploadedImageResponse = [
//                "name" => basename($image_uploaded_path),
                "url" => $image_url,
//                "type" => $image->getMimeType()
            ];
        } else {
            $uploadedImageResponse = [
//                "name" => basename($image_uploaded_path),
                "url" => $user->image,
//                "type" => $image->getMimeType()
            ];
        }
        // Handle image upload if a new image is provided
//        if ($request->hasFile('image')) {
//            $image = $request->file('image');
//            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
//            $extension = $image->getClientOriginalExtension();
//            $slugifiedName = Str::slug($originalName) . '.' . $extension;
//            $image_uploaded_path = $image->storeAs('users', $slugifiedName, 'public');
//            $image_url = url(Storage::url($image_uploaded_path));
//
//            $uploadedImageResponse = [
////                "name" => basename($image_uploaded_path),
//                "url" => $image_url,
////                "type" => $image->getMimeType()
//            ];
//
//            // Update the image URL in the user record
//            $user->image = $uploadedImageResponse['url'];
//        }else{
//            $uploadedImageResponse = [
////                "name" => basename($image_uploaded_path),
//                "url" => $user->image,
////                "type" => $image->getMimeType()
//            ];
//        }

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
                    'country_code' => $user->country_code,
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
        $user->country_code = $request->country_code;
        $user->mobile_number = $request->mobile_number;


//        if ($request->password) {
//            $user->password = Hash::make($request->password);
//        }

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
                'country_code' => $user->country_code,
                'mobile_number' => $user->mobile_number,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'image' => $uploadedImageResponse,
            ]
        ];

        $this->setResponse($this->data);
        return $this->getResponse();
    }


    public function completeProfile(Request $request)
    {
        $user = auth('sanctum')->user();

        $rules = [
            'type_of_baccalaureate' => ['required', 'string'],
        ];

        // Validation messages
        $messages = [
            'type_of_baccalaureate.required' => 'Please enter type of baccalaureate.',
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

        if ($request->input('type_of_baccalaureate') == 'General') {
            $rules = [
                'specialities' => ['required', 'string'],
                'european_section' => ['required', 'string'],
                'options' => ['required', 'string']
            ];
            $messages = [
                'specialities.required' => 'Please enter specialities.',
                'european_section.required' => 'Please enter european section.',
                'options.required' => 'Please enter options.',
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
        }

        if ($request->input('type_of_baccalaureate') == 'Technologique') {
            $rules = [
                'filliere_de_formation' => ['required', 'string'],
            ];
            $messages = [
                'filliere_de_formation.required' => 'Please enter filliere de formation.',
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
        }

        $rules = [
            'general_mean' => ['required', 'numeric', 'min:0', 'max:20'],
            'subject_id' => ['required', 'int'],
            'learning_a_language' => ['required', 'string'],
        ];

        $messages = [
            'general_mean.required' => 'Please enter general mean.',
            'subject_id.required' => 'Please enter subject_id.',
            'learning_a_language.required' => 'Please enter learning a language.',
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

        if ($request->input('learning_a_language') == 'Yes') {
            $rules = [
                'language' => ['required', 'string'],
            ];
            $messages = [
                'language.required' => 'Please enter language.',
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
        }

        $rules = [
            'international_experience' => ['required', 'string'],
            'traveling_to_a_peculiar_region' => ['required', 'string'],
        ];
        $messages = [
            'international_experience.required' => 'Please enter international experience.',
            'traveling_to_a_peculiar_region.required' => 'Please enter traveling to a peculiar region.',
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

        if ($request->input('traveling_to_a_peculiar_region') == 'Yes') {
            $rules = [
                'region' => ['required', 'string'],
            ];
            $messages = [
                'region.required' => 'Please enter region.',
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
        }

        // Validation rules
        $rules = [
            'prefer_school' => ['required', 'string'],
            'study' => ['required', 'string'],
            'minimum_monthly_cost' => ['required', 'string'],
            'pay_for_your_studies' => ['required', 'string'],
            'professionalizing_formation' => ['required', 'string'],
            'study_online' => ['required', 'string'],
            'iapprentissage' => ['required', 'string'],
        ];

        // Validation messages
        $messages = [
            'prefer_school.required' => 'Please enter prefer school.',
            'study.required' => 'Please enter study likeness.',
            'minimum_monthly_cost.required' => 'Please enter minimum monthly cost.',
            'pay_for_your_studies.required' => 'Please enter pay for your studies.',
            'professionalizing_formation.required' => 'Please enter professionalizing formation.',
            'study_online.required' => 'Please enter study online.',
            'iapprentissage.required' => 'Please enter iapprentissage.'
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


        // Update/complete user details
        $user->type_of_baccalaureate = $request->type_of_baccalaureate;
        $user->specialities = $request->specialities;
        $user->european_section = $request->european_section;
        $user->options = $request->options;
        $user->iapprentissage = $request->iapprentissage;
        $user->general_mean = $request->general_mean;
        $user->subject_id = $request->subject_id;
        $user->learning_a_language = $request->learning_a_language;
        $user->language = $request->language;
        $user->international_experience = $request->international_experience;
        $user->traveling_to_a_peculiar_region = $request->traveling_to_a_peculiar_region;
        $user->region = $request->region;
        $user->prefer_school = $request->prefer_school;
        $user->study = $request->study;
        $user->minimum_monthly_cost = $request->minimum_monthly_cost;
        $user->pay_for_your_studies = $request->pay_for_your_studies;
        $user->professionalizing_formation = $request->professionalizing_formation;
        $user->study_online = $request->study_online;


        // Save the updated user
        $user->save();

        // Prepare response
        $this->data = [
            'status_code' => 200,
            'code' => 100200,
            'response' => '',
            "success" => ["Profile completed successfully."],
            'data' => [
                'type_of_baccalaureate' => $user->type_of_baccalaureate,
                'specialities' => $user->specialities,
                'european_section' => $user->european_section,
                'options' => $user->options,
                'general_mean' => $user->general_mean,
                'subject_id' => $user->subject_id,
                'learning_a_language' => $user->learning_a_language,
                'language' => $user->language,
                'international_experience' => $user->international_experience,
                'traveling_to_a_peculiar_region' => $user->traveling_to_a_peculiar_region,
                'region' => $user->region,
                'prefer_school' => $user->prefer_school,
                'study' => $user->study,
                'minimum_monthly_cost' => $user->minimum_monthly_cost,
                'pay_for_your_studies' => $user->pay_for_your_studies,
                'professionalizing_formation' => $user->professionalizing_formation,
                'study_online' => $user->study_online,
                'iapprentissage' => $user->iapprentissage,
            ]
        ];

        $this->setResponse($this->data);
        return $this->getResponse();
    }

    public function userDetail(Request $request)
    {
        $auth_token = $request->bearerToken();
        $user = auth('sanctum')->user();

        // Get the default avatar image
//        $avatarPath = public_path('img/user_avatar.png');
//        $avatarUrl = asset('img/user_avatar.png');

//        $file = File::get($avatarPath);
//        $mimeType = File::mimeType($avatarPath);

        // Base64 encode the image
//        $encodedImage = base64_encode($file);
        // List of fields to check
        $fields = [
            'type_of_baccalaureate',
            'specialities',
            'european_section',
            'options',
            'iapprentissage',
            'general_mean',
            'subject_id',
            'learning_a_language',
            'language',
            'international_experience',
            'traveling_to_a_peculiar_region',
            'region',
            'prefer_school',
            'study',
            'minimum_monthly_cost',
            'pay_for_your_studies',
            'professionalizing_formation',
            'study_online',
            'filliere_de_formation'
        ];

        // Initialize the survey variable
        $user_survey = 0;

        // Check if any field is not null
        foreach ($fields as $field) {
            if (!is_null($user->$field)) {
                $user_survey = 1;
                break; // Exit the loop early if any field is not null
            }
        }
        $this->data = [
            'status_code' => 200,
            'code' => 100200,
            'response' => '',
            "success" => ["User details fetched successfully."],
            "auth_token" => $auth_token,
            'data' =>
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'date_of_birth' => $user->date_of_birth,
                    'high_school' => $user->high_school,
                    'postal_code' => $user->postal_code,
                    'country_code' => $user->country_code,
                    'mobile_number' => $user->mobile_number,
                    'email' => $user->email,


                    'image' => [
                        'url' => $user->image,
                        // mime_type
//                        'type' => $mimeType,
                    ],
                    'complete_profile' => [
                        //Complete Profile Detail
                        'type_of_baccalaureate' => $user->type_of_baccalaureate,
                        'specialities' => $user->specialities,
                        'european_section' => $user->european_section,
                        'options' => $user->options,
                        'iapprentissage' => $user->iapprentissage,
                        'general_mean' => $user->general_mean,
                        'subject_id' => $user->subject_id,
                        'learning_a_language' => $user->learning_a_language,
                        'language' => $user->language,
                        'international_experience' => $user->international_experience,
                        'traveling_to_a_peculiar_region' => $user->traveling_to_a_peculiar_region,
                        'region' => $user->region,
                        'prefer_school' => $user->prefer_school,
                        'study' => $user->study,
                        'minimum_monthly_cost' => $user->minimum_monthly_cost,
                        'pay_for_your_studies' => $user->pay_for_your_studies,
                        'professionalizing_formation' => $user->professionalizing_formation,
                        'study_online' => $user->study_online,
                        'filliere_de_formation' => $user->filliere_de_formation
                    ],
                    "user_survey" => $user_survey,
                    "last_login" => $user->last_login


                ]

        ];

        $this->setResponse($this->data);
        return $this->getResponse();


        //  Invalid
        $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', "success" => ["User not authorized."], 'data' => []];
        $this->setResponse($this->data);
        return $this->getResponse();

    }

    public function statistics(Request $request)
    {
        $auth_token = $request->bearerToken();
        $user = auth('sanctum')->user();

        $basket = Basket::select('*')->where('created_by', '=', $user->id)->count();
        $program_seen = UserProgramView::select('*')->where('user_id', '=', $user->id)->count();
        $applied_program = UserProgramApplication::select('*')->where('user_id', '=', $user->id)->count();


        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $perPage = $request->get('per_page');
        } else {

            $perPage = "7";
        }


//        $applied_program_data =  UserProgramApplication::select('user_program_applications.program_id','programs.formation_id','programs.name_of_the_formation','programs.link_to_its_webpage','programs.region','programs.town','user_program_applications.created_at')->leftJoin('programs','user_program_applications.program_id','=','programs.id')->where('user_program_applications.user_id','=',$user->id)->orderBy('user_program_applications.id','desc')->paginate($perPage);

        $applied_program_data = UserProgramApplication::select(
            'user_program_applications.program_id',
            'programs.formation_id',
            'programs.name_of_the_formation',
            'programs.link_to_its_webpage',
            'programs.region',
            'programs.town',
            DB::raw("DATE_FORMAT(user_program_applications.created_at, '%Y-%m-%d %H:%i:%s') as date")
        )
            ->leftJoin('programs', 'user_program_applications.program_id', '=', 'programs.id')
            ->where('user_program_applications.user_id', '=', $user->id)
            ->orderBy('user_program_applications.id', 'desc')
            ->paginate($perPage);


        if ($applied_program_data->isNotEmpty()) {
//            dd('123');
            $data = [
                "auth_token" => $auth_token,
                //                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
//                'email' => $user->email,
                'image' => [
                    'url' => $user->image,
                ],
//                "last_login" => $user->last_login,
                'current_page' => $applied_program_data->currentPage(),
                'data' => $applied_program_data->items(),
                'first_page_url' => $applied_program_data->url(1),
                'from' => $applied_program_data->firstItem(),
                'last_page' => $applied_program_data->lastPage(),
                'last_page_url' => $applied_program_data->url($applied_program_data->lastPage()),
                'links' => [
                    [
                        'url' => $applied_program_data->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $applied_program_data->onFirstPage() ? false : true,
                    ],
                    [
                        'url' => $applied_program_data->url($applied_program_data->currentPage()),
                        'label' => $applied_program_data->currentPage(),
                        'active' => true,
                    ],
                    [
                        'url' => $applied_program_data->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $applied_program_data->hasMorePages() ? true : false,
                    ],
                ],
                'next_page_url' => $applied_program_data->nextPageUrl(),
                'path' => $applied_program_data->path(),
                'per_page' => $perPage,
                'prev_page_url' => $applied_program_data->previousPageUrl(),
                'to' => $applied_program_data->lastItem(),
                'total' => $applied_program_data->total(),

                "programs_seen" => $program_seen,
                "programs_in_basket" => $basket,
                "applications_sent" => $applied_program
            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['User Statistics.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = [
                //                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
//                'email' => $user->email,
                'image' => [
                    'url' => $user->image,
                ],
//                "last_login" => $user->last_login,
                'current_page' => $applied_program_data->currentPage(),
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


                "programs_seen" => $program_seen,
                "programs_in_basket" => $basket,
                "applications_sent" => $applied_program

            ];
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No programs found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        //  Invalid
        $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', "success" => ["User not authorized."], 'data' => []];
        $this->setResponse($this->data);
        return $this->getResponse();

    }
}
