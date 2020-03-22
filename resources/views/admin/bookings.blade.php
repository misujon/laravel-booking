@extends('layouts.master')

@section('title')
    Services Booking
@endsection

@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bookings</h1>
    </div>
    @include('admin.partial.error')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Bookings</h6>
            <a href="{{ url('/admin/booking_trash') }}" class="position-absolute btn btn-sm btn-danger" style="right: 20px;top: 10px;">Booking Trash</a>
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
                        <th>Service Info</th>
                        <th>User Info</th>
                        <th>Extra Note</th>
                        <th>Date</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($bookings)>0)
                        @foreach($bookings as $book)
                            <tr>
                                <td>
                                    <div class="checkbox">
                                        <label style="font-size: 1.5em">
                                            <input type="checkbox" class="mi_select_book" value="{{ $book->id }}">
                                            <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    @php( $user = \App\Computers::find($book->service_id))
                                    <p><strong>{{ $user->service_name }}</strong></p>
                                    <p>{{ $user->service_price }} Tk &nbsp;|&nbsp; {{ $user->service_duration }} day</p>
                                </td>
                                <td>
                                    @php( $user = \App\User::find($book->user_id))
                                    <p><strong>{{ $user->name }}</strong></p>
                                    <p>{{ $user->email }}</p>
                                    <p>{{ $user->phone }}</p>
                                    <p>{{ $user->address }}</p>
                                </td>
                                <td>{{ $book->extra_note }}</td>
                                <td>
                                    Booking: {{ $book->created_at }}
                                    <br>
                                    Recieve: {{ $book->recieve_date }}
                                </td>
                                <td class="text-center">
                                    <div class="form-group">
                                        <select class="form-control mi_booking_status" mi_id="{{ $book->id }}">
                                            <option value="1" {{ ($book->status == 1)?'selected':'' }}>Pending</option>
                                            <option value="2" {{ ($book->status == 2)?'selected':'' }}>Accept</option>
                                            <option value="3" {{ ($book->status == 3)?'selected':'' }}>Processing</option>
                                            <option value="4" {{ ($book->status == 4)?'selected':'' }}>Completed</option>
                                            <option value="5" {{ ($book->status == 5)?'selected':'' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-circle btn-sm mi_delete_booking" title="Delete" value="{{ $book->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Booking Found</td>
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
        $('body').on('change', '.mi_booking_status', function (e) {
            e.preventDefault();
            var id = $(this).attr('mi_id');
            var status = $(this).val();

            $.ajax({
                method: "POST",
                url: "{{ url('/admin/booking_status') }}/",
                data: {
                    booking: id,
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


        $('body').on('click', '.mi_delete_booking', function (e) {
            e.preventDefault();
            var id = $(this).val();
            if(confirm('Do you really want to delete this service?')){
                window.location.href = "{{ url('/admin/booking_delete') }}/"+id;
            }else{
                return;
            }
        });


        $('body').on('change', '.mi_select_all', function (e) {
            e.preventDefault();
            if (this.checked == true){
                $('.mi_select_book').attr('checked', true);
                $('.mi_submit_delete').show();
            }else{
                $('.mi_select_book').attr('checked', false);
                $('.mi_submit_delete').hide();
            }
        });


        $('body').on('click', '.mi_submit_delete', function (e) {
            e.preventDefault();

            if (confirm('Do you really want to delete the selected users?')){
                var ids = [];
                $('.mi_select_book:checked').each(function () {
                    ids.push($(this).val());
                });

                window.location.href = "{{ url('/admin/booking_delete') }}/"+ids.join(',');
            }else{
                return;
            }
        });
    </script>
@endsection
