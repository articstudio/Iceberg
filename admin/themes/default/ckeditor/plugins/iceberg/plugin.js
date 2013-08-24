/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

CKEDITOR.plugins.add('iceberg',
{
    init: function(editor)
    {
        
        /* ICEBERG LINK */
        CKEDITOR.on('dialogDefinition', function( ev ) {
            var dialogName = ev.data.name;
            var dialogDefinition = ev.data.definition;
            if ( dialogName == 'link' ) {
                var infoTab = dialogDefinition.getContents( 'info' );
                var urlOptionsPanel = infoTab.get('urlOptions'); 
                urlOptionsPanel.children.push({
                    id: 'localPage',
                    type: 'select',
                    label: 'Iceberg pages',
                    items: [],
                    onChange: function(ev) {
                        var diag = CKEDITOR.dialog.getCurrent();
                        var url = diag.getContentElement('info','url');
                        url.setValue(ev.data.value);
                    },
                    setup: function (element) {
                       var element_id = '#' + this.getInputElement().$.id;
                        $.ajax({
                            type: 'POST',
                            url: ckeditorAPI,
                            data: {
                                action: 'page-list'
                            },
                            dataType: 'json',
                            async: false,
                            success: function(data) {
                                $(element_id).children('option').remove();
                                $(element_id).get(0).options[$(element_id).get(0).options.length] = new Option('', '');
                                $.each(data, function(index, item) {
                                    $(element_id).get(0).options[$(element_id).get(0).options.length] = new Option(item[1], item[0]);
                                });
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                                alert(xhr.status);
                                alert(thrownError);
                            } 
                        });
                    }
                });
            }
        });
        
        
        
        
        /*CKEDITOR.dialog.add( 'icebergLinkDialog', function (editor)
        {
            return {
                title : 'Iceberg Link',
                minWidth : 550,
                minHeight : 200,
                contents : [
                    {
                        id : 'iceberglinkdialog',
                        expand : true,
                        elements : [
                            {
                                id : 'iceberglinkArea',
                                type : 'textarea',
                                label : 'Paste Embed Code Here',
                                setup: function(element){},
                                commit: function(element){}
                            }
                        ]
                    }
                ],
                onOk: function() {
                    for (var i = 0; i < window.frames.length; i++) {
                        if (window.frames[i].name == 'iframeMediaEmbed') {
                            var content = window.frames[i].document.getElementById("embed").value;
                        }
                    }
                    // console.log(this.getContentElement( 'iframe', 'embedArea' ).getValue());
                    div = editor.document.createElement('div');
                    div.setHtml(this.getContentElement('iframe', 'embedArea').getValue());
                    editor.insertElement(div);
                }
            };
        });*/
        
        /* icebergLink */
        /*CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/iframe.js' );
        editor.addCommand('icebergLinkDialog', new CKEDITOR.dialogCommand('icebergLinkDialog'));
        editor.ui.addButton(
            'icebergLink',
            {
                label: 'Insert an Iceberg Link',
                command: 'icebergLinkDialog',
                icon: this.path + 'images/iceberg2_16x16.png',
                toolbar: 'iceberg,1'
            }
        );*/
        
        
        
        
        
        
        
    }
});


