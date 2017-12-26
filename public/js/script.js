/**
 * Created by Simanov on 21.11.2017.
 */

var i = 1;

function objForm(formArray) {
    var returnArray = {};
    for (var i = 0; i < formArray.length; i++) {
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}
function setPage(clone, h, n1 = 127, n2 = 300) {
    if (h > 0 && h < n1) {

    } else if (h > n1 + n2 * (i - 1) && h < n1 + n2 * i) {
        i++;
    }
    let page = clone.find("#page");
    page.html(i);
}

function getVals() {
    let vals = $('form').serializeArray();
    vals = objForm(vals);
    vals.date = vals.date.replace(/(\d{2})(\d{2}).(\d{2}).(\d{2})/g, '$4\.$3\.$2');
    return vals;
}
function getRecs() {
    let rec = $('.record');
    var item = null;
    let recs = [];
    var i = 0;
    for (item of rec) {
        let name = $(item).find('#name').html();
        recs[i] = {
            numberIzv: $(item).find('#name').html(), act: $(item).find('#act').html(),
            cont: $(item).find('#cont').html(), link: $(item).find('#link').attr('href'),
            p: $(item).find('#page').html()
        };
        i++;
    }
    return recs;
}

$(document).ready(function () {
    $('#upload').on('submit',function () {
        let el = $('#upload');
        let a = el.attr('action');
        let name = $('#name').val();
        el.attr('action', a + '&num=' + name);
        return true;
    });
    $('#clear').click(function () {
        let name = $('#name').val();
        $.ajax({
            url: '/izv/all/clear/?name=notice&num=' + name,
            type: 'post',
            dataType: 'json',
            data: name
        }).then(function (data) {
            if (data['success'] == 1){
                alert('Папка с извещением очищина');
                $('.main').hide();
                $('.record').remove();
                $('table').hide();
                $('#appendix').val('');
            } else {
                alert(data['error']);
            }
        })
    });
    $(':button').click(function () {
        let divs;
        let str = {};
        let ppp = $(this).parent().parent().parent().parent();
        let pp = $(this).parent().parent().parent();
        let p = $(this).parent().parent();
        divs = p.children();
        str.name = divs.eq(0).find(".w100").val();
        str.link = divs.eq(0).find("#link").text();
        str.act = divs.eq(1).find(".w100 option:selected").text();
        str.content = divs.eq(2).find(".w100").val();
        if (str.content && str.content.indexOf('\n') != -1) {
            str.content = '\n' + str.content;
            str.content = str.content.replace(/\n/g, "<br />");
        }
        let appl = $('textarea[name = "applicability"]');
        let spl = str.name.split('.')[0];
        if (appl.val().indexOf(spl) == -1) {
            let a = appl.val() + spl + '; ';
            appl.val(a);
        }
        let clone = $('.m').first().clone();
        clone.attr('id', 'record');
        clone.find("#name").html(str.name);
        clone.find("#link").attr('href', str.link);
        clone.find("#act").html(str.act);
        clone.find("#cont").html('Примечание: ' + str.content);
        let main = $('.main');
        main.append(clone);
        let h = main.height();
        setPage(clone, h, 550, 1010);
        pp.remove();
        let base = $('#base');
        if (base)
            base.remove();
        let ch = ppp.children();
        if (ch.length == 1) {
            a = appl.val();
            a = a.substr(0, a.length - 2);
            appl.val(a);
            ppp.hide();
            $('#create').show();
        }
        // console.log(main.height());
    });
    $('#create').click(function () {
        let vals = getVals();
        let recs = getRecs();
        $.ajax({
            url: '/izv/all/download/?name=notice',
            type: 'post',
            dataType: 'json',
            data: {recs, vals}
        }).then(function (data) {
            if (data['success'] == 1) {
                let b = $('#create');
                $(b[0]).attr('id', 'show');
                let a = $(b[0]).find('a');
                a.attr('href', data['link']);
                a.attr('target', '_blank');
                a.html('Посмотреть');

                b = $('#save');
                b.show();
            } else {
                alert(data['error']);
                // $('#errors').html('');
                // for (let key in data) {
                //     $('#errors').append('<span>' + data[key][0] + '</span>');
                // }
            }
        }).catch(function (error) {
            alert('Непредвиденная ошибка');
        });
    });
    $('#save').click('click', function () {
        let vals = getVals();
        let recs = getRecs();
        $.ajax({
            url: '/izv/all/save/?name=notice',
            type: 'post',
            dataType: 'json',
            data: {recs, vals}
        }).then(function (data) {
            if (data['success'] == 1) {
                window.location = data['url'];
            }
        }).catch(function (error) {
            
        })
    })
});
