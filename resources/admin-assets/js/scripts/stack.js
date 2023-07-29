function deleteRecord($routeName,clicked){
    row = $(clicked).parents('tr');
    var text = {
        'title': lang == 'en' ? "Confirm Delete" : "تأكيد الحذف",
        'text': lang == 'en' ? "Delete the clicked data" : "حذف البيانات المحددة",
        'confirmButtonText': lang == 'en' ? "Delete it" : "نعم, تأكيد الحذف!",
        'cancelButtonText': lang == 'en' ? "No, cancel please!" : "لا, لاتقم بالحذف!",
    };
    swal({
        title: text.title,
        text: text.text,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#DA4453",
        confirmButtonText: text.confirmButtonText,
        cancelButtonText: text.cancelButtonText,
        closeOnConfirm: true,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            BlockUi();
            $.post($routeName,{'_method':'DELETE','_token':$('meta[name="csrf-token"]').attr('content')},function(response){
                $.unblockUI();
                CustomAlert(response.type,response.msg,response.title);
                if(response.type=='success') {
                    $(row).remove();
                }
            },'json');
        } else {
            return false;
        }
    });
}

function BlockUi(){
    $.blockUI({
        message: '<div class="ft-refresh-cw icon-spin font-medium-2"></div>',
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
}
