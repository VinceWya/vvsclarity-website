<?php
// PayPal IPN listener script

// Set PayPal's IPN URL for verification
$paypal_url = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'; // Use https://ipnpb.paypal.com for live
$paypal_email = 'vincentreyes2007@gmail.com';  // Your PayPal email

// Get the IPN data from PayPal
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$ipn_data = array();

foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $ipn_data[$keyval[0]] = urldecode($keyval[1]);
    }
}

// Prepare data for PayPal verification
$ipn_string = 'cmd=_notify-validate';
foreach ($ipn_data as $key => $value) {
    $ipn_string .= "&$key=$value";
}

// Post back to PayPal for verification
$ch = curl_init($paypal_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $ipn_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Check if the payment is verified
if ($response == 'VERIFIED') {
    // Process the payment and assign the download link
    if ($ipn_data['payment_status'] == 'Completed' && $ipn_data['mc_gross'] == '15.00') {
        // Example: send the download link to the buyer
        $buyer_email = $ipn_data['payer_email'];
        $beat_name = $ipn_data['item_name'];  // Get beat name from IPN data

        // Logic to send download link (via email or database update)
        mail($buyer_email, 'Your Beat Purchase', 'Thank you for your purchase! Download your beat from: https://vincewya.github.io/vvsclarity-website/store.html/thank-you.html');
    }
} else {
    // Log any failures for debugging
    file_put_contents('ipn.log', date('Y-m-d H:i:s') . ' - Invalid IPN: ' . $response . "\n", FILE_APPEND);
}
?>
