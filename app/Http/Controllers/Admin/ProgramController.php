<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=[
            'page_title'=>'Programs',
            'p_title'=>'Programs',
            'p_summary'=>'List of Programs',
            'p_description'=>null,
            'url'=> route('program.create'),
            'url_text'=>'Add New',
        ];
//        dd('12');
        return view('admin.program.index')->with($data);
    }

    public function getIndex(Request $request)
    {
//        dd('123');
        ## Read values
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Program::select('programs.*')->count();
        // Total records with filter
        $totalRecordswithFilter = Program::select('programs.*')
            ->where(function ($q) use ($searchValue){
                $q->where('programs.name_of_the_formation', 'like', '%' .$searchValue . '%');
            })
            ->count();
        // Fetch records
        $records = Program::select('programs.*')
            ->where(function ($q) use ($searchValue){
                $q->where('programs.name_of_the_formation', 'like', '%' .$searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->orderBy($columnName,$columnSortOrder)
            ->get();

        $data_arr = array();

        foreach($records as $record){
            $id = $record->id;
            $formation_id = $record->formation_id;
            $name_of_the_formation = $record->name_of_the_formation;
            $link_to_its_webpage = $record->link_to_its_webpage;
            $region = $record->region;
            $schooling_cost = $record->schooling_cost;
            $length_of_the_formation = $record->length_of_the_formation;
            $status = $record->status;
            $access_rate = $record->access_rate;
            $type_of_formation = $record->type_of_formation;
            $town = $record->town;
            $schooling_modalities = $record->schooling_modalities;
            $schooling_pursuit = $record->schooling_pursuit;
            $description_of_the_formation = $record->description_of_the_formation;
            $number_of_students = $record->number_of_students;
            $keyword_option_one = $record->keyword_option_one;
            $keyword_secondary_one = $record->keyword_secondary_one;
            $keyword_main_one = $record->keyword_main_one;
            $is_fav = $record->is_fav;
            $created_at = $record->created_at;
            $updated_at = $record->updated_at;
            $checkBox = '<div class="form-check"> <input class="form-check-input" type="checkbox" name="chk_child" value="option1"></div>';


            $data_arr[] = array(
                "checkbox" => $checkBox,
                "id" => $id,
                "formation_id" => $formation_id,
                "name_of_the_formation" => $name_of_the_formation,
                "link_to_its_webpage" => $link_to_its_webpage,
                "region" => $region,
                "schooling_cost" => $schooling_cost,
                "length_of_the_formation" => $length_of_the_formation,
                "status" => $status,
                "access_rate" => $access_rate,
                "type_of_formation" => $type_of_formation,
                "town" => $town,
                "schooling_modalities" => $schooling_modalities,
                "schooling_pursuit" => $schooling_pursuit,
                "description_of_the_formation" => $description_of_the_formation,
                "number_of_students" => $number_of_students,
                "keyword_option_one" => $keyword_option_one,
                "keyword_secondary_one" => $keyword_secondary_one,
                "keyword_main_one" => $keyword_main_one,
                "is_fav" => $is_fav,
                "created_at" => $created_at,
                "updated_at" => $updated_at
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );
//        dd($response);
        echo json_encode($response);
        exit;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'page_title'=>'Programs',
            'p_title'=>'Programs',
            'p_summary'=>'Add Programs',
            'p_description'=>null,
            'method' => 'POST',
            'action' => route('program.store'),
            'url'=>route('program.index'),
            'url_text'=>'View All',
            // 'enctype' => 'multipart/form-data' // (Default)Without attachment
            'enctype' => 'application/x-www-form-urlencoded', // With attachment like file or images in form
        );
        return view('admin.program.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        // Open and read the CSV file
        if (($handle = fopen($request->file('csv_file'), 'r')) !== FALSE) {
            $header = null;
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data = array_combine($header, $row);

                    // Ensure the 'id' field exists in the data
                    if (isset($data['id'])) {
                        // Find the record in the database and update it, or create a new one
                        Program::updateOrCreate(
                            ['formation_id' => $data['formation_id']],  // Use 'id' as the unique identifier
                            [
                                'formation_id' => $data['formation_id'],
                                'name_of_the_formation' => $data['name_of_the_formation'],
                                'link_to_its_webpage' => $data['link_to_its_webpage'],
                                'region' => $data['region'],
                                'schooling_cost' => $data['schooling_cost'],
                                'length_of_the_formation' => $data['length_of_the_formation'],
                                'status' => $data['status'],
                                'access_rate' => $data['access_rate'],
                                'type_of_formation' => $data['type_of_formation'],
                                'town' => $data['town'],
                                'schooling_modalities' => $data['schooling_modalities'],
                                'schooling_pursuit' => $data['schooling_pursuit'],
                                'description_of_the_formation' => $data['description_of_the_formation'],
                                'number_of_students' => $data['number_of_students'],
                                'keyword_option_one' => $data['keyword_option_one'],
                                'keyword_secondary_one' => $data['keyword_secondary_one'],
                                'keyword_main_one' => $data['keyword_main_one'],
                                'is_fav' => $data['is_fav'],
                                'created_by' => auth()->id(),  // Auto-populate auth user
                                'updated_by' => auth()->id(),
                            ]
                        );
                    }
                }
            }
            fclose($handle);
        }

        return redirect()->back()->with('success', 'CSV data imported successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
