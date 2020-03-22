@extends('layouts.master')

@section('title')
    Generated Report
@endsection

@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Report</h1>
    </div>
    @include('admin.partial.error')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generated Booking Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered user" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Service Info</th>
                        <th>User Info</th>
                        <th>Extra Note</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($report_data['bookings'])>0)
                        @php
                            $total = 0;
                            $qty = 0;
                        @endphp
                        @foreach($report_data['bookings'] as $book)
                            <tr>
                                <td>
                                    @php( $user = \App\Computers::find($book->service_id))
                                    @php($total+=$user->service_price)
                                    @php($qty+=1)
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
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Booking Found</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Booking</th>
                            <td>{{ $qty }}</td>
                            <th>Total Amount</th>
                            <td>{{ $total }} Tk</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generated Users Report</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered user" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($report_data['users'])>0)
                        @php($uto = 0)
                        @foreach($report_data['users'] as $user)
                            @php($uto+=1)
                            <tr>
                                <td>
                                    {{ $user->name }}
                                    <br><small>Signup: {{ $user->created_at }}</small>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->address }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Users Found</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Signed up Users: </th>
                            <td colspan="3">{{ $uto }}</td>
                        </tr>
                    </tfoot>
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
