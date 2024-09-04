<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\UserProgramApplication;
use App\Models\UserProgramView;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];

    public function index(Request $request)
    {

        $programQuery = Program::query();

        // Apply filters based on the request
        if ($request->has('name_of_the_formation') && $request->get('name_of_the_formation') !== null) {
            $name_of_formation = $request->get('name_of_the_formation');
            $programQuery->where('name_of_the_formation', 'LIKE', "%$name_of_formation%");
        }

        if ($request->has('region') && $request->get('region') !== null) {
            $region = $request->get('region');
            $programQuery->where('region', 'LIKE', "%$region%");
        }

        if ($request->has('access_rate') && $request->get('access_rate') !== null) {
            $access_rate = $request->get('access_rate');
            $programQuery->where('access_rate', '=', $access_rate);
        }

        if ($request->has('type_of_formation') && $request->get('type_of_formation') !== null) {
            $type_of_formation = $request->get('type_of_formation');
            $programQuery->where('type_of_formation', 'LIKE', "%$type_of_formation%");
        }

        if ($request->has('town') && $request->get('town') !== null) {
            $town = $request->get('town');
            $programQuery->where('town', 'LIKE', "%$town%");
        }

        if ($request->has('keywords') && $request->get('keywords') !== null) {
            $keyword = $request->get('keywords');
            $programQuery->where('link_to_its_webpage', 'LIKE', "%$keyword%")
                ->orWhere('formation_id', 'LIKE', "%$keyword%")
                ->orWhere('region', 'LIKE', "%$keyword%")
                ->orWhere('schooling_cost', 'LIKE', "%$keyword%")
                ->orWhere('length_of_the_formation', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('access_rate', 'LIKE', "%$keyword%")
                ->orWhere('type_of_formation', 'LIKE', "%$keyword%")
                ->orWhere('town', 'LIKE', "%$keyword%")
                ->orWhere('schooling_modalities', 'LIKE', "%$keyword%")
                ->orWhere('schooling_pursuit', 'LIKE', "%$keyword%")
                ->orWhere('description_of_the_formation', 'LIKE', "%$keyword%")
                ->orWhere('number_of_students', 'LIKE', "%$keyword%");
        }

        // Get the filtered results
        $program = $programQuery->get();

        if ($program->isNotEmpty()) {
            $data =
//                []
                $program;
//                'current_page' => $program->currentPage(),
//                'data' => $program->items(),
//                'first_page_url' => $program->url(1),
//                'from' => $program->firstItem(),
//                'last_page' => $program->lastPage(),
//                'last_page_url' => $program->url($program->lastPage()),
//                'links' => [
//                    [
//                        'url' => $program->previousPageUrl(),
//                        'label' => '&laquo; Previous',
//                        'active' => $program->onFirstPage() ? false : true,
//                    ],
//                    [
//                        'url' => $program->url($program->currentPage()),
//                        'label' => $program->currentPage(),
//                        'active' => true,
//                    ],
//                    [
//                        'url' => $program->nextPageUrl(),
//                        'label' => 'Next &raquo;',
//                        'active' => $program->hasMorePages() ? true : false,
//                    ],
//                ],
//                'next_page_url' => $program->nextPageUrl(),
//                'path' => $program->path(),
//                'per_page' => $perPage,
//                'prev_page_url' => $program->previousPageUrl(),
//                'to' => $program->lastItem(),
//                'total' => $program->total(),
//            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All programs fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data =
//                []
                $program;
//                'current_page' => $program->currentPage(),
//                'data' => [],
//                'first_page_url' => null,
//                'from' => null,
//                'last_page' => null,
//                'last_page_url' => null,
//                'links' => [],
//                'next_page_url' => null,
//                'path' => $request->url(),
//                'per_page' => $perPage,
//                'prev_page_url' => null,
//                'to' => null,
//                'total' => 0,

            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No programs found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }

    public function programById(Request $request, string $id)
    {
        // find the program is valid or not
        $program = Program::find($id);

        if (!$program) {
            //program not found
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => ['Invalid program id.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


        $prog = Program::where('id',$id)->get();
        foreach ($prog as $pro) {
            $pro->is_fav; // true or false
        }

        if ($prog->isNotEmpty() ) {
            $data = $prog;
        } else {
            $data = [];

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No program found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
//        $this->trackView($id);
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program fetched successfully.'], 'data' => $data];
        $this->setResponse($this->data);
        return $this->getResponse();
    }

    public function programSeenById(Request $request, string $id)
    {
        // find the program is valid or not
        $program = Program::find($id);

        if (!$program) {
            //program not found
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => ['Invalid program id.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


        $prog = Program::where('id',$id)->get();
        foreach ($prog as $pro) {
            $pro->is_fav; // true or false
        }

        if ($prog->isNotEmpty() ) {
            $data = $prog;
        } else {
            $data = [];

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No program found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
        $this->trackView($id);
        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program seen successfully.'], 'data' => $data];
        $this->setResponse($this->data);
        return $this->getResponse();
    }


    public function indexFilters(Request $request)
    {
        $user = auth('sanctum')->user();
        $programQuery = Program::query();


        if (isset($user->region)) {
            $region = $user->region;
            $programQuery->where('region', 'LIKE', "%$region%");
        }

        if (isset($user->prefer_school)) {
            $prefer_school = $user->prefer_school;
            $programQuery->where('status', 'LIKE', "%$prefer_school%");
        }

        $programs = $programQuery->get();


        if ($programs->isNotEmpty()) {
            $data =
//                []
                $programs;
//                'current_page' => $program->currentPage(),
//                'data' => $program->items(),
//                'first_page_url' => $program->url(1),
//                'from' => $program->firstItem(),
//                'last_page' => $program->lastPage(),
//                'last_page_url' => $program->url($program->lastPage()),
//                'links' => [
//                    [
//                        'url' => $program->previousPageUrl(),
//                        'label' => '&laquo; Previous',
//                        'active' => $program->onFirstPage() ? false : true,
//                    ],
//                    [
//                        'url' => $program->url($program->currentPage()),
//                        'label' => $program->currentPage(),
//                        'active' => true,
//                    ],
//                    [
//                        'url' => $program->nextPageUrl(),
//                        'label' => 'Next &raquo;',
//                        'active' => $program->hasMorePages() ? true : false,
//                    ],
//                ],
//                'next_page_url' => $program->nextPageUrl(),
//                'path' => $program->path(),
//                'per_page' => $perPage,
//                'prev_page_url' => $program->previousPageUrl(),
//                'to' => $program->lastItem(),
//                'total' => $program->total(),
//            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All programs fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data =
//                []
                $programs;
//                'current_page' => $program->currentPage(),
//                'data' => [],
//                'first_page_url' => null,
//                'from' => null,
//                'last_page' => null,
//                'last_page_url' => null,
//                'links' => [],
//                'next_page_url' => null,
//                'path' => $request->url(),
//                'per_page' => $perPage,
//                'prev_page_url' => null,
//                'to' => null,
//                'total' => 0,

            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No programs related to user preferences found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }

    public function trackView($id)
    {
        $user = auth('sanctum')->user();
//        dd($user);
        if ($user) {
            // Check if the user has already viewed the program
            if (!UserProgramView::where('user_id', $user->id)->where('program_id', $id)->exists()) {
                UserProgramView::create([
                    'user_id' => $user->id,
                    'program_id' => $id
                ]);
                // Optionally update the view count in the Program model if needed
                // $program = Program::find($id);
                // $program->increment('view_count');
            }
        }
    }

    // Apply to a program
    public function apply($id)
    {
        $user = auth('sanctum')->user();
        if ($user) {
            // Check if the user has already applied to the program
            if (!UserProgramApplication::where('user_id', $user->id)->where('program_id', $id)->exists()) {
                UserProgramApplication::create([
                    'user_id' => $user->id,
                    'program_id' => $id
                ]);
                // Optionally update the apply count in the Program model if needed
                // $program = Program::find($id);
                // $program->increment('apply_count');
            }
        }
        // find the program is valid or not
        $program = Program::find($id);

        if (!$program) {
            //program not found
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => ['Invalid program id.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
//        $applyUrl = $program->link_to_its_webpage;

        $prog = Program::where('id',$id)->get();


        if ($prog->isNotEmpty() ) {
            $data = $prog;
        } else {
            $data = [];

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No program found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Applied Program.'], 'data' => $data];
        $this->setResponse($this->data);
        return $this->getResponse();

    }



}
