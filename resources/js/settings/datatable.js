$.fn.dataTable.ext.errMode = 'ignore';

$.extend(true, $.fn.dataTable.defaults, {
  processing: true,
  language: {
    processing: '<div class="fa-3x"><i class="fas fa-spinner fa-pulse"></i></div>',
  },
});
