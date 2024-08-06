<a class="clear"></a>
@if (count($datas) > 0)
    <ul>
        @foreach ($datas as $data)
            <li>
                <a
					style="color:{{@$data->productVarient->color_code ?? 'red'}}"
					href="{{ route('product.show-variant', [
						$data->product_id, $data->product_varient
					]) }}"> <i class="icofont-cart"></i>
                    {{ 	mb_strlen($data->product->name, 'UTF-8') > 30 ?
						mb_substr($data->product->name, 0, 30, 'UTF-8') :
						$data->product->name
					}}
				</a>
                <a class="clear">{{ __('Stock') }} : {{@$data->productVarient->quantity ?? 0}}</a>
            </li>
        @endforeach

    </ul>
@else
    <a class="clear" href="javascript:;">
    </a>

@endif
