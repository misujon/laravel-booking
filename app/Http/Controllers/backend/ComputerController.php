<?php

namespace App\Http\Controllers\backend;

use App\Computers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComputerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin')->except('admin_logout');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Computers::where('status', '!=', 3)->get();
        return view('admin.computer', compact('services'));
    }


    public function cservice_add(Request $request){
        $this->validate($request, [
            'cname'=>'required|string',
            'cprice'=>'required|integer',
            'cduration'=>'required|integer',
            'cdescription'=>'string',
            'uimage'=>'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $user = Computers::where('service_name', $request->cname)->get();

        if (count($user)>0){
            session()->flash('error', 'Service already exists!');
        }else{
            $imageName = time().'.'.$request->uimage->extension();
            $request->uimage->move(public_path('img'), $imageName);

            $add = new Computers;
            $add->service_name = $request->cname;
            $add->service_description = $request->cdescription;
            $add->service_price = $request->cprice;
            $add->service_duration = $request->cduration;
            $add->service_image = $imageName;

            if ($add->save()){
                session()->flash('success', 'New service added successfully!');
            }else{
                session()->flash('error', 'Error to add service!');
            }

        }

        return redirect('/admin/computer');
    }


    public function cserviceStatusUpdate(Request $request){

        $user = Computers::where('id', $request->service)->first();
        $user->status = $request->status;
        if ($user->save()){
            return 'Status Updated';
        }else{
            return 'Error to update status';
        }

    }

    public function showByID($id)
    {
        $service = Computers::where('id', $id)->first();
        $data = [
            'id' => $service->id,
            'name' => $service->service_name,
            'price' => $service->service_price,
            'duration' => $service->service_duration,
            'description' => $service->service_description,
        ];
        return json_encode($data);
    }


    public function cservice_update(Request $request){
        $this->validate($request, [
            'cname'=>'required|string',
            'cprice'=>'required|integer',
            'cduration'=>'required|integer',
            'cdescription'=>'string'
        ]);

        $service = Computers::where('id', $request->cid)->first();
        $service->service_name = $request->cname;
        $service->service_description = $request->cdescription;
        $service->service_price = $request->cprice;
        $service->service_duration = $request->cduration;

        if ($service->save()){
            session()->flash('success', 'Service Information Updated!');
        }else{
            session()->flash('error', 'Error to update service!');
        }
        return redirect('/admin/computer');
    }


    public function cservice_delete(Request $request){

        if (count(explode(',', $request->id)) <= 1){
            $user = Computers::where('id', $request->id)->first();
            $user->status = 3;
            if ($user->save()){
                session()->flash('success', 'Service Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete Service!');
            }
        }else{
            foreach (explode(',', $request->id) as $isd){
                $user = Computers::where('id', $isd)->first();
                $user->status = 3;
                $user->save();
            }
            if ($user->save()){
                session()->flash('success', 'Service Deleted Successfully!');
            }else{
                session()->flash('error', 'Error to delete Service!');
            }
        }

        return redirect('/admin/computer');
    }


    public function cserviceTrash()
    {
        $services = Computers::where('status', 3)->get();
        return view('admin.cservice_trash', compact('services'));
    }

    public function cservice_restore(Request $request){

        foreach (explode(',', $request->id) as $isd){
            $user = Computers::where('id', $isd)->first();
            $user->status = 1;
            $user->save();
        }
        if ($user->save()){
            session()->flash('success', 'Services Restored Successfully!');
        }else{
            session()->flash('error', 'Error to restore services!');
        }

        return redirect('/admin/computer');
    }
}
