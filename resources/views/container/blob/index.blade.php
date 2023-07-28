@extends('layout')

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-6">
                <h2>Manage <b>{{ $container }}</b></h2>
            </div>
            <div class="col-sm-6">
                <a href="/container/{{ $container }}/create" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Upload New Files</span></a>
                <a href="#deleteBlobsModal" class="btn btn-danger" data-toggle="modal" onclick="showDeleteModal('blobs')"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>						
            </div>
        </div>
    </div>
    @if ($alert!=='' && $alert_type!=='')
        <div id="alert" class="alert text-center {{$alert_type}}">
            {{ $alert }}
        </div>
    @endif
    <table class="table table-hover" id="containers">
        <thead>
            <tr>
                <th>
                    <span class="custom-checkbox">
                        <input type="checkbox" id="selectAll" onclick='selectAll(this)'>
                        <label for="selectAll"></label>
                    </span>
                </th>
                <th>Name</th>
                <th>Created on</th>
                <th>Blob tier</th>
                <th>Content type</th>
                <th>Size</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($blobs))
                @foreach ($blobs as $blob)
                    <tr>
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="{{$blob['name']}}" name="options[]" value="{{$blob['name']}}" onclick="changeContainerToDelete(this)">
                                <label for="{{$blob['name']}}"></label>
                            </span>
                        </td>
                        <td>{{$blob['name']}}</td>
                        <td>{{$blob['created_on']}}</td>
                        <td>{{$blob['blob_tier']}}</td>
                        <td>{{$blob['content_type']}}</td>
                        <td>{{$blob['size']}} Kib</td>
                        <td>
                            <div class="d-flex">
                                <a href="/container/{{ $container }}/{{ $blob['name'] }}" class="view"><i class="material-icons" data-toggle="tooltip" title="view">visibility</i></a>
                                <a href="/container/{{ $container }}/{{ $blob['name'] }}/download" class="download"><i class="material-icons" data-toggle="tooltip" title="download">download</i></a>
                                <a href="#deleteBlobModal{{ str_replace(['-', '.', ',', ':', '?', '\'', '\"', '!'], '', $blob['name']) }}" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                
            @endif
        </tbody>
        
    </table>

    <!-- Delete One Container Modal HTML -->
    @if (count($blobs))
        @foreach ($blobs as $blob)
            <div id="deleteBlobModal{{ str_replace(['-', '.', ',', ':', '?', '\'', '\"', '!'], '', $blob['name']) }}" class="modal fade text-center pr-0">
                <div class="modal-dialog" style="min-width:fit-content">
                    <div class="modal-content" style="min-width:min-content; text-align: left">
                        <div class="modal-header">						
                            <h6 class="modal-title" style="line-height: 2em; font-size: 17px"><b>Delete blob :</b> {{$blob['name']}}</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">		
                            <p>Are you sure you want to delete these Records?</p>
                            <p class="text-warning"><small>This action cannot be undone.</small></p>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <a href="/container/{{ $container }}/{{ $blob['name'] }}/delete" class="btn btn-danger">Delete</a>
                            
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Delete List Of Containers Modal HTML -->
    <div id="deleteBlobsModal" class="modal fade text-center pr-0">
        <div class="modal-dialog" style="min-width:fit-content">
            <div class="modal-content" style="min-width:min-content; text-align: left">
                <form action="/container/deleteBlobs" method="POST">
                    @csrf

                    <div class="modal-header">						
                        <h5 class="modal-title"style="line-height: 2em; font-size: 17px"><b>Delete Blobs :  </b><span id="modal-title"></span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>               
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="hidden" name="container" value="{{ $container }}">
                        <span id="params">

                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{asset('js/script.js')}}"></script>

    
@endsection