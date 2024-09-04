<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubjectSubCategory;
use Illuminate\Http\Request;

class SubjectSubCategoryController extends Controller
{
    public function subCategory(Request $request, string $id)
    {
        // find the sub is valid or not
        $sub = SubjectSubCategory::find($id);


        if (!$sub) {
            //sub not found
            $this->data = ['status_code' => 200, 'code' => 100401, 'response' => '', 'success' => ['Invalid subject id.'], 'data' => []];
            $this->setResponse($this->data);
            return $this->getResponse();
        }


        if ($request->has('per_page') && $request->get('per_page') !== null) {

            $per_page = $request->get('per_page');
        }else{

            $per_page = 7 ;
        }


        $sub = Subject::where('room_id',$id)->where('created_at', 'LIKE', '%'.$date.'%')->get();


        $pending = Task::where('room_id',$id)->where('created_at', 'LIKE', '%'.$date.'%')->where('status', 0)->paginate($per_page);

        $completed = Task::where('room_id',$id)->where('created_at', 'LIKE', '%'.$date.'%')->where('status', 1)->paginate($per_page);

        $task_count = Task::where('room_id',$id)->count();

        $pending_count = Task::where('room_id',$id)->where('status', 0)->count();

        $completed_count = Task::where('room_id',$id)->where('status', 1)->count();

//        $perPage = $request->input('per_page', 5);



        if ($task->isNotEmpty() ) {
            $data = [
                'pending' => [
                    'current_page' => $pending->currentPage(),
                    'data' => $pending->items(),
                    'first_page_url' => $pending->url(1),
                    'from' => $pending->firstItem(),
                    'last_page' => $pending->lastPage(),
                    'last_page_url' => $pending->url($pending->lastPage()),
                    'links' => [
                        [
                            'url' => $pending->previousPageUrl(),
                            'label' => '&laquo; Previous',
                            'active' => $pending->onFirstPage() ? false : true,
                        ],
                        [
                            'url' => $pending->url($pending->currentPage()),
                            'label' => $pending->currentPage(),
                            'active' => true,
                        ],
                        [
                            'url' => $pending->nextPageUrl(),
                            'label' => 'Next &raquo;',
                            'active' => $pending->hasMorePages() ? true : false,
                        ],
                    ],
                    'next_page_url' => $pending->nextPageUrl(),
                    'path' => $pending->path(),
                    'per_page' => $per_page,
                    'prev_page_url' => $pending->previousPageUrl(),
                    'to' => $pending->lastItem(),
                    'total' => $pending->total(),
                ],
                'completed' => [
                    'current_page' => $completed->currentPage(),
                    'data' => $completed->items(),
                    'first_page_url' => $completed->url(1),
                    'from' => $completed->firstItem(),
                    'last_page' => $completed->lastPage(),
                    'last_page_url' => $completed->url($completed->lastPage()),
                    'links' => [
                        [
                            'url' => $completed->previousPageUrl(),
                            'label' => '&laquo; Previous',
                            'active' => $completed->onFirstPage() ? false : true,
                        ],
                        [
                            'url' => $completed->url($completed->currentPage()),
                            'label' => $completed->currentPage(),
                            'active' => true,
                        ],
                        [
                            'url' => $completed->nextPageUrl(),
                            'label' => 'Next &raquo;',
                            'active' => $completed->hasMorePages() ? true : false,
                        ],
                    ],
                    'next_page_url' => $completed->nextPageUrl(),
                    'path' => $completed->path(),
                    'per_page' => $per_page,
                    'prev_page_url' => $completed->previousPageUrl(),
                    'to' => $completed->lastItem(),
                    'total' => $completed->total(),
                ],
                'total_tasks' => $task_count,
                'pending_tasks' => $pending_count,
                'completed_tasks' => $completed_count,
            ];
        } else {

            $data = [
                'pending' => [
                    'current_page' => $pending->currentPage(),
                    'data' => [],
                    'first_page_url' => null,
                    'from' => null,
                    'last_page' => null,
                    'last_page_url' => null,
                    'links' => [],
                    'next_page_url' => null,
                    'path' => $request->url(),
                    'per_page' => $pending,
                    'prev_page_url' => null,
                    'to' => null,
                    'total' => 0,

                ],
                'completed' => [
                    'current_page' => $completed->currentPage(),
                    'data' => [],
                    'first_page_url' => null,
                    'from' => null,
                    'last_page' => null,
                    'last_page_url' => null,
                    'links' => [],
                    'next_page_url' => null,
                    'path' => $request->url(),
                    'per_page' => $completed,
                    'prev_page_url' => null,
                    'to' => null,
                    'total' => 0,

                ],
                'total_tasks' => $task_count,
                'pending_tasks' => $pending_count,
                'completed_tasks' => $completed_count,
            ];

            $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['No room tasks found.'], 'data' => $data];
            $this->setResponse($this->data);
            return $this->getResponse();
        }

        $this->data = ['status_code' => 200, 'code' => 100200, 'response' => '', 'success' => ['All room tasks.'], 'data' => $data];
        $this->setResponse($this->data);
        return $this->getResponse();
    }
}
