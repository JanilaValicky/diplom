$('#submit-button').on('click', (event) => {
  event.preventDefault();

  $.ajax({
    method: 'POST',
    data: {
      name: $('#name').val(),
      email: $('#email').val(),
      password: $('#password').val(),
      password_confirmation: $('#password_confirmation').val(),
    },
    url: route('register'),
    headers: {
      'X-CSRF-TOKEN': $('#x-token').val(),
    },
    success: (response, status, xhr) => {
      localStorage.setItem('auth_token', response.token);
      setTimeout(() => {
        location.href = response.redirect_url;
      }, 300);
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
