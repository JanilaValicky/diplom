$('#save-button').click(() => {
  const brandName = $('#brand-name');
  if (brandName.hasClass('is-invalid')) {
    return;
  }
  $('#brand-warning').hide();
  $.ajax({
    method: 'POST',
    url: route('api.brand.create'),
    data: {
      name: brandName.val(),
    },
    dataType: 'json',
    success() {
      $('#brand-success').show();
      brandName.prop('disabled', true);
      $('#save-button').prop('disabled', true);
    },
    error(xhr) {
      switch (xhr.status) {
        case 403: {
          $('#brand-error').show();
          break;
        }
        case 422: {
          if (!$('#name-exists').isShown()) {
            brandName.addClass('is-invalid');
            $('#invalid-name').show();
          }
          break;
        }
      }
    },
  });
});

$('#brand-name').on('input', () => {
  const brandName = $('#brand-name');
  if (!brandName.val().trim().length) {
    $('#name-exists').hide();
    brandName.addClass('is-invalid');
    $('#invalid-name').show();
  } else {
    brandName.removeClass('is-invalid');
    $('#invalid-name').hide();
    $.ajax({
      global: false,
      method: 'GET',
      url: route('api.brand.exists'),
      data: {
        name: brandName.val(),
      },
      dataType: 'json',
      success(d) {
        if (d.data.exists) {
          brandName.addClass('is-invalid');
          $('#name-exists').show();
        } else {
          brandName.removeClass('is-invalid');
          $('#name-exists').hide();
        }
      },
    });
  }
});
