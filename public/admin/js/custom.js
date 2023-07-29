var lang = $('html').attr('lang');
var row;
var audio = new Audio('/admin/skype.mp3');
var ChartColors = ["#71d1bd","#7bb6dd","#ffc04d","#d78f89", "rgb(62,149,205,0.1)","#c45850","#ffa500","#3cba9f"];
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    error : function(jqXHR, textStatus, error) {
        if (jqXHR.status == 404) {
            alert("Element not found.");
            //toastr.error(error, 'Error !', {"closeButton": true, positionClass: 'toast-top-left', containerId: 'toast-top-left'});
        } else {
            alert("Error: " + textStatus + ": " + error);
        }
    }
});
/**
 * Axios set CSRF token
 * @type {*|jQuery}
 */
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';
window.axios.defaults.headers.common['Accept'] = 'application/json';

function CustomAlert(type,msg,title){
    $('.toast-container').remove();
    pos = ((lang == 'ar') ? 'toast-top-left': 'toast-top-right')
    switch (type){
        case 'error':
            if(title==null)
                title = lang == 'en' ? 'Error !' : ' خطأ ! ';
            toastr.error(msg, title, {"closeButton": true, positionClass: pos, containerId: pos});
        break;

        case 'info':
            if(title==null)
                title = lang == 'en' ? 'Information !' : ' معلومات ! ';
            toastr.info(msg, title, {"closeButton": true, positionClass: pos, containerId: pos});
        break;

        case 'success':
            if(title==null)
                title = lang == 'en' ? ' Successfully !' : ' بنجاح ! ';
            toastr.success(msg, title, {"closeButton": true, positionClass: pos, containerId: pos});
        break;

        case 'warning':
            if(title==null)
                title = lang == 'en' ? 'Warning !' : ' تحذير ! ';
            toastr.warning(msg, title, {"closeButton": true, positionClass: pos, containerId: pos});
            break;
    }
}


