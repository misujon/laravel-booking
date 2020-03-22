@extends('layouts.front')

@section('title')
    Mi Bookings
@endsection

@section('styles')

@endsection



@section('content')
    @include('front.banner')

    <main id="main">

        <!-- ======= Services Section ======= -->
        <section id="services" class="services section-bg">
            <div class="container">

                <header class="section-header">
                    <h3>Our Services</h3>
                    <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus.</p>
                </header>

                <div class="row" id="mi_services">

                </div>

            </div>
        </section><!-- End Services Section -->

    </main><!-- End #main -->


    <!-- Modal -->
    <div class="modal fade" id="view_service" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content p-4">
                <div class="row mi_view_service_single p-0 m-0">
                    <div class="col-md-5">
                        <img src="" style="width: 100%;" class="img-fluid img-thumbnail">
                    </div>
                    <div class="col-md-7">
                        <div class="show_message">

                        </div>
                        <table class="table table-bordered mi_ser_details">
                            <tr>
                                <th>Name</th>
                                <td class="ser_name"></td>
                            </tr>

                            <tr>
                                <th>Price</th>
                                <td class="ser_price"></td>
                            </tr>

                            <tr>
                                <th>Duration</th>
                                <td class="ser_duration"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="ser_details"></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @guest
                                    <a href="{{ route('login') }}" class="btn btn-primary">Book This Service</a>
                                    @else
                                        <button type="button" class="btn btn-primary ser_book_btn">Book This Service</button>
                                        @endguest
                                </td>
                            </tr>
                        </table>
                        <form class="mi_ser_booking" id="mi_ser_booking" style="display: none;">
                            {{ csrf_field() }}
                            @guest

                            @else
                                <input type="hidden" name="book_user" value="{{ Auth::user()->id }}">
                            @endguest
                            <input type="hidden" name="book_service">
                            <h3>Book this service</h3>
                            <div class="form-group">
                                <label>Choose When you can want to start</label>
                                <input type="date" name="book_sr_date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>Note</label>
                                <textarea type="date" name="book_sr_note" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Complete Booking</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection



@section('scripts')
    <script>
        function services_get() {
            $.ajax({
                method: "GET",
                url: "{{ url('/api/services_all') }}",
                success: function (data) {
                    var html = ``;

                    for (var i=0;i<data.length;i++){

                        html+= `<div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
                                    <a href="#" class="mi_services" mi_val="`+data[i]['id']+`">
                                    <div class="box">
                                        <div class="icon">
                                            <img src="{{ asset("img") }}/`+data[i]['service_image']+`" style="width: 100%;">
                                        </div>
                                        <p class="mb-1">Price: `+data[i]['service_price']+`Tk | Duration: `+data[i]['service_duration']+` days</p>
                                        <h4 class="title">
                                            `+data[i]['service_name']+`
                                        </h4>
                                        <p class="description text-dark">
                                            `+data[i]['service_description'].substr(0, 110)+`...
                                        </p>
                                    </div>
                                    </a>
                                </div>`;
                    }

                    $('#mi_services').html(html);
                },
                error: function () {
                    console.log('Ajax not working')
                }
            });
        }
        services_get();


        $('body').on('click', '.mi_services', function (e) {
            e.preventDefault();
            var sr = parseInt($(this).attr('mi_val'));

            $.ajax({
                method: "GET",
                url: "{{ url('/api/services') }}/"+sr,
                success: function (data) {
                    $('input[name=book_service]').val(sr);
                    $('.mi_ser_booking').hide();
                    $('.mi_ser_details').show();

                    $('.mi_view_service_single img').attr('src', '{{ asset('img') }}/'+data['service_image']);
                    $('.mi_view_service_single .ser_name').text(data['service_name']);
                    $('.mi_view_service_single .ser_price').text(data['service_price']+' Tk');
                    $('.mi_view_service_single .ser_duration').text(data['service_duration']+' days');
                    $('.mi_view_service_single .ser_details').text(data['service_description']);
                    $('.mi_view_service_single .ser_book_btn').val(data['id']);

                    $('#view_service').modal('show');
                },
                error: function () {
                    console.log('Ajax not working')
                }
            });
        });

        $('body').on('click', '.ser_book_btn', function (e) {
            e.preventDefault();
            $('.mi_ser_details').hide();
            $('.mi_ser_booking').fadeIn('slow');
        });

        $('#mi_ser_booking').on('submit', function (e) {
            e.preventDefault();
            var fdata = $(this).serialize();

            $.ajax({
                method: "POST",
                url: "{{ url('/api/service_booking') }}",
                data: fdata,
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
        })
    </script>
@endsection