var Services = (function() {

    function init() {
        var servicesID = getQueryStringVariables()['servicesID'];
        if (servicesID > 0) {
            // Highlight the selected row in the grid.
            $('#services-grid table tbody tr td:first-child').filter(function() {
                return $(this).text() === servicesID;
            }).parent().css({backgroundColor:'rgb(232,237,255)'});
            $('#lnkAddNewService').show();
        }
        else {
            $('#lnkAddNewService').show();
        }
        
        $('#lnkAddNewService').click(function () {
            var id = getQueryStringVariables()['id'];
            location.href = 'index.php?r=outfitters/update&id=' + id + '&servicesID=';
        });
    }

    function onSearchService(id, servicesID) {
        location.href = 'index.php?r=outfitters/update&id=' + id + '&servicesID=' + servicesID;
    }
    
    /**
    * Gets query string variables from the URL.
    */
    function getQueryStringVariables() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

    return {
        init: init,
        onSearchService: onSearchService
    };

})();

(function() {
    Services.init();
})();