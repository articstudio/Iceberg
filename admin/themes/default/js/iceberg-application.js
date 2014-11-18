
Array.prototype.inArray = function(value)
{
    for (var i = 0, length = this.length; i < length; ++i)
    {
        if (this[i] === value || parseInt(this[i]) === value)
        {
            return true;
        }
    }
    return false;
};
Array.prototype.unionArray = function(arr)
{
    var obj = {};
    for (var i = this.length-1; i >= 0; --i)
    {
        obj[this[i]] = this[i];
    }
    for (var i = arr.length-1; i >= 0; --i)
    {
        obj[arr[i]] = arr[i];
    }
    var result = [];
    for (var k in obj)
    {
        if (obj.hasOwnProperty(k))
        {
            result.push(obj[k]);
        }
    }
    return result;
};
Array.prototype.intersectionArray = function (arr)
{
    var result = [];
    if (arr.length>0 && this.length>0)
    {
        for (var i = 0, length = this.length; i < length; ++i)
        {
            var item = this[i];
            if (!result.inArray(item))
            {
                if (arr.inArray(item))
                {
                    result.push(item);
                }
            }
        }
    }
    return result;
};

String.prototype.escapeAttr = function()
{
    var htmlEscapes = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#x27;',
        '/': '&#x2F;'
    };
    var htmlEscaper = /[&<>"'\/]/g;
    return this.replace(htmlEscaper, function(match){
        return htmlEscapes[match];
    });
};
String.prototype.isURL = function()
{
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(this);
};
String.prototype.stringListToArray = function(separator)
{
    if (typeof separator !== 'string')
    {
        separator = ',';
    }
    return this.split(separator);
};
String.prototype.inStringList = function(value, separator)
{
    var arr = this.stringListToArray(separator);
    return arr.inArray(value);
};




function showAlert(message, type)
{
    var html = '';
    if (type === 'success')
    {
        html = '<p class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">' + js_iceberg_i18n.close + '</span></button><span class="glyphicon glyphicon-ok"></span> <strong>' + message + '</strong></p>';
    }
    else if (type === 'success')
    {
        html = '<p class="alert alert-info"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">' + js_iceberg_i18n.close + '</span></button><span class="glyphicon glyphicon-info-sign"></span> <strong>' + message + '</strong></p>';
    }
    else
    {
        html = '<p class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">' + js_iceberg_i18n.close + '</span></button><span class="glyphicon glyphicon-warning-sign"></span> <strong>' + message + '</strong></p>';
    }
    if (html !== '')
    {
        $('#alerts .alert').alert('close');
        $('#alerts').append(html);
    }             
}

function initSidebar()
{
    $(".sidebar-toggle").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#wrapper').toggleClass('toggled');
        return false;
    });
}

function initDatapicker()
{
    $('[data-datepicker]').each(function(){
        var datepicker_options = {};
        var $obj = $(this);
        var target = $obj.data('datepicker');
        var format = $obj.data('format');
        var value = $obj.val();
        if (typeof format === 'string' && format !== '')
        {
            datepicker_options.dateFormat = format;
        }
        if (typeof value === 'string' && value !== '')
        {
            datepicker_options.defaultDate = value;
        }
        if (target !== '')
        {
            var $target = $(this);
            $obj = $(target);
            datepicker_options.inline = true;
            datepicker_options.altField = $target;
            $target.change(function(){
                $obj.datepicker('setDate', $target.val());
            });
        }
        /*else
        {
            datepicker_options.onSelect = function(dateText, inst)
            {
                $obj.val(dateText);
            };
        }*/
        $obj.datepicker(datepicker_options);
    });
}

