@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">SubscriberName</th>
                            <th scope="col">Nummer</th>
                            <th scope="col">@sortablelink('Date', 'Datum')</th>
                            <th scope="col">@sortablelink('Time','Zeit')</th>
                            <th scope="col">@sortablelink('RingingDuration','Ringing')</th>
                            <th scope="col">@sortablelink('CallDuration','Duration')</th>
                            <th scope="col">@sortablelink('CallStatus','Status')</th>
                            <th scope="col">@sortablelink('CommunicationType','Type')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($XmlDatas as $XmlData)
                            <tr>
                                <!--  Data output here in paginated form  -->
                                <td>{{ $XmlData->SubscriberName }}</td>
                                <td>{{ $XmlData->DialledNumber }}</td>
                                <td>{{ $XmlData->Date }}</td>
                                <td>{{ $XmlData->Time }}</td>
                                <td>{{ $XmlData->RingingDuration }}</td>
                                <td>{{ $XmlData->CallDuration }}</td>
                                <td>{{ $XmlData->CallStatus }}</td>
                                <td>{{ $XmlData->CommunicationType }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination HTML Markup -->
                <div>
                    {{ $XmlDatas->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
