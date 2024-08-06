
<div class="row col-md-12 mt-2" aria-modal="true">
	<p class="mt-3 special"><b>Product Quantity Break</b>(# of products before printing price change)</p>
	<table class="table table-striped" style="width:100%;">
		<caption style="display:none;">&nbsp;</caption>
		<thead>
			<tr>
				<!-- <th style="width:10%;">&nbsp;</th> -->
				@for ($i =1 ; $i <= Helper::totalBreak() ; $i++)
					<th>Break {{$i}}</th>
				@endfor
			</tr>
		</thead>
		<tbody>
			<tr>
				<!-- <td>&nbsp;</td> -->
				@for ($i =1 ; $i <= Helper::totalBreak() ; $i++)
					<?php
						$result=Helper::getPriceStructure($i);
						$value=$result ? $result->break_qty : 0;
					?>
					<td>
						@if ($i == 1)
							<!-- {{$i}} -->
							<input
								type="text"
								class="break_count"
								name="break_count[{{$i}}]"
								value="1"
							/>
						@else
							<input
								type="text"
								class="break_count"
								name="break_count[{{$i}}]"
								value="{{$value}}"
								onkeyup="isNumeric(this)"
							/>
						@endif
						
					</th>
				@endfor
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="{{Helper::totalBreak() + 1}}">
					<strong
						class="errors error-break_fields"
						style="color:red;font-size:bold"
					></strong>
				</td>
			</tr>
		</tfoot>
	</table>
</div>