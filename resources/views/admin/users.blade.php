@extends('layouts.master')

@section('title')
    Users
@endsection

@section('styles')
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#userAdd" data-toggle="modal">
            <i class="fas fa-user-plus fa-sm text-white-50"></i> Add User
        </a>
    </div>
    @include('admin.partial.error')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Users Table</h6>
            <a href="{{ url('/admin/users_trash') }}" class="position-absolute btn btn-sm btn-danger" style="right: 20px;top: 10px;">User Trash</a>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th class="text-center">Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($users)>0)
                            @foreach($users as $user)
                                @if($user['status'] != 3)
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label style="font-size: 1.5em">
                                                    <input type="checkbox" class="mi_select_user" value="{{ $user->id }}">
                                                    <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $user->name }}
                                            <br><small>Signup: {{ $user->created_at }}</small>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td class="text-center">
{{--                                            <input type="checkbox" {{ ($user->status == 1)?'checked':'' }} data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">--}}
                                            <label class="switch">
                                                <input type="checkbox" class="primary user_status" {{ ($user->status == 1)?'checked':'' }} value="{{ $user->id }}">
                                                <span class="slider round"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-circle btn-sm mi_user_edit" title="Edit" value="{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-circle btn-sm mi_delete_user" title="Delete" value="{{ $user->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No Users Found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="userAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('auser_add') }}" style="width: 100%;" method="post">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" name="uname">
                        </div>
                        <div class="form-group">
                            <label>User Email</label>
                            <input type="email" class="form-control" name="uemail">
                        </div>
                        <div class="form-group">
                            <label>User Phone</label>
                            <input type="tel" class="form-control" name="uphone">
                        </div>
                        <div class="form-group">
                            <label>User Password</label>
                            <input type="password" class="form-control" name="upass">
                        </div>
                        <div class="form-group">
                            <label>User Address</label>
                            <input type="text" class="form-control" name="uaddress">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="userEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('auser_update') }}" style="width: 100%;" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="uid">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" class="form-control" name="uname">
                        </div>
                        <div class="form-group">
                            <label>User Email</label>
                            <input type="email" class="form-control" name="uemail">
                        </div>
                        <div class="form-group">
                            <label>User Phone</label>
                            <input type="tel" class="form-control" name="uphone">
                        </div>
                        <div class="form-group">
                            <label>User Address</label>
                            <input type="text" class="form-control" name="uaddress">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update user</button>
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
        $('body').on('click', '.mi_user_edit', function (e) {
            e.preventDefault();
            var id = $(this).val();
            $.ajax({
                method: "GET",
                url: "{{ url('/admin/auser') }}/"+id,
                success: function (data) {
                    var res = JSON.parse(data);
                    $('#userEdit input[name=uid]').val(res.id);
                    $('#userEdit input[name=uname]').val(res.name);
                    $('#userEdit input[name=uemail]').val(res.email);
                    $('#userEdit input[name=uphone]').val(res.phone);
                    $('#userEdit input[name=uaddress]').val(res.address);
                    $('#userEdit').modal('show');
                },
                error: function () {
                    console.log('Ajax not working')
                }
            });
        });

        $('body').on('click', '.mi_delete_user', function (e) {
            e.preventDefault();
            var id = $(this).val();
            if(confirm('Do you really want to delete this user?')){
                window.location.href = "{{ url('/admin/auser_delete') }}/"+id;
            }else{
                return;
            }
        });

        $('body').on('change', '.user_status', function (e) {
            e.preventDefault();
            var id = $(this).val();
            if (this.checked == true){
                var status = 1;
            }else{
                var status = 2;
            }
            $.ajax({
                method: "POST",
                url: "{{ url('/admin/auser_status') }}/",
                data: {
                    user: id,
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


        $('body').on('change', '.mi_select_all', function (e) {
            e.preventDefault();
            if (this.checked == true){
                $('.mi_select_user').attr('checked', true);
                $('.mi_submit_delete').show();
            }else{
                $('.mi_select_user').attr('checked', false);
                $('.mi_submit_delete').hide();
            }
        });


        $('body').on('click', '.mi_submit_delete', function (e) {
            e.preventDefault();

            if (confirm('Do you really want to delete the selected users?')){
                var ids = [];
                $('.mi_select_user:checked').each(function () {
                    ids.push($(this).val());
                });

                window.location.href = "{{ url('/admin/auser_delete') }}/"+ids.join(',');
            }else{
                return;
            }
        });
    </script>
@endsection
