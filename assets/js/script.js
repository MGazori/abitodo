$(document).ready(function() {
    //define setting for sweetalert2 toast mode
    const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-start',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            showClass: {
                popup: 'animate__animated animate__fadeInUp animate__faster',
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut animate__faster',
            }
        })
        //submit add folder form
    $("#addFolderForm").submit(function(event) {
            var inputAddFolder = $("#addFolderInput");
            event.preventDefault()
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "addFolder",
                    folderName: inputAddFolder.val()
                },
                success: function(response) {
                    if (response.name == "addFolderError") {
                        Toast.fire({
                            icon: 'error',
                            title: 'error:',
                            text: response.description,
                        })
                    } else {
                        var addFolderID = response[0].id;
                        var addFoldername = response[0].name;
                        var folderRow = "<li class='folderRow' data-folder-id='" + addFolderID + "'><span class='folderTitle' data-folder-id='" + addFolderID + "'>" + addFoldername + "</span><button class='removeFolderBtn' data-folder-id='" + addFolderID + "'></button></li>";
                        $("#foldersList").append(folderRow);
                        Toast.fire({
                            icon: 'success',
                            title: inputAddFolder.val() + ' Folder Added! 😊',
                        })
                        inputAddFolder.val('');
                        removeFolderFunc();
                        selectTaskFolderFunc();
                    }
                }
            })
        })
        //ajax delete folder
    function removeFolderFunc() {
        $('.removeFolderBtn').click(function() {
            var folderId = $(this).attr('data-folder-id');
            var folderTitle = $('span.folderTitle[data-folder-id=' + folderId + ']').html();
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to delete " + folderTitle + " Folder",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "process/ajaxHandler.php",
                        method: "POST",
                        dataType: "JSON",
                        data: {
                            action: "deleteFolder",
                            deleteFolderId: folderId
                        },
                        success: function(response) {
                            if (response == 1) {
                                Toast.fire({
                                    icon: 'success',
                                    title: folderTitle + ' Folder Deleted! 😊',
                                })
                                $('.folderRow[data-folder-id=' + folderId + ']').remove();
                                $('li.folderRow[data-folder-id="all"]').click();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'error:',
                                    text: response.description,
                                })
                            }
                        }
                    })
                }
            })
        })
    }
    removeFolderFunc();
    //select task Folder
    function selectTaskFolderFunc() {
        $('li.folderRow').click(function() {
            var folderId = $(this).attr('data-folder-id');
            $('.folderRow').removeClass('active');
            $(this).addClass("active");
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                data: {
                    action: "selectFolder",
                    folderSelectedId: folderId
                },
                success: function(response) {
                    if (response.length > 0) {
                        $("#taskList").html(response);
                        removeTaskFunc()
                    } else {
                        $("#taskList").html('<div class="emptyTask">No Task Here!</div>');
                    }
                }
            })
        })
    }
    selectTaskFolderFunc();
    //ajax delete task
    function removeTaskFunc() {
        $('.removeTaskBtn').click(function() {
            var taskId = $(this).attr('data-task-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "process/ajaxHandler.php",
                        method: "POST",
                        dataType: "JSON",
                        data: {
                            action: "deleteTask",
                            deleteTaskId: taskId
                        },
                        success: function(response) {
                            if (response == 1) {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Task Deleted! 😊',
                                })
                                $('.taskRow[data-task-id=' + taskId + ']').remove();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'error:',
                                    text: response.description,
                                })
                            }
                        }
                    })
                }
            })
        })
    }
    removeTaskFunc();
})