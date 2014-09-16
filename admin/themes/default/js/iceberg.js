/* EVENTS */
function addIcebergEventListener(event_name, callback) {
    if (typeof event_name === 'string' && typeof callback !== 'undefined') {
        if(window.addEventListener){ console.log('a');
            window.addEventListener(event_name, callback, false);
        } else if(window.attachEvent){ console.log('b');
            window.attachEvent(event_name, callback);
        } else{ console.log('c');
           document.addEventListener(event_name, callback, false);
        }
    }
}

/* JS I18N */
function get_text(key)
{
    if(_.isObject(js_iceberg_i18n) && _.has(js_iceberg_i18n, key))
    {
        return _.result(js_iceberg_i18n, key) + '';
    }
    return '';
}

/* JS ALERTS */
function showAlert(message, type)
{
    var html = '';
    if (type === 'success')
    {
        html = '<p class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-ok icon-white"></i> <strong>' + message + '</strong></p>';
    }
    else if (type === 'success')
    {
        html = '<p class="alert alert-info"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-hand-right icon-white"></i> <strong>' + message + '</strong></p>';
    }
    else
    {
        html = '<p class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="icon-warning-sign icon-white"></i> <strong>' + message + '</strong></p>';
    }
    
    if (html !== '')
    {
        $('#alerts .alert').alert('close');
        $('#alerts').append(html);
    }             
}


/* LOADING */
function IcebergLoading(sms)
{
    if (typeof sms == 'string' && sms != '')
    {
        $('#loading .title').html(sms);
    } else {
        $('#loading .title').html('').hide(0);
    }
    $('body').addClass('loading');
    $('.loading a, .loading input, .loading button').attr('disabled', 'disabled').bind('click', function(e){
        e.preventDefault();
        e.stopPropagation();
    });
}
function IcebergUnloading()
{
    $('body').removeClass('loading');
    $('.loading a, .loading input, .loading button').removeAttr("disabled").unbind('click', function(e){
        e.preventDefault();
        e.stopPropagation();
    });
}

/* MODAL */
var _modal_options = {}, _modal_defaults = {
    title: '',
    content: '',
    ajax: false,
    buttons: {
        close: true,
        save: false
    },
    callback: {
        close: function(){},
        save: function(){}
    }
}
function openIcebergModal(args) {
    _modal_options = _.defaults(args, _modal_defaults);
    if (args.ajax) {
        IcebergLoading();
        $.getJSON(ajax, function(data){
            IcebergUnloading();
            _modal_options.ajax = false;
            args = _.defaults(data, _modal_options);
            openIcebergModal(args);
        }).error(function(){
            IcebergUnloading();
        });
        return false;
    } else {
        var $elem = $('#iceberg-modal');
        if (typeof _modal_options.title === 'string' && _modal_options.title !== '')
        {
            $('.modal-header h3', $elem).html(_modal_options.title);
            $('.modal-header', $elem).show(0);
        } else {
            $('.modal-header h3', $elem).html('');
            $('.modal-header', $elem).hide(0);
        }
        $('.modal-body', $elem).html(_modal_options.content);
        if (_modal_options.buttons.close) {
            $('.modal-footer .button-close', $elem).unbind('click').show(0).bind('click', function(e){
                return closeIcebergModel(e, false);
            });
        } else {
            $('.modal-footer .button-close', $elem).unbind('click').hide(0);
        }
        if (_modal_options.buttons.save) {
            $('.modal-footer .button-save', $elem).unbind('click').show(0).bind('click', function(e){
                return closeIcebergModel(e, true);
            });
        } else {
            $('.modal-footer .button-save', $elem).unbind('click').hide(0);
        }
        $elem.modal({
            backdrop: true,
            keyboard: true,
            show: true,
            remote: false
        });
    }
    return true;
}
function closeIcebergModel(e, save) {
    e.preventDefault();
    e.stopPropagation();
    var $elem = $('#iceberg-modal');
    $elem.modal('hide');
    callbackIcebergModal(save);
    return false;
}
function callbackIcebergModal(save) {
    var callback = save ? _modal_options.callback.save : _modal_options.callback.close;
    if (typeof callback === 'function') { 
        callback(); 
    }
    if (typeof callback === 'string' && typeof window[callback] === 'function') { 
        window[callback](); 
    }
    if (typeof callback === 'string' && _.isURL(callback)) { 
        location.href = callback;
    }
}
function SearchIcebergConfirm()
{
    $('a[confirm]').each(function(){
        $(this).bind('click', function(e){
            e.preventDefault();
            e.stopPropagation();
            var args = {
                title: js_iceberg_i18n.confirm_modal_title,
                content: $(this).attr('confirm'),
                buttons: {
                    close: true,
                    save: true
                },
                callback: {
                    save: $(this).attr('href')
                }
            };
            openIcebergModal(args);
        })
    });
}

/* Validate Forms */
function SearchIcebergValidatite() {
    $('form[validate]').each(function(){
        $(this).validate({
            highlight: function (element, errorClass, validClass) {
                $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).closest('.control-group').removeClass('error').addClass('success');
            },
            success: function (label) {
                $(label).closest('form').find('.valid').removeClass('invalid');
            },
            errorPlacement: function(err, element) {
                return false;
            },
            onkeyup: false,
            onclick: false,
            onsubmit: true
            /*showErrors: function(errorMap, errorList) {
                $.each(this.successList, function(index, value) {
                    return $(value).popover('hide');
                });
                return $.each(errorList, function(index, value) {
                    var _popover;
                    _popover = $(value.element).popover({
                        trigger: 'manual',
                        placement: 'top',
                        content: value.message,
                        template: '<div class="popover"><div class="arrow"></div><div class="popover-inner"><div class="popover-content"><p></p></div></div></div>'
                    });
                    _popover.data('popover').options.content = value.message;
                    return $(value.element).popover('show');
                });
            }*/
        });
    });
}

$(document).ready(function(){
    
    /* Confirm Dialogs */
    SearchIcebergConfirm();
    
    /* Form validations */
    SearchIcebergValidatite();
    
    
    
    /******************** MODERNIZR *********************************/
    /* PLACEHOLDER */
    if(!Modernizr.input.placeholder){
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
            $(this).find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });
    }
    
    
});


