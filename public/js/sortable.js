$(function () {

    // $('#myDropzone').sortable({
    //     items:'.dz-preview',
    //     cursor: 'move',
    //     opacity: 0.5,
    //     containment: '#myDropzone',
    //     distance: 20,
    //     tolerance: 'pointer'
    // });

    let sortable = $('.sortable');
    let route = sortable.data('reorder-route');

    sortable.sortable({
        connectWith: '.sortable',
        items: 'tr.sortable-row',
        stop: (event, ui) => {

            let body = $(event.target);

            let items = body.sortable('toArray', { attribute: 'data-id' });

            let ids = $.grep(items, (item) => item !== "");

            $.ajax({
                url: route,
                type: "post",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'media' : ids,
                    'parent_id': body.data('parent-id'),
                    'parent_type': body.data('parent-type')
                },
                success: function (xhr) {

                    body.children('tr.sortable-row').each(function (index, value) {
                        $(value).children('th').text(xhr.data.positions[$(value).data('id')])
                    });
                },
                error: function (xhr) {
                    alert('Error occured while reordering.');
                    console.log(xhr);
                    location.reload();
                }
            });
        }
    });

    sortable.disableSelection();
});