@extends('layouts.front')

@section('title')
    Mi Bookings - All My Bookings
@endsection

@section('styles')

@endsection



@section('content')

    <main id="main" style="margin-top: 7%;">

        <!-- ======= Services Section ======= -->
        <section id="services" class="services section-bg" style="padding-bottom: 4%;border-bottom: 1px solid #e3e3e3;">
            <div class="container">

                <header class="section-header">
                    <h3>{{ Auth::user()->name }} Bookings</h3>
                    @include('admin.partial.error')
                </header>

                <div class="row">
                    <div class="col-12">
                        <div class="show_message">

                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Info</th>
                                    <th>User Info</th>
                                    <th>Extra Note</th>
                                    <th>Receive Date</th>
                                    <th class="text-center">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            {{--@dd($bookings)--}}
                            @if(count($bookings)>0)
                                @foreach($bookings as $key => $book)
                                    {{--@dd();--}}
                                    <tr>
                                        <td>
                                            {{ $key+1 }}
                                        </td>
                                        <td>
                                            <p class="mb-0"><strong>{{ $book->computer->service_name }}</strong></p>
                                            <p class="mb-0">{{ $book->computer->service_price }} Tk &nbsp;|&nbsp; {{ $book->computer->service_duration }} day</p>
                                        </td>
                                        <td>
                                            <p class="mb-0"><strong>{{ $book->user->name }}</strong></p>
                                            <p class="mb-0">{{ $book->user->email }}</p>
                                            <p class="mb-0">{{ $book->user->phone }}</p>
                                            <p class="mb-0">{{ $book->user->address }}</p>
                                        </td>
                                        <td>{{ $book->extra_note }}</td>
                                        <td>{{ $book->recieve_date }}</td>
                                        <td class="text-center">
                                            @php
                                                if ($book->status == 1){
                                                    echo 'Pending';
                                                }elseif ($book->status == 2){
                                                    echo 'Accepted';
                                                }elseif ($book->status == 3){
                                                    echo 'Processing';
                                                }elseif ($book->status == 4){
                                                    echo 'Completed';
                                                }elseif ($book->status == 5){
                                                    echo 'Cancelled';
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                if ($book->status == 1){
                                                    echo '<button class="btn btn-sm btn-danger mi-cancel-booking" type="button" value="'.$book->id.'">Cancel</button>';
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No Bookings found!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section><!-- End Services Section -->

    </main><!-- End #main -->


@endsection



@section('scripts')
    <script>
        $('body').on('click', '.mi-cancel-booking', function (e) {
            e.preventDefault();
            if (confirm('Do you really want to cancel the booking?')){
                $.ajax({
                    method: "GET",
                    url: "{{ url('/api/cancel_booking') }}/"+$(this).val(),
                    success: function (data) {
                        var response = JSON.parse(data);
                        if (response.error){
                            var mmsg = '<span class="alert alert-danger">'+response.msg+'</span>';
                        }else{
                            var mmsg = '<span class="alert alert-primary">'+response.msg+'</span>';
                        }

                        $('.show_message').html(mmsg);
                        setTimeout(function () {
                            window.location.href = '{{ route('bookings_user') }}';
                        }, 1500);
                    },
                    error: function () {
                        console.log('Ajax not working')
                    }
                });
            }else{
                return;
            }
        })
    </script>
@endsection