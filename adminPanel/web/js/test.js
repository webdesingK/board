$('#addField').click(function () {
    $.ajax({
        type: 'POST',
        data: {request: 'add'},
        success: function (data) {
            $('#addField').before(data);
        }
    });

});
