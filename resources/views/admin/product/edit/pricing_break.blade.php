<style>
    .color-circle {
    display: inline-block;
    width: 20px; /* Adjust the size as needed */
    height: 20px; /* Adjust the size as needed */
    border-radius: 50%; /* Makes it a circle */
}
</style>
<div class="col-xs-12 col-sm-12 col-md-12 table-responsive" style="background-color:#fff">
<table class="table pricing-table">
  <thead>
    <tr>
      <th class="pricing-break">Break Qty.</th>
      <th class="pricing-break">Colors 1(per Side)</th>
      <th class="pricing-break">Colors 2 (per Side)</th>
      <th class="pricing-break">Colors 3 (per Side)</th>
      <th class="pricing-break">Colors 4 (per Side)</th>
      @if(request()->view != 'true')
        <th class="pricing-break">Action</th>
      @endif
    </tr>
  </thead>
  <tbody class="pricing-table-add-row">
    @foreach($pricing_details as $pricing_brek_val)
      <tr data-id="{{$pricing_brek_val->id}}">
        <input type="hidden" name="prod_id" value="{{$data->id}}" id="prodid{{$pricing_brek_val->id}}" disabled>
        <td class="padding-pricing-td"><input type="number" name="quantity" value="{{$pricing_brek_val->quantity}}" id="quantity{{$pricing_brek_val->id}}" disabled></td>
        <td class="padding-pricing-td">$ <input type="number" name="color_1" value="{{$pricing_brek_val->color_1}}" id="color1{{$pricing_brek_val->id}}" disabled></td>
        <td class="padding-pricing-td">$ <input type="number" name="color_2" value="{{$pricing_brek_val->color_2}}" id="color2{{$pricing_brek_val->id}}" disabled></td>
        <td class="padding-pricing-td">$ <input type="number" name="color_3" value="{{$pricing_brek_val->color_3}}" id="color3{{$pricing_brek_val->id}}" disabled></td>
        <td class="padding-pricing-td">$ <input type="number" name="color_4" value="{{$pricing_brek_val->color_4}}" id="color4{{$pricing_brek_val->id}}" disabled></td>
        @if(request()->view != 'true')
          <td class="padding-pricing-td"><button onclick="pricingBreakEdit(this,'{{$pricing_brek_val->id}}')" class="btn btn-success btn-sm save-button" data-id="{{$pricing_brek_val->id}}"><i class="fa fa-edit"></i></button> <button class="btn btn-sm btn-danger" data-href="{{route('admin-pricing-break-delete',[$pricing_brek_val->id])}}" data-id="{{$pricing_brek_val->id}}" data-toggle="modal" data-target="#pricing-break-delete"><i class="fa fa-trash"></i></button></td>
        @endif
      </tr>
    @endforeach
 
  </tbody>
</table>

<style>
  table {
    border-collapse: collapse;
  }

  th, td {
    border: 1px solid black;
    padding: 5px;
  }

 .pricing-break{
    background-color: #ccc;
  }

  .save-button {
    background-color: #008000;
    color: white;
  }

  .add-button {
    background-color: #0000ff;
    color: white;
  }
  .padding-pricing-td {
    padding: 2px 2px 2px 12px!important;
  }
</style>
        <div class="col-xs-12 col-sm-12 col-md-12 text-right mb-4">
              @if(request()->view != 'true')
                <button type="button" class="btn btn-primary black-button black-btn form-button btn-sm add-button" onclick="addButtion()"> <i class="fa fa-plus" aria-hidden="true"></i> </button>
              @endif
            </div>
        </div>

        <!-- /.modal -->
    </div>
    <!-- delete variants model -->
    <div class="modal fade" id="pricing-break-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

	<div class="modal-header d-block text-center">
		<h4 class="modal-title d-inline-block">{{ __("Confirm Delete") }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
	</div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center">{{ __("Are you sure you want to delete?") }}</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
            			<form action="" class="d-inline delete-form" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
			            </form>
      </div>

    </div>
  </div>
</div>
 <!-- delete end variants model -->