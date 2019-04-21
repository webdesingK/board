$('.name').css({
    padding: '5px',
    cursor: 'default',
    position: 'relative'
}).hover(
    function () {
        $(this).css({
            backgroundColor: '#ccc'
        }).find('button').fadeIn(0);
    },
    function () {
        $(this).css({
            backgroundColor: ''
        }).find('button').fadeOut(0);
    }
).each(function () {
    let marg = $(this).data('depth') * 10;
    $(this).css({
        marginLeft: marg
    })
}).find('button').css({
    position: 'absolute',
    right: 20,
    top: 2,
    display: 'none'
}).click(function (e) {
    e.preventDefault();
    let data = {
        nameOfOperate: 'del',
        parentId: $(this).parent().data('id')
    };
    $.ajax({
        type: 'POST',
        data: data
    });
});