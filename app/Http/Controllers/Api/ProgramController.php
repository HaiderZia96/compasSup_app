<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Traits\Api\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];

    public function index(Request $request)
    {

        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $perPage = $request->get('per_page');
        }else{

            $perPage = "7" ;
        }

//        dd($perPage);
//        $perPage = $request->input('per_page', 5);

        $program = Program::paginate($perPage);


        if ($program->isNotEmpty()) {

            $data = [
                'current_page' => $program->currentPage(),
                'data' => $program->items(),
                'first_page_url' => $program->url(1),
                'from' => $program->firstItem(),
                'last_page' => $program->lastPage(),
                'last_page_url' => $program->url($program->lastPage()),
                'links' => [
                    [
                        'url' => $program->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $program->onFirstPage() ? false : true,
                    ],
                    [
                        'url' => $program->url($program->currentPage()),
                        'label' => $program->currentPage(),
                        'active' => true,
                    ],
                    [
                        'url' => $program->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $program->hasMorePages() ? true : false,
                    ],
                ],
                'next_page_url' => $program->nextPageUrl(),
                'path' => $program->path(),
                'per_page' => $perPage,
                'prev_page_url' => $program->previousPageUrl(),
                'to' => $program->lastItem(),
                'total' => $program->total(),
            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All programs fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = [
                'current_page' => $program->currentPage(),
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

            ];
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


        if ($prog->isNotEmpty() ) {
            $data = $prog;
        } else {
            $data = [];

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No program found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['Program fetched successfully.'], 'data' => $data];
        $this->setResponse($this->data);
        return $this->getResponse();
    }
}
