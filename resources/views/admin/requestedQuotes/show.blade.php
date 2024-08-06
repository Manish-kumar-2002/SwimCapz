<table class="table table-bordered">
    <tr>
        <th scope>Name :-</th>
        <td>{{$requestedQuotes->name}}</td>
    </tr>
    <tr>
        <th scope>Email :-</th>
        <td>{{$requestedQuotes->email}}</td>
    </tr>
    <tr>
        <th scope>Phone :-</th>
        <td>{{$requestedQuotes->phone}}</td>
    </tr>
    <tr>
        <th scope>Created At :-</th>
        <td>{{ $requestedQuotes->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y H:i:s') }}</td>
        
    </tr>
    <tr>
        <th scope>Tell us about your project:-</th>
        <td>{{$requestedQuotes->project_desc}}</td>
    </tr>
    <tr>
        <th scope>How many items do you need?  :-</th>
        <td>{{$requestedQuotes->noi}}</td>
    </tr>

    <tr>
        <th scope>Budget($)  :-</th>
        <td>{{$requestedQuotes->budget}}</td>
    </tr>
    
    <tr>
        <th scope>When do you need it?  :-</th>
        <td>{{$requestedQuotes->date ? date('d-m-Y', strtotime($requestedQuotes->date)) : ''}}</td>
    </tr>
    <tr>
        <th scope>Tell Us About Your Art  :-</th>
        <td>
            <h5>Number of Print Colors :-</h5>
            <p><b>Front</b> : {{$requestedQuotes->front}}</p>
            <p><b>Back</b> : {{$requestedQuotes->back}}</p>
            <p><b>Side 3</b> : {{$requestedQuotes->side3}}</p>
            <p><b>Side 4</b> : {{$requestedQuotes->side4}}</p>
        </td>
    </tr>
    <tr>
        <th scope>Attached File :-</th>
        <td>
            @if ($isImage)
                <img download src="{{asset('storage/quotes/' . $requestedQuotes->file)}}" alt="img">
                <a 
                download
                href="{{asset('storage/quotes/' . $requestedQuotes->file)}}"
            >{{asset('storage/quotes/' . $requestedQuotes->file)}}</a>
            @endif
            
        </td>
    </tr>
    <tr>
        <th colspan="2">
            <button onclick="$('#common-modal').modal('hide')" type="button" class="btn btn-success">Close</button>
        </th>
    </tr>
</table>