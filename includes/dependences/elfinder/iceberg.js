
function elFinderPushFile(file, target)
{
    var $target = $('#' + target);
    $target.val(file).trigger('change');
}

$(document).ready(function(){
    $('.elfinder').each(function(){
        $(this).elfinder({
            resizable : false,
            url : js_elfinder.api,       // connector URL (REQUIRED)
            lang: js_elfinder.lang       // language (OPTIONAL)
        }).elfinder('instance');
    });
    $(document).on('click', 'button[data-elfinder-file], a[data-elfinder-file]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).data('elfinder-file');
        window.open(js_elfinder.popup+'&action=elFinderPushFile&callbackAttr=' + target,'', 'resizable=no,width=950,height=490,status=no,menubar=no,directories=no,location=no');
        return false;
    });
    $(document).on('click', 'button[data-elfinder-callback], a[data-elfinder-callback]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var callback = $(this).data('elfinder-callback');
        var callbackAttr = $(this).data('elfinder-callback-attr');
        window.open(js_elfinder.popup+'&action=' + callback + '&callbackAttr=' + callbackAttr,'', 'resizable=no,width=950,height=490,status=no,menubar=no,directories=no,location=no');
        return false;
    });
});
