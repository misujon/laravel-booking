<?php

namespace App\Http\Controllers\backend;

use App\Computers;
use App\Http\Controllers\Controller;
use App\Mi_admin;
use App\ServiceBooking;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:admin')->except('admin_logout');
    }

    public function index()
    {
        $users = User::where('status', '!=', 3)->get();
        return view('admin.users', compact('users'));
    }


    public function usersTrash()
    {
        $users = User::where('status', 3)->get();
        return view('admin.users_trash', compact('users'));
    }

    public function showByID($id)
    {
        $user = User::where('id', $id)->first();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
        ];
        return json_encode($data);
    }

    public function auser_update(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uemail'=>'required|string|email',
            'uphone'=>'required|string'
        ]);

        $user = User::where('id', $request->uid)->first();
        $user->name = $request->uname;
        $user->email = $request->uemail;
        $user->phone = $request->uphone;
        $user->address = $request->uaddress;

        if ($user->save()){
            session()->flash('success', 'User Information Updated!');
        }else{
            session()->flash('error', 'Error to update user information!!');
        }
        return redirect('/admin/users');
    }

    public function auser_add(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uemail'=>'required|string|email',
            'uphone'=>'required|string',
            'upass'=>'required|string'
        ]);

        $user = User::where('email', $request->uemail)->get();
        if (count($user)>0){
            session()->flash('error', 'User already exists!');
        }else{
            $add = new User;
            $add->name = $request->uname;
            $add->email = $request->uemail;
            $add->password = Hash::make($request->upass);
            $add->phone = $request->uphone;
            $add->address = $request->uaddress;

            if ($add->save()){
                session()->flash('success', 'New user added successfully!');
            }else{
                session()->flash('error', 'Error to add user!');
            }

        }

        return redirect('/admin/users');
    }

    public function userStatusUpdate(Request $request){

        $user = User::where('id', $request->user)->first();
        $user->status = $request->status;
        if ($user->save()){
            return 'Status Updated';
        }else{
            return 'Error to update status';
        }

    }

    public function auser_delete(Request $request){

        if (count(explode(',', $request->id)) <= 1){
            $user = User::where('id', $request->id)->first();
            $user->status = 3;
            if ($user->save()){
                session()->flash('success', 'User Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete user!');
            }
        }else{
            foreach (explode(',', $request->id) as $isd){
                $user = User::where('id', $isd)->first();
                $user->status = 3;
                $user->save();
            }
            if ($user->save()){
                session()->flash('success', 'Users Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete user!');
            }
        }

        return redirect('/admin/users');
    }


    public function auser_restore(Request $request){

        foreach (explode(',', $request->id) as $isd){
            $user = User::where('id', $isd)->first();
            $user->status = 1;
            $user->save();
        }
        if ($user->save()){
            session()->flash('success', 'Users Restored Successfully!');
        }else{
            session()->flash('error', 'Error to restore user!');
        }

        return redirect('/admin/users');
    }


    public function profilePicture(Request $request){
        $this->validate($request, [
            'profile_id'=>'required|integer',
            'profile_img'=>'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $imageName = time().'.'.$request->profile_img->extension();
        $request->profile_img->move(public_path('img'), $imageName);

        $user = Mi_admin::where('id', $request->profile_id)->first();

        $user->picture = $imageName;

        if ($user->save()){
            $msg = array('status'=>'success', 'msg'=>'Picture Uploaded', 'url'=>asset('img').'/'.$imageName);
        }else{
            $msg = array('status'=>'error', 'msg'=>'Error to upload profile picture');
        }

        echo json_encode($msg);
    }


    public function updateUser(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uphone'=>'required|string'
        ]);

        $user = Mi_admin::where('id', $request->uid)->first();

        if (count(json_decode($user, true))>0){

            $user->admin_name = $request->uname;
            $user->admin_phone = $request->uphone;

            if (!empty($request->upass)){
                $user->password = Hash::make($request->upass);
            }

            if ($user->save()){
                session()->flash('success', 'Profile Updated Successfully!');
            }else{
                session()->flash('error', 'Error to update profile!');
            }
        }else{
            session()->flash('error', 'Undefined User!');
        }
        return redirect('/admin/profile');
    }


    public function dashboard()
    {
        $all_book = ServiceBooking::all()->count();
        $all_serv = Computers::all()->count();
        $all_user = User::all()->count();
        $all_staf = Mi_admin::all()->count();

        return view('admin.dashboard')->with('dashdata', [$all_book, $all_serv, $all_user, $all_staf]);
    }


    public function generate_report(Request $request){
        $this->validate($request, [
            'from'=>'required|date',
            'to'=>'required|date'
        ]);

        $bookings = ServiceBooking::whereBetween('created_at', [$request->from, $request->to])->get();
        $users = User::whereBetween('created_at', [$request->from, $request->to])->get();

        return view('admin.report')->with('report_data', ['bookings'=>$bookings, 'users'=>$users]);
    }
}
