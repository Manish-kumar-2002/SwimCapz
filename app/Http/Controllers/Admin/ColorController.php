<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;

class ColorController extends Controller
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

        return view('admin.colors.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.colors.create');
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
            'code' => 'required',
            'name' => 'required'
        ]);

        $color = new Color();
        $color->name = $request->name;
        $color->code = explode('#', $request->code)[1];
        $color->save();

        Artisan::call('cache:clear');
        return redirect()
            ->route('colors.index')
            ->with('success', __('Color added successfuly.'));
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
        $color = Color::findOrFail($id);
        return view('admin.colors.edit', compact('color'));
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
            'name' => 'required',
            'code' => 'required'
        ]);

        $color = Color::find($id);
        $color->name = $request->name;
        $color->code = explode('#', $request->code)[1];
        $color->save();

        Artisan::call('cache:clear');
        return redirect()
            ->route('colors.index')
            ->with('success', __('Color updated successfuly.'));
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
            $color = Color::findOrFail($id);
            $color->delete();
        } catch (Exception $e) {
        }

        return redirect()
            ->route('colors.index')
            ->with('success', __('Color deleted successfuly.'));
    }

    public function dataTables()
    {
        $datas = Color::orderBy('created_at', 'desc');
        return DataTables::of($datas)
            ->addColumn('code', function ($row) {
                return  '<div class="godropdown action-list">
                                <span style="display: inline-block; width: 20px; height: 20px; border-radius: 50%; margin-right: 5px; background-color:#' . $row->code . ';"></span>
                                        </div>';
            })
            ->addColumn('action', function ($row) {
                return  '<div class="godropdown action-list">
                                <a href="' . route('colors.edit', $row->id) . '" >
                                                    <i class="fa fa-edit"></i> </a>
                                                <a href="' . route('colors.destroy', $row->id) . '" >
                                                    <i class="fa fa-trash"></i> </a>
                                                
                                        </div>';
            })
            ->rawColumns(['code', 'action'])
            ->toJson();
    }
}
