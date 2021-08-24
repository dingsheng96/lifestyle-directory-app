$(function () {

    let importTranslationModal = $('#importTranslationModal');

    if(importTranslationModal.length > 0) {
        importTranslationModal.on('show.bs.modal', function (event) {

            let version =   $(event.relatedTarget).data('version');
            let form    =   $(this).find('form');
            form.find('#version').val(version);
        });
    }

    let updateServiceModal = $('#updateServiceModal');

    if(updateServiceModal.length > 0) {
        updateServiceModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');

            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('input#update_name').val(obj.name);
            $(this).find('textarea#update_description').text(obj.description);
        });
    }

    let updateAdminModal = $('#updateAdminModal');

    if(updateAdminModal.length > 0) {
        updateAdminModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');

            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('input#update_name').val(obj.name);
            $(this).find('input#update_email').val(obj.email);
            $(this).find('select#update_status').val(obj.status).trigger('change');
        });
    }

});