$(() => {
  $('#dropzone').css('height', $('#drag-zone').height());
});

$(() => {
  $('.datepicker').datepicker();
});

$('.drag').draggable({
  appendTo: 'body',
  helper: 'clone',
});

$('#dropzone').droppable({
  activeClass: 'active',
  hoverClass: 'hover',
  accept: ':not(.ui-sortable-helper)', // Reject clones generated by sortable
  drop(e, ui) {
    const $el = $(`<div class="drop-item"><summary>${ui.draggable.text()}</summary></div>`);
    switch (ui.draggable.text()) {
      case 'header':
        const $headerInputAndButtonWrapper = $('<div class="d-flex"></div>');
        const $headerInput = $('<input type="text" id="input-header" class="form-control form-control-lg mb-2" placeholder="Enter header">');
        const $headerButton = $('<button type="button" class="btn btn-success btn-lg rounded-end mb-2 me-1"><i class="fa-regular fa-floppy-disk"></i></button>');
        const $headerDisplay = $('<h1 class="mb-2 display-4" id="display-header"></h1>');

        $headerInputAndButtonWrapper.append($headerInput, $headerButton);
        $el.append($headerInputAndButtonWrapper, $headerDisplay);

        $headerButton.click(function() {
          var inputText = $headerInput.val();
          $headerDisplay.text(inputText);
          $headerInputAndButtonWrapper.hide();
          $headerInput.hide();
          $headerButton.hide();
        });
      break;
      case 'text.field':
        const $textField = $('<input type="text" id="text-field" class="form-control form-control-lg mb-2" placeholder="Enter your text">');

        $el.append($textField);
      break;
      case 'file':
        const $file = $('<div class="position-relative mb-2"><input id="file" type="file" class="d-none w-0 h-0 position-absolute"><label for="file" data-tippy-placement="bottom" data-tippy-content="Update photo" class="btn btn-primary me-3"><i class="fas fa-file"></i>Upload</label></div>');

        $el.append($file);
      break;
      case 'paragraph':
        const $paragraphInputAndButtonWrapper = $('<div class="d-flex"></div>');
        const $paragraphInput = $('<input type="text" id="input-paragraph" class="form-control form-control-lg mb-2" placeholder="Enter paragraph">');
        const $paragraphButton = $('<button type="button" class="btn btn-success btn-lg rounded-end mb-2 me-1"><i class="fa-regular fa-floppy-disk"></i></button>');
        const $paragraphDisplay = $('<p class="mb-2" id="display-header"></p>');

        $paragraphInputAndButtonWrapper.append($paragraphInput, $paragraphButton);
        $el.append($paragraphInputAndButtonWrapper, $paragraphDisplay);

        $paragraphButton.click(function() {
          var inputText = $paragraphInput.val();
          $paragraphDisplay.text(inputText);
          $paragraphInputAndButtonWrapper.hide();
          $paragraphInput.hide();
          $paragraphButton.hide();
        });
      break;
      // default:
      //   showMessage('Something went wrong.');
      //   break;
    }
    $el.append($('<button type="button" class="btn btn-danger btn-xs remove mb-2">Remove <i class="fa-solid fa-trash"></i></button>').click(function () { $(this).parent().detach(); }));
    $(this).append($el);
  },
}).sortable({
  items: '.drop-item',
  sort() {
    $(this).removeClass('active');
  },
});

$(document).ready(function() {
  $('#input-header').on('input', function() {
    var inputText = $(this).val();
    $('#display-header').text(inputText);
    $(this).hide();
  });
});
