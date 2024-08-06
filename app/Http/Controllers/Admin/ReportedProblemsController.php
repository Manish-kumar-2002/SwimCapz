<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportedProblem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportedProblemsController extends Controller
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

        return view('admin.reportedProblem.index');
    }

    /**
     * Use for rander datatable
     *
     * @return \Illuminate\Http\Response
    */
    private function dataTable()
    {
        $datas = ReportedProblem::latest('id');
        return DataTables::of($datas)
            ->editColumn('created_at', function($row) {
                return date('d-m-Y H:i:s', strtotime($row->created_at));
            })->addColumn('action', function ($row) {
                return '<div class="action-list">
                            <a
                                href="'.route('reported-problems.show', $row->id).'"
                                class="link-modal"
                                link-url="'.route('reported-problems.show', $row->id).'"
                                link-title="Reported Problems"
                                link-isfooter="1"
                            >
                                <i class="fas fa-eye" ></i></a>
                                <a href="javascript:;" data-href="' . route('reported-problems.destroy', $row->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
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
        $reportedProblem=ReportedProblem::find($id);
        return view('admin.reportedProblem.show', compact('reportedProblem'));
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
        $requestProblelm = ReportedProblem::find($id);
        if ($requestProblelm) {
            $requestProblelm->delete();
            return response()->json('Requested problem deleted Success.');
        }
        return response()->json('Requested problem deleted fail.');
    }
}
