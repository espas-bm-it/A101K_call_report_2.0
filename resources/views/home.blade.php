@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Costumer</th>
                            <th scope="col">Nummer</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Zeit</th>
                            <th scope="col">Ringing</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Status</th>
                            <th scope="col">Type</th>
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