function callbackAjaxTableListCellActions(actions)
{
    var buttons = [];
    
    /* LANGUAGES */
    if (typeof actions.languages === 'object')
    {
        var i;
        for (i in actions.languages)
        {
            if (actions.languages[i].translated)
            {
                buttons.push('<a href="'+actions.languages[i].edit+'" class="btn btn-default"><img src="'+actions.languages[i].flag+'" alt="'+actions.languages[i].name.escapeAttr()+'" class="flag"></a>');
            }
            else
            {
                buttons.push('<div class="btn-group"><button class="btn btn-default dropdown-toggle" id="lang-'+i+'-'+actions.languages[i].id+'" data-toggle="dropdown" href="#"><img src="'+actions.languages[i].flag+'" alt="'+actions.languages[i].name.escapeAttr()+'" class="flag"> <span class="caret"></span></button><ul class="dropdown-menu" role="menu" aria-labelledby="lang-'+i+'-'+actions.languages[i].id+'"><li role="presentation"><a href="'+actions.languages[i].duplicate+'" role="menuitem" tabindex="-1"><span class="glyphicon glyphicon-transfer"></span> '+js_iceberg_i18n.duplicate+'</a></li><li role="presentation"><a href="'+actions.languages[i].translate+'" role="menuitem" tabindex="-1"><span class="glyphicon glyphicon-globe"></span> '+js_iceberg_i18n.translate+'</a></li></ul></div>');
            }
        }
    }
    
    /* STATUS */
    if (typeof actions.status === 'object')
    {
        var btn_icon_classes = ['glyphicon','glyphicon-ok'];
        var btn_classes = ['btn'];
        btn_classes.push(actions.status.status===1 ? 'btn-success' : 'btn-default');
        if (actions.status.can)
        {
            buttons.push('<a href="'+actions.status.url+'" class="'+btn_classes.join(' ')+'"><span class="'+btn_icon_classes.join(' ')+'"></span></a>');
        }
        else
        {
            btn_classes.push('disabled');
            buttons.push('<button class="'+btn_classes.join(' ')+'"><span class="'+btn_icon_classes.join(' ')+'"></span></button>');
        }
    }
    
    /* EDIT */
    if (typeof actions.edit === 'object')
    {
        var btn_icon_classes = ['glyphicon','glyphicon-pencil'];
        var btn_classes = ['btn','btn-default'];
        if (actions.edit.can)
        {
            buttons.push('<a href="'+actions.edit.url+'" class="'+btn_classes.join(' ')+'"><span class="'+btn_icon_classes.join(' ')+'"></span></a>');
        }
        else
        {
            btn_classes.push('disabled');
            buttons.push('<button class="'+btn_classes.join(' ')+'"><span class="'+btn_icon_classes.join(' ')+'"></span></button>');
        }
    }
    
    /* REMOVE */
    if (typeof actions.remove === 'object')
    {
        var btn_icon_classes = ['glyphicon','glyphicon-trash'];
        var btn_classes = ['btn','btn-danger'];
        if (actions.remove.can)
        {
            buttons.push('<a href="'+actions.remove.url+'" class="'+btn_classes.join(' ')+'" data-confirm="'+actions.remove.confirm.escapeAttr()+'"><span class="'+btn_icon_classes.join(' ')+'"></span></a>');
        }
        else
        {
            btn_classes.push('disabled');
            buttons.push('<button class="'+btn_classes.join(' ')+'"><span class="'+btn_icon_classes.join(' ')+'"></span></button>');
        }
    }
    
    return buttons.join(' ');
}
function callbackAjaxTableListCell(cell)
{
    if (typeof cell === 'object')
    {
        if (cell.type === 'actions')
        {
            return callbackAjaxTableListCellActions(cell);
        }
    }
    return cell;
}
function callbackAjaxTableListRow(row)
{
    var i, length=row.length;
    for (i=0;i<length;++i)
    {
        row[i] = callbackAjaxTableListCell(row[i]);
    }
    return row;
}
function callbackAjaxTableList(data)
{
    var i, length=data.data.length;
    for (i=0;i<length;++i)
    {
        data.data[i] = callbackAjaxTableListRow(data.data[i]);
    }
    return data;
}

$.fn.dataTable.callCallback = function (opts)
{
	var conf = $.extend({
		url: '',        // script url
		callback: null, // function or function name to callback
		method: 'GET'   // Ajax HTTP method
	}, opts);

	return function ( request, drawCallback, settings)
    {
        $.ajax({
            "type":     conf.method,
            "url":      conf.url,
            "data":     request,
            "dataType": "json",
            "cache":    false
        }).then(function(data){
            if (typeof conf.callback === 'function')
            { 
                data = conf.callback(data);
            }
            else if (typeof conf.callback === 'string' && conf.callback !== '' && typeof window[conf.callback] === 'function')
            {
                data = window[conf.callback](data);
            }
            drawCallback(data);
        });
    };
};

$.fn.dataTable.slicecalls = function (opts)
{
	var conf = $.extend({
        items: 100,     // items for every call
		selector: '',   // Datatable selector
		url: '',        // script url
		callback: null, // function or function name to callback
		method: 'GET'   // Ajax HTTP method
	}, opts);

	return function ( request, drawCallback, settings)
    {
        
        drawCallback({data:[]});
        function requestSlice(page)
        {
            var requestData = {
                start: page * conf.items,
                items: conf.items
            };
            return $.ajax({
				"type":     conf.method,
				"url":      conf.url,
				"data":     requestData,
				"dataType": "json",
				"cache":    false
			}).then(function(data){
                if (typeof conf.callback === 'function') { 
                    data = conf.callback(data); 
                }
                else if (typeof conf.callback === 'string' && conf.callback !== '' && typeof window[conf.callback] === 'function')
                {
                    data = window[conf.callback](data);
                }
                return data;
            });
        }
        function requestSliceFrom(page, cacheTotal)
        {
            return requestSlice(page).then(function(data){
                cacheTotal += data.data.length;
                var i, length=data.data.length;
                for (i=0; i<length; ++i)
                {
                    _datatables[conf.selector].oApi._fnAddData(settings, data.data[i]);
                    _datatables[conf.selector].fnDraw();
                }
                return (data.data.length<conf.items || cacheTotal>=data.total) ? true : requestSliceFrom(++page, cacheTotal);
            });
        }
        requestSliceFrom(0, 0);
        
    };
};

