document.addEventListener('DOMContentLoaded', function() {
    var stripe = Stripe('pk_test_51PjmO9Jbpg1LH6H10zoPjdadXemXAP9XBlsLhY5UGuqaXPCasL7vSykxLz1V2OeD7iZ8mcAn73m7MzeK7ez35S9I00QVrgP4kA'); 
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // console.log("PaymentMethod ID: ", result.paymentMethod.id); 
                var existingHiddenInput = document.getElementById('stripePaymentMethodId');
                if (!existingHiddenInput) {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripePaymentMethodId');
                    hiddenInput.setAttribute('id', 'stripePaymentMethodId');
                    hiddenInput.setAttribute('value', result.paymentMethod.id);
                    console.log("PaymentMethod ID:", result.paymentMethod.id);
                    form.appendChild(hiddenInput);
                } else {
                    existingHiddenInput.value = result.paymentMethod.id;
                }

                // console.log("Hidden Input Value: ", existingHiddenInput.value); 
                form.submit();
            }
        });
    });
});
