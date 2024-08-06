
<div class="row col-md-12">
	<p class="special"><b>Setup charge </b>&nbsp;per Side per Design</p>
	<table class="table table-striped" style="width:100%;">
		<caption style="display:none;">&nbsp;</caption>
		<thead>
			<tr>
				<th colpsna="2"># of colors</th>
				<th colspan="{{Helper::totalBreak()}}" style="text-align:center;">
					Price
				</th>
			</tr>
		</thead>
		<tbody>

			@for ($color =1 ; $color <= Helper::totalColor() ; $color++)
                <?php
                    $setup=[];
                    if(array_key_exists($color, $setupCharge)) {
                        $setup=$setupCharge[$color];
                    }
				?>
				<tr>
					<td style="width:10%;text-align:center;">{{$color}}</td>
					@for ($i =1 ; $i <= Helper::totalBreak() ; $i++)
                        <?php
                            $value=0;
                            if(array_key_exists($i, $setup)) {
                                $value=$setup[$i];
                            }
                        ?>
						<td>
							<span>
								$&nbsp;<input
									id="input-{{$i}}"
									type="text"
									class="break_count"
                                    value="{{$value}}"
                                    name="setupCharge[{{$color}}][{{$i}}]"
									onkeyup="isNumeric(this)"
								/>
							</span>
							
						</th>
					@endfor
				</tr>
			@endfor

		</tbody>
	</table>
</div>