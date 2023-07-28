@extends('layout')

@section('content')
    <div class="table-title">
        <div class="row">
            <div class="col-sm-6">
                <h2>Manage <b>Containers</b></h2>
            </div>
            <div class="col-sm-6">
                <a href="{{ route('container.create') }}" class="btn btn-success"><i class="material-icons">&#xE147;</i> <span>Add New Container</span></a>
                <a href="#deleteContainersModal" class="btn btn-danger" data-toggle="modal" onclick="showDeleteModal('containers')"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>						
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
                <th>Last modification</th>
                <th>Public access level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if (count($containers))
                @foreach ($containers as $container)
                    <tr>
                        <td>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="{{$container['name']}}" name="options[]" value="{{$container['name']}}" onclick="changeContainerToDelete(this)">
                                <label for="{{$container['name']}}"></label>
                            </span>
                        </td>
                        <td>{{$container['name']}}</td>
                        <td>{{$container['last_modification'] }}</td>
                        <td>{{ ucfirst($container['public_access_level']) }}</td>
                        <td>
                            <a href="{{ route('container.show', $container['name']) }}" class="view"><i class="material-icons" data-toggle="tooltip" title="view">visibility</i></a>
                            <a href="{{ route('container.edit', $container['name']) }}" class="edit"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteContainerModal{{str_replace(['-', '.', ',', ':', '?', '\'', '\"', '!'], '', $container['name'])}}" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                @endforeach
                
            @endif
        </tbody>
        
    </table>

    <!-- Delete One Container Modal HTML -->
    @if (count($containers))
        @foreach ($containers as $container)
            <div id="deleteContainerModal{{str_replace(['-', '.', ',', ':', '?', '\'', '\"', '!'], '', $container['name'])}}" class="modal fade etxt-center pr-0">
                <div class="modal-dialog" style="min-width:fit-content">
                    <div class="modal-content" style="min-width:min-content; text-align: left">
                        <form id="formForDelete" action="{{ route('container.destroy', $container['name']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">						
                                <h6 class="modal-title" style="line-height: 2em; font-size: 17px"><b>Delete Container :</b> {{$container['name']}}</h6>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">		
                                <p>Are you sure you want to delete these Records?</p>
                                <p class="text-warning"><small>This action cannot be undone.</small></p>
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                                <input type="submit" class="btn btn-danger" value="Delete">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Delete List Of Containers Modal HTML -->
    <div id="deleteContainersModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/container/deleteContainers" method="POST">
                    @csrf

                    <div class="modal-header">						
                        <h5 class="modal-title"style="line-height: 2em; font-size: 17px"><b>Delete Containers :  </b><span id="modal-title"></span> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>               
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <span id="params">

                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{asset('js/script.js')}}"></script>

    
@endsection