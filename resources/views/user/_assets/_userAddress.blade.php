<div class="row">
    @foreach ($user->addresses as $row)
        <div class="col-md-5 address">
            <h6 style="text-align:right;">
                <input type="checkbox" value="{{ $row->id }}" {{ $row->isDefault ? 'checked' : '' }} title="Default"
                    class="default-address" />
            </h6>
            <table class="table table-bordered p-3">
                <caption>&nbsp;</caption>
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $row->name !="" ? $row->name : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $row->email != "" ? $row->email : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $row->phone != "" ? $row->phone : '-' }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Company</th>
                        <td>{{ $row->company != "" ? $row->company : '-' }}</td>
                    </tr> --}}
                    <tr>
                        <th>Address</th>
                        <td>{{ $row->address != "" ? $row->address : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address Type</th>
                        <td>{{ $row->address_type != "" ? $row->address_type : '-' }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Appartment</th>
                        <td>{{ $row->appartment !="" ? $row->appartment : '-' }}</td>
                    </tr> --}}
                    <tr>
                        <th>City</th>
                        <td>{{ $row->city != "" ? $row->city : '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ @$row->getState->state ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ @$row->getCountry->country_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Zip</th>
                        <td>{{ $row->zip != "" ? $row->zip  : '-' }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">
                            <a href="{{ route('addressEdit', $row->id) }}" class="link-modal"
                                link-url="{{ route('addressEdit', $row->id) }}" link-title="Edit Address"
                                link-isFooter="1">Edit</a>

                            <a 
                                href="{{ route('addressDelete', $row->id) }}"
                                class="address-destroy"
                            >Delete</a>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach
</div>
