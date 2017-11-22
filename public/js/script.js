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