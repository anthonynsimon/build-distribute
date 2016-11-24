(function($){
    var originalContents = $("#editableTextArea").val();

    $("#editableTextArea").click(function() {
        $(this).attr("readonly", false);

        $("#editableTextAreaSubmit").attr("hidden", false);
        $("#editableTextAreaCancel").attr("hidden", false);
    });

    $("#editableTextAreaCancel").click(function() {
        $("#editableTextArea").val(originalContents);
        $("#editableTextArea").attr("readonly", true);

        $("#editableTextAreaSubmit").attr("hidden", true);
        $("#editableTextAreaCancel").attr("hidden", true);
    });
})(jQuery);

