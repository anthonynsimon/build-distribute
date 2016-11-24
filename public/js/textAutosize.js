(function($, document){
    var lineHeight = 24;

    var update = function(elem) {
    var scrollHeight = $(elem).prop('scrollHeight');
        $(elem).attr('rows', Math.max(scrollHeight/lineHeight, 5));
    };

    $("#editableTextArea").bind('input propertychange', function() {
        update(this);
    });

    update($("#editableTextArea"));
})(jQuery, document);