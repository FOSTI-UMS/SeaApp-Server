<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\Models\User;


class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function index()
    {
        $event = Event::all();
        return response()->json($event);
    }

    public function add(Request $request)
    {
        if($request->event_id == 1 || $request->event_id == 2){
            $event = new Event();
            $event->user_id = Auth::id();
            $event->event_id = $request->event_id;
            //step 1 == tedaftar
            //step 2 == show link zoom
            //step 3 == show link sertif
            $event->step = 1;
            $event->catatan_user = $request->catatan_user;
            $event->instansi = $request->instansi;
            $event->kota = $request->kota;
            $event->save();
        }else{
            $event = new Event();
            $event->user_id = Auth::id();
            $event->event_id = $request->event_id;
            //step 0 == tedaftar
            //step 1 == sudah upload abstrak
            //step 2 == sudah membayar
            //step 3 == sudah upload full paper
            $event->step = 0;
            $event->catatan_user = $request->catatan_user;
            $event->instansi = $request->instansi;
            $event->kota = $request->kota;
            $event->save();
        }
        return response()->json(array("status" => "Success"));
    }

    public function cekEvent(Request $request)
    {
        $event = Event::where('user_id', Auth::id())
        ->where('event_id',$request->event_id)
        ->get();
        return response()->json($event);
    }
    public function me()
    {
        return response()->json(Auth::user(),200);
    }
    
    function ubahProfile(Request $request){
        if($request->password == ""){
            DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone
            ]);
        }else{
            DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => app('hash')->make($request->password)
            ]);
        }
    }

    public function pesertawebinar(Request $request)
    {
        $cek = User::where('id', Auth::id())->get();

        if($cek[0]->role == 1){
        
        
            $urutan = $request->urutan;
          

            $event = DB::table('events')
            ->join('users', 'users.id', '=', 'events.user_id')
            ->select('users.name', 'users.phone', 'users.email','events.*')
            ->where('event_id', $urutan)
            ->get();
            return response()->json(array("status" => "Success","data" => $event));
        }else{
            return response()->json(array("status" => "Errorr"));
        }
    }

    public function setBayar(Request $request)
    {
        $cek = User::where('id', Auth::id())->get();

        if($cek[0]->role == 1){
        
        
            DB::table('events')
            ->where('id', $request->id)
            ->update([
                'sudah_bayar' => $request->status
            ]);
            return response()->json(array("status" => "Success"));
        }else{
            return response()->json(array("status" => "Errorr"));
        }
    }
}
