require('@popperjs/core')
window.$ = require('jquery')
require('./bootstrap');
require('admin-lte');

window.axios = require('axios')

window.Swal = require('sweetalert2')

import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()

$('.nav-link').on('click', function() {
    $('.nav-link').removeClass('active');
    $(this).addClass('active');
});

window.myModal = (function() {
    let modal = $('<div class="modal" id="my-modal" />');
    let modal_dialog = $('<div class="modal-dialog modal-xl" />');
    let modal_content = $('<div class="modal-content" />');
    let modal_header = $('<div class="modal-header" />');
    let dismiss_button = $('<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>');
    let modal_body = $('<div class="modal-body" />');
    let modal_footer = $('<div class="modal-footer" />');
    let close_button = $('<buttom class="btn btn-sm btn-danger">Fechar</buttom>');

    close_button.on('click', function() {
        myModal.close();
    });
    dismiss_button.on('click', function() {
        myModal.close();
    });

    modal_header.append(dismiss_button);
    modal_footer.append(close_button);
    modal_content.append(modal_header, modal_body, modal_footer);
    modal_dialog.append(modal_content);
    modal.append(modal_dialog);
    modal.appendTo('body');

    return {
        open: function(content) {
            modal_body.empty().append(content);
            modal.show();
        },
        close: function() {
            modal_body.empty();
            modal.hide();
        }
    }
}());
