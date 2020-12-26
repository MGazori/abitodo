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
                    var folderRow = "<li><a href='?folder_id=" + addFolderID + "'><i class='fa fa-folder'></i>" + addFoldername + "</a>" + "<a href='?delete_folder=" + addFolderID + "'><i class='fa fa-times removeFolderBtn'></i></a></li>";
                    $("#foldersList").append(folderRow)
                    Toast.fire({
                        icon: 'success',
                        title: 'Folder Added! 😊',
                    })
                    inputAddFolder.val('');
                }
            }
        })
    })
})