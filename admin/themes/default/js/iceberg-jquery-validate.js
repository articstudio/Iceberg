
$.validator.methods.multiSelectMinItems = function(value, element, param) {
    return ($('option', element).length >= param);
};

function initFormValidate()
{
    $('form[validate]').each(function(){
        $(this).validate({
            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'help-block-error',
            errorPlacement: function(error, element) {}
        });
    });
}



$(document).ready(function(){
    initFormValidate();
});