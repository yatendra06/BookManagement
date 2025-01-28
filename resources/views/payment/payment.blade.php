<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <form id="payment-form">
        <input type="number" id="amount" placeholder="Enter Amount" required />
        <button type="button" onclick="makePayment()">Pay Now</button>
    </form>

    <script>
        function makePayment() {
            const amount = document.getElementById("amount").value;

            fetch('/api/create-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ amount }),
            })
            .then(response => response.json())
            .then(order => {
                const options = {
                    key: '{{ env("RAZORPAY_KEY") }}',
                    amount: order.amount,
                    currency: order.currency,
                    name: "Test Payment",
                    description: "Test Transaction",
                    order_id: order.id,
                    handler: function (response) {
                        fetch('/api/verify-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'Payment successful') {
                                alert('Payment Successful!');
                            } else {
                                alert('Payment Verification Failed: ' + data.error);
                            }
                        });
                    },
                    theme: {
                        color: "#3399cc",
                    },
                };

                const rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(err => console.error('Error creating order:', err));
        }
    </script>
</body>
</html>
