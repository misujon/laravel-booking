@extends('layouts.front')

@section('title')
    Mi Bookings - Profile
@endsection

@section('styles')

@endsection



@section('content')

    <main id="main" style="margin-top: 7%;">

        <!-- ======= Services Section ======= -->
        <section id="services" class="services section-bg" style="padding-bottom: 4%;border-bottom: 1px solid #e3e3e3;">
            <div class="container">

                <header class="section-header">
                    <h3>Welcome {{ Auth::user()->name }}</h3>
                    @include('admin.partial.error')
                </header>

                <div class="row">
                    <div class="col-md-4 col-12">
                        <div class="card" style="width: 100%;">
                            <img src="{{ (Auth::user()->user_image != null)?asset('img').'/'.Auth::user()->user_image:asset('img/no-image.png') }}" class="card-img-top mi_user_profile_pic" alt="...">
                            <div class="card-body">
                                <small class="card-text mb-0">Upload different image here...</small>
                                <div class="form-group">
                                    <input class="form-control" type="file" style="height: 44px;" id="user_image" uIds="{{ Auth::user()->id }}">
                                </div>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">{{ Auth::user()->email }}</li>
                                <li class="list-group-item">{{ Auth::user()->phone }}</li>
                                <li class="list-group-item">{{ Auth::user()->address }}</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-8 col-12">
                        <div class="card">
                            <div class="card-header">
                                Update your information here
                            </div>
                            <div class="card-body">
                                <form action="{{ route('update_profile') }}" method="post" style="width: 100%;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="uid" value="{{ Auth::user()->id }}">
                                    <div class="form-group">
                                        <label for="">Your name</label>
                                        <input type="text" name="uname" value="{{ Auth::user()->name }}" class="form-control" placeholder="Enter your name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Your email</label>
                                        <input type="email" value="{{ Auth::user()->email }}" class="form-control" placeholder="Enter your email" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Your phone</label>
                                        <input type="tel" name="uphone" value="{{ Auth::user()->phone }}" class="form-control" placeholder="Enter your phone" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Your address</label>
                                        <textarea type="text" name="uaddress" class="form-control" placeholder="Enter your name">{{ Auth::user()->address }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary btn-lg float-right" value="Update Profile">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section><!-- End Services Section -->

    </main><!-- End #main -->


@endsection



@section('scripts')
    <script>
        $('#user_image').on('change', function (e) {
            e.preventDefault();
            var id  = $(this).attr('uIds');
            var img  = $(this).prop('files')[0];

            var form = new FormData();
            form.append('profile_id', id);
            form.append('profile_img', img);
            form.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('profile_picture') }}",
                method: "POST",
                enctype: 'multipart/form-data',
                data: form,
                contentType: false,
                cache: false,
                processData:false,
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.status == 'success'){
                        $('.mi_user_profile_pic').attr('src', response.url);
                    }else{
                        console.log("Error to upload picture");
                    }
                },
                error: function () {
                    console.log("Profile image upload ajax not working");
                }
            });
        });
    </script>
@endsection