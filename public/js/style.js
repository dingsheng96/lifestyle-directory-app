$(function() {

    // loader
    $('form').on('submit', function () {
        $('.loading').show();
    });

    $(document).ajaxStart(function () {
        $('.loading').show();
    }).ajaxStop(function () {
        $('.loading').hide();
    });

    // initialize select2
    $(".select2").select2({
        theme: "bootstrap4"
    });

    $(".select2-disabled").select2({
        theme: "bootstrap4",
        disabled: true
    });

    $(".select2-multiple").select2({
        theme: "bootstrap4",
        multiple: true,
        allowClear:true,
        placeholder: $(this).data("placeholder")
    });

    // initialize summernote
    $(".summernote").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['fontname', ['fontname']],
            ['para', ['ul', 'ol', 'paragraph']],
        ],
        fontNames: ['Poppins']
    });

    $(".summernote-disabled").summernote("disable");

    // disabled form submit button when loading submission
    $("form").not(".loading").on("submit", function() {
        $(this).find(":submit").attr("disabled", "disabled");
    });

    // select all checkbox within container
    $(".select-all-toggle").on('click',
        function() {
            if($(this).is(':checked')) {
                $(".select-all-container").find("input:checkbox")
                .each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".select-all-container").find("input:checkbox")
                .each(function() {
                    $(this).prop("checked", false);
                });
            }
        }
    );

    // lower case all
    $(".lcall").on("input", function() {
        let input = $(this)
            .val()
            .toLowerCase();

        $(this).val(input);
    });

    // upper case all
    $(".ucall").on("input", function() {
        let input = $(this)
            .val()
            .toUpperCase();

        $(this).val(input);
    });

    // upper case first letter
    $(".ucfirst").on("input", function() {
        let input = $(this)
            .val()
            .replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

        $(this).val(input);
    });

    // image-reader and preview
    $("body").on("change", ".custom-img-input", function(e) {
        let file    =   e.target.files[0];
        let input   =   $(this);

        if (file) {
            let reader = new FileReader();
            reader.onload = function() {
                input.parents('.form-group').find(".custom-img-preview").attr("src", reader.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // price
    $('.uprice-input').on('input', function () {

        unit_price  =   $(this).val();
        discount    =   $('.disc-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });

    $('.disc-input').on('input', function () {

        discount    = $(this).val();
        unit_price  = $('.uprice-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });

    if($('.btn-decrement').length > 0) {
        $('.btn-decrement').on('click', function () {

            let input = $(this).parents('.input-group').find('.quantity-input');
            let value = parseInt(input.val());

            if(value <= 0) {
                input.val(0);
            } else {
                input.val(value-1);
            }
        });
    }

    if($('.btn-increment').length > 0) {
        $('.btn-increment').on('click', function () {

            let input = $(this).parents('.input-group').find('.quantity-input');
            let value = parseInt(input.val());

            input.val(value+1);
        });
    }

    $('.timepicker').datetimepicker({
        format: 'HH:mm',
        pickDate: false,
        pickSeconds: false,
        pick12HourFormat: false,
        ignoreReadonly: true,
    })

    $('.btnNextTab').on('click', function(){
        $('.toggleTab > .active').next('.list-group-item').trigger('click');
    });

    $('.btnPreviousTab').on('click', function(){
        $('.toggleTab > .active').prev('.list-group-item').trigger('click');
    });
});

