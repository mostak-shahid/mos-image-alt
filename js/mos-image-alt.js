jQuery(document).ready(function($) {
    $("#set-alt").click(function(){
        var post_title = $('#title').val();
        //alert(post_title);

        var mos_img_primary_Key = $('#mosalt_primary_key').val();
        var mos_img_location = $('#mosalt_location').val();
        var mos_img_last_key = $('#mosalt_last_key').val();
        var common = '';
        if(mos_img_primary_Key) {
            common = mos_img_primary_Key;
            if(mos_img_location) {
                common += " | " +mos_img_location;
            }
        }
        else {
            if(mos_img_location) {
                common = mos_img_location;
            }
        }
        //alert(mos_img_last_key);

        var text = $('#content').val();

        var sac = /src="(.+)?" alt="(.+)?" class="(.+)?"/gi;
        var csa = /class="(.+)?" src="(.+)?" alt="(.+)?"/gi;
        var replace_reorder = /src="(.+)?" alt="(.+)?" class="(.+)?"/g;
        var replace = /alt="((.+ \| )?(.+ \| )?(.+))?"/g;
        if ( text.match(sac) || text.match(csa)){ 
            text = text.replace(replace_reorder, 'class="\$3" src="\$1" alt="\$2" ');
            $('#content').val(text);
            text = $('#content').val();
            if(mos_img_last_key == 'title')    
                text = text.replace(replace, 'alt="'+common+' | '+post_title+'"');
            else if(mos_img_last_key == 'alt')
                text = text.replace(replace, 'alt="'+common+' | \$4"');
            else 
                text = text.replace(replace, 'alt="'+common+'"');

            $("#content_ifr").contents().find("img").each(function(){
                var oldalt = $(this).attr("alt");
                if(mos_img_last_key == 'title')    
                    $(this).attr("alt",  common+' | '+post_title);
                else if(mos_img_last_key == 'alt')
                    $(this).attr("alt",  common+' | '+oldalt);
                else 
                    $(this).attr("alt",  common);
            });

            //.attr("alt",  common+' | \$4');
            alert("Alter tag implemented");
            return $('#content').val(text);
        }
        return false;



    });
}); 
