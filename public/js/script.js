$("document").ready(function(){
    $('#operation_time').timepicker({
        timeFormat: 'hh:mm p',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });
    $('.btnRemoveGroup').on('click', function(){
        swal({
            title: "Are you sure?",
            text: "You want to remove this?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
                var groupId = this.id;
                var schedId = $('#hdnSchedId').val();
                var url = window.location.origin;
                var redirect = url + '/schedules/' + schedId + '/remove/' + groupId;
                $('#frmRemoveGroup').attr('action', redirect).submit();
            }
          });
    })
});