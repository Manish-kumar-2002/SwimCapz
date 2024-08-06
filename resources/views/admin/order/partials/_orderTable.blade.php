 <div class="mr-table">
     <h4 class="title widget-title down-line mb-30">
         {{ __('Products Ordered') }}
     </h4>
    <div class="table-responsive">
        <table id="example2" class="table table-hover dt-responsive" class="100%">
            <caption style="display:none;">&nbsp;</caption>
            <thead>
                <tr>
                    <th scope></th>
                    <th scope>{{ __('ID#') }}</th>
                    <th scope>{{ __('Name') }}</th>
                    @if (!@$hideExtra)
                        <th scope>{{ __('Details') }}</th>
                    @endif
                    <th scope>{{ __('Price') }}</th>
                    <th scope>{{ __('Qty') }}</th>
                    <th scope>{{ __('Total') }}</th>
                     @if (!@$hideExtra)
                        <th scope>{{ __('Notes') }}</th>
                    @endif
                    
                </tr>
            </thead>
             <tbody>
                 @foreach ($order->details as $details)
                     <tr>
                         <th colspan="8" scope>
                             <b>{{ $details->design_name }}</b>
                         </th>
                     </tr>
                
                    @foreach ($details->details as $product)
                        <tr>
                            <td style="width:100px;">
                                <img src="{{ @$product->front_design_url }}" style="height:100px;width:auto;"
                                    alt="{{ @$details->product->name }}" />
                            </td>
                            <td>
                                {{ $product->id }}
                            </td>
                            <td>
                                {{ $details->product->name }}
                            </td>

                            @if (!@$hideExtra)
                               <td>{!! $details->product->details !!}</td>
                            @endif
                            
                            <td>{{ Helper::convertPrice($product->total_price) }}
                            </td>
                            <td>{{ $product->total_qty }}</td>
                            <td>
                                {{ Helper::convertPrice($product->total_price * $product->total_qty) }}
                            </td>
                            @if (!@$hideExtra)
                               <td>
                                    {{ $product->remarks }}
                                </td>
                            @endif
                            
                        </tr>
                    @endforeach
                 @endforeach
             </tbody>
         </table>
     </div>
 </div>
