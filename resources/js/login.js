$('#submit-button').on('click', (event) => {
  event.preventDefault();

  $.ajax({
    method: 'POST',
    data: {
      email: $('#email').val(),
      password: $('#password').val(),
    },
    url: route('login.auth'),
    headers: {
      'X-CSRF-TOKEN': $('#x-token').val(),
    },
    success: (response, status, xhr) => {
      localStorage.setItem('auth_token', response.token);
      setTimeout(() => {
        location.href = response.redirect_url;
      }, 1000);
    },
    error: (xhr, status, error) => {
      $('.form-control').removeClass('is-invalid');

      if (xhr.status === 422) {
        const { errors } = xhr.responseJSON;
        for (const field in errors) {
          if (errors.hasOwnProperty(field)) {
            const errorMessage = errors[field][0];
            $(`#${field}`).addClass('is-invalid');
            $(`#${field}`).parent('div').find('.invalid-feedback').text(errorMessage);
          }
        }
      }
    },
  });
});
