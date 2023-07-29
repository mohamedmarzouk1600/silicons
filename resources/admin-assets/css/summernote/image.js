$(document).ready(function() {
  $('#body').summernote({
    height: 400,
    tabsize: 2,
    direction: 'ltr',
    onImageUpload: function(files) {
        sendFile(files[0]);
    }
  });
});

// send the file

function sendFile(file) {
        data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
                return myXhr;
            },
            url: url + '/image',
            cache: false,
            contentType: false,
            processData: false,
            success: function(imgUrl) {
                //editor.insertImage(welEditable, url);
                $('.summernote').summernote('editor.insertImage', imgUrl);
            }
        });
}

// update progress bar

function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded, max:e.total});
        // reset progress on complete
        if (e.loaded == e.total) {
            $('progress').attr('value','0.0');
        }
    }
}


$(function(){
    $('#keywords').focus(function(){
        var title = $('#title').val();
        var newtitle = title.replace(/ /g, ',');
        $('#keywords').val(newtitle);
    });
});