<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RequestedQuote;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RequestedQuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTable();
        }

        return view('admin.requestedQuotes.index');
    }

    /**
     * Use for rander datatable
     *
     * @return \Illuminate\Http\Response
     */
    private function dataTable()
    {
        $datas = RequestedQuote::latest('id');
        return DataTables::of($datas)
            ->editColumn('created_at', function ($row) {
                $date = new DateTime($row->created_at);
                $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                return $date->format('d-m-Y H:i:s');
            })->addColumn('action', function ($row) {
                return '<div class="action-list">
                            <a
                                href="' . route('requested-quotes.show', $row->id) . '"
                                class="link-modal"
                                link-url="' . route('requested-quotes.show', $row->id) . '"
                                link-title="Requested Quotes"
                                link-isfooter="1"
                            >
                                <i class="fas fa-eye" ></i></a>
                                <a href="javascript:;" data-href="' . route('requested-quotes.destroy', $row->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>
                                </div>';
            })->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $requestedQuotes = RequestedQuote::find($id);
        $isImage = $requestedQuotes->file &&
            is_array(getimagesize(public_path('storage/quotes/' . $requestedQuotes->file))) ? 1 : 0;
        return view('admin.requestedQuotes.show', compact('requestedQuotes', 'isImage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $requestedQuote = RequestedQuote::find($id);
        if ($requestedQuote) {
            if ($requestedQuote->file) {
                $filePath = public_path('storage/quotes/' . $requestedQuote->file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $requestedQuote->delete();
            return response()->json('Requested quote deleted Success.');
        }
        return response()->json(['error' => 'Requested quote deleted failed.']);
    }
}
