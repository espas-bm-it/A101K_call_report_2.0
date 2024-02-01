@extends('layouts.app')

@section('content')
    <div class="container">

        <div>
            <!--Dste range picker button -->
            <a href="#" id="dateRangePickerBtn">Datum Bereich auswählen</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Kund</th>
                            <th scope="col">Telefon Nummer</th>
                            <th scope="col">@sortablelink('Date', 'Datum')</th>
                            <th scope="col">@sortablelink('Time', 'Zeit')</th>
                            <th scope="col">@sortablelink('RingingDuration', 'Ringing')</th>
                            <th scope="col">@sortablelink('CallDuration', 'Duration')</th>
                            <th scope="col">@sortablelink('CallStatus', 'Status')</th>
                            <th scope="col">@sortablelink('CommunicationType', 'Type')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($XmlDatas as $XmlData)
                            <tr>
                                <!--  Data output here in paginated form  -->
                                <td>{{ $XmlData->SubscriberName }}</td>
                                <td>{{ $XmlData->formattedPhoneNumber }}</td> <!-- siehe XmlData.php -->
                                <td>{{ \Carbon\Carbon::parse($XmlData->Date)->format('d.m.Y') }}</td>
                                <!-- siehe HomeController.php -->
                                <td>{{ $XmlData->Time }}</td>
                                <td>{{ $XmlData->RingingDuration }}</td>
                                <td>{{ $XmlData->CallDuration }}</td>
                                <td>{{ $XmlData->CallStatus }}</td>
                                <td>{{ $XmlData->CommunicationType }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">Keine Daten verfügbar.</td>
                            </tr>
                        @endforelse
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
