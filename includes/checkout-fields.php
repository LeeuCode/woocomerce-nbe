

<?php
$url = "https://test-nbe.gateway.mastercard.com/api/rest/version/57/merchant/EGPTEST1/session";
$username = 'merchant.EGPTEST1';
$password = '61422445f6c0f954e24c7bd8216ceedf';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// $headers = array(
//    "Authorization: Basic merchant.EGPTEST1:61422445f6c0f954e24c7bd8216ceedf",
//    "Content-Type: application/json",
//    "Content-Length: 0",
// );
curl_setopt($curl, CURLOPT_USERPWD, $username . ":" . $password);
// curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
$result = json_decode($resp);
// var_dump($result->session->id);
?>
<script defer src="https://test-nbe.gateway.mastercard.com/checkout/version/57/checkout.js"
        data-error="?errorCallback"
        data-complete="?result=completeCallbackElnady"
        data-cancel="?result=cancelCallbackElnady">
</script>
<script async>
    // window.addEventListener("DOMContentLoaded", function(){
    // Your code
    setTimeout(function() {
        document.getElementById('showLightbox').addEventListener('click', function() {
            var test = Checkout.showLightbox();
            console.log(test);
        });
        document.getElementsByName("checkout")[0].addEventListener('submit', function(e) {
            e.preventDefault();
            alert('yes');
            return false;
        });
        function errorCallback(error) {
            console.log(JSON.stringify(error));
        }
        function cancelCallback() {
            console.log('Payment cancelled');
        }
        Checkout.configure( {
            merchant: 'EGPTEST1',
            order: {
                amount: '<?php echo WC()->cart->total; ?>',
                currency: '<?php echo get_option('woocommerce_currency'); ?>',
                description: 'Ordered goods',
                id: '<?php echo $order_id; ?>'
            },
            session:{
                id: '<?php echo $result->session->id; ?>'
            },
            interaction: {
                operation: 'PURCHASE',
                merchant: {
                    name: 'NBE Test',
                    address: {
                        line1: '200 Sample St',
                        line2: '1234 Example Town'
                    }
                }
            }
        });
    }, 500)
    // });
</script>
...
<input id="showLightbox" type="button" value="Pay with Lightbox" />
<input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" data-error="errorCallback"
        data-complete="?result=completeCallbackElnady"
        data-cancel="?result=cancelCallbackElnady" />
...


