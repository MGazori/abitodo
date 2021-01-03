$(document).ready(function() {
    // define function for find empty element
    function isEmpty(el) {
        return !$.trim(el.html())
    }
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
                        $('button.sortBtn[data-sort-mode="old-first"]').click();
                        removeTaskFunc();
                        changeTaskStatus();
                    } else {
                        $("#taskList").html('<div class="emptyTask">No Task Here!</div>');
                    }
                }
            })
        })
    }
    selectTaskFolderFunc();
    //add task function
    function addTaskFunc() {
        $("#addTaskForm").submit(function(event) {
            var inputAddTask = $("#addTaskInput");
            var selectedFolder = $('.folderRow.active').attr('data-folder-id');
            event.preventDefault()
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "addTask",
                    taskTitle: inputAddTask.val(),
                    tasksFolder: selectedFolder
                },
                success: function(response) {
                    if (response.id) {
                        var addTaskID = response.id;
                        var addTaskTitle = response.title;
                        var addTaskCreated_at = response.created_at;
                        var taskRowElement = "<li data-task-id='" + addTaskID + "' class='taskRow'><i class='fa fa-square-o'></i><span>" + addTaskTitle + "</span><div class='info'><span class='task-created-at'>" + addTaskCreated_at + "</span><button class='removeTaskBtn' data-task-id='" + addTaskID + "'></button></div></li>"
                        if ($(".emptyTask").hasClass("emptyTask")) {
                            $("#taskList").html(taskRowElement);
                        } else {
                            $("#taskList").append(taskRowElement);
                        }
                        Toast.fire({
                            icon: 'success',
                            title: 'Task Added! 🎉',
                        })
                        removeTaskFunc()
                        changeTaskStatus()
                        inputAddTask.val('')
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'error:',
                            text: response.description,
                        })
                    }
                }
            })
        })
    }
    addTaskFunc();
    //ajax delete task
    function removeTaskFunc() {
        $('.removeTaskBtn').click(function(event) {
            event.stopPropagation();
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
                                if (isEmpty($('#taskList'))) {
                                    var emptyTask = "<div class='emptyTask'>No Task Here!</div>";
                                    $("#taskList").html(emptyTask);
                                }
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
    //done or undone tasks
    function changeTaskStatus() {
        $('.taskRow').click(function(e) {
            e.stopImmediatePropagation();
            $(this).toggleClass('checked');
            $(this).find('i').toggleClass('fa-square-o fa-check-square-o');
            var taskClickedId = $(this).attr('data-task-id');
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                dataType: "JSON",
                data: {
                    action: "taskDoneSwitch",
                    taskId: taskClickedId
                },
                success: function(response) {
                    if (response.is_done == 1) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Task Completed! ✔',
                        })
                        document.getElementById('task-done-audio').currentTime = 0;
                        document.getElementById('task-done-audio').play();
                    } else if (response.is_done == 0) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Task UnChecked! ❌',
                        })
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: 'error:',
                            text: response.description,
                        })
                    }
                }
            })
        })
    }
    changeTaskStatus();
    //define function for sort task
    function sortTasks() {
        $('.sortBtn').click(function() {
            $('.sortBtn').removeClass('active');
            $(this).addClass("active");
            var activeFolder = $('.folderRow.active').attr('data-folder-id');
            var sortModeAttr = $(this).attr('data-sort-mode');
            if (sortModeAttr == 'old-first') {
                var activeSortMode = "created_at_ASC";
            } else if (sortModeAttr == 'new-first') {
                var activeSortMode = "created_at_DESC";
            } else if (sortModeAttr == 'just-done') {
                var activeSortMode = "is_done_checked";
            } else if (sortModeAttr == 'just-undone') {
                var activeSortMode = "is_done_unchecked";
            }
            $.ajax({
                url: "process/ajaxHandler.php",
                method: "POST",
                data: {
                    action: "filterTasks",
                    selectedFolderId: activeFolder,
                    sortMode: activeSortMode
                },
                success: function(response) {
                    $("#taskList").html(response);
                    if (isEmpty($('#taskList'))) {
                        var emptyTask = "<div class='emptyTask'>No Task Here!</div>";
                        $("#taskList").html(emptyTask);
                    }
                    removeTaskFunc();
                    changeTaskStatus();
                }
            })
        })
    }
    sortTasks();
    //search tasks feature
    function searchTasks() {
        $('#taskSeachInput').on('keyup change', function() {
            var searchText = $(this).val();
            var searchTaskText = searchText.length;
            var activeFolder = $('.folderRow.active').attr('data-folder-id');
            if (searchTaskText > 1) {
                $.ajax({
                    url: "process/ajaxHandler.php",
                    method: "POST",
                    data: {
                        action: "searchTasks",
                        selectedFolderId: activeFolder,
                        searchTxt: searchText
                    },
                    success: function(response) {
                        $("#taskList").html(response);
                        if (isEmpty($('#taskList'))) {
                            var emptyTask = "<div class='emptyTask'>No Task Here!</div>";
                            $("#taskList").html(emptyTask);
                        }
                        removeTaskFunc();
                        changeTaskStatus();
                    }
                })
            } else {
                $.ajax({
                    url: "process/ajaxHandler.php",
                    method: "POST",
                    data: {
                        action: "getTasks",
                        selectedFolderId: activeFolder,
                    },
                    success: function(response) {
                        $("#taskList").html(response);
                        if (isEmpty($('#taskList'))) {
                            var emptyTask = "<div class='emptyTask'>No Task Here!</div>";
                            $("#taskList").html(emptyTask);
                        }
                        removeTaskFunc();
                        changeTaskStatus();
                    }
                })
            }
        });
    }
    searchTasks()
    $('#addTaskInput').focus();
})