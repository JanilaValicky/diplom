const formsTable = new DataTable('#forms-table', {
  dom: 'lrtip',
  serverSide: true,
  ordering: true,
  language: {
    emptyTable: 'There are no forms.', // todo FIX
    paginate: {
      first: '<i class="fa-solid fa-angles-left"></i>',
      last: '<i class="fa-solid fa-angles-right"></i>',
      next: '<i class="fa-solid fa-angle-right"></i>',
      previous: '<i class="fa-solid fa-angle-left"></i>',
    },
  },
  ajax: {
    method: 'GET',
    url: route('api.forms.index'),
    dataSrc: (d) => {
      d.recordsTotal = d.data.meta.total;
      d.recordsFiltered = d.data.meta.total;
      return d.data.data;
    },
    data: (d) => {
      d.search = $('#form-search').val();
    },
  },
  createdRow: (row, data) => {
    $(row).attr('id', data.id);

    $(row).off('click', '.delete-button');
    $(row).on('click', '.delete-button', (e) => {
      const button = $(e.target);
      const formId = button.data('id');
      const formName = button.data('name');
      $('#deleteModal').modal('show');
      $('#deleteModal .element-name').text(` "${formName}" `);
      $('#confirmDelete').on('click', () => {
        $.ajax({
          url: route('api.forms.delete', { id: formId }),
          type: 'DELETE',
          success() {
            const row = formsTable.row(`#${formId}`);
            if (row) {
              row.remove().draw(false);
              $('#deleteModal').modal('hide');
            }
          },
          error(xhr, status, error) {
            const deleteModal = $('#deleteModal');
            const errorTitle = deleteModal.data('error-title');
            const errorMessage = deleteModal.data('error-message');
            $('#deleteModal .modal-title').text(errorTitle);
            $('#deleteModal .modal-body').text(`${errorMessage}${error}`);
            $('#confirmDelete').hide();
          },
        });
      });
    });
  },
  scrollX: false,
  columns: [
    {
      data: 'id',
    },
    {
      data: 'name',
    },
    {
      data: 'slug',
      render: (data) => `${data}`,
    },
    {
      data: 'start_date',
    },
    {
      data: 'end_date',
    },
    {
      data: 'status',
      render: (data) => `<span class="badge bg-secondary">${data}</span>`,
    },
    {
      render: (data, type, row) => `
            <div class="btn-group text-nowrap">
              <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle-split rounded-end"
                data-bs-toggle="dropdown" aria-expanded="false">
                Actions<i class="fa-solid fa-ellipsis-vertical align-middle fs-5 ms-2"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item edit-button" data-toggle="modal" data-target="#deleteModal" data-id="${row.id}" href="${route('forms.edit', row.id)}"><i class="fa-regular fa-copy align-middle opacity-75 me-2"></i>Copy code</a>
                <button class="dropdown-item delete-button" data-id="${row.id}" data-name="${row.name}"><i class="fa-solid fa-trash-can align-middle opacity-75 me-2"></i>Delete</button>
              </div>
            </div>
        `, // todo localization

    },
  ],
  columnDefs: [
    { orderable: false, targets: [6] },
  ],
});

$('#form-search').on('input', () => {
  formsTable.search($('#form-search').val()).draw();
});

$(document).off('click', '.export-button');
$(document).on('click', '.export-button', function () {
  const exportType = $(this).data('export-type');

  $.ajax({
    url: route(`api.forms.export.${exportType}`),
    method: 'GET',
    success(data, status, xhr) {
      const type = xhr.getResponseHeader('Content-Type') || 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
      const blob = new Blob([data], { type });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      if (exportType === 'excel') {
        a.download = xhr.getResponseHeader('Content-Disposition')?.split('=').pop().trim() || 'forms.xlsx';
      } else {
        a.download = xhr.getResponseHeader('Content-Disposition')?.split('=').pop().trim() || 'forms.csv';
      }
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    },
  });
});
