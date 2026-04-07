(function() {
    tinymce.PluginManager.add( 'isermons', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('isermons', {
            title: 'Insert isermons shortcode',
            cmd: 'isermons',
            image: url + '/imi-logo-mce.png',
        });
 
        editor.addCommand('isermons', function() {
            tb_show('Add Sermon shortcode', jQuery('#isermons-ajax-url').attr('data-ajax')+'?action=isermons_generate_shortcode');
            jQuery('#TB_ajaxContent').css('width', 'auto');
            jQuery('#TB_ajaxContent').css('height', '575px');
            jQuery(document).on('change', '.isermons-admin-shortcode-name', function(){
                var fields = jQuery("option:selected", this).attr('data-relate');
                jQuery('.isermons-shortcode-fields').hide();
                jQuery('.'+fields).show();
                jQuery(this).closest('tr').show();
            });
            var shortcode = '';
            jQuery(document).on('click', '.isermons-admin-generate-shortcode', function(event)
            {
                event.preventDefault();
                var attr = '';
                var shortcode_name = jQuery('.isermons-admin-shortcode-name').val();
                attr += '['+shortcode_name+'';
                jQuery('.isermons-admin-shortcode-fields').each(function(){
                    if(jQuery(this).is(":hidden"))
                    {
                        return true;
                    }
                    var type = jQuery(this).attr('type');
                    type = (typeof type==='undefined')?jQuery(this).get(0).tagName:type;
                    attr += isermons_get_shortcode_atts(jQuery(this), type);
                });
                attr += ']';
                shortcode = attr;
                editor.execCommand('mceReplaceContent', false, shortcode);
                tb_remove();
                editor.removeCommand('isermons');
                return;
            });
        });
 
    });
})();