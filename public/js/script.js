let recordsToDelete = []
        const changeContainerToDelete = (checkbox) => {
            let val = checkbox.value;
            if(checkbox.checked){
                recordsToDelete.push(val);
            }else{
                recordsToDelete = recordsToDelete.filter(container => container !== val)
            }
            console.log(recordsToDelete);
        }

        const selectAll = (checkbox) => {
            recordsToDelete = []
            if(checkbox.checked){
                const checkboxes = document.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    if (checkbox.id != "selectAll") {
                        recordsToDelete.push(checkbox.id);
                    }
                });
            }
            console.log(recordsToDelete);
        }

        const showDeleteModal = (name) => {
            let modalBodyContent;

            if(recordsToDelete.length!=0){
                let params = "";

                modalBodyContent = "<div><ul style='line-height:26px'>"
                    recordsToDelete.forEach(function(record){
                        modalBodyContent += "<li>"+record+"</li>"
                        params += '<input type="hidden" class="btn btn-danger" name="'+name+'[]" value="'+record+'">';
                    });
                modalBodyContent += "<ul></div>"
                modalBodyContent += "<p>Are you sure you want to delete these Records?</p><p class='text-warning'><small>This action cannot be undone.</small></p>";
                    
                params += '<input type="submit" class="btn btn-danger" value="Delete">';
                document.getElementById("params").innerHTML = params;
            }else{
                modalBodyContent = "<p>There are no selected Records to delete!</p>";
            }

            document.getElementById('modal-body').innerHTML = modalBodyContent;

            document.getElementById('modal-title').innerHTML = recordsToDelete.length + ' ' + name;
        }

setTimeout(function () {
    // Closing the alert
    $('#alert').alert('close');
}, 50000);