@extends('layout.index')
@section('title','Program | Program')
@section('custom-style')
    <style>

        /* Add any custom styles here */
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                    <h4 class="mb-sm-0">Programs List</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Program</a></li>
                            <li class="breadcrumb-item active">Program List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="StarCampsList">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title mb-0 flex-grow-1">All Programs</h5>
                            <div class="flex-shrink-0">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-danger add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Add Program
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-body">
                        <div class="mb-4 table-responsive">
                            <table id="example" class="table nowrap align-middle dataTable" style="width:100%">
                                <thead class="table-light text-muted">
                                <tr>
                                    <th scope="col" style="width: 40px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="sort" data-sort="id">ID</th>
                                    <th class="sort" data-sort="formation_id">Formation ID</th>
                                    <th class="sort" data-sort="name_of_the_formation">Name of the Formation</th>
                                    <th class="sort" data-sort="link_to_its_webpage">Link to Webpage</th>
                                    <th class="sort" data-sort="region">Region</th>
                                    <th class="sort" data-sort="schooling_cost">Schooling Cost</th>
                                    <th class="sort" data-sort="length_of_the_formation">Length of the Formation</th>
                                    <th class="sort" data-sort="status">Status</th>
                                    <th class="sort" data-sort="access_rate">Access Rate</th>
                                    <th class="sort" data-sort="type_of_formation">Type of Formation</th>
                                    <th class="sort" data-sort="town">Town</th>
                                    <th class="sort" data-sort="schooling_modalities">Schooling Modalities</th>
                                    <th class="sort" data-sort="schooling_pursuit">Schooling Pursuit</th>
                                    <th class="sort" data-sort="description_of_the_formation">Description of the Formation</th>
                                    <th class="sort" data-sort="number_of_students">Number of Students</th>
                                    <th class="sort" data-sort="keyword_option_one">Keyword Option One</th>
                                    <th class="sort" data-sort="keyword_secondary_one">Keyword Secondary One</th>
                                    <th class="sort" data-sort="keyword_main_one">Keyword Main One</th>
                                    <th class="sort" data-sort="is_fav">Is Favorite</th>
                                    <th class="sort" data-sort="created_at">Created At</th>
                                    <th class="sort" data-sort="updated_at">Updated At</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for adding programs -->
        <div class="modal fade zoomIn static" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header p-3 bg-info-subtle">
                        <h5 class="modal-title" id="exampleModalLabel">Add Programs List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                    </div>
                    <form class="tablelist-form" autocomplete="off" action="{{ route('program.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="tasksId" />
                            <div class="row g-3">
                                <div class="col-lg-12">
{{--                                    <form action="{{ route('program.store') }}" method="POST" enctype="multipart/form-data">--}}
{{--                                        @csrf--}}
                                        <div class="form-group">
                                            <label for="csv_file">Upload CSV File</label>
                                            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
                                        </div>
{{--                                    </form>--}}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success" id="add-btn">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
@endsection

@section('custom-script')
    <script>
        $(function() {
            let URL = "{{ route('get-program') }}";

            var t = $('.dataTable').DataTable({
                processing: true,
                serverSide: true,
                order: [[1, 'desc']],
                bDestroy: true,
                ajax: {
                    "type": "GET",
                    "url": URL
                },
                columns: [
                    { data: 'checkbox', orderable: false },
                    { data: 'id' },
                    { data: 'formation_id' },
                    { data: 'name_of_the_formation' },
                    { data: 'link_to_its_webpage' },
                    { data: 'region' },
                    { data: 'schooling_cost' },
                    { data: 'length_of_the_formation' },
                    { data: 'status' },
                    { data: 'access_rate' },
                    { data: 'type_of_formation' },
                    { data: 'town' },
                    { data: 'schooling_modalities' },
                    { data: 'schooling_pursuit' },
                    { data: 'description_of_the_formation' },
                    { data: 'number_of_students' },
                    { data: 'keyword_option_one' },
                    { data: 'keyword_secondary_one' },
                    { data: 'keyword_main_one' },
                    { data: 'is_fav' },
                    { data: 'created_at' },
                    { data: 'updated_at' }
                ],
            });
        });

        function selectRange() {
            $('.dataTable').DataTable().ajax.reload();
        }



        // $("#deleteCamp").on('show.bs.modal', function(event) {
        //     var triggerLink = $(event.relatedTarget);
        //     var url = triggerLink.data("url");
        //     $("#deleteCampForm").attr('action', url);
        // });
    </script>

@endsection
