<div class="table-responsive">
    <table class="table dt-responsive">
        <caption>&nbsp;</caption>
        <thead>
            <tr>
                <th scope>&nbsp;</th>
                <th scope>{{ __('ID#') }}</th>
                <th scope>{{ __('Name') }}</th>
                <th scope>{{ __('Details') }}</th>
                <th scope>{{ __('Qty') }}</th>
                <th scope>{{ __('Price/Item') }}</th>
                <th scope>{{ __('Subtotal') }}</th>
                <th scope>{{ __('Order Total') }}</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($order->details as $key => $details)
                <tr>
                    <th colspan="7" scope>
                        {{-- <b>{{ $details->design_name }}</b> --}}
                    </th>
                    @if ($key == 0)
                        <th
                            scope
                            rowspan="{{$order->getIncludedItemCount() * 3}}"
                            class="text-center"
                        >{{ Helper::convertPrice($order->pay_amount)}}</th>
                    @endif
                    
                </tr>
                @foreach ($details->details as $product)
               
                    <tr>
                        <td style="text-align:center; width:137px;">
                            <a
                                href="{{ route('front.product', [
                                    $details->product->slug,
                                    $product->product_variant_id
                                ]) }}">
                                <img
                                    src="{{ @$product->front_design_url }}"
                                    style="height:100px;width:100px;"
                                    alt="{{ $details->product->name }}"
                                />
                            </a>
                        </td>
                        <td>
                            {{ $product->id }}
                        </td>
                        <td>
                            <a
                                href="{{ route('front.product', [
                                    $details->product->slug,
                                    $product->product_variant_id
                                ]) }}">
                                {{ $details->product->name }}
                            </a>
                        </td>
                        <td>{!! $details->product->details !!}</td>
                        <td>{{ $product->total_qty }}</td>
                        <td>{{ Helper::convertPrice($product->total_price) }}</td>
                        <td>
                            {{ Helper::convertPrice($product->total_price * $product->total_qty) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="3"></td>
                    <td>Total attached names :</td>
                    <td>{{ $details->getTotalNames() > 0 ? $details->getTotalNames() : '-' }}</td>
                    <td>{{ $details->getTotalNames() > 0 ? $details->nameChargeEach() : '-' }}</td>
                    <td>{{ $details->getTotalNames() > 0 ? $details->totalNameCharge() : '-' }}</td>
                </tr>

            @endforeach
        </tbody>
    </table>
</div>
