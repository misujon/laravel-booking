<?php

namespace App\Http\Controllers\backend;

use App\ServiceBooking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('admin_logout');
    }

    public function index()
    {
        $bookings = ServiceBooking::where('status', '!=', 6)->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function bookingTrash()
    {
        $bookings = ServiceBooking::where('status', 6)->get();
        return view('admin.booking_trash', compact('bookings'));
    }


    public function bookingStatusUpdate(Request $request){
        $user = ServiceBooking::where('id', $request->booking)->first();
        $user->status = $request->status;
        if ($user->save()){
            return 'Status Updated';
        }else{
            return 'Error to update status';
        }
    }



    public function booking_delete(Request $request){

        if (count(explode(',', $request->id)) <= 1){
            $user = ServiceBooking::where('id', $request->id)->first();
            $user->status = 6;
            if ($user->save()){
                session()->flash('success', 'Booking Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete Booking!');
            }
        }else{
            foreach (explode(',', $request->id) as $isd){
                $user = ServiceBooking::where('id', $isd)->first();
                $user->status = 6;
                $user->save();
            }
            if ($user->save()){
                session()->flash('success', 'Booking Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete Booking!');
            }
        }

        return redirect('/admin/booking');
    }



    public function book_restore(Request $request){

        foreach (explode(',', $request->id) as $isd){
            $user = ServiceBooking::where('id', $isd)->first();
            $user->status = 1;
            $user->save();
        }
        if ($user->save()){
            session()->flash('success', 'Bookings Restored Successfully!');
        }else{
            session()->flash('error', 'Error to restore Bookings!');
        }

        return redirect('/admin/booking');
    }


}
