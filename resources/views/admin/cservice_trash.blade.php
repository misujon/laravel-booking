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
        <h1 class="h3 mb-0 text-gray-800">Service Trash</h1>
    </div>
    @include('admin.partial.error')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Trash</h6>
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
                            <button class="btn btn-danger btn-sm mi_submit_restore" style="display: none;">
                                Restore
                            </button>
                        </th>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($services)>0)
                            @foreach($services as $service)
                                <tr>
                                    <td>
                                        <div class="checkbox">
                                            <label style="font-size: 1.5em">
                                                <input type="checkbox" class="mi_select_user" value="{{ $service->id }}">
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
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No Service Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
    <script>

        $('body').on('change', '.mi_select_all', function (e) {
            e.preventDefault();
            if (this.checked == true){
                $('.mi_select_user').attr('checked', true);
                $('.mi_submit_restore').show();
            }else{
                $('.mi_select_user').attr('checked', false);
                $('.mi_submit_restore').hide();
            }
        });


        $('body').on('click', '.mi_submit_restore', function (e) {
            e.preventDefault();

            var ids = [];
            $('.mi_select_user:checked').each(function () {
                ids.push($(this).val());
            });

            window.location.href = "{{ url('/admin/cservice_restore') }}/"+ids.join(',');
        });
    </script>
@endsection
