@extends('layouts.master')

@section('title')
    Computer Services
@endsection

@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Computer Services</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#serviceAdd" data-toggle="modal">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Add Service
        </a>
    </div>
    @include('admin.partial.error')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Computer Services</h6>
            <a href="{{ url('/admin/cservice_trash') }}" class="position-absolute btn btn-sm btn-danger" style="right: 20px;top: 10px;">Service Trash</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered user" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>
                            <div class="checkbox d-inline">
                                <label style="font-size: 1.5em;margin-bottom: -13px;">
                                    <input type="checkbox" class="mi_select_all">
                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                </label>
                            </div>
                            <button class="btn btn-danger btn-sm mi_submit_delete" style="display: none;">
                                <i class="fa fa-trash-alt" style="font-size: 1rem;"></i>
                            </button>
                        </th>
                        <th>Picture</th>
                        <th>Title</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($services)>0)
                        @foreach($services as $service)
                            <tr>
                                <td>
                                    <div class="checkbox">
                                        <label style="font-size: 1.5em">
                                            <input type="checkbox" class="mi_select_service" value="{{ $service->id }}">
                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{ asset('img/'.$service->service_image) }}" class="img-thumbnail img-fluid" style="max-width: 70px;">
                                </td>
                                <td>{{ $service->service_name }}</td>
                                <td>{{ $service->service_duration }} Day</td>
                                <td>{{ $service->service_price }} Tk</td>
                                <td class="text-center">
                                    <label class="switch">
                                        <input type="checkbox" class="primary service_status" {{ ($service->status == 1)?'checked':'' }} value="{{ $service->id }}">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-circle btn-sm mi_service_edit" title="Edit" value="{{ $service->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-circle btn-sm mi_delete_service" title="Delete" value="{{ $service->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Services Found</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="serviceAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('cservice_add') }}" style="width: 100%;" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Service Name</label>
                            <input type="text" class="form-control" name="cname">
                        </div>
                        <div class="form-group">
                            <label>Service Price</label>
                            <input type="number" class="form-control" name="cprice">
                        </div>
                        <div class="form-group">
                            <label>Service Duration</label>
                            <input type="number" class="form-control" name="cduration">
                        </div>
                        <div class="form-group">
                            <label>Service Description</label>
                            <textarea class="form-control" type="text" name="cdescription" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Service Image</label>
                            <input type="file" class="form-control" name="uimage">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save service</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="serviceUpdate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('cservice_update') }}" style="width: 100%;" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input name="cid" type="hidden">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Service Name</label>
                            <input type="text" class="form-control" name="cname">
                        </div>
                        <div class="form-group">
                            <label>Service Price</label>
                            <input type="number" class="form-control" name="cprice">
                        </div>
                        <div class="form-group">
                            <label>Service Duration</label>
                            <input type="number" class="form-control" name="cduration">
                        </div>
                        <div class="form-group">
                            <label>Service Description</label>
                            <textarea class="form-control" type="text" name="cdescription" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update service</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
    <script>
        $('body').on('change', '.service_status', function (e) {
            e.preventDefault();
            var id = $(this).val();
            if (this.checked == true){
                var status = 1;
            }else{
                var status = 2;
            }
            $.ajax({
                method: "POST",
                url: "{{ url('/admin/cservice_status') }}/",
                data: {
                    service: id,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    console.log(data);
                },
                error: function () {
                    console.log('Ajax not working')
                }
            });
        });

        $('body').on('click', '.mi_service_edit', function (e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                method: "GET",
                url: "{{ url('/admin/cservice') }}/"+id,
                success: function (data) {
                    var res = JSON.parse(data);
                    $('#serviceUpdate input[name=cid]').val(res.id);
                    $('#serviceUpdate input[name=cname]').val(res.name);
                    $('#serviceUpdate input[name=cprice]').val(res.price);
                    $('#serviceUpdate input[name=cduration]').val(res.duration);
                    $('#serviceUpdate textarea[name=cdescription]').val(res.description);
                    $('#serviceUpdate').modal('show');
                },
                error: function () {
                    console.log('Ajax not working')
                }
            });
        });

        $('body').on('click', '.mi_delete_service', function (e) {
            e.preventDefault();
            var id = $(this).val();
            if(confirm('Do you really want to delete this service?')){
                window.location.href = "{{ url('/admin/cservice_delete') }}/"+id;
            }else{
                return;
            }
        });


        $('body').on('change', '.mi_select_all', function (e) {
            e.preventDefault();
            if (this.checked == true){
                $('.mi_select_service').attr('checked', true);
                $('.mi_submit_delete').show();
            }else{
                $('.mi_select_service').attr('checked', false);
                $('.mi_submit_delete').hide();
            }
        });


        $('body').on('click', '.mi_submit_delete', function (e) {
            e.preventDefault();

            if (confirm('Do you really want to delete the selected users?')){
                var ids = [];
                $('.mi_select_service:checked').each(function () {
                    ids.push($(this).val());
                });

                window.location.href = "{{ url('/admin/cservice_delete') }}/"+ids.join(',');
            }else{
                return;
            }
        });
    </script>
@endsection
