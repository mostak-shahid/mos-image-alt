<?php
function mos_image_alt_admin_enqueue_scripts () {
	global $pagenow, $typenow;
	if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
		//wp_enqueue_style( 'dwwp-admin', plugins_url( 'css/admin-jobs.css', __FILE__ ) );
		wp_enqueue_script( 'mos-image-alt', plugins_url( 'js/mos-image-alt.js', __FILE__ ), array('jquery') );
	}
}
add_action( 'admin_enqueue_scripts', 'mos_image_alt_admin_enqueue_scripts' );

function mos_set_image_meta_upon_image_upload( $post_ID ) {	
	if ( wp_attachment_is_image( $post_ID ) ) {		
		$mos_image_title = get_post( $post_ID )->post_title;		
		$mos_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $mos_image_title );		
		$mos_image_title = ucwords( strtolower( $mos_image_title ) );		
		$mos_image_meta = array(			
			'ID'		    => $post_ID,						
			'post_title'	=> $mos_image_title,		
		);				
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $mos_image_title );		
		wp_update_post( $mos_image_meta );	
	} 
}
add_action( 'add_attachment', 'mos_set_image_meta_upon_image_upload' );


//This function will put image name into alt field if no alt is present
function mos_image_send_to_editor($html, $id) {
	$modified_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', get_the_title($id) );
	$mos_image_title = ucwords( strtolower( $modified_title ) );	
	update_post_meta( $id, '_wp_attachment_image_alt', $mos_image_title );	
	return str_replace('alt=""','alt="'.$mos_image_title.'"',$html);
}
add_filter('image_send_to_editor', 'mos_image_send_to_editor', 10, 2);


function mos_alt_generator($post_id) {

	$alt_text = array();
	
	$mosalt_option_primary_key = get_option('mos_alt_primary_key');
	$mosalt_option_location_key = get_option('mos_alt_location_key');
	$mosalt_option_last_key = get_option('mos_alt_last_key');

	$mosalt_primary_key = (get_post_meta( $post_id, '_mosalt_primary_key', true )) ? get_post_meta( $post_id, '_mosalt_primary_key', true ) : $mosalt_option_primary_key;
	$mosalt_location = (get_post_meta( $post_id, '_mosalt_location', true )) ? get_post_meta( $post_id, '_mosalt_location', true ) : $mosalt_option_location_key;
	$mosalt_last_key = (get_post_meta( $post_id, '_mosalt_last_key', true )) ? get_post_meta( $post_id, '_mosalt_last_key', true ) : $mosalt_option_last_key;

	// if ($alt_for == 'social') {
	// 	$output = ($mosalt_primary_key) ? $mosalt_primary_key . " | " : "";
	// 	$output .= ($mosalt_location ) ? $mosalt_location . " | " : "";
	// 	$output .= get_bloginfo( 'name' ); 
	// } elseif ($alt_for == 'service') {
	// 	$output = $mosalt_location; 
	// } else {
	// 	$output = ($mosalt_primary_key) ? $mosalt_primary_key . " | " : "";
	// 	$output .= ($mosalt_location ) ? $mosalt_location . " | " : "";
	// }
	$output = ($mosalt_primary_key) ? $mosalt_primary_key . " | " : "";
	$output_service = ($mosalt_location ) ? $mosalt_location . " | " : "";
	$output_inner = $output . $output_service;
	$output_social = $output_inner . get_bloginfo( 'name' ) . " | " ;

	$alt_text['inner'] =  $output_inner;
	$alt_text['service'] =  $output_service;
	$alt_text['social'] =  $output_social;

	return $alt_text;
}

function mos_post_thumbnail_html ($html, $post_id, $post_thumbnail_id, $size, $attr) {	
	
	$mosalt_option_primary_key = get_option('mos_alt_primary_key');
	$mosalt_option_location_key = get_option('mos_alt_location_key');
	$mosalt_option_last_key = get_option('mos_alt_last_key');

	$mosalt_primary_key = (get_post_meta( $post_id, '_mosalt_primary_key', true )) ? get_post_meta( $post_id, '_mosalt_primary_key', true ) : $mosalt_option_primary_key;
	$mosalt_location = (get_post_meta( $post_id, '_mosalt_location', true )) ? get_post_meta( $post_id, '_mosalt_location', true ) : $mosalt_option_location_key;
	$mosalt_last_key = (get_post_meta( $post_id, '_mosalt_last_key', true )) ? get_post_meta( $post_id, '_mosalt_last_key', true ) : $mosalt_option_last_key;
	if(!$post_thumbnail_id) $post_thumbnail_id = get_post_thumbnail_id(); // gets the id of the current post_thumbnail (in the loop)

    
	$alt = @$attr['alt'];
	if(!$alt) {
		$alt = (get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true )) ? get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true ) : get_the_title($id) ;
	}
    $alt_ex = ($mosalt_primary_key) ? $mosalt_primary_key .' | ' : ''; 
    $alt_ex .= ($mosalt_location) ? $mosalt_location .' | ' : '';

    if($mosalt_last_key == 'title') {
    	$final_alt = $alt_ex . get_the_title($post_id);
    } elseif($mosalt_last_key == 'alt') {
    	$final_alt = $alt_ex . $alt;
    }

    $class = @$attr['class']; // gets classes passed to the post thumbnail, defined here for easier function access

    $other_attr = '';
    /*if ($attr) {
	    foreach ($attr as $key => $value) {
	    	if($key != 'alt' OR $key != 'class')
	    		$other_attr .= $key.'="'.$value.'" ';
	    }
	}*/

    $src = wp_get_attachment_image_src($post_thumbnail_id, $size); 
        $html = '<img class="' . $class . '" src="' . $src[0] . '" alt="' . $final_alt . '"'.$other_attr.' />';
        //$html = $post_id . ', ' . $post_thumbnail_id . ', ' . $id;

    return $html;
}
add_filter('post_thumbnail_html', 'mos_post_thumbnail_html', 99, 5);
