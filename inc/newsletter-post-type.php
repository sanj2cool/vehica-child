<?php

function register_newsletter_post_type() {
    $labels = array(
        'name'                  => 'Newsletters',
        'singular_name'         => 'Newsletter',
        'menu_name'             => 'Newsletters',
        'name_admin_bar'        => 'Newsletter',
        'add_new'               => '', // Remove 'Add New'
        'add_new_item'          => '', // Remove 'Add New Newsletter'
        'new_item'              => 'New Newsletter',
        'edit_item'             => '', // Remove 'Edit Newsletter'
        'view_item'             => 'View Newsletter',
        'all_items'             => 'All Newsletters',
        'search_items'          => 'Search Newsletters',
        'parent_item_colon'     => 'Parent Newsletters:',
        'not_found'             => 'No newsletters found.',
        'not_found_in_trash'    => 'No newsletters found in Trash.',
        'featured_image'        => 'Newsletter Cover Image',
        'set_featured_image'    => 'Set cover image',
        'remove_featured_image' => 'Remove cover image',
        'use_featured_image'    => 'Use as cover image',
        'archives'              => 'Newsletter archives',
        'insert_into_item'      => 'Insert into newsletter',
        'uploaded_to_this_item' => 'Uploaded to this newsletter',
        'filter_items_list'     => 'Filter newsletters list',
        'items_list_navigation' => 'Newsletters list navigation',
        'items_list'            => 'Newsletters list',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'newsletter' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'thumbnail' ), // Keep 'title' and 'thumbnail' but remove 'editor' and 'custom-fields'
    );

    register_post_type( 'newsletter', $args );
}
add_action( 'init', 'register_newsletter_post_type' );



/*---------------With ajax submit newsletter form start code in wordpress---------------*/
function create_newsletter_post() {
    check_ajax_referer('newsletter_nonce', 'nonce');

    if (!isset($_POST['email']) || !is_email($_POST['email'])) {
        wp_send_json_error(array('message' => 'Invalid email address.'));
    }

    $email = sanitize_email($_POST['email']);

    // Check if the email already exists
    $existing_post = get_posts(array(
        'post_type'  => 'newsletter',
        'meta_query' => array(
            array(
                'key'   => '_newsletter_email',
                'value' => $email,
                'compare' => '='
            )
        )
    ));

    if ($existing_post) {
        wp_send_json_error(array('message' => 'This email is already subscribed.'));
    }

    // Create new post if email doesn't exist
    $post_data = array(
        'post_title'  => $email,
        'post_type'   => 'newsletter',
        'post_status' => 'publish',
    );

    $post_id = wp_insert_post($post_data);

    if (!is_wp_error($post_id)) {
        update_post_meta($post_id, '_newsletter_email', $email);
        wp_send_json_success(array('message' => 'Thank you for subscribed'));
    } else {
        wp_send_json_error(array('message' => 'Error creating post.'));
    }
}
add_action('wp_ajax_create_newsletter_post', 'create_newsletter_post');
add_action('wp_ajax_nopriv_create_newsletter_post', 'create_newsletter_post');

function custom_wp_mail_from_name( $original_email_from ) {
    return 'Travel Cars NZ'; 
}

add_filter( 'wp_mail_from_name', 'custom_wp_mail_from_name' );

?>