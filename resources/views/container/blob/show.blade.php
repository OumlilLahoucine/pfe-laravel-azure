@extends('layout')

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-6">
                <h2><b>{{ $blob }}</b></h2>
            </div>
            <div class="col-sm-6">
                <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="material-icons">&#129044;</i> <span class="mt-1">Back</span></a>
            </div>
        </div>
    </div>
    <div class="container-xl table-responsive table-wrapper my-0 py-0 text-center" style="overflow:hidden; height: auto; min-height: 500px;">
        <object style="min-height : 500px; height: auto; width: 80%;overflow : hidden;"
            data="data:{{ $content_type }};base64,{{ base64_encode($content) }}" type="{{ $content_type }}"
        >
            <p class="text-center mt-4 mb-1">Viewer not supported.</p>
        </object>
    </div>
    

    
@endsection