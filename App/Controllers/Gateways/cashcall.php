<?php
// PARAMS TO USE FOR GATEWAY
// ===================================>
// Booking REF  -> ($payload->booking_ref_no)
// Invoice URL  -> ($payload->invoice_url)
// Client Email -> ($payload->client_email)
// Price        -> ($payload->price);
// Currency     -> ($payload->currency)
// ===================================>

// GATEWAY NAME CONTROLLER ( IF SPACES IN NAME FOLLOW BY UNDERSCOPE INSTEAD DASH )

$router->post('payment/cashcall', function() {

    $payload = json_decode(base64_decode($_POST['payload']));

    if($payload->type == 'wallet'){
        $payload->price = $_POST['price'];
    }

    $rand =date('Ymdhis').rand();
    $_SESSION['bookingkey'] = $rand;

    // SUCCESS URL
    $success_url = (root).'success/payment?token='.$_POST['payload']."&key=".$rand."&type=0";

    $gateway = array_column(base()->payment_gateways, null, 'name')['cashcall'];


    // Get the current exchange rate for the segment's currency
    $currency_name = $payload->currency;
    $current_currency = array_values(array_filter(App()->currencies, function($currency) use ($currency_name) {
            return $currency->name == $currency_name;
        }))[0] ?? null;
    $current_currency_price = $current_currency->rate;

    // Get the exchange rate for the user's selected currency

    $gateway_name = $gateway->currency;
    $currency_rate = array_values(array_filter(App()->currencies, function($currency) use ($gateway_name) {
            return $currency->name == $gateway_name;
        }))[0] ?? null;
    $con_rate = $currency_rate->rate;

    $price_get = ceil(str_replace(',', '', $payload->price) / $current_currency_price);

    $price = $price_get * $con_rate; // Total price

    if($gateway->dev_mode == 1){
        $status = true;
    }else{
        $status = false;
    }

 ?>

<?php
    include "App/Views/Pay_view.php";
});

