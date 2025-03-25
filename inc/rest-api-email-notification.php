<?php
function register_custom_email_endpoint() {
    register_rest_route('custom/v1', '/send-email-notification', array(
        'methods'  => 'POST',
        'callback' => 'handle_send_email_request',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_custom_email_endpoint');

/* api url path https://travelcarsnz.com/wp-json/custom/v1/send-email-notification*/
function handle_send_email_request(WP_REST_Request $request) {
	  echo "<pre>";
	  print_r($request);
}

/*onload popup show start code*/
function homepage_popup_shortcode() {
ob_start();?>
<style type="text/css">
.overlay_pop {display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0, 0, 0, 0.5);z-index: 999;}
#popuponload {display: none;position: fixed;
top: 50%;left: 50%;transform: translate(-50%, -50%);background-color: white;padding: 20px;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);z-index: 1000;width: 500px;}
.close-btn {position: absolute;top: 4px;right: 6px;cursor: pointer;background-color: white;border: 1px solid black;border-radius: 50%;width: 31px;height: 31px;display: flex;align-items: center;justify-content: center;font-size: 25px;}

td, th {border: none!important;}
.tnp-field.tnp-field-email input {width: 70%;max-width: 100%; padding: 8px 10px !important;border: 0;background: #fff;margin: auto;}
.tnp-field.tnp-field-button input.tnp-submit {padding: 9px 38px 5px;font-size: 16px;letter-spacing: 1.8px;background: #ffac1e;border: 0;outline: none;cursor: pointer;margin: 20px auto 0;display: inherit;height: auto !important;line-height: normal; font-weight: 800;}
.tnp-field.tnp-field-email label { display: none;}

#popup-message {
display: none;position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);
padding: 20px;background-color: white;border: 1px solid #ccc;z-index: 1000;text-align: center;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);}
#popup-overlay {display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0, 0, 0, 0.5);
z-index: 999;}
.hr_cls:after{
background: white;
display: block;
content: '';
height: 1px;
width: 70px;
margin: auto;
}

</style>

  <!-- Fallback force center content -->
  <div id="popuponload" style="text-align: center;"> 
    <div id="popup-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;"></div>
    <table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 500px; background-color: #ffffff; position: absolute; margin: auto; left:0; right:0; top:0; bottom:0;" width="600">
        <tr>
      <td style="background:rgb(35, 79, 118); padding: 10px 10px 30px; text-align: center;">
        <span class="close-btn">&times;</span>
      <img src="<?php echo site_url(); ?>/wp-content/uploads/2024/07/logo-1.webp" style="width: 130px;">
        <h5 class="hr_cls" style="font-size: 18px;color:#fff;line-height: 40px;letter-spacing: 0.205em;text-transform: uppercase;list-style-type: none;margin: 0 0 10px;">join TRAVEL CARS NEW ZEALAND</h5>
        <h1 style="color:#ffac1e; font-size: 50px; line-height: normal; font-weight:800; margin-bottom:22px; padding: 0 0 10px;">SUBSCRIBE NOW!</h1>
        <p style="color:#fff; padding: 0 0 20px; line-height:1.4;letter-spacing: 0.098em;--pY_8zA: 0;text-transform: uppercase;--oJepFA: 0;--uYCVzQ: none;list-style-type: none; font-size: 18px;     font-weight: 500 !important;">MONTHLY UPDATES & TRAVEL<br>DISCOUNTS</p> 
            <div class="tnp tnp-subscription ">
                <form method="post">
                    <input type="hidden" name="nlang" value="">
                    <div class="tnp-field tnp-field-email"><label for="tnp-1">Email</label>
                    <input class="tnp-email" type="email" name="ne" id="tnp-1" placeholder="Email" required="">
                <div id="popup-message" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); padding:20px; background-color:white; border:1px solid #ccc; z-index:1000; text-align:center;">
                <span id="popup-content"></span>
                </div>
                   </div>
                    <div class="tnp-field tnp-field-button" style="text-align: left; font-weight: 600;">
                        <input class="tnp-submit" type="submit" value="SIGN ME UP !" style="">
                    </div>
                </form>
            </div>
      </td>
    </tr>
      </table>
</div>

<?php
        $output = ob_get_clean(); 
        return $output; 
}
add_shortcode('homepage_popup', 'homepage_popup_shortcode');
/*end onload popup code in wp */


