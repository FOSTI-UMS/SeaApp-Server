<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\Models\User;

use Aws\S3\S3Client;



class UploadController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        
    }


    public function submisi(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|max:5000'
            //'file' => 'required|mimes:jpeg,bmp,png,gif,svg,pdf|max:5000'
        ]);

        $tipe = $request->tipe;
            
        try {
            $file = $request->file('file');
            $subname = time()."_".$tipe.".".$file->getClientOriginalExtension();
            $file->move('./upload/submisi',$subname);
            //backup to s3
            $this->upload('./upload/submisi/',$subname);
            if($tipe=="abstrak"){
                    DB::table('events')
                    ->where('user_id', Auth::id())
                    ->where('event_id', 3)
                    ->update([
                        'file_abstrak' => $subname,
                        'c1' => "Menunggu Penilaian",
                        'step' => 1
                    ]);
            }else if($tipe=="ktm"){
                DB::table('events')
                ->where('user_id', Auth::id())
                ->where('event_id', 3)
                ->update([
                    'file_ktm' => $subname,
                ]);
            }if($tipe=="full"){
                DB::table('events')
                ->where('user_id', Auth::id())
                ->where('event_id', 3)
                ->update([
                    'file_full_paper' => $subname,
                    'c2' => "Menunggu Penilaian",
                    'step' => 2
                ]);
            }
            return response()->json(array("status" => "Success"),200);
        } catch (\Throwable $th) {
            return response()->json($th,200);
        }


    }

    public function upload($path,$subname)
    {
        $client = new S3Client([
            'region' => 'ap-southeast-1',
            'version' => 'latest',
            'endpoint' => 'https://fostifest.s3-ap-southeast-1.amazonaws.com',
            'credentials' => [
                'key' => 'AKIAZ4YKUHAVS7DI5HNH',
                'secret' => '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'
            ],
            'use_path_style_endpoint' => true
        ]);
        $client->putObject([
            'Bucket' => 'fostifest',
            'Key' => $subname,
            'Body' => fopen($path.$subname,'r'),
            'ACL'    => 'public-read',
        ]);
    }


}
