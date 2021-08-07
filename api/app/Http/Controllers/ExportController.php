<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExportController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function download($event_id)
    {
        $name='';
        $data = [];
        switch ($event_id) {
            case 1:
                $name='Webinar_1';
                $data=[];
                break;
                case 2:
                    $name='Webinar_2';
                    $data=[];
                    break;
                    case 3:
                        $name='Lomba';
                        $data=[];
                        break;
            
            default:
                # code...
                break;
        }
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Kunjungan');
        
       

        $event = DB::table('events')
        ->join('users', 'users.id', '=', 'events.user_id')
        ->select('users.name', 'users.phone', 'users.email','events.*')
        ->where('event_id', $event_id)
        ->get();

        foreach ($event as $ev) {
            foreach ($ev as $kol) {
                $kolom[] = $kol;
            }
            $isi[] = $kolom;
        }
        foreach (['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S'] as $k) {
            $worksheet->getColumnDimension($k)->setAutoSize(true);
        }
  

        $worksheet->fromArray(json_decode(json_encode($event), true));
		$writer = new Xlsx($spreadsheet);
		$writer->setIncludeCharts(true);
		$filename = 'simple';
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Data_Fostifest_'.$name.'__'. date("d-m-Y H:i:s") .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
    }

}