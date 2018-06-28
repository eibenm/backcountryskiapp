$(function() {

    var $document = $(document);

    // Default View
    $document.on("click", "#columns-default-view", function() {
        $("#column-form input[type='checkbox']").each(function () {
            $(this).removeAttr("checked");
        });
        $("#column-form input[name='columnsToShow[name_route]']").prop("checked", true);
        $("#column-form input[name='columnsToShow[elevation_gain]']").prop("checked", true);
        $("#column-form input[name='columnsToShow[vertical]']").prop("checked", true);
        $("#column-form input[name='columnsToShow[distance]']").prop("checked", true);
        return false;
    });

    // Close Columns View
    $document.on("click", "#close-columns-to-show", function() {
        $('#column-form').collapse('hide');
        return false;
    });

});