var _datatables = [], _datatables_allow_filter = [];
function initDataTables()
{
    $('table.datatable').each(function(){
        var $obj = $(this);
        var id = $obj.attr('id');
        if (!(id in _datatables))
        {
            addDataTable($obj);
        }
    });
}
function addDataTable($obj)
{
    var dt_selector = $obj.attr('id');
    var dt_paginate = parseInt($obj.data('paginate'));
    var dt_order = $obj.data('order');
    var dt_sort = $obj.hasClass('data-sort') || (typeof dt_order === 'string' && dt_order !== '');
    var dt_filter = $obj.hasClass('data-filter');
    var dt_buttons = [];
    var dt_new = $obj.data('new');
    var dt_ajax = $obj.data('ajax');
    var dt_ajax_slice = $obj.data('ajax-slice');
    var dt_ajax_callback = $obj.data('ajax-callback');
    if (typeof dt_new === 'string' && dt_new !== '')
    {
        dt_buttons.push({
            'sExtends': 'text',
            'sButtonText': '<span class="glyphicon glyphicon-plus"></span> ' + js_iceberg_i18n.new,
            'fnClick': function (nButton, oConfig, oFlash)
            {
                location.href = dt_new;
            }
        });
    }
    var dt_args = {
        'sDom': "<'row'<'col-md-6'T><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        'bSort': dt_sort,
        'bPaginate': dt_paginate,
        'bInfo': dt_paginate,
        'iDisplayLength': dt_paginate,
        'bFilter': dt_filter,
        'oTableTools': {
            'aButtons': dt_buttons
        }
    };
    
    if (typeof dt_ajax === 'string' && dt_ajax !== '')
    {
        if (typeof dt_ajax_slice === 'number')
        {
            dt_args.ajax = $.fn.dataTable.slicecalls({
                url: dt_ajax,
                items: dt_ajax_slice,
                selector: dt_selector,
                callback: dt_ajax_callback
            });
        }
        else if (typeof dt_ajax_callback !== 'undefined')
        {
            dt_args.ajax = $.fn.dataTable.callCallback({
                url: dt_ajax,
                callback: dt_ajax_callback
            });
        }
        else
        {
            dt_args.ajax = dt_ajax;
        }
        
    }
    
    var dt = _datatables[dt_selector] = $obj.dataTable(dt_args);
    if (typeof dt_order === 'string' && dt_order !== '')
    {
        dt.rowReordering({ 
            sURL: dt_order,
            sRequestType: "POST",
            fnSuccess: function(json, type) {
                var objs = jQuery.parseJSON(json),
                        length = objs.length,
                        i;
                for (i=0; i<length; ++i)
                {
                    showAlert(objs[i].text, objs[i].type);
                }
            },
            fnAlert: function(message, type) {
                showAlert(js_iceberg_i18n.error['reorder'], 'error');
            }
        });
    }
}

var _loading, _loading_active = false;
function initLoading()
{
    var loadingHTML = '<div id="loading-wrapper"><div id="loading"><span id="loading-title"></span></div></div>';
    $('body').append(loadingHTML);
    _loading = $('#loading-wrapper');
}
function IcebergLoading(sms)
{
    _loading_active = true;
    if (typeof sms === 'string' && sms !== '')
    {
        $('#loading-title', _loading).html(sms).show(0);
    } else {
        $('#loading-title', _loading).html(js_iceberg_i18n.loading).show(0);
    }
    $('body').addClass('loading');
}
function IcebergUnloading()
{
    _loading_active = false;
    $('body').removeClass('loading');
}

