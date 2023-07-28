<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Blob Storage Azure</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        
    </head>
    <body>
        <div class="container-xl">
            <div class="table-responsive" style="min-width : fit-content">
                <div class="table-wrapper">
                    @yield('content')
                </div>
            </div>        
        </div>
        <!-- Add Modal HTML -->
        {{-- <div id="addContainerModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">						
                            <h4 class="modal-title">Add Container</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">					
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="container_name" maxlength="63" minlength="3" required>
                            </div>
                            <div class="form-group">
                                <label>Public access level</label>
                                <ul style="list-style: none;">
                                    <li><input type="radio" name="public-access-level" value="private"> Private</li>
                                    <li><input type="radio" name="public-access-level" value="blob"> Blob</li>
                                    <li><input type="radio" name="public-access-level" value="container"> Container</li>
                                </ul>
                            </div>					
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="button" id="create-container-button" class="btn btn-success" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        <!-- Edit Modal HTML -->
        {{-- <div id="editContainerModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">						
                            <h4 class="modal-title">Edit Container</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">					
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" maxlength="63" minlength="3" required>
                            </div>
                            <div class="form-group">
                                <label>Public access level</label>
                                <ul style="list-style: none;">
                                    <li><input type="radio" name="public-access-level" value="private"> Private</li>
                                    <li><input type="radio" name="public-access-level" value="blob"> Blob</li>
                                    <li><input type="radio" name="public-access-level" value="container"> Container</li>
                                </ul>
                            </div>					
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn btn-info" value="Save">
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        <!-- Delete Modal HTML -->
        {{-- <div id="deleteContainerModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">						
                            <h4 class="modal-title">Delete Container : <script>alert(containerToDelete);</script></h4>
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
        </div> --}}
        
        {{-- <script src="scripts/index.js"></script> --}}
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        {{-- <script defer src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
        <script defer src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script defer src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function(){
                // Data Table
                $('#containers').DataTable();

                // Activate tooltip
                // $('[data-toggle="tooltip"]').tooltip();
                
                // Select/Deselect checkboxes
                var checkbox = $('table tbody input[type="checkbox"]');
                $("#selectAll").click(function(){
                    if(this.checked){
                        checkbox.each(function(){
                            this.checked = true;                        
                        });
                    } else{
                        checkbox.each(function(){
                            this.checked = false;                        
                        });
                    } 
                });
                checkbox.click(function(){
                    if(!this.checked){
                        $("#selectAll").prop("checked", false);
                    }
                });
            });
        </script>
    </body>
</html>