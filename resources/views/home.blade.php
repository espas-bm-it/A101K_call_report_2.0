@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!--  Data output here in paginated form  -->
                @foreach ($XmlDatas as $XmlData)
                    <p>{{ $XmlData->SubscriberName }}</p>
                @endforeach

                <!-- Pagination HTML Markup -->
                <div>
                    {{ $XmlDatas->links() }}
                </div>
            @endsection
        </div>
    </div>
</div>
