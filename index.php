<?php declare(strict_types=1);

use InteractionDesignFoundation\PaytmIntegration\PaytmClient;

require __DIR__.'/autoload.php';

$paytmClient = new PaytmClient(
    $_SERVER['PAYTM_MERCHANT_ID'],
    $_SERVER['PAYTM_MERCHANT_KEY'],
    $_SERVER['PAYTM_WEBSITE_NAME'],
    $_SERVER['PAYTM_API_ENDPOINT']
);

// A random order ID, e.g. 15415
$orderId = time() % 100000;

$txnToken = $paytmClient->createTransactionToken($orderId, 'member:124208', 'INR', 100.0);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover minimum-scale=1">
    <title>Payment | UX Master Classes</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather:400,400i,700|Source+Code+Pro|Source+Sans+Pro:400,400i,700&display=swap">
    <link rel="stylesheet" href="http://localhost:8000/css/app.css?id=f6a9b66432e0b7df404a">
    <link rel="stylesheet" href="http://localhost:8000/css/pages/payment.css?id=7b2c9e3202b8ebafddfa">

</head>
<body class="app-minimal js-layout--minimal">

<div class="pageContainer pageContainer--minimal">

    <nav class="navigationHeader navigationHeader--minimal sm:hide hide-print">
        <div class="navigationHeader__primaryNavigationBar gridContainer">
            <div class="gridContent">
                <div class="primaryNavigationBar">
                    <a class="primaryNavigationBar__logo" href="/" title="Interaction Design Foundation logo" aria-label="Home">
                        <img src="http://localhost:8000/img/ixdf-brand/ixdf-logo-full-expanded.svg?id=dcdda592d21d7653892a" class="navigationHeaderLogo__image" alt="Interaction Design Foundation"/>
                    </a>
                </div>
            </div>
        </div>

    </nav>

    <main class="content-wrapper">
        <div class="gridContainer">
            <div class="gridContent">
                <div class="panel--minimalFlows">
                    <section class="">
                        <h1>One Final Step!</h1>
                        <div class="panel__inner">
                            <h2>Your Master Class Ticket</h2>
                        </div>

                        <div>
                            <div class="productDescriptionWithPrice">
                                <div class="mr-medium-large">
                                    <p class="mb-tiny">“How To Successfully Change Your Career To UX Design”</p>
                                    <p class="mb-none text-neutral-05">Special member price</p>
                                </div>
                                <p class="font-bold text-right mb-none">$5</p>
                            </div>
                        </div>

                        <div class="panel__inner mt-medium">
                            <h2>Payment Method</h2>
                        </div>

                        <form method="POST" action="" id="checkoutForm" class="form" autocomplete="off">

                            <div class="paymentOptions paymentOptions--columnsLayout m-none mb-medium">
                                <div class="mt-small pl-none mb-small">
                                    <input id="stripe_usa__credit_card--input" type="radio" name="paymentOption" value="stripe_usa__credit_card" checked="" required="">
                                    <label class="flex" for="stripe_usa__credit_card--input">
                                        <span>Credit/Debit card</span>
                                    </label>
                                </div>
                                <div class="mt-small pl-none mb-small">
                                    <input id="amazon_pay_usa__amazon_pay--input" type="radio" name="paymentOption" value="amazon_pay_usa__amazon_pay" required="">
                                    <label class="flex" for="amazon_pay_usa__amazon_pay--input">
                                        <span>Amazon Pay</span>
                                    </label>
                                </div>
                                <div class="mt-small pl-none mb-small">
                                    <input id="stripe_usa__credit_card--input" type="radio" name="paymentOption" value="stripe_usa__credit_card" checked required>
                                    <label class="flex" for="stripe_usa__credit_card--input">
                                        <img src="https://assetscdn1.paytm.com/frontendcommonweb/11272962.svg" width="75px">
                                    </label>
                                </div>

                            </div>

                            <button type="button" class="button button--primary" onclick="window.Paytm.CheckoutJS.invoke()">
                                Proceed to Pay
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </main>

</div>
<script>
    function initiatePaytmCheckout(){
        var config = {
            "root": "",
            "flow": "DEFAULT",
            "data": {
                "orderId": "<?php echo $orderId ?>", /* update order id */
                "token": "<?php echo $txnToken ?>", /* update token value */
                "tokenType": "TXN_TOKEN",
                "amount": "100" /* update amount */
            },
            "handler": {
                "notifyMerchant": function(eventName,data){
                    console.log("notifyMerchant handler function called");
                    console.log("eventName => ",eventName);
                    console.log("data => ",data);
                }
            }
        };

        if (window.Paytm && window.Paytm.CheckoutJS){
            window.Paytm.CheckoutJS.onLoad(function excecuteAfterCompleteLoad() {
                // initialze configuration using init method
                window.Paytm.CheckoutJS.init(config).then(function onSuccess() {
                    // after successfully updating configuration, invoke JS Checkout
                    console.log('Ready for Paytm checkout');
                }).catch(function onError(error){
                    console.log("error => ",error);
                });
            });
        }
    }
</script>
<script type="application/javascript" crossorigin="anonymous" src="https://securegw-stage.paytm.in/merchantpgpui/checkoutjs/merchants/<?php echo $_SERVER['PAYTM_MERCHANT_ID'] ?>.js" onload="initiatePaytmCheckout()"> </script>
</body>
</html>
