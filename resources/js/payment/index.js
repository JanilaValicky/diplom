const stripe = Stripe(stripeKey);

let elements;

initialize();
checkStatus();

document
  .querySelector('#payment-form')
  .addEventListener('submit', handleSubmit);

async function initialize() {
  const paymentButton = document.getElementById('submit');
  const planName = paymentButton.getAttribute('data-plan-name');

  $.ajax({
    url: route('api.payment.checkout', { plan_name: planName }),
    type: 'POST',
    dataType: 'json',
    contentType: 'application/json',
    async success(responseData) {
      const { clientSecret } = responseData;

      elements = stripe.elements({ clientSecret });

      const paymentElementOptions = {
        layout: 'tabs',
      };

      const paymentElement = elements.create('payment', paymentElementOptions);
      paymentElement.mount('#payment-element');
    },
  });
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);

  const response = await stripe.confirmPayment({
    elements,
    confirmParams: { },
    redirect: 'if_required',
  });

  if (response.error) {
    Swal.fire({
      title: response.error.status,
      text: response.error.message,
      icon: 'error',
      timer: 3000,
    });
  } else {
    const { paymentIntent } = response;

    $.ajax({
      url: route('api.payment.success'),
      type: 'GET',
      data: { payment_intent: paymentIntent },
      success(response) {
        $('#payment-details').hide();
        $('#success-message').text(response.message);
        $('#success-block').show();
      },
    });
  }

  setLoading(false);
}

async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    'payment_intent_client_secret',
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
  handlePaymentStatus(paymentIntent);

  switch (paymentIntent.status) {
    case 'succeeded':
      showMessage('Your payment is succeeded.');
      break;
    case 'processing':
      showMessage('Your payment is processing.');
      break;
    case 'requires_payment_method':
      showMessage('Your payment was not successful, please try again.');
      break;
    default:
      showMessage('Something went wrong.');
      break;
  }
}

// UI helpers

function showMessage(messageText) {
  const messageContainer = document.querySelector('#payment-message');

  messageContainer.classList.remove('hidden');
  messageContainer.textContent = messageText;

  setTimeout(() => {
    messageContainer.classList.add('hidden');
    messageContainer.textContent = '';
  }, 4000);
}

function setLoading(isLoading) {
  if (isLoading) {
    document.querySelector('#submit').disabled = true;
    document.querySelector('#spinner').classList.remove('hidden');
    document.querySelector('#button-text').classList.add('hidden');
  } else {
    document.querySelector('#submit').disabled = false;
    document.querySelector('#spinner').classList.add('hidden');
    document.querySelector('#button-text').classList.remove('hidden');
  }
}
