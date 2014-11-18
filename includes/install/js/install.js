
function initSidebar()
{
    $(".sidebar-toggle").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#wrapper').toggleClass('toggled');
        return false;
    });
}

function initForms()
{
    /*$('form').submit(function(){
        $('form button[type=submit]').prop('disabled', true);
    });*/
}

/** STEP 1 **/
function initStep1()
{
    $('#language-select').change(function(){
        $('#form-language').submit();
    });
}

/** STEP 3 **/
function initStep3()
{
    // DOMAINS
    $('#domain-plus').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var domain = $('#domain').val();
        if (domain !== '') {
            $('#domains').append($("<option/>", {
                value: domain,
                text: domain
            }));
            $('#domain').val('');
        }
        return false;
    });
    $('#domain-minus').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#domains option:selected").remove();
        return false;
    });
    // ROOT
    $('#password-generate').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var length=8;
        var sPassword = '';
        var noPunction = true;
        for (i=0; i < length; i++) {
            numI = getRandomNum();
            if (noPunction) {while (checkPunc(numI)) {numI = getRandomNum();}}
            sPassword = sPassword + String.fromCharCode(numI);
        }
        $('#password').val(sPassword);
        return false;
    });
    // DATABASES
    $('#db-plus').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var dbhost = $('#dbhost').val();
        var dbport = $('#dbport').val();
        var dbname = $('#dbname').val();
        var dbuser = $('#dbuser').val();
        var dbpass = $('#dbpass').val();
        var dbcollate = $('#dbcollate').val();
        if (dbhost!=='' && dbport!=='' && dbname!=='' && dbuser!=='') {
            $('#dbs').append($("<option/>", {
                value: '["' + dbhost + '","' + dbport + '","' + dbuser + '","' + dbpass + '","' + dbname + '","' + dbcollate + '"]',
                text: dbhost + ':' + dbport + ' / ' + dbname + ' / ' + dbuser + ':' + dbpass
            }));
            /*value: "['" + dbhost + "','" + dbport + "','" + dbuser + "','" + dbpass + "','" + dbcollate + "']",*/
            $('#dbname, #dbuser, #dbpass').val('');
            $('#dbhost').val('localhost');
            $('#dbport').val('3306');
            $('#dbcollate').removeAttr('selected').find(':first').attr('selected', 'selected');
        }
        return false;
    });
    $('#db-minus').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#dbs option:selected").remove();
        return false;
    });
    // FORM
    $('#form-config').validate({
        rules: {
            username: {
                minlength: 3,
                maxlength: 20,
                required: true
            },
            password: {
                minlength: 3,
                maxlength: 20,
                required: true
            },
            email: {
                email: true,
                required: true
            },
            dbs: 'multiSelectNotEmpty'
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {},
        submitHandler: function(form) {
            var list
            list = new Array;
            $("#domains option").each(function(){list.push( $(this).val() );});
            list = JSON.stringify(list, null, 2);
            $('#domainslist').val(list);
            list = new Array;
            $("#dbs option").each(function(){list.push( $(this).val() );});
            list = JSON.stringify(list, null, 2);
            $('#dbslist').val(list);
            form.submit();
        }
    });
}
function getRandomNum() {
    var rndNum = Math.random()
    rndNum = parseInt(rndNum * 1000);
    rndNum = (rndNum % 94) + 33;
    return rndNum;
}
function checkPunc(num) {
    if ((num >=33) && (num <=47)) {return true;}
    if ((num >=58) && (num <=64)) {return true;}
    if ((num >=91) && (num <=96)) {return true;}
    if ((num >=123) && (num <=126)) {return true;}
    return false;
}


$(document).ready(function(){
    initSidebar();
    initForms();
    initStep1();
    initStep3();
});