$('#delete-button').on('click', (event) => {
  event.preventDefault();

  $.ajax({
    url: route('api.avatar.delete'),
    type: 'DELETE',
    success(response) {
      $('#avatar').attr('src', '/assets/images/pflogo.jpg');
      $('#header-avatar').attr('src', '/assets/images/pflogo.jpg');
      $('#profile_picture').val('');
    },
  });
});

$('#profile_picture').on('change', (event) => {
  event.preventDefault();
  const input = document.getElementById('profile_picture');
  const file = input.files[0];
  const formData = new FormData();
  formData.append('profile_picture', file);

  $.ajax({
    url: route('api.avatar.upload'),
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success(response) {
      $('#avatar').attr('src', response.data.path);
      $('#header-avatar').attr('src', response.data.path);
    },
  });
});
