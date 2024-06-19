window.logout = () => {
  localStorage.removeItem('auth_token');

  $.ajax({
    method: 'POST',
    url: route('logout'),
    success(response) {
      window.location.href = '/login';
    },
    error(xhr, status, error) {
      console.error(xhr);
    },
  });
};
