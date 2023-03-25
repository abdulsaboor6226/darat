function doGet(url, params, div) {
    params = params || {};
    $.get(url, params, function (response) { // requesting url which in form
        $(div).html(response); // getting response and pushing to element with id #response
        loadingStop()
    }).fail(function (e) {
        loadingStop()
        let errors = Object.entries(e.responseJSON.errors)
        if (errors) {
            alert(errors[0][1][0])
        }
    });
}



function inputBind() {
    $('input').bind("keypress", function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });
}

function openFullWindowLink(url) {
    window.open(url, '_blank', 'height=' + (screen.height - 110) + ',width=' + (screen.width - 50) + ',resizable=yes,scrollbars=yes,toolbar=yes,menubar=yes,location=yes')
}

function loadingStart(title = null) {
    return new  swal({
        title: title ? title : 'Loading',
        allowEscapeKey: false,
        allowOutsideClick: false,
        onOpen: () => {
            swal.showLoading()
        }
    });
}

function loadingStop() {
    swal.close()
}

function showSuccess(title) {
    return new  swal({
        position: 'top-end',
        type: 'success',
        title: title,
        buttons: false,
        timer: 1500
    })
}

function showWarn(title) {
    return new   swal({
        position: 'center',
        type: 'warning',
        title: title,
        showConfirmButton: true,
    })
}



function deleteRecordAjax(url) {

    return new    swal({
        title: 'Are you sure?',
        text: 'This record and it`s details will be permanantly deleted!',
        icon: 'warning',
        buttons: ["Cancel", "Yes!"],
    }).then((willDelete) => {
        if (willDelete) {
        $.ajax({
            type: 'DELETE',
            url: url,
            headers:
                {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            success: function (data) {
                showSuccess('Record Deleted Successfully.');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (error) {
                let message = 'Network error';
                if (error.responseJSON) {
                    message = error.responseJSON.message
                }
                showWarn(message)
            }
        });
    }
    });
}