// Hook into WooCommerce when a product is added to the cart
add_action('woocommerce_add_to_cart', 'schedule_cart_reminders', 10, 6);
function schedule_cart_reminders($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data) {
    $user_id = get_current_user_id();
    // Schedule the first reminder email for 12 hours later
    wp_schedule_single_event(time() + 12 * 60 * 60, 'send_first_cart_reminder_email', array($user_id));
    // Schedule the second reminder email for 48 hours later
    wp_schedule_single_event(time() + 48 * 60 * 60, 'send_second_cart_reminder_email', array($user_id));
}

// Action hook to send the first reminder email after 12 hours
add_action('send_first_cart_reminder_email', 'send_first_cart_reminder_email');

function send_first_cart_reminder_email($user_id) {
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
    ));

    if (empty($customer_orders)) {
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        $subject = 'Reminder: Complete Your Purchase';
        //$subject = 'Travel Cars NZ';
        $message = '<html><link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<body style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #f2f4f6; color: #000000" align="center">   
<div style="text-align: center; margin: 0; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%; background-color: #f2f4f6; color: #000000;" align="center">
<div style="text-align: center;">
<table style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #fcb500;" width="600" align="center">
<tbody>
<tr style="background-color: #fcb500;">
<td style="width: 596px; vertical-align: top;" width="596">
<img style="width: 120px; max-width: 120px; height: 65px; max-height: 45px; text-align: center; color: #ffffff;" src="https://travelcarsnz.com/wp-content/uploads/2024/01/Travelcarslogo.png" alt="Logo" align="center" /></td>
</tr>
</tbody>
</table>
<img style="width: 600px; max-width: 600px; height: 350px;  text-align: center;" alt="Hero image" src="https://travelcarsnz.com/wp-content/uploads/2024/07/12h-image.jpeg" align="center" width="600" height="350">
<table style="text-align: center; vertical-align: top; margin-top: -1px !important; width: 600px; max-width: 600px; background-color: #ffffff;" width="600" align="center">
<tbody>
<tr>
<td style="width: 596px; vertical-align: top;" width="596">
<p style="font-size: 35px;margin-top: 30px; color: #000000; letter-spacing: -0.05em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000;">FORGOT TO LIST YOUR CAR?</span></p>
<p style="font-size: 15px; color: #000000; letter-spacing: 0em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000; text-decoration: none;">🚘 List it now and make live in the number one travel <br> platform in New Zealand.</span></p>
<p style="font-size: 15px; color: #000000; letter-spacing: 0em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000;">⚡ Take advantage of the </span><span style="font-weight: bold; font-style: normal; color: #ff3131;">super-boost option </span><span style="font-weight: bold; font-style: normal; color: #000000;">and reach <br> over 300,000 TCNZ members!</span></p>
<div style="font-size: 15px; color: #000000; letter-spacing: 0em; padding-top: 10px; padding-bottom: 40px; "><span style="font-size: 50px; color: red; margin-right: 5px;    vertical-align: middle;"> 🡆</span>
<a style="font-weight: bold; font-style: normal; display: inline-block; font-size: 18px; padding: 10px 10px; border-radius: 10px; background: #fcb500; color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); transition: background 0.3s, color 0.3s; text-decoration: none;     margin-right: 60px;" href="https://travelcarsnz.com/">SELL YOUR CAR NOW!</a></div>
<hr style="height: 1px; width: 20%; background: black;" />
</td>
</tr>
</tbody>
</table>
<table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #fcb500;" width="600">
<tbody>
<tr style="background-color: #fcb500;">
<td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 20px; padding-bottom: 20px;" width="596">
    <a style="text-decoration: none; font-size: 14px; etter-spacing: -0.02em; color:#fff" href="mailto:INFO@TRAVELCARSNZ.COM">INFO@TRAVELCARSNZ.COM</a>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>';

        $fromName = "Travel Cars NZddddddddddddd";
        $headers = "From: $fromName <$user_email>" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        wp_mail($user_email, $subject, $message, $headers);
    }
}

// Action hook to send the second reminder email after 48 hours
add_action('send_second_cart_reminder_email', 'send_second_cart_reminder_email');

