var Image = (function() {

    function init() {
        var photoID = getQueryStringVariables()['photoID'];
        if (photoID > 0) {
            // Highlight the selected row in the grid.
            $('#gps-grid table tbody tr td:first-child').filter(function() {
                return $(this).text() === photoID;
            }).parent().css({backgroundColor:'rgb(232,237,255)'});
            $('#lnkAddNewImage').show();
        }
        else {
            $('#lnkAddNewImage').show();
        }
        
        $('#lnkAddNewImage').click(function () {
            var id = getQueryStringVariables()['id'];
            location.href = 'index.php?r=skiroutes/update&id=' + id + '&photoID=';
        });
    }

    function onSearchImage(id, photoID) {
        location.href = 'index.php?r=skiroutes/update&id=' + id + '&photoID=' + photoID;
    }
    
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
        onSearchImage: onSearchImage
    };

})();

(function() {
    Image.init();
})();