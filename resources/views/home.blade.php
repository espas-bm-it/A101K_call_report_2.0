@extends('layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
<!--  Data output here in paginated form  -->
@foreach($XmlDatas as $XmlData)
<p>{{$XmlData->SubscriberName}}</p>
@endforeach

<!-- Pagination HTML Markup -->
<div>
{{ $XmlDatas->links() }}
</div>

@endsection