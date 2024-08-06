<div class="table-responsive">
    <table class="table">
        <caption>&nbsp;</caption>
        <thead>
            <tr>
                <th scope>&nbsp;</th>
                <th scope>{{ __('ID#') }}</th>
                <th scope>{{ __('Name') }}</th>
                <th scope>{{ __('Details') }}</th>
                <th scope>{{ __('Qty') }}</th>
                <th scope>{{ __('Price/Item') }}</th>
                <th scope>{{ __('Tax') }}</th>
                <th scope>{{ __('Subtotal') }}</th>
                <th scope>{{ __('Order Total') }}</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($order->details as $key => $details)
                <tr>
                    <th colspan="8" scope>
                        <b>{{ $details->design_name }}</b>
                    </th>
                   
                    
                </tr>
                @foreach ($details->details as $product)
                    <tr>
                        <td style="text-align:center; width:137px;">
                            <img
                                src="{{ @$product->front_design_url }}"
                                style="height:100px;width:100px;"
                                alt="{{ $details->product->name }}"
                            />
                        </td>
                        <td>
                            {{ $product->id }}
                        </td>
                        <td>
                           {{ $details->product->name }}
                        </td>
                        <td>{!! $details->product->details !!}</td>
                        <td>{{ $product->total_qty }}</td>
                        <td>{{ Helper::convertPrice($product->total_price) }}</td>
                        <td>0.0</td>
                        <td>
                            {{ Helper::convertPrice($product->total_price * $product->total_qty) }}
                        </td>
                        <td></td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3"></td>
                    <td>Total attached names :</td>
                    <td>{{ $details->getTotalNames() }}</td>
                    <td>{{ $details->nameChargeEach() }}</td>
                    <td>0.0</td>
                    <td>{{ $details->totalNameCharge() }}</td>
                    <td></td>
                </tr>

                <tr style="border-bottom: 1px solid #ddd !important;">
                    <th colspan="8"></th>
                @if ($key == 0)
                        <th
                            scope
                            colspan="{{$order->getIncludedItemCount() * 1}}"
                            
                        >{{ Helper::convertPrice($order->pay_amount)}}</th>
                    @endif
                </tr>

            @endforeach
        </tbody>
    </table>
</div>
