$(document).ajaxStart(() => {
  $('.loader').show();
});

$(document).ajaxStop(() => {
  $('.loader').hide();
});

$(document).ajaxError((xhr, error) => {
  ajaxErrorHandler(error.responseJSON);
});

$.ajaxSetup({
  headers: {
    Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
  },
});

function ajaxErrorHandler(error) {
  Swal.fire({
    title: error.status,
    text: error.message,
    icon: 'error',
    timer: 3000,
  });
}
