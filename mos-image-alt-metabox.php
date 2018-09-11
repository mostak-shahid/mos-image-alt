<?php 
function mos_img_alt_management () {
	//add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null );

	add_meta_box( 
		'_mos_img_alt_management', 
		'Image alt Mamagement', 
		'mos_img_alt_management_html', 
		'',
		'normal', //advanced, normal, side
		$priority = 'default' //high, core, low
		/*$callback_args = null */
	);
}
add_action( 'add_meta_boxes', 'mos_img_alt_management' );

function mos_img_alt_management_html ($post) { 
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'mos_save_img_alt_management', 'mos_img_alt_management_nonce' );
	$mosalt_option_primary_key = get_option('mos_alt_primary_key');
	$mosalt_option_location_key = get_option('mos_alt_location_key');
	$mosalt_option_last_key = get_option('mos_alt_last_key');

	$mosalt_primary_key = (get_post_meta( $post->ID, '_mosalt_primary_key', true )) ? get_post_meta( $post->ID, '_mosalt_primary_key', true ) : $mosalt_option_primary_key;
	$mosalt_location = (get_post_meta( $post->ID, '_mosalt_location', true )) ? get_post_meta( $post->ID, '_mosalt_location', true ) : $mosalt_option_location_key;
	$mosalt_last_key = (get_post_meta( $post->ID, '_mosalt_last_key', true )) ? get_post_meta( $post->ID, '_mosalt_last_key', true ) : $mosalt_option_last_key;
	?>
	
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="mosalt_primary_key">Primary Key</label></p>
	<input class="widefat" type="text" id="mosalt_primary_key" name="_mosalt_primary_key" placeholder="Primary Key" value="<?php echo $mosalt_primary_key; ?>">
	
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="mosalt_location">Location</label></p>
	<input class="widefat" type="text" id="mosalt_location" name="_mosalt_location" placeholder="Location" value="<?php echo $mosalt_location; ?>">
	
	<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="mosalt_last_key">Last Key</label></p>
	<!-- <input class="widefat" type="text" id="last_key" name="_last_key" placeholder="Last Key" value="<?php echo $last_key; ?>"> -->
	<select class="widefat" type="text" id="mosalt_last_key" name="_mosalt_last_key">
		<option value="">--Select One--</option>
		<option value="none" <?php selected( $mosalt_last_key, 'none' ); ?>>None</option>
		<option value="title" <?php selected( $mosalt_last_key, 'title' ); ?> >Post/Page Title</option>
		<option value="alt" <?php selected( $mosalt_last_key, 'alt' ); ?>>Current Alt</option>
	</select>
	<p class="post-attributes-label-wrapper"><button type="button" id="set-alt" class="button">Apply image alt</button></p>

	<?php
}
function mos_img_alt_management_update ($post_ID) {

	if ( ! isset( $_POST['mos_img_alt_management_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['mos_img_alt_management_nonce'], 'mos_save_img_alt_management' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

 	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	$mosalt_primary_key = sanitize_text_field( $_POST['_mosalt_primary_key'] );
	$mosalt_location = sanitize_text_field( $_POST['_mosalt_location'] );
	$mosalt_last_key = sanitize_text_field( $_POST['_mosalt_last_key'] );

	update_post_meta( $post_ID, '_mosalt_primary_key', $mosalt_primary_key );
	update_post_meta( $post_ID, '_mosalt_location', $mosalt_location );
	update_post_meta( $post_ID, '_mosalt_last_key', $mosalt_last_key );
}
add_action( 'save_post', 'mos_img_alt_management_update' );