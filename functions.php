<?php
    
    add_action('wp_enqueue_scripts', static function () {
        $deps = [];
        
        if (class_exists(\Elementor\Plugin::class)) {
            $deps[] = 'elementor-frontend';
        }
        
        wp_enqueue_style('vehica', get_template_directory_uri() . '/style.css', $deps, VEHICA_VERSION);
        wp_enqueue_style('vehica-child', get_stylesheet_directory_uri() . '/style.css', ['vehica']);
    });
    
    add_action('after_setup_theme', static function () {
        load_child_theme_textdomain('vehica', get_stylesheet_directory() . '/languages');
    });
    
    /*
         * Footer Scripts
         */
    if (!function_exists('tra_foolter_scripts')) {
        function tra_foolter_scripts()
        {
            ?>
            <script>
                $ = jQuery;
                $(function () {
                        if ($('.page-id-12758').length) {
                            // Select the target node
                            const targetNode = document;
                            // Callback function to execute when changes are observed
                            const callback = function (mutationsList, observer) {
                                    for (const mutation of mutationsList) {
                                        if (mutation.type === 'childList') {
                                            if ($('.swal2-container').length) {
                                                let title = $('.swal2-container .swal2-title').text();
                                                if (title === 'Your payment has been processed') {

                                                    let key = $('.vehica-packages div:first-child').find('.vehica-package--active').data('id');
                                                    $(".user-packages>div").each(function (i) {
                                                        let _key = $(this).find('.current-plan').text();
                                                        if (_key === key) {
                                                            $(this).find('.vehica-package--owned').trigger('click');
                                                        }
                                                    });

                                                    $('.swal2-container .swal2-title').text('');
                                                    // $('.vehica-car-form__switcher input').prop('checked', true);
                                                    $('.vehica-car-form__save-submit .vehica-button').trigger('click');
                                                    $('.vehica-packages').trigger('click');
                                                    $('.swal2-container .swal2-confirm').trigger('click');
                                                    $('#vehica-select-package').addClass('vehica-select-package-disabled');
                                                    setTimeout(function () {
                                                        $('#vehica-select-package').removeClass('vehica-select-package-disabled');
                                                    }, 5000);
                                                }
                                            }
                                        }
                                    }
                                }
                            ;
                            // Create an observer instance linked to the callback function
                            const observer = new MutationObserver(callback);
                            // Options for the observer (which mutations to observe)
                            const config = {attributes: true, childList: true, subtree: true};
                            // Start observing the target node for configured mutations
                            observer.observe(targetNode, config);
                        }
                    }
                )
            </script>
            <?php
        }
        
        add_action('wp_footer', 'tra_foolter_scripts');
    }
    
    /*
         * Post author
         */
    if (!function_exists('tra_post_author_shortcode')) {
        function tra_post_author_shortcode($attr = [], $content = '')
        {
            ob_start();
            if (is_singular('post')) {
                $defaults = [
                    'author_title' => '',
                    'author_link' => '',
                    'author_image' => '',
                    'facebook' => '',
                    'instagram' => '',
                    'linkedin' => '',
                ];
                extract(shortcode_atts($defaults, $attr));
                $author_id = get_the_author_meta('ID');
                $author_name = empty($author_title) ? get_the_author_meta('display_name') : $author_title;
                $avatar_url = get_avatar_url($author_id);
                // Get the author's description
                $author_description = empty($content) ? get_the_author_meta('description', $author_id) : $content;
                $author_url = empty($author_link) ? get_author_posts_url($author_id) : $author_link;
                ?>
                <div class="tra-post-author">
                    <div class="post-author-avatar">
                        <a href="<?php echo $author_url; ?>">
                            <?php
                                $image_url = $author_image;
                                if (empty($image_url)) {
                                    $vehica_image = get_user_meta($author_id, 'vehica_image');
                                    if (!empty($vehica_image)) {
                                        $image_id = current($vehica_image);
                                        $image_url = wp_get_attachment_image_src($image_id, 'thumbnail')[0];
                                    }
                                }
                                if (!empty($image_url)) {
                                    ?>
                                    <img src="<?php echo $image_url; ?>" alt="<?php echo $author_name; ?>" class="author-avatar"/>
                                    <?php
                                }
                            ?>
                        </a>
                        <div class="social-icons">
                            <?php if (!empty($facebook)) {
                                ?>
                                <a target="_blank" href="<?php echo esc_url($facebook); ?>">
                                    <i class="fab fa-facebook"></i>
                                </a>
                                <?php
                            }
                                if (!empty($instagram)) {
                                    ?>
                                    <a target="_blank" href="<?php echo esc_url($instagram); ?>">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                <?php }
                                if (!empty($linkedin)) {
                                    ?>
                                    <a target="_blank" href="<?php echo esc_url($linkedin); ?>">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                <?php } ?>
                        </div>
                    </div>
                    <div class="post-author-description">
                        <h3 class="author-title">
                            <a href="<?php echo $author_url; ?>"><?php echo $author_name; ?></a>
                        </h3>
                        <?php echo wpautop($author_description); ?>
                    </div>
                </div>
                <?php
            }
            return ob_get_clean();
        }
        
        add_shortcode('tra_post_author', 'tra_post_author_shortcode');
    }
    /*
    * Empty cart on add to cart
    */
   if (!function_exists('tra_remove_cart_item_before_add_to_cart')) {
	function tra_remove_cart_item_before_add_to_cart($passed, $product_id, $quantity) {
		if (!WC()->cart->is_empty() && $passed) {
			WC()->cart->empty_cart();
		}
		
		return $passed;
	}
	 add_filter('woocommerce_add_to_cart_validation', 'tra_remove_cart_item_before_add_to_cart', 99, 3);
   }
   /*
    * Redirect to checkout on add to cart
    */
    if (!function_exists('tra_custom_add_to_cart_redirect')) {
        function tra_custom_add_to_cart_redirect() {
            return wc_get_checkout_url();
        }
        add_filter('woocommerce_add_to_cart_redirect', 'tra_custom_add_to_cart_redirect');
    }
    /*
    * Redirect to checkout on add to cart
    */
    if (!function_exists('tra_redirect_single_product')) {
        function tra_redirect_single_product() {
            if (is_product()) {
                wp_redirect(site_url());
                exit();
            }
        }
        add_action('template_redirect', 'tra_redirect_single_product');
    }
