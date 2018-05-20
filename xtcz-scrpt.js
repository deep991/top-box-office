jQuery( document ).ready(function($){
    
    var idsTohandle = '#xtcz_plugin_options-mpaa_rating-true';
        idsTohandle += ', #xtcz_plugin_options-mpaa_rating-false';
        idsTohandle += ', #xtcz_plugin_options-runtime-true';
        idsTohandle += ', #xtcz_plugin_options-runtime-false';
        idsTohandle += ', #xtcz_plugin_options-cast-true';
        idsTohandle += ', #xtcz_plugin_options-cast-false';
        idsTohandle += ', #xtcz_plugin_options-synopsis-true';
        idsTohandle += ', #xtcz_plugin_options-synopsis-false';
        
    var selected = $("input[type='radio'][name='xtcz_plugin_options[theme]']:checked");
    if (selected.length > 0) {
        selectedVal = selected.val();
        if(selectedVal == 'standard') {
            $(idsTohandle).attr('disabled',true);
        }
    } 
    
    $("input[type='radio'][name='xtcz_plugin_options[theme]']").change(function(){
        if($(this).val() == 'standard') {
            $(idsTohandle).attr('disabled',true);
        } else {
            $(idsTohandle).attr('disabled',false);
        }
    });
    
});