var _modal, _modal_options = {}, _modal_defaults = {
    title: '',
    content: '',
    success: '',
    ajax: false,
    buttons: {
        close: true,
        success: false
    },
    callback: {
        close: function(){},
        success: function(){}
    }
};
function initModals()
{
    var modalHTML = '<div class="modal fade" id="iceberg-modal" tabindex="-1" role="dialog" aria-labelledby="iceberg-modal-label" aria-hidden="true">'
                        + '<div class="modal-dialog">'
                            + '<div class="modal-content">'
                                + '<div class="modal-header">'
                                    + '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>'
                                    + '<h4 class="modal-title" id="iceberg-modal-label"></h4>'
                                + '</div>'
                                + '<div class="modal-body"></div>'
                                + '<div class="modal-footer">'
                                    + '<button type="button" class="btn btn-default btn-close" data-dismiss="modal"></button>'
                                    + '<button type="button" class="btn btn-success"></button>'
                                + '</div>'
                            + '</div>'
                        + '</div>'
                    + '</div>';
    $('body').append(modalHTML);
    _modal = $('#iceberg-modal');
    $('.modal-header .close .sr-only', _modal).text(js_iceberg_i18n.close);
    $('.modal-footer .btn-close', _modal).text(js_iceberg_i18n.close);
}
function openIcebergModal(args)
{
    _modal_options = $.extend({}, _modal_defaults, args);
    if (_modal_options.ajax) {
        IcebergLoading();
        $.getJSON(_modal_options.ajax, function(data){
            IcebergUnloading();
            _modal_options.ajax = false;
            var args = $.extend({}, _modal_options, data);
            openIcebergModal(args);
        }).error(function(){
            IcebergUnloading();
        });
        return false;
    } else {
        if (typeof _modal_options.title === 'string' && _modal_options.title !== '')
        {
            $('.modal-header .modal-title', _modal).html(_modal_options.title);
            $('.modal-header', _modal).show(0);
        } else {
            $('.modal-header .modal-title', _modal).html('');
            $('.modal-header', _modal).hide(0);
        }
        $('.modal-body', _modal).html(_modal_options.content);
        if (_modal_options.buttons.close) {
            $('.modal-footer .btn-close', _modal).unbind('click').show(0).bind('click', function(e){
                return closeIcebergModel(e, false);
            });
        } else {
            $('.modal-footer .btn-close', _modal).unbind('click').hide(0);
        }
        if (_modal_options.buttons.success && typeof _modal_options.success === 'string' && _modal_options.success !== '') {
            $('.modal-footer .btn-success', _modal).text(_modal_options.success).unbind('click').show(0).bind('click', function(e){
                return closeIcebergModel(e, true);
            });
        } else {
            $('.modal-footer .btn-success', _modal).unbind('click').hide(0);
        }
        _modal.modal({
            backdrop: true,
            keyboard: true,
            show: true,
            remote: false
        });
    }
    return true;
}
function closeIcebergModel(e, success)
{
    e.preventDefault();
    e.stopPropagation();
    _modal.modal('hide');
    callbackIcebergModal(success);
    return false;
}
function callbackIcebergModal(success)
{
    var callback = success ? _modal_options.callback.success : _modal_options.callback.close;
    if (typeof callback === 'function') { 
        callback(); 
    }
    else if (typeof callback === 'string' && callback !== '')
    {
        if (typeof window[callback] === 'function')
        {
            window[callback](); 
        }
        else if (callback.isURL())
        {
            location.href = callback;
        }
    }
}
function initConfirmModals()
{
    $(document).on('click', 'a[data-confirm]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var args = {
            title: js_iceberg_i18n.confirm,
            content: $(this).data('confirm'),
            success: js_iceberg_i18n.confirm,
            buttons: {
                close: true,
                success: true
            },
            callback: {
                success: $(this).attr('href')
            }
        };
        openIcebergModal(args);
        return false;
    });
}


function initDynamicSelectors()
{
    $('[data-select]').each(function(){
        var $container = $(this);
        var selector = $(this).data('select');
        //var $selector = $('#' + selector);
        var $selectorList = $('#' + selector);
        $('[data-add]', $container).each(function(){
            $(this).bind('click', function(e){
                e.preventDefault();
                e.stopPropagation();
                var $obj = $(this);
                var source = $obj.data('add');
                var $source = $('#' + source);
                if ($source.is('input[type=text]')) {
                    var value = $source.val();
                    $source.val('');
                    $selectorList.append($("<option/>", {
                        value: value,
                        text: value
                    }));
                }
                else if ($source.is('select')) {
                    $('option:selected', $source).each(function(){
                        var value = $(this).val();
                        var text = $(this).text();
                        $(this).remove();
                        $selectorList.append($("<option/>", {
                            value: value,
                            text: text
                        }));
                    });
                    dynamicSelectorDestination($source);
                }
                dynamicSelectorDestination($selectorList);
                return false;
            });
        });
        $('[data-remove]', $container).each(function(){
            $(this).bind('click',function(e){
                e.preventDefault();
                e.stopPropagation();
                var $obj = $(this);
                var source = $obj.data('remove');
                var $source = $('#' + source);
                if ($source.is('select')) {
                    $('option:selected', $source).each(function(){
                        var value = $(this).val();
                        var text = $(this).text();
                        $(this).remove();
                        $('select[data-list]', $container).each(function(){
                            var $destionation = $(this);
                            var list = $(this).data('list'); console.log(typeof list); console.log(list);
                            if ((typeof list === 'string' && list.inStringList(value)) || (typeof list === 'number' && list === parseInt(value)))
                            {
                                $destionation.append($("<option/>", {
                                    value: value,
                                    text: text
                                }));
                                dynamicSelectorDestination($destionation);
                            }
                        });
                    });
                    dynamicSelectorDestination($source);
                }
            });
        });
    });
}
function dynamicSelectorDestination($obj)
{
    if ($obj.is('select[data-destionation]')) {
        var destionation = $obj.data('destionation');
        var $destionation = $('#' + destionation);
        var list = [];
        $('option', $obj).each(function(){
            list.push( $(this).val() );
        });
        $destionation.val(list.join(','));
    }
    else if ($obj.is('select[data-hidden-destionation]')) {
        var destionation_name = $obj.data('hidden-destionation');
        var $parent = $obj.parent();
        //var $destionation = $('#' + destionation);
        $('[name="'+destionation_name+'[]"]', $parent).remove();
        $('option', $obj).each(function(){
            var value = $(this).val();
            $parent.append('<input type="hidden" name="'+destionation_name+'[]" id="'+destionation_name+'-'+value+'" value="'+value+'">');
        });
    }
}


