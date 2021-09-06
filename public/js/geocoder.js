$(function () {

    if($('#location-address-panel ').length > 0) {

        let panel = $('#location-address-panel');
        let button = panel.find('#btn_get_coordinates');
        let route = panel.data('route');

        button.on('click', function () {

            let data = new FormData();

            panel.find('input:text, select').each(function () {
                data.append($(this).attr('name'), $(this).val());
            });

            data.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                cache: false,
                processData: false,
                contentType: false,
                url: route,
                type: 'post',
                data: data,
                dataType: 'json',
                success: xhr => {

                    if (xhr.status) {

                        let data = xhr.data;

                        panel.find('input[name=latitude]').val(data.latitude);
                        panel.find('input[name=longitude]').val(data.longitude);
                    }
                }
            });
        });

    }

});