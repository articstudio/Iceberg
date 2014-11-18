

$.validator.methods.multiSelectNotEmpty = function(value, element) {
    return ($('option', element).length > 0);
};