function initSortableLists()
{
    $('ul[data-sortable]').each(function(){
        var $obj = $(this);
        var attributes = $obj.data('sortable');
        var connect = $obj.data('sortable-connect');
        var update = $obj.data('sortable-update');
        var $sortable = $obj.sortable({
            revert: (attributes.inStringList('revert')),
            connectWith: (typeof connect === 'undefined' || connect === '') ? false : connect,
            update: (typeof connect === 'undefined' || connect === '') ? function(event, ui) {} : function(event, ui) {
                var ids = $obj.sortable('toArray').toString();
                $(update).val(ids);
            }
        });
        if (attributes.inStringList('droppable')) {
            $sortable.droppable({
                drop: function(event, ui) {
                    sortableDrop(ui.draggable);
                }
            });
        }
    });
}
function initDragableLists()
{
    $('ul[data-draggable]').each(function(){
        var $obj = $(this);
        var to = $obj.data('draggable');
        var $to = $(to);
        $('li', $obj).draggable({
            connectToSortable: to,
            containment: $('#page-wrapper'),
            helper: "clone",
            cursorAt: {top:0, left:0},
            opacity: 0.8,
            revert: false,
            start: function(event, ui) {
                $(ui.helper).width($to.width()).height('auto');
            }
        }).disableSelection();
    });
}
function sortableDrop($item)
{
    $('.well', $item).removeClass('closed');
    $('[data-drop-attr]', $item).each(function(){
        $(this).attr($(this).data('drop-attr'), '1');
    });
    ResetWidgets();
}


function initPushLists()
{
    $('[data-push]').each(function(){
        var $obj = $(this);
        var to_id = $obj.data('push');
        var $to = $('#'+to_id);
        var template_id = $obj.data('push-template');
        var $template = $('#'+template_id);
        if ($to.length > 0 && $template.length === 1) {
            var template = $template.html();
            $('.btn-add', $obj).bind('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    var buffer = template;
                    var done = true;
                    $('[data-push-value]', $obj).each(function(){
                        var $elem = $(this);
                        var key = $elem.data('push-value');
                        var value = $elem.val();
                        if ($elem.is('input[type=checkbox]'))
                        {
                            value = $elem.is(':checked') ? 'checked' : '';
                            $elem.attr('checked', false);
                        }
                        else
                        {
                            $elem.val('');
                        }
                        if (($elem.data('push-required') === 1 || $elem.data('push-required') === 'true') && value === '')
                        {
                            done = false;
                            return false;
                        }
                        buffer = buffer.replace(new RegExp('%data-'+key+'%', 'g'), value);
                    });
                    if (done)
                    {
                        $to.append(buffer);
                        ResetWidgets();
                    }
            });
        }
    });
}


function initWidgets()
{
    $('.widget').each(function(){
        var $widget = $(this);
        $('.btn-toolbar.header .btn[btn-action]', $widget).each(function(){
            var $btn = $(this);
            var action = $btn.attr('btn-action');
            if (action === 'remove') {
                $btn.bind('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    RemoveWidget($widget);
                });
            }
            else if (action === 'expand') {
                if ($widget.hasClass('collapsed')) {
                    $btn.show();
                }
                else {
                    $btn.hide();
                }
                $btn.bind('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    ExpandWidget($widget);
                });
            }
            else if (action === 'collapse') {
                if ($widget.hasClass('collapsed')) {
                    $btn.hide();
                }
                else {
                    $btn.show();
                }
                $btn.bind('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    CollapseWidget($widget);
                });
            }
        });
        $('[widget-action]', $widget).each(function(){
            var $elem = $(this);
            var action = $elem.attr('widget-action');
            if (action === 'title') {
                $elem.bind('keyup', function(e){
                    $('> header', $widget).html($elem.val());
                });
            }
        });
    });
}
function UnWidgets()
{
    $('.widget').find('*').andSelf().unbind();
}
function ResetWidgets()
{
    UnWidgets();
    initWidgets();
}
function RemoveWidget($widget)
{
    $widget.find('*').andSelf().unbind().slideUp(500, function(){
        $(this).remove();
    });
}
function ExpandWidget($widget)
{
    $('.btn-toolbar.header .btn[btn-action=expand]', $widget).hide();
    $('.btn-toolbar.header .btn[btn-action=collapse]', $widget).show();
    $widget.removeClass('collapsed');
}
function CollapseWidget($widget)
{
    $('.btn-toolbar.header .btn[btn-action=expand]', $widget).show();
    $('.btn-toolbar.header .btn[btn-action=collapse]', $widget).hide();
    $widget.addClass('collapsed');
}

