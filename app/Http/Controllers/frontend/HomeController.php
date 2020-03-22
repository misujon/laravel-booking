<?php

namespace App\Http\Controllers\frontend;

use App\Computers;
use App\Http\Controllers\Controller;
use App\ServiceBooking;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function showServices(){
        $services = Computers::where('status', 1)->orderBy('id', 'desc')->take(6)->get();
        return response()->json($services);
    }


    public function allServices(){
        $services = Computers::where('status', 1)->orderBy('id', 'desc')->get();
        return response()->json($services);
    }

    public function showServiceSingle(Request $request){
        $services = Computers::where('id', $request->id)->where('status', 1)->first();
        return response()->json($services);
    }


    public function bookService(Request $request){
        $this->validate($request, [
            'book_user'=>'required|integer',
            'book_service'=>'required|integer',
            'book_sr_date'=>'required|date',
            'book_sr_note'=>'required|string'
        ]);

        $book = ServiceBooking::create([
            'user_id' => $request->book_user,
            'service_id' => $request->book_service,
            'recieve_date' => $request->book_sr_date,
            'extra_note' => $request->book_sr_note
        ]);

        if ($book){
            return json_encode(array('status'=>'success', 'msg'=>'Successfully Booked!'));
        }else{
            return json_encode(array('status'=>'error', 'msg'=>'Error to book service!'));
        }
    }


    public function cancelBooking($id){

        $book = ServiceBooking::where('id', $id)->first();
        $book->status = 5;

        if ($book->save()){
            return json_encode(array('status'=>'success', 'msg'=>'Booking Cancelled Successfully!'));
        }else{
            return json_encode(array('status'=>'error', 'msg'=>'Error to cancel booking!'));
        }
    }

}
