$('#addField').click(function () {
    $.ajax({
        type: 'POST',
        data: {request: 'add'},
        success: function (data) {
            $('#addField').before(data);
            // console.log('vasa');
        }
    });

});

$('a[data-toggle=collapse]').click(function () {
   $(this).find('.fa-angle-down').toggleClass('rotate');
});
