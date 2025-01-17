<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rating;
use Yajra\DataTables\Facades\DataTables;

class RatingController extends AdminBaseController
{
	public function datatables()
	{
		$datas = Rating::latest('id')->get();
		return DataTables::of($datas)
						->addColumn('product', function(Rating $data) {
							$name =  mb_strlen(strip_tags($data->product->name),'UTF-8') > 50
								? mb_substr(strip_tags($data->product->name),0,50,'UTF-8').'...' : strip_tags($data->product->name);
							return '<a href="'.route('front.product',$data->product->slug).'" target="_blank">'.$name.'</a>';
						})
						->addColumn('reviewer', function(Rating $data) {
							return $data->user->name;
						})
						->addColumn('review', function(Rating $data) {
							return mb_strlen(strip_tags($data->review),'UTF-8') > 250
								? mb_substr(strip_tags($data->review),0,250,'UTF-8').'...' : strip_tags($data->review);
						})
						->addColumn('action', function(Rating $data) {
							return '<div class="action-list">
								<a
									data-href="' . route('admin-rating-show',$data->id) . '"
									class="view details-width"
									data-toggle="modal"
									data-target="#modal1"
								> <i class="fas fa-eye"></i>'.__('Details').'</a>
								<a
									href="javascript:;"
									data-href="' . route('admin-rating-delete',$data->id) . '"
									data-toggle="modal"
									data-target="#confirm-delete"
									class="delete"
								><i class="fas fa-trash-alt"></i></a></div>';
						})
						->rawColumns(['product','action'])
						->toJson();
	}

	public function index(){
		return view('admin.rating.index');
	}

	public function show($id)
	{
		$data = Rating::findOrFail($id);
		return view('admin.rating.show',compact('data'));
	}

	public function destroy($id)
	{
		$rating = Rating::findOrFail($id);
		$rating->delete();
		
		$msg = __('Data Deleted Successfully.');
		return response()->json($msg);
	}
}