/*
 *
 */
if (!function_exists('tra_remove_woo_checkout_fields')) {

	function tra_remove_woo_checkout_fields( $fields ) {

		// remove billing fields
		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_address_1']);
		unset($fields['billing']['billing_address_2']);
		unset($fields['billing']['billing_city']);
		unset($fields['billing']['billing_postcode']);
		unset($fields['billing']['billing_country']);
		unset($fields['billing']['billing_state']);
		unset($fields['billing']['billing_phone']);

		// remove order comment fields
		unset($fields['order']['order_comments']);

		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields' , 'tra_remove_woo_checkout_fields' );
}
    
    function trav_hide_mail($email) {
        // Split the email into parts
        list($name, $domain) = explode('@', $email);
        $domainParts = explode('.', $domain);
        
        // Show only the first and last letter of the name part
        $hiddenName = substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        
        // Show only the first and last letter of each part of the domain
        $hiddenDomain = '';
        foreach ($domainParts as $part) {
            $hiddenDomain .= substr($part, 0, 1) . str_repeat('*', strlen($part) - 2) . substr($part, -1) . '.';
        }
        $hiddenDomain = rtrim($hiddenDomain, '.');
        
        // Combine the hidden parts
        return $hiddenName . '@' . $hiddenDomain;
    }


   require_once get_stylesheet_directory() . "/inc/rest-api-email-notification.php";
   
   // Function to get the current post category title
function get_current_post_category_title() {
    if (is_category()) {
        // Get the current queried object (post category)
        $current_category = get_queried_object();

        // Check if it's a valid category object and return the name
        if ($current_category && !is_wp_error($current_category)) {
            return esc_html($current_category->name);
        }
    }
    
    // Return 'Category not found' if not a post category or category is invalid
    return esc_html__('Category not found', 'text-domain');
}

// Shortcode function
function current_post_category_title_shortcode() {
    return get_current_post_category_title();
}

// Register the shortcode
add_shortcode('current_post_category_title', 'current_post_category_title_shortcode');
   
   // Enable shortcodes in Elementor Heading Widget
function enable_shortcodes_in_elementor_widgets($content) {
    if (is_callable('do_shortcode')) {
        return do_shortcode($content);
    }
    return $content;
}
add_filter('elementor/widget/render_content', 'enable_shortcodes_in_elementor_widgets', 10, 1);

add_filter('widget_text', 'do_shortcode');
add_filter('widget_text_content', 'do_shortcode'); 



require_once get_stylesheet_directory() . "/inc/newsletter-post-type.php";


function enqueue_custom_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-ajax-script', get_stylesheet_directory_uri() . '/assets/js/custom-ajax.js', array('jquery'), null, true );

    wp_localize_script('custom-ajax-script', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('newsletter_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');




function custom_social_media_thumbnail() {
    if (is_singular('vehica_car')) { // Applies to all single posts/pages
        global $post;

        // Get all images from content
        preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $post->post_content, $matches);
        $images = $matches[1]; // Extract image URLs

        // Get site logo URL (Properly)
        $logo_id = get_theme_mod('custom_logo'); 
        $logo_url = ($logo_id) ? wp_get_attachment_image_url($logo_id, 'full') : '';

        // Get first non-logo image from post content
        $content_image = '';
        foreach ($images as $image) {
            if ($image !== $logo_url) {
                $content_image = $image;
                break;
            }
        }

        // Get featured image
        $featured_image = get_the_post_thumbnail_url($post->ID, 'full');

        // Get first attached image (if content image not found)
        $attached_images = get_attached_media('image', $post->ID);
		echo "<pre>";
		//print_r($attached_images);
		echo "</pre>";
        $first_attached_image = '';
        foreach ($attached_images as $attachment) {
            $first_attached_image = wp_get_attachment_url($attachment->ID);
            if ($first_attached_image !== $logo_url) {
                break;
            }
        }

        // Select the best image (Priority: Featured > Content Image > Attached Image)
      $custom_image = $first_attached_image;

        // Output Open Graph & Twitter meta tags
        if ($custom_image) {
            echo '<meta property="og:image" content="' . ($custom_image) . '">' . "\n";
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:image" content="' . ($custom_image) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'custom_social_media_thumbnail',40);






/**
add_action('wp_head', function () {
    ob_start();
}, 0);

add_action('wp_head', function () {
    $head_content = ob_get_clean();
    
    // Remove Twitter meta tags
    $head_content = preg_replace('/<meta property="twitter:.*?"[^>]+>/', '', $head_content);
    
    // Remove Open Graph meta tags
    $head_content = preg_replace('/<meta property="og:.*?"[^>]+>/', '', $head_content);
    
    echo $head_content;
}, PHP_INT_MAX);

**/
add_action('wp_head', function () {
    if (is_singular('vehica_car')) { // Check if it's a single post of 'vahica_car'
	   global $post;
	   $attached_images = get_attached_media('image', $post->ID);
	  // print_r($attached_images);
	   foreach ($attached_images as $key => $val) {
		   //echo "<pre>";
    //echo ($val->guid);
	$custom_image_url=$val->guid;
	break;
}
echo "testing";
        ob_start(function ($buffer) {
            // Remove old meta tags
           /* $buffer = preg_replace([
                '/<meta property="og:image"[^>]+>/',      // Remove <meta property="og:image" ...>
                '/<meta name="twitter:image"[^>]+>/'     // Remove <meta name="twitter:image" ...>
            ], '', $buffer);**/

            // Add new meta tags with custom image URL
            //$custom_image_url = 'http://example.com/path-to-your-new-image.jpg'; // Set your custom image URL
            $buffer .= '<meta property="og:image" content="' . ($custom_image_url) . '" />';
            $buffer .= '<meta name="twitter:image" content="tt' . ($custom_image_url) . '" />';

            return $buffer;
        });
    }
}, 0);