var _filters_values = [];
var _filters_selecteds = [];
function initFilters() {
    $('[data-filter]').each(function(){
        var $obj = $(this);
        var obj_id = $obj.attr('id');
        var filter_id = $obj.data('filter');
        var $filter = $('#'+filter_id);
        
        /* SELECT */
        if ($filter.is('select'))
        {
            
            /* SELECT => SELECT */
            if ($obj.is('select'))
            {
                _filters_values[obj_id] = [];
                _filters_selecteds[obj_id] = [];
                $('option', $obj).each(function(){
                    _filters_values[obj_id][$(this).val()] = $(this).text();
                    if ($(this).is(':selected'))
                    {
                        _filters_selecteds[obj_id][$(this).val()] = $(this).val();
                    }
                });
                
                $obj.bind('change', function(){
                    _filters_selecteds[obj_id] = [];
                    $('option:selected', $obj).each(function(){
                        _filters_selecteds[obj_id][$(this).val()] = $(this).text();
                    });
                });
                
                $filter.bind('change', function(){
                    var values = [];
                    $('option:selected', $filter).each(function(){
                        var fvalues = $(this).data('filter-values');
                        fvalues = (typeof fvalues === 'number') ? [fvalues] : ((typeof fvalues === 'string') ? fvalues.stringListToArray() : []);
                        values = values.unionArray(fvalues);
                    });
                    $('option', $obj).remove();
                    if ((typeof _filters_values[obj_id]['NULL'] !== 'undefined') && values.inArray('NULL'))
                    {
                        $obj.append($('<option>', {value:'NULL',text:''}));
                    }
                    for (var key in _filters_values[obj_id])
                    {
                        if (_filters_values[obj_id].hasOwnProperty(key) && key !== 'NULL')
                        {
                            if (values.inArray(key))
                            {
                                var $option = $('<option>', {value:key,text:_filters_values[obj_id][key]});
                                $obj.append($option);
                                if (typeof _filters_selecteds[obj_id][key] !== 'undefined')
                                {
                                    $option.prop('selected', true);
                                }
                            }
                        }
                    }
                    $obj.trigger('change');
                });
            }
            
            /* SELECT => DIV / P / SPAN / SMALL / A */
            else if ($obj.is('div') || $obj.is('p') || $obj.is('span') || $obj.is('small') || $obj.is('a'))
            {
                $filter.bind('change', function(){
                    var values = [];
                    $('option:selected', $filter).each(function(){
                        values.push($(this).val());
                    });
                    var fvalues = $obj.data('filter-values');
                    fvalues = (typeof fvalues === 'number') ? [fvalues] : ((typeof fvalues === 'string') ? fvalues.stringListToArray() : []);
                    var found = values.intersectionArray(fvalues);
                    (found.length > 0) ? $obj.show() : $obj.hide();
                });
            }
            
            $filter.trigger('change');
        }
        
        /** CHECKBOX **/
        else if ($filter.is('input[type=checkbox]'))
        {
            $filter.bind('change', function(){
                ($filter.is(':checked')) ? $obj.show() : $obj.hide();
            });
            $filter.trigger('change');
        }
    });
}