function deleteRecord($routeName,clicked){
    row = $(clicked).parents('tr');
    var text = {
        'title': lang == 'en' ? "Confirm Delete" : "تأكيد الحذف",
        'text': lang == 'en' ? "Delete the clicked data" : "حذف البيانات المحددة",
        'confirmButtonText': lang == 'en' ? "Delete it" : "نعم, تأكيد الحذف!",
        'cancelButtonText': lang == 'en' ? "No, cancel please!" : "لا, لاتقم بالحذف!",
    };
    swal.fire({
        icon:'info',
        title: text.title,
        text: text.text,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#DA4453",
        confirmButtonText: text.confirmButtonText,
        cancelButtonText: text.cancelButtonText,
    }).then(function(result) {
        if (result.isConfirmed) {
            $.post($routeName,{'_method':'DELETE','_token':$('meta[name="csrf-token"]').attr('content')},function(response){
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
function sendRenewalNotification($routeName) {
    var text = {
        'title': lang == 'en' ? "Confirm Send" : "تأكيد الارسال",
        'text': lang == 'en' ? "Send notification to subscription" : "ارسال اشعار الي الاشتراك المحدد",
        'confirmButtonText': lang == 'en' ? "Send it" : "نعم, تأكيد الارسال!",
        'cancelButtonText': lang == 'en' ? "No, cancel please!" : "لا, لاتقم بالارسال!",
    };
    swal.fire({
        icon:'info',
        title: text.title,
        text: text.text,
        type: "error",
        showCancelButton: true,
        confirmButtonColor: "#DA4453",
        confirmButtonText: text.confirmButtonText,
        cancelButtonText: text.cancelButtonText,
    }).then(function(result) {
        if (result.isConfirmed) {
            $.post($routeName,{'_method':'POST','_token':$('meta[name="csrf-token"]').attr('content')},function(response){
                CustomAlert(response.type,response.msg,response.title);
            },'json');
        } else {
            return false;
        }
    });
}

function incomingVideoCall(data, callURL){
    let patient_id = data.patient_id;
    let token = data.token;
    let patient_name = data.patient_fullname;
    let nano_clinic = data.nano_clinic;
    let call_id = data.call_id;
    let coupon_name = data.coupon_name ?? '';
    if(!nano_clinic){
        nano_clinic = 'Patient APP';
    }
    audio.play();
    var text = {
        'title': lang == 'en' ? "Incoming call" : "مكالمة فيديو",
        'html': (lang == 'en' ? "Video call from "+patient_name+(nano_clinic.length ? " From clinic "+ nano_clinic :null ) : "مكالمة من "+ patient_name + (nano_clinic.length ? " من عيادة " + nano_clinic : null))+(coupon_name.length ? ' <span class="tag tag-pill tag-default tag-info tag-default">#'+coupon_name+'</span>' : null),
        'confirmButtonText': lang == 'en' ? "Answer call" : "الرد على المكالمة",
        'cancelButtonText': lang == 'en' ? "Reject" : "رفض",
    };
    swal.fire({
        title: text.title,
        html: text.html,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#21a59d",
        cancelButtonColor:"#ff7588",
        allowOutsideClick: false,
        allowEscapeKey: false,
        closeOnClickOutside: false,
        timer: 1000 * 20, //20 seconds
        confirmButtonText: text.confirmButtonText,
        cancelButtonText: text.cancelButtonText,
    }).then(function(result) {
            audio.pause();
            if (result.isConfirmed) {
                openPopUp(callURL);
                window.location.href = window.location.origin + '/administrators/patients/'+patient_id;
                return;
            }
            if (
                // dismissed by timer
                result.dismiss === swal.DismissReason.timer
                // dissmised by cancel or closed
                || result.dismiss === swal.DismissReason.cancel || result.dismiss === swal.DismissReason.close
            ) {
                // lets redirect call only works for 5 minutes
                axios.get(window.location.origin + '/administrators/call-gp/'+call_id+'/redirect')
                    .then((response)=>{
                        CustomAlert('info','Call redirected to another GP','Notice');
                    })
                    .catch(()=>{
                        CustomAlert('error','Could not redirect call','call reject error');
                    });
            }
    });
}

function updateTimezone(data){
    let user_id = data.user_id;
    let timezone = data.timezone;
    var text = {
        'title': lang == 'en' ? "Update timezone" : "تغير الموقع",
        'text': lang == 'en' ? "Did you change your location? " : "هل غيرت مكانك؟ ",
        'confirmButtonText': lang == 'en' ? "Change" : "تغير",
        'cancelButtonText': lang == 'en' ? "No Thanks" : "رفض",
    };
    swal.fire({
        title: text.title,
        text: text.text,
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#21a59d",
        cancelButtonColor:"#ff7588",
        allowOutsideClick: false,
        // timer: 1000 * 20, //25 seconds
        confirmButtonText: text.confirmButtonText,
        cancelButtonText: text.cancelButtonText,
    }).then(function(result) {
        if (result.isConfirmed) {
            window.location.href = window.location.origin + '/administrators/updateTimezone?user_id='+user_id+'&timezone='+timezone;
            return;
        }
    });
}

function OptstoSelect(data,sel,val,text){
    $(sel).find('option').remove();
    $.each(data,function (key,val) {
        $('<option>').text(val).val(key).appendTo(sel);
    });
    /*
    for (var i = 0; i < data.length; i++){
        console.log(data[i]);
        seval = data[i][val];
        setext = data[i][text];

    }
    */
}

$(function(){
    $('.datepicker').datetimepicker({
        autoclose: 1,
        format: 'yyyy-mm-dd',
        startView:2,
        minView:2,
        language:lang
    });
    $('.datetimepicker').datetimepicker({
        autoclose: 1,
        format: 'yyyy-mm-dd h:i',
        startView:2,
        minView:0,
        language:lang
    });
    // $('.timepicker').datetimepicker({
    //     autoclose: 1,
    //     format: 'h:i',
    //     startView:0,
    //     minView:0,
    //     language:lang
    // });
    $('.timepicker').datetimepicker({
        autoclose: 1,
        startView:0,
        minView:0,
        language:lang,
        // format:'HH:i',
        format: 'hh:ii',
    });
});


function openPopUp(url){
    window.open(url,'targetWindow',`toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=660,height=500`);
    return false;
}

function changeUserStatusUI(status)
{
    $("#userStatus").removeClass("avatar-online avatar-off avatar-busy");
    if(status == 'OFFLINE'){
        $("#userStatus").addClass("avatar-off");
    } else if(status == 'ONLINE'){
        $("#userStatus").addClass("avatar-online");
    } else if(status == 'BUSY'){
        $("#userStatus").addClass("avatar-busy");
    }
}

function setUserStatus(user_status){
    $.ajax({
        dataType: 'json',
        type      : 'GET',
        url       : '/administrators/setuserstatus?user_status='+user_status,
        success   : function(data) {
            $("#userStatus").removeClass("avatar-online avatar-off avatar-busy");
            if(data.status == 'OFFLINE'){
                $("#userStatus").addClass("avatar-off");
            } else if(data.status == 'ONLINE'){
                $("#userStatus").addClass("avatar-online");
            } else if(data.status == 'BUSY'){
                $("#userStatus").addClass("avatar-busy");
            }

        }
    });
}

function generateDatatableCode(referenceTable, languageUrl, displayLength, dataUrl, cols) {
    $(referenceTable).DataTable({
        language: {
            url: languageUrl
        },
        "iDisplayLength": displayLength,
        processing: true,
        serverSide: true,
        columns: JSON.parse("[" + cols.replace(/&quot;/g, '"') + "]"),
        // "order": [[ 'id', "desc" ]],
        "order": [[ 0, "desc" ]],
        "ajax": {
            "url": dataUrl,
            "type": "GET",
            "data": function (data) {
                data = "true";
            }
        },
        "fnPreDrawCallback": function(oSettings) {
            for (var i = 0, iLen = oSettings.aoData.length; i < iLen; i++) {
                if(oSettings.aoData[i]._aData['status'] != '') {
                    // oSettings.aoData[i].nTr.className = oSettings.aoData[i]._aData['status'];
                }
            }
        }
    });
}

function price (amount, fraction) {
    return amount.toFixed(fraction ? 2 : 0) + ' ' + 'LE';
}



// $(function(){
//     setInterval(function () {
//             $.ajax({
//                 type: "get",
//                 url: "administrators/filterationGP",
//                 success: function (response) {
//                 }
//             });
//         }
//         , 1000 * 60 * 10);
// });
function ChartNoData(chartObj) {
    let ctx = chartObj.ctx;
    let width = chartObj.width;
    let height = chartObj.height;
    chartObj.clear();
    ctx.save();
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('No data to display', width / 2, height / 2);
    ctx.restore();
}

function chartFormResponse(chartParentId, route) {

    const canvas = document.createElement("canvas");
    canvas.setAttribute('id', chartParentId + '_chart');
    document.querySelector('[data-id=' + chartParentId + ']').appendChild(canvas);

    var formData = new FormData(document.getElementById(chartParentId + '_form'));

    var chart_element_with_canvas = new Chart(canvas.getContext('2d'), {
        type: formData.get('type')
    });

    axios({
        method: 'POST',
        url: route,
        data: formData
    }).then((response) => {
        let data = response.data.data;
        chart_element_with_canvas.data.labels = data.labels;
        Object.entries(data.lines).forEach(function callback(lineData, index) {
            chart_element_with_canvas.data.datasets.push({
                data: lineData[1],
                label: formData.get('label') ?? lineData[0],
                backgroundColor: ChartColors[Math.floor(Math.random()*ChartColors.length)],
                fill: false,
            });
        });
        chart_element_with_canvas.update();
    }).catch(error => {
        ChartNoData(chart_element_with_canvas);
    });
}
