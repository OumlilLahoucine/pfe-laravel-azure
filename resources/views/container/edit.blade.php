@extends('layout')

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-6">
                <h2>Manage <b>Containers</b></h2>
            </div>
            <div class="col-sm-6">
                <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="material-icons">&#129044;</i> <span class="mt-1">Back</span></a>
            </div>
        </div>
    </div>
    <div class="">
        @if ($alert!=='')
            <div id="alert" class="alert alert-danger text-center">
               {{ $alert }}
            </div> 
        @endif
        <form action="{{ route('container.update', $container) }}" method="POST">
            @csrf
            @method('PUT')
            <div class = "my-4">  
                <label for="container_name" class="my-0"><h6>Container name :</h6></label>  
                <input type="text" class="form-control mt-2 mb-2" disabled id="container_name" placeholder="Container name" name="container_name" value="{{ $container }}">  
            </div>
            <div class = "my-4">  
                <label class="my-0"><h6>Public access level :</h6></label>  
                <div class="ml-4 mt-2">
                    <input id="private" name="public_access_level" type="radio" value="private" {{$public_access_level == ""? 'checked' : ''}}>
                    <label for="private" class="ml-1 ">Private</label>
                    <br>
                    <input id="blob" name="public_access_level" type="radio" value="blob" {{$public_access_level == "blob"? 'checked' : ''}}>
                    <label for="blob" class="ml-1 ">Blob</label>
                    <br>
                    <input id="container" name="public_access_level" type="radio" value="containerr" {{$public_access_level == "container"? 'checked' : ''}}>
                    <label for="container" class="ml-1 ">Container</label>
                </div>
                @error('public_access_level')
                    <span class="ml-1 text-danger" style="font-size:13px">
                        {{ str_replace("_"," ", $message) }}
                    </span>
                @enderror
            </div>
            <input type="submit" class="btn btn-success" value="Edit">
                
        </form>
    </div>

    
@endsection