var defaultDiacriticsRemovalMap = [
    {'base':'A', 'letters':/[\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F]/g},
    {'base':'AA','letters':/[\uA732]/g},
    {'base':'AE','letters':/[\u00C6\u01FC\u01E2]/g},
    {'base':'AO','letters':/[\uA734]/g},
    {'base':'AU','letters':/[\uA736]/g},
    {'base':'AV','letters':/[\uA738\uA73A]/g},
    {'base':'AY','letters':/[\uA73C]/g},
    {'base':'B', 'letters':/[\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181]/g},
    {'base':'C', 'letters':/[\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E]/g},
    {'base':'D', 'letters':/[\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779]/g},
    {'base':'DZ','letters':/[\u01F1\u01C4]/g},
    {'base':'Dz','letters':/[\u01F2\u01C5]/g},
    {'base':'E', 'letters':/[\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E]/g},
    {'base':'F', 'letters':/[\u0046\u24BB\uFF26\u1E1E\u0191\uA77B]/g},
    {'base':'G', 'letters':/[\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E]/g},
    {'base':'H', 'letters':/[\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D]/g},
    {'base':'I', 'letters':/[\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197]/g},
    {'base':'J', 'letters':/[\u004A\u24BF\uFF2A\u0134\u0248]/g},
    {'base':'K', 'letters':/[\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2]/g},
    {'base':'L', 'letters':/[\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780]/g},
    {'base':'LJ','letters':/[\u01C7]/g},
    {'base':'Lj','letters':/[\u01C8]/g},
    {'base':'M', 'letters':/[\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C]/g},
    {'base':'N', 'letters':/[\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4]/g},
    {'base':'NJ','letters':/[\u01CA]/g},
    {'base':'Nj','letters':/[\u01CB]/g},
    {'base':'O', 'letters':/[\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C]/g},
    {'base':'OI','letters':/[\u01A2]/g},
    {'base':'OO','letters':/[\uA74E]/g},
    {'base':'OU','letters':/[\u0222]/g},
    {'base':'P', 'letters':/[\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754]/g},
    {'base':'Q', 'letters':/[\u0051\u24C6\uFF31\uA756\uA758\u024A]/g},
    {'base':'R', 'letters':/[\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782]/g},
    {'base':'S', 'letters':/[\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784]/g},
    {'base':'T', 'letters':/[\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786]/g},
    {'base':'TZ','letters':/[\uA728]/g},
    {'base':'U', 'letters':/[\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244]/g},
    {'base':'V', 'letters':/[\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245]/g},
    {'base':'VY','letters':/[\uA760]/g},
    {'base':'W', 'letters':/[\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72]/g},
    {'base':'X', 'letters':/[\u0058\u24CD\uFF38\u1E8A\u1E8C]/g},
    {'base':'Y', 'letters':/[\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE]/g},
    {'base':'Z', 'letters':/[\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762]/g},
    {'base':'a', 'letters':/[\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250]/g},
    {'base':'aa','letters':/[\uA733]/g},
    {'base':'ae','letters':/[\u00E6\u01FD\u01E3]/g},
    {'base':'ao','letters':/[\uA735]/g},
    {'base':'au','letters':/[\uA737]/g},
    {'base':'av','letters':/[\uA739\uA73B]/g},
    {'base':'ay','letters':/[\uA73D]/g},
    {'base':'b', 'letters':/[\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253]/g},
    {'base':'c', 'letters':/[\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184]/g},
    {'base':'d', 'letters':/[\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A]/g},
    {'base':'dz','letters':/[\u01F3\u01C6]/g},
    {'base':'e', 'letters':/[\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD]/g},
    {'base':'f', 'letters':/[\u0066\u24D5\uFF46\u1E1F\u0192\uA77C]/g},
    {'base':'g', 'letters':/[\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F]/g},
    {'base':'h', 'letters':/[\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265]/g},
    {'base':'hv','letters':/[\u0195]/g},
    {'base':'i', 'letters':/[\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131]/g},
    {'base':'j', 'letters':/[\u006A\u24D9\uFF4A\u0135\u01F0\u0249]/g},
    {'base':'k', 'letters':/[\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3]/g},
    {'base':'l', 'letters':/[\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747]/g},
    {'base':'lj','letters':/[\u01C9]/g},
    {'base':'m', 'letters':/[\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F]/g},
    {'base':'n', 'letters':/[\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5]/g},
    {'base':'nj','letters':/[\u01CC]/g},
    {'base':'o', 'letters':/[\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275]/g},
    {'base':'oi','letters':/[\u01A3]/g},
    {'base':'ou','letters':/[\u0223]/g},
    {'base':'oo','letters':/[\uA74F]/g},
    {'base':'p','letters':/[\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755]/g},
    {'base':'q','letters':/[\u0071\u24E0\uFF51\u024B\uA757\uA759]/g},
    {'base':'r','letters':/[\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783]/g},
    {'base':'s','letters':/[\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B]/g},
    {'base':'t','letters':/[\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787]/g},
    {'base':'tz','letters':/[\uA729]/g},
    {'base':'u','letters':/[\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289]/g},
    {'base':'v','letters':/[\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C]/g},
    {'base':'vy','letters':/[\uA761]/g},
    {'base':'w','letters':/[\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73]/g},
    {'base':'x','letters':/[\u0078\u24E7\uFF58\u1E8B\u1E8D]/g},
    {'base':'y','letters':/[\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF]/g},
    {'base':'z','letters':/[\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763]/g}
];
function makeStringPermalink(str) {
    str = str.toLowerCase();
    for(var i=0; i<defaultDiacriticsRemovalMap.length; i++) {
        str = str.replace(defaultDiacriticsRemovalMap[i].letters, defaultDiacriticsRemovalMap[i].base);
    }
    var str_hyphens = str.replace(/\s/g,'-');
    var finishedstr = str_hyphens.replace(/[^a-zA-Z0-9\-]/g,'');
    finishedstr = finishedstr.toLowerCase();
    finishedstr = finishedstr.replace(/-+/g,'-');
    finishedstr = finishedstr.replace(/(^-)|(-$)/g,'');
    return finishedstr;
}
function checkPermalinkExists(permalink, $target, exclude, language)
{
    $.ajax({
        url: icebergAPI,
        type: 'GET',
        dataType: 'JSON',
        data: {
            module:'permalinks',
            action:'exists',
            permalink:permalink,
            exclude:exclude,
            lang:language
        },
        success: function(data){
            if (typeof data === 'object')
            {
                if (data.exists)
                {
                    $target.val(data.permalink);
                }
            }
        },
        error: function() {}
    });
}
function initPermalinks()
{
    $('input[data-permalink]').each(function(){
        var $obj = $(this);
        var target = $obj.data('permalink');
        var $target = $(target);
        var exclude = $target.data('id');
        var language = $target.data('language');
        if ($target.length > 0)
        {
            $obj.focusout(function(){
                var permalink = makeStringPermalink($obj.val());
                $target.val(permalink);
                checkPermalinkExists(permalink, $target, exclude, language);
            });
            $target.focusout(function(){
                var permalink = makeStringPermalink($target.val());
                $target.val(permalink);
                checkPermalinkExists(permalink, $target, exclude, language);
            });
        }
    });
}

