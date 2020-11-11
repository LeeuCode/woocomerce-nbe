

        <script defer src="https://test-nbe.gateway.mastercard.com/checkout/version/57/checkout.js"
                data-error="errorCallback"
                data-complete="?result=completeCallbackElnady"
                data-cancel="?result=cancelCallbackElnady">
        </script>
        <script defer >
            window.onload = function(){
            document.getElementById('showLightbox').addEventListener('click', function() {
                var test = Checkout.showLightbox();
                console.log(test);
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
                    amount: function() {
                        //Dynamic calculation of amount
                        return 35 + 0;
                    },
                    currency: 'EGP',
                    description: 'Ordered goods',
                   id: '88'
                },
                session:{
                   id: 'SESSION0002296876940I1471431M01'
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
            }
        </script>
        ...
        <input id="showLightbox" type="button" value="Pay with Lightbox" />
        <input type="button" value="Pay with Payment Page" onclick="Checkout.showPaymentPage();" data-error="errorCallback"
                data-complete="?result=completeCallbackElnady"
                data-cancel="?result=cancelCallbackElnady" />
        ...


