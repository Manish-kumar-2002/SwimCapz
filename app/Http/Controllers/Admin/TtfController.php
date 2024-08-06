<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Ttf;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TtfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dataTables();
        }

        return view('admin.ttf.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ttf.create');
    }

    protected function uploadTTf($request)
    {
        $file = $request->file('ttf');
        $originalName = $file->getClientOriginalName();
        return $file->storeAs('ttf', $originalName, 'public');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'ttf'   => 'required|file'
        ]);

        $ttf = new Ttf();
        $ttf->title = $request->title;
        $ttf->font_name = $request->font_name;
        $ttf->ttf = $this->uploadTTf($request);
        $ttf->save();

        $ttf->generatePreview($request);

        return redirect()
            ->route('ttf.index')
            ->with('success', __('TTF file uploaded successfuly.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ttf = Ttf::findOrFail($id);
        return view('admin.ttf.edit', compact('ttf'));
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
        $request->validate([
            'title' => 'required'
        ]);

        $ttf = Ttf::find($id);
        $ttf->title = $request->title;
        $ttf->font_name = $request->font_name;
        if ($request->file('ttf')) {
            $ttf->ttf = $this->uploadTTf($request);
        }
        $ttf->save();
        if ($request->file('ttf')) {
            $ttf->generatePreview($request);
        }

        return redirect()
            ->route('ttf.index')
            ->with('success', __('TTF file updated successfuly.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $ttf = Ttf::find($id);
            $ttf->delete();
        } catch (Exception $e) {
        }

        return redirect()
            ->route('ttf.index')
            ->with('success', __('TTF file deleted successfuly.'));
    }

    public function dataTables()
    {
        $datas = Ttf::orderBy('created_at', 'desc');
        return DataTables::of($datas)
            ->addColumn('action', function ($row) {
                return  '<div class="godropdown action-list">
                                <a href="' . route('ttf.edit', $row->id) . '" >
                                    <i class="fa fa-edit"></i></a>
                                <a href="' . route('ttf.destroy', $row->id) . '" >
                                    <i class="fa fa-trash"></i> </a>
                        </div>';
            })
            ->editColumn('preview', function ($row) {
                return '<img src="' . $row->preview_path . '" style="heigh:100px;width:100px;">';
            })
            ->rawColumns(['action', 'preview'])
            ->toJson();
    }
}
