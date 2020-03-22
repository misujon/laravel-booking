@extends('layouts.master')

@section('title')
    Admin - Profile
@endsection

@section('styles')

@endsection



@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ Auth::guard('admin')->user()->admin_name }}</h1>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 col-12">
                    <div class="card" style="width: 100%;">
                        <img src="{{ (Auth::guard('admin')->user()->picture != null)?asset('img').'/'.Auth::guard('admin')->user()->picture:asset('img/no-image.png') }}" class="card-img-top mi_user_profile_pic" alt="...">
                        <div class="card-body">
                            <small class="card-text mb-0">Upload different image here...</small>
                            <div class="form-group">
                                <input class="form-control" type="file" style="height: 44px;" id="user_image" uIds="{{ Auth::guard('admin')->user()->id }}">
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ Auth::guard('admin')->user()->admin_email }}</li>
                            <li class="list-group-item">{{ Auth::guard('admin')->user()->admin_phone }}</li>
                            <li class="list-group-item">{{ (Auth::guard('admin')->user()->role == 1)?'Admin':'Manager' }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-9 col-12">
                    <div class="card">
                        <div class="card-header">
                            Update your information here
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update_admin_profile') }}" method="post" style="width: 100%;">
                                {{ csrf_field() }}
                                <input type="hidden" name="uid" value="{{ Auth::guard('admin')->user()->id }}">
                                <div class="form-group">
                                    <label for="">Your name</label>
                                    <input type="text" name="uname" value="{{ Auth::guard('admin')->user()->admin_name }}" class="form-control" placeholder="Enter your name" required>
                                </div>

                                <div class="form-group">
                                    <label for="">Your email</label>
                                    <input type="email" value="{{ Auth::guard('admin')->user()->admin_email }}" class="form-control" placeholder="Enter your email" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="">Your phone</label>
                                    <input type="tel" name="uphone" value="{{ Auth::guard('admin')->user()->admin_phone }}" class="form-control" placeholder="Enter your phone" required>
                                </div>

                                <div class="form-group">
                                    <label for="">Set new password</label>
                                    <input type="password" name="upass" class="form-control" placeholder="Set new password">
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
    </div>


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
                url: "{{ route('admin_profile_picture') }}",
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
                        $('#userDropdown img').attr('src', response.url);
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