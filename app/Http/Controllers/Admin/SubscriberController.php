<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscriber;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends AdminBaseController
{
   
    public function datatables()
    {
        $datas = Subscriber::orderBy('created_at', 'asc');
        return DataTables::of($datas)
                            ->addColumn('sl', function(Subscriber $data) {
                                $id = 1;
                                return $id++;
                            }) 
                            ->toJson();
    }

    public function index(){
        return view('admin.subscribers.index');
    }

    public function download()
    {
        //  Code for generating csv file
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=subscribers.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('Subscribers Emails'));
        $result = Subscriber::all();
        foreach ($result as $row){
            fputcsv($output, $row->toArray());
        }
        fclose($output);
    }
}