function send_second_cart_reminder_email($user_id) {
    $customer_orders = get_posts(array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()),
    ));

    if (empty($customer_orders)) {
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        //$subject = 'Travel Cars NZ';
        $subject = 'Final Reminder: Complete Your Purchase';
        $message = '<html><link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<body style="text-align: center; margin: 0; padding-top: 10px; padding-bottom: 10px; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%;background-color: #f2f4f6; color: #000000" align="center">   
<div style="text-align: center; margin: 0; padding-left: 0; padding-right: 0; -webkit-text-size-adjust: 100%; background-color: #f2f4f6; color: #000000;" align="center">
<div style="text-align: center;">
<table style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #fcb500;" width="600" align="center">
<tbody>
<tr style="background-color: #fcb500;">
<td style="width: 596px; vertical-align: top;" width="596">
<img style="width: 120px; max-width: 120px; height: 65px; max-height: 45px; text-align: center; color: #ffffff;" src="https://travelcarsnz.com/wp-content/uploads/2024/01/Travelcarslogo.png" alt="Logo" align="center" /></td>
</tr>
</tbody>
</table>
<img style="width: 600px; max-width: 600px; height: 350px;  text-align: center;" alt="Hero image" src="https://travelcarsnz.com/wp-content/uploads/2024/07/12h-image.jpeg" align="center" width="600" height="350">
<table style="text-align: center; vertical-align: top; margin-top: -1px !important; width: 600px; max-width: 600px; background-color: #ffffff;" width="600" align="center">
<tbody>
<tr>
<td style="width: 596px; vertical-align: top;" width="596">
<p style="font-size: 35px;margin-top: 30px; color: #000000; letter-spacing: -0.05em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000;">FORGOT TO LIST YOUR CAR?</span></p>
<p style="font-size: 15px; color: #000000; letter-spacing: 0em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000; text-decoration: none;">🚘 List it now and make live in the number one travel <br> platform in New Zealand.</span></p>
<p style="font-size: 15px; color: #000000; letter-spacing: 0em; text-transform: none;"><span style="font-weight: bold; font-style: normal; color: #000000;">⚡ Take advantage of the </span><span style="font-weight: bold; font-style: normal; color: #ff3131;">super-boost option </span><span style="font-weight: bold; font-style: normal; color: #000000;">and reach <br> over 300,000 TCNZ members!</span></p>
<div style="font-size: 15px; color: #000000; letter-spacing: 0em; padding-top: 10px; padding-bottom: 40px; "><span style="font-size: 50px; color: red; margin-right: 5px;    vertical-align: middle;"> 🡆</span>
<a style="font-weight: bold; font-style: normal; display: inline-block; font-size: 18px; padding: 10px 10px; border-radius: 10px; background: #fcb500; color: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); transition: background 0.3s, color 0.3s; text-decoration: none;     margin-right: 60px;" href="https://travelcarsnz.com/">SELL YOUR CAR NOW!</a></div>
<hr style="height: 1px; width: 20%; background: black;" />
</td>
</tr>
</tbody>
</table>
<table align="center" style="text-align: center; vertical-align: top; width: 600px; max-width: 600px; background-color: #fcb500;" width="600">
<tbody>
<tr style="background-color: #fcb500;">
<td style="width: 596px; vertical-align: top; padding-left: 30px; padding-right: 30px; padding-top: 20px; padding-bottom: 20px;" width="596">
    <a style="text-decoration: none; font-size: 14px; etter-spacing: -0.02em; color:#fff" href="mailto:INFO@TRAVELCARSNZ.COM">INFO@TRAVELCARSNZ.COM</a>
</td>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>';
        //$headers = array('Content-Type: text/html; charset=UTF-8');

        $fromName = "Travel Cars NZ";
        $headers = "From: $fromName <$user_email>" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

        wp_mail($user_email, $subject, $message, $headers);
    }
}

// Clear scheduled events on plugin deactivation
register_deactivation_hook(__FILE__, 'clear_scheduled_events');
function clear_scheduled_events() {
    $timestamp = wp_next_scheduled('send_first_cart_reminder_email');
    wp_unschedule_event($timestamp, 'send_first_cart_reminder_email');

    $timestamp = wp_next_scheduled('send_second_cart_reminder_email');
    wp_unschedule_event($timestamp, 'send_second_cart_reminder_email');
}



?>