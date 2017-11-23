/**
 * Created by Simanov on 21.11.2017.
 */
$(function (urlName) {
    $("form#item").submit(function () {
        let fData = $('#item').serialize();
        $.ajax({
            url: urlName,
            type: 'post',
            dataType: 'json',
            data: fData,
            success: function (data) {
                if(data['success'] == 1){

                } else {
                    $('#errors').html('');
                    for (let key in data){
                        $('#errors').append('<span>'+data[key][0]+'</span>');
                    }
                }
            }
        })
    })
});
$(function () {
    $("a:contains('Добавить')").addClass('btn-sm btn-success').css('border-radius','4px')
        .css('text-decoration','none');
    $("a:contains('Редактировать')").addClass('btn-sm btn-warning')
        .css('text-decoration','none').css('color', 'black').css('border-radius','4px');
    $("a:contains('Удалить')").addClass('btn-sm btn-danger')
        .css('text-decoration','none').css('border-radius','4px');
});