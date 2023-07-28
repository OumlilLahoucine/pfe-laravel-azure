@extends('layout')

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-6">
                <h2>Manage <b>{{ $container }}</b></h2>
            </div>
            <div class="col-sm-6">
                <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="material-icons">&#129044;</i> <span class="mt-1">Back</span></a>
            </div>
        </div>
    </div>
    <div class="">
        @if ($alert!=='')
            <div id="alert" class="alert alert-danger">
               {{ html_entity_decode($alert) }}
            </div>
        @endif
        <form action="/container/{{ $container }}/upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div class = "my-4">  
                <label for="files" class="my-0"><h6>Upload files :</h6></label>  
                <input type="file" class="form-control mt-2 mb-2 pl-2 h-100" id="files" name="files[]" multiple>  
                @error('files.*')
                    <span class="ml-1 text-danger" style="font-size:13px">
                        The file field is required
                    </span>
                @enderror
            </div>
            <div class = "my-4">  
                <label class="my-0"><h6>Blob tier :</h6></label>  
                <div class="ml-4 mt-2">
                    <input id="hot" name="blob_tier" type="radio" value="Hot" checked>
                    <label for="hot" class="ml-1 ">Hot</label>
                    <br>
                    <input id="cool" name="blob_tier" type="radio" value="Cool">
                    <label for="cool" class="ml-1 ">Cool</label>
                    <br>
                    {{-- <input id="cold" name="blob_tier" type="radio" value="Cold" checked>
                    <label for="cold" class="ml-1 ">Cold</label>
                    <br> --}}
                    <input id="archive" name="blob_tier" type="radio" value="Archive">
                    <label for="archive" class="ml-1 ">Archive</label>
                </div>
                @error('blob-tier')
                    <span class="ml-1 text-danger" style="font-size:13px">
                        {{ str_replace("_"," ", $message) }}
                    </span>
                @enderror
            </div>
            <input type="submit" class="btn btn-success" value="Upload">
                
        </form>
    </div>

    
@endsection