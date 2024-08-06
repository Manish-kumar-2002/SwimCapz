<form action="{{route('price-structure.store')}}" method="post" class="form">
	@csrf
	<div class="row input-wrap" style="background: #e5e1e1;">
		<div class="col-md-2">
			<span
				class="verticle-align"
			>Break count
				<input
					type="text"
					id="break_count"
					class="break_count totalBreak break-size"
					value="{{Helper::totalBreak()}}"
					onkeyup="isNumeric(this)"
				>
				<a href="">
					<i class="fa fa-refresh" style="color:green"></i>
				</a>
				<span
					class="break-process error-break_count"
					style="color:red;font-weight:bold"
				></span>
			</span>
		</div>
		<div class="col-md-10 d-flex" style="gap: 20px">
			<span
				class="verticle-align"
			>Fix screen cost
				<input
					type="text"
					id="fixScreenCost"
					name="fixScreenCost"
					class="fixScreenCost break-size"
					value="{{Helper::priceStructure()->fixScreenCost}}"
					onkeyup="isNumeric(this)"
				/>
			</span>

			<span
				class="verticle-align"
			>Fix cost per name
				<input
					type="text"
					id="fixCostPerName"
					class="fixCostPerName break-size"
					name="fixCostPerName"
					value="{{Helper::priceStructure()->fixCostPerName}}"
					onkeyup="isNumeric(this)"
				/>
			</span>

			<span
				class="verticle-align"
			>Fix front back per color
				<input
					type="text"
					id="fixFrontBackPerColor"
					class="fixFrontBackPerColor break-size"
					name="fixFrontBackPerColor"
					value="{{Helper::priceStructure()->fixFrontBackPerColor}}"
					onkeyup="isNumeric(this)"
				/>
			</span>
		</div>
	</div>
	@include('admin.priceStructure.productQty', [
		'storePriceStructure' => Helper::priceStructure()->breakPoint
	])
	<div class="row">
		<div class="col-md-12 text-right">
			<button type="submit" class="btn btn-success">Save</button>
		</div>
	</div>
</form>

