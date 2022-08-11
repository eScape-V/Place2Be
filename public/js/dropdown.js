$(function() {

    $('li.dropdown').on({
        "shown.bs.dropdown": function() { this.close = false; },
        "click":             function() { this.close = true; },
        "hide.bs.dropdown":  function() { return this.close; }
    });

});