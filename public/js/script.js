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



function addRecord() {
    console.log(this.innerHTML);
}

$(document).ready(function () {
    $(':button').click(function () {
        let divs;
        let str = {};
        let p = $(this).parent().parent();
        divs = p.children();
        str.name = divs.eq(0).find(".w-100").val();
        str.act = divs.eq(1).find(".w-100 option:selected").text();
        str.content = divs.eq(2).find(".w-100").val();
        let clone = $('.m').first().clone();
        clone.find("#name").html(str.name);
        clone.find("#act").html(str.act);
        clone.find("#cont").html('Примечание: ' + str.content);
        let main = $('.main');
        main.append(clone);
        p.remove();
        // console.log(main.height());
    });
});