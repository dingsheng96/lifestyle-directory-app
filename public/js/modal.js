$(function () {

    let importTranslationModal = $('#importTranslationModal');

    if(importTranslationModal.length > 0) {
        importTranslationModal.on('show.bs.modal', function (event) {

            let version =   $(event.relatedTarget).data('version');
            let form    =   $(this).find('form');
            form.find('#version').val(version);
        });
    }

});