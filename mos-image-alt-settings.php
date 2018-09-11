<?php
/*WordPress Menus API.*/
function add_new_menu_items() {
    //add a new menu item. This is a top level menu item i.e., this menu item can have sub menus
    //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
    add_menu_page(
        "Mos image alt management", //Required. Text in browser title bar when the page associated with this menu item is displayed.
        "Image ALT", //Required. Text to be displayed in the menu.
        "manage_options", //Required. The required capability of users to access this menu item.
        "mos-alt-options", //Required. A unique identifier to identify this menu item.
        "mos_alt_page", //Optional. This callback outputs the content of the page associated with this menu item.
        plugins_url( 'images/logo-white-min.png', __FILE__ ),
        61 
    );

}

function mos_alt_page() {
    ?>
        <div class="wrap">
        <div id="icon-options-general" class="icon32"></div>
        <h1>Mos image alt management</h1>
        <form method="post" action="options.php">
            <?php
            
                //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
                //settings_fields( $option_group )
                settings_fields("mos_alt_general_section");
                
                // all the add_settings_field callbacks is displayed here
                //do_settings_sections( $page )
                do_settings_sections("mos-alt-options");
            
                // Add the submit button to serialize the options
                submit_button(); 
                
            ?>          
        </form>
    </div>
    <?php
}

//this action callback is triggered when wordpress is ready to add new items to menu.
add_action("admin_menu", "add_new_menu_items");


/*WordPress Settings API Demo*/

function display_options() {
    //section name, display name, callback to print description of section, page to which section is attached.
    //add_settings_section("mos_alt_general_section", "Header Options", "display_header_options_content", "mos-alt-options");
    add_settings_section("mos_alt_general_section", "Gereral Section", "", "mos-alt-options");

    //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    //last field section is optional.
    add_settings_field("mos_alt_primary_key", "General Primary Key", "display_mos_alt_primary_key", "mos-alt-options", "mos_alt_general_section");
    add_settings_field("mos_alt_location_key", "General Location Key", "display_mos_alt_location_key", "mos-alt-options", "mos_alt_general_section");
    add_settings_field("mos_alt_last_key", "General Last Key", "display_mos_alt_last_key", "mos-alt-options", "mos_alt_general_section");

    //section name, form element name, callback for sanitization
    $args = array (
        'sanitize_callback' => 'sanitize_data'
    );
    register_setting("mos_alt_general_section", "mos_alt_primary_key", $args);
    register_setting("mos_alt_general_section", "mos_alt_location_key", $args);
    register_setting("mos_alt_general_section", "mos_alt_last_key", $args);
}

function display_header_options_content(){echo "The header of the theme";}
function display_mos_alt_primary_key() {
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" name="mos_alt_primary_key" id="mos_alt_primary_key" value="<?php echo get_option('mos_alt_primary_key'); ?>" />
    <?php
}
function display_mos_alt_location_key() {
    //id and name of form element should be same as the setting name.
    ?>
        <input type="text" name="mos_alt_location_key" id="mos_alt_location_key" value="<?php echo get_option('mos_alt_location_key'); ?>" />
    <?php
}
function display_mos_alt_last_key() {
    //id and name of form element should be same as the setting name.
    ?>
        <?php $selected = get_option('mos_alt_last_key'); ?>
        <select name="mos_alt_last_key" id="mos_alt_last_key">
            <option value="none" <?php selected($selected, 'none') ?>>None</option>
            <option value="title" <?php selected($selected, 'title') ?>>Post/Page Title</option>
            <option value="alt" <?php selected($selected, 'alt') ?>>Current Alt</option>
        </select>
    <?php
}

//this action is executed after loads its core, after registering all actions, finds out what page to execute and before producing the actual output(before calling any action callback)
add_action("admin_init", "display_options");

function sanitize_data ( $input ) {
    $output = sanitize_text_field( $input );
    return apply_filters( 'mos_alt_options_validate', $output, $input );
}
