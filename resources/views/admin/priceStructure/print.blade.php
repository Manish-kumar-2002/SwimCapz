
<div class="row col-md-12">
	<p class="special"><b>Print charge </b>&nbsp;per Item per Side [Cap Cost based on quantity and ink colours]</p>
	<table class="table table-striped" style="width:100%;">
		<caption style="display:none;">&nbsp;</caption>
		<thead>
		
			<tr>
				<th colpsna="2"># of colors</th>
				@for ($i=1; $i <= Helper::totalBreak() ; $i++)
					<th style="text-align:center;">
						Break {{ $i }}
					</th>
				@endfor
			</tr>
		</thead>
		<tbody>

			@for ($color =1 ; $color <= Helper::totalColor() ; $color++)
                <?php
                    $print=[];
                    if(array_key_exists($color, $printCharge)) {
                        $print=$printCharge[$color];
                    }
				?>
				<tr>
					<td style="width:10%;text-align:center;">{{$color}}</td>
					@for ($i =1 ; $i <= Helper::totalBreak() ; $i++)
                        <?php
                            $value=0;
                            if(array_key_exists($i, $print)) {
                                $value=$print[$i];
                            }
                        ?>
						<td>
							<span>
								$&nbsp;<input
									type="text"
									class="break_count"
                                    value="{{$value}}"
                                    name="printCharge[{{$color}}][{{$i}}]"
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