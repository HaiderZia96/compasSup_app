<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectSubCategory;
use App\Traits\Api\Response;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use Response;

    public $data;
    public $dataArray = [];

    public function index(Request $request)
    {

        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $perPage = $request->get('per_page');
        } else {

            $perPage = "7";
        }


        $subjects = Subject::paginate($perPage);

        if ($subjects->isNotEmpty()) {
            //            dd('123');
            $data = [
                'current_page' => $subjects->currentPage(),
                'data' => $subjects->items(),
                'first_page_url' => $subjects->url(1),
                'from' => $subjects->firstItem(),
                'last_page' => $subjects->lastPage(),
                'last_page_url' => $subjects->url($subjects->lastPage()),
                'links' => [
                    [
                        'url' => $subjects->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $subjects->onFirstPage() ? false : true,
                    ],
                    [
                        'url' => $subjects->url($subjects->currentPage()),
                        'label' => $subjects->currentPage(),
                        'active' => true,
                    ],
                    [
                        'url' => $subjects->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $subjects->hasMorePages() ? true : false,
                    ],
                ],
                'next_page_url' => $subjects->nextPageUrl(),
                'path' => $subjects->path(),
                'per_page' => $perPage,
                'prev_page_url' => $subjects->previousPageUrl(),
                'to' => $subjects->lastItem(),
                'total' => $subjects->total(),
            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All subjects fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = [
                'current_page' => $subjects->currentPage(),
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
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No subjects found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }
    }

    public function subCategory(Request $request, string $id)
    {
        // find the sub is valid or not
        $subject = Subject::find($id);


        if (!$subject) {
            //sub not found
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => ['Invalid subject id.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $per_page = $request->get('per_page');
        } else {

            $per_page = 7;
        }

        $subjectCategories = explode(',', $subject->subject_category); // assuming $subject is the result of $subject = Subject::find($id);

        $results = SubjectSubCategory::select('subject_sub_categories.*')
            ->leftJoin('subjects', 'subject_sub_categories.id', '=', 'subjects.sub_category_id')
            ->whereIn('subject_sub_categories.id', $subjectCategories)
            ->distinct()
            ->paginate($per_page);

        if ($results->isNotEmpty()) {
            $data = [
                'current_page' => $results->currentPage(),
                'data' => $results->items(),
                'first_page_url' => $results->url(1),
                'from' => $results->firstItem(),
                'last_page' => $results->lastPage(),
                'last_page_url' => $results->url($results->lastPage()),
                'links' => [
                    [
                        'url' => $results->previousPageUrl(),
                        'label' => '&laquo; Previous',
                        'active' => $results->onFirstPage() ? false : true,
                    ],
                    [
                        'url' => $results->url($results->currentPage()),
                        'label' => $results->currentPage(),
                        'active' => true,
                    ],
                    [
                        'url' => $results->nextPageUrl(),
                        'label' => 'Next &raquo;',
                        'active' => $results->hasMorePages() ? true : false,
                    ],
                ],
                'next_page_url' => $results->nextPageUrl(),
                'path' => $results->path(),
                'per_page' => $per_page,
                'prev_page_url' => $results->previousPageUrl(),
                'to' => $results->lastItem(),
                'total' => $results->getCollection()->count(),
            ];
            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All subject categories fetched.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        } else {

            $data = [
                'current_page' => $results->currentPage(),
                'data' => [],
                'first_page_url' => null,
                'from' => null,
                'last_page' => null,
                'last_page_url' => null,
                'links' => [],
                'next_page_url' => null,
                'path' => $request->url(),
                'per_page' => $per_page,
                'prev_page_url' => null,
                'to' => null,
                'total' => 0,

            ];
            $this->data = ['status_code' => 200, 'code' => 100402, 'response' => '', 'success' => ['No subject categories found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();

        }

    }
}