function initToggles()
{
    $(document).on('click', 'a[data-toggle], button[data-toggle]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var target = $(this).data('toggle');
        $(target).toggle();
        return false;
    });
}

function initThumbnails()
{
    $(document).on('change', '[data-thumbnail]', function(e){
        e.preventDefault();
        e.stopPropagation();
        var $obj = $(this);
        var file = $obj.val();
        var thumbnail_target = $obj.data('thumbnail');
        if (typeof thumbnail_target === 'string' && thumbnail_target !== '')
        {
            var $thumbnail_target = $(thumbnail_target);
            $('.thumbnail', $thumbnail_target).remove();
            $thumbnail_target.append('<span class="thumbnail"><img src="'+file+'" /></span>');
        }
        return false;
    });
}

function callbackGalleryFile(file, target)
{
    var $target = $('#'+target);
    if ($target.length === 1) {
        var correct = true;
        var limit = $target.data('images-limit');
        if (typeof limit === 'number')
        {
            var count = $('.widget', $target).length;
            correct = !(count >= limit);
        }
        if (correct)
        {
            $target.append('<li class="well widget">'
                + '<div class="btn-toolbar header"><a href="#" class="btn btn-danger btn-xs" btn-action="remove"><span class="glyphicon glyphicon-trash"></span></a></div>'
                + '<span class="thumbnail"><img src="'+file+'"></span>'
                + '<input type="hidden" name="'+target+'[]" value="'+file+'" />'
                + '<input type="text" class="form-control" name="'+target+'-alt[]" value="'+file+'" />'
                + '</li>');
            ResetWidgets();
        }
    }
}
var gMapsLoaded = false;
window.gMapsCallback = function(){
    gMapsLoaded = true;
    $(window).trigger('gMapsLoaded');
}
window.loadGoogleMaps = function(){
    if(gMapsLoaded) { return window.gMapsCallback(); }
    var script_tag = document.createElement('script');
    script_tag.setAttribute("type","text/javascript");
    script_tag.setAttribute("src","http://maps.google.com/maps/api/js?sensor=false&callback=gMapsCallback");
    (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script_tag);
}
function initializeGMaps() {
    var $maps = $('div.gmap');
    $maps.each(function(){
        var $obj = $(this);
        var id = $obj.attr('id');
        var ll = $obj.data('center');
        ll = ll.split(',');
        var mll = $obj.data('marker');
        mll = mll.split(',');
        var lat = $obj.data('latitude');
        var lng = $obj.data('longitude');
        var mapOptions = {
            zoom: 5,
            center: new google.maps.LatLng(ll[0], ll[1]),
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(this, mapOptions);
        var markers = [];
        
        function placeMarker(position, map) {
            deleteOverlays();
            $('#'+lat).val(position.lat());
            $('#'+lng).val(position.lng());
            var marker = new google.maps.Marker({
                position: position,
                map: map
            });
            map.panTo(position);
            markers.push(marker);
        }
        function deleteOverlays() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }
        
        google.maps.event.addListener(map, 'click', function(e) {
            placeMarker(e.latLng, map);
        });
        if (mll[0] !== '' && mll[1] !== '') {
            placeMarker(new google.maps.LatLng(mll[0],mll[1]), map);
        }
        
        $('#wrapper').bind('change', function(event) {
            google.maps.event.trigger(map, 'resize');  
        });
    });

}
function initGMaps()
{
    var $maps = $('div.gmap');
    if ($maps.length > 0) {
        if (gMapsLoaded) {
            initializeGMaps();
        } else {
            $(window).bind('gMapsLoaded', initializeGMaps);
            window.loadGoogleMaps();
        }
    }
}


var IcebergApplicationLoaded = false;
$(document).ready(function(){
    initSidebar();
    initDatapicker();
    initDataTables();
    initLoading();
    initModals();
    initConfirmModals();
    initDynamicSelectors();
    initSortableLists();
    initDragableLists();
    initPushLists();
    initWidgets();
    initFilters();
    initPermalinks();
    initToggles();
    initThumbnails();
    initGMaps();
    
    $('body').addClass('iceberg-loaded');
    IcebergApplicationLoaded = true;
    $(window).trigger('IcebergApplicationLoaded');
});
