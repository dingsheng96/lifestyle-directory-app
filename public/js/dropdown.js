$(function() {

    if ($(".city-filter").length > 0) {

        if ($(".city-filter option:selected").val() != 0) {
            getCitiesFromCountryState($(".city-filter option:selected").val());
        }

        $(".city-filter").on("change", function() {
            getCitiesFromCountryState($(this).val());
        });
    }
});

function getCitiesFromCountryState(state_id) {
    let dropdown = $(".city-dropdown");

    removeChildOption(dropdown);

    let url = dropdown.data("city-route").replace("__REPLACE__", state_id);

    if (state_id != null && state_id != "") {
        setDataIntoDropdown(url, dropdown, "name", "id");
    }
}