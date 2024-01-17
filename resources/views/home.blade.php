@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Costumer</th>
                            <th scope="col">Numer</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Zeit</th>
                            <th scope="col">Ringin</th>
                            <th scope="col">Status</th>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                            <td scope="row">{{ $XmlData->SubscriberName }}</td>
                            <td scope="row">{{ $XmlData->DialledNumber }}</td>
                            <td scope="row">{{ $XmlData->Date }}</td>
                            <td scope="row">{{ $XmlData->Time }}</td>
                            <td scope="row">{{ $XmlData->RingingDuration }}</td>
                            <td scope="row">{{ $XmlData->Callstatus }}</td>
                            <td scope="row">{{ $XmlData->Type }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                                <td scope="row">{{ $XmlData->Date }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                                <td scope="row">{{ $XmlData->Date }}</td>
                                <td scope="row">{{ $XmlData->Time }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                                <td scope="row">{{ $XmlData->Date }}</td>
                                <td scope="row">{{ $XmlData->Time }}</td>
                                <td scope="row">{{ $XmlData->RingingDuration }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                                <td scope="row">{{ $XmlData->Date }}</td>
                                <td scope="row">{{ $XmlData->Time }}</td>
                                <td scope="row">{{ $XmlData->RingingDuration }}</td>
                                <td scope="row">{{ $XmlData->Callstatus }}</td>
                            @endforeach
                        </tr>
                        <tr>
                            <!--  Data output here in paginated form  -->
                            @foreach ($XmlDatas as $XmlData)
                                <td scope="row">{{ $XmlData->SubscriberName }}</td>
                                <td scope="row">{{ $XmlData->DialledNumber }}</td>
                                <td scope="row">{{ $XmlData->Date }}</td>
                                <td scope="row">{{ $XmlData->Time }}</td>
                                <td scope="row">{{ $XmlData->RingingDuration }}</td>
                                <td scope="row">{{ $XmlData->Callstatus }}</td>
                                <td scope="row">{{ $XmlData->Type }}</td>
                            @endforeach
                        </tr>
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
