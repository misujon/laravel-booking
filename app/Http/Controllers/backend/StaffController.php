<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Mi_admin;
use App\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
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
        $staffs = Mi_admin::where('status', '=', 1)->get();
        return view('admin.staff', compact('staffs'));
    }


    public function staff_add(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uemail'=>'required|string|email',
            'uphone'=>'required|string',
            'upass'=>'required|string',
            'uRole'=>'required|string'
        ]);

        $user = Mi_admin::where('admin_email', $request->uemail)->get();
        if (count($user)>0){
            session()->flash('error', 'Staff already exists!');
        }else{
            $add = new Mi_admin;
            $add->admin_name = $request->uname;
            $add->admin_email = $request->uemail;
            $add->password = Hash::make($request->upass);
            $add->admin_phone = $request->uphone;
            $add->role = $request->uRole;

            if ($add->save()){
                session()->flash('success', 'New staff added successfully!');
            }else{
                session()->flash('error', 'Error to add staff!');
            }

        }

        return redirect('/admin/staff');
    }


    public function showByID($id)
    {
        $user = Mi_admin::where('id', $id)->first();
        $data = [
            'id' => $user->id,
            'name' => $user->admin_name,
            'email' => $user->admin_email,
            'phone' => $user->admin_phone,
            'role' => $user->role,
        ];
        return json_encode($data);
    }


    public function staff_update(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uemail'=>'required|string|email',
            'uphone'=>'required|string',
            'uRole'=>'required|integer'
        ]);

        $user = Mi_admin::where('id', $request->uid)->first();
        $user->admin_name = $request->uname;
        $user->admin_email = $request->uemail;
        $user->admin_phone = $request->uphone;
        $user->role = $request->uRole;

        if ($user->save()){
            session()->flash('success', 'Staff Information Updated!');
        }else{
            session()->flash('error', 'Error to update staff information!!');
        }
        return redirect('/admin/staff');
    }


    public function staffStatusUpdate(Request $request){

        $user = Mi_admin::where('id', $request->user)->first();
        $user->status = $request->status;
        if ($user->save()){
            return 'Status Updated';
        }else{
            return 'Error to update status';
        }

    }



    public function staff_delete(Request $request){

        if (count(explode(',', $request->id)) <= 1){
            $user = Mi_admin::where('id', $request->id)->first();
            $user->status = 3;
            if ($user->save()){
                session()->flash('success', 'Staff Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete staff!');
            }
        }else{
            foreach (explode(',', $request->id) as $isd){
                $user = Mi_admin::where('id', $isd)->first();
                $user->status = 3;
                $user->save();
            }
            if ($user->save()){
                session()->flash('success', 'Staff Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete staff!');
            }
        }

        return redirect('/admin/staff');
    }



    public function usersTrash()
    {
        $users = Mi_admin::where('status', 3)->get();
        return view('admin.staff_trash', compact('users'));
    }


    public function staff_restore(Request $request){

        foreach (explode(',', $request->id) as $isd){
            $user = Mi_admin::where('id', $isd)->first();
            $user->status = 1;
            $user->save();
        }
        if ($user->save()){
            session()->flash('success', 'Staffs Restored Successfully!');
        }else{
            session()->flash('error', 'Error to restore staff!');
        }

        return redirect('/admin/staff');
    }
}
