<?php

namespace App\Http\Controllers\frontend;

use App\ServiceBooking;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('/login');
    }

    public function updateUser(Request $request){
        $this->validate($request, [
            'uname'=>'required|string',
            'uphone'=>'required|string'
        ]);

        $user = User::where('id', $request->uid)->first();

        if (count(json_decode($user, true))>0){

            $user->name = $request->uname;
            $user->phone = $request->uphone;
            $user->address = $request->uaddress;

            if ($user->save()){
                session()->flash('success', 'Profile Updated Successfully!');
            }else{
                session()->flash('error', 'Error to update profile!');
            }
        }else{
            session()->flash('error', 'Undefined User!');
        }
        return redirect('/profile');
    }


    public function bookingsUser(){

        $bookings = ServiceBooking::where('user_id', '=', Auth::user()->id)->where('status', '!=', '6')->get();
       // return $bookings;
        return view('front.booking', compact('bookings'));
    }


    public function profilePicture(Request $request){
        $this->validate($request, [
            'profile_id'=>'required|integer',
            'profile_img'=>'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);
        $imageName = time().'.'.$request->profile_img->extension();
        $request->profile_img->move(public_path('img'), $imageName);

        $user = User::where('id', $request->profile_id)->first();

        $user->user_image = $imageName;

        if ($user->save()){
            $msg = array('status'=>'success', 'msg'=>'Picture Uploaded', 'url'=>asset('img').'/'.$imageName);
        }else{
            $msg = array('status'=>'error', 'msg'=>'Error to upload profile picture');
        }

        echo json_encode($msg);
    }


}
