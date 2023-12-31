Dropzone.autoDiscover = false;

$(function () {

    let dropzoneDiv = $('div#myDropzone');

    if (dropzoneDiv.length > 0) {

        let max_files = dropzoneDiv.data('max-files');
        let accepted_files = dropzoneDiv.data('accepted-files');
        let action = dropzoneDiv.data('action');
        let form = dropzoneDiv.parents('form');
        let form_url = form.attr('action');
        let btn_submit = form.find(':submit');

        dropzoneDiv.dropzone({
            url: form_url,
            method: 'post',
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: max_files,
            parallelUploads: 100,
            maxFilesize: 10, //mb
            paramName: 'files',
            acceptedFiles: accepted_files,
            init: function () {

                let myDropzone = this;

                btn_submit.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    dropzoneDiv.removeClass('is-invalid');
                    dropzoneDiv.parent().find('.invalid-feedback').remove();

                    if (action != 'update' && myDropzone.getQueuedFiles().length < 1) {

                        let message = 'At least 1 image is required.';
                        customAlert(message, 'error');

                    } else if(action == 'update' && myDropzone.getQueuedFiles().length < 1) {

                        form.submit();

                    } else {

                        myDropzone.processQueue();
                    }
                });

                this.on('maxfilesreached', function ($file) {
                    this.removeFile(file);
                });

                this.on('maxfilesexceeded', function (file) {
                    this.removeFile(file);
                });

                this.on('sendingmultiple', function (file, xhr, formData) {

                    let data = form.serializeArray();

                    $.each(data, function (key, el) {
                        formData.append(el.name, el.value);
                    });

                    let logo = form.find('input:file[name=logo]');
                    if(logo.length > 0 && logo[0].files.length > 0) {
                        formData.append('logo', logo[0].files[0]);
                    }

                    let ssm_cert = form.find('input:file[name=ssm_cert]');
                    if(ssm_cert.length > 0 && ssm_cert[0].files.length > 0) {
                        formData.append('ssm_cert', ssm_cert[0].files[0]);
                    }
                });

                this.on('successmultiple', function (file, response) {

                    this.removeAllFiles();

                    Swal.fire({
                        icon: response.status ? 'success' : 'error',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        if (response.status) {
                            window.location.replace(response.data.redirect_to);
                        }
                    });
                });

                this.on('errormultiple', function (file, response) {

                    let errors = response.errors;
                    let messages = [];

                    if (errors) {

                        this.removeAllFiles();

                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();

                        $.each(errors, function (name, message) {

                            let array = name.split('.');
                            let el = null;

                            if(array.length == 0) {
                                el = form.find('[name="' + name + '"]');
                            } else {

                                let element = array[0];

                                for(i = 0; i < array.length; i++) {
                                    element += '[' + array[i] + ']';
                                }
                                el = form.find('[name="' + array[0] + element + + '"]');
                            }

                            messages[name] = message;
                            el.addClass('is-invalid');
                            el.parent().append('<span class="invalid-feedback" role="alert"><strong>' + message + '</strong></span>');
                        });

                    }

                    // alertHeader(messages);
                    customAlert(response.message ?? response, 'error');
                });
            }
        });
    }
});