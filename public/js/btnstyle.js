/**
 * Created by Simanov on 27.11.2017.
 */
$(function () {
    $("a:contains('Посмотреть'), a:contains('Добавить'), a:contains('Скачать архив')").addClass('btn-sm btn-success').css('border-radius','4px')
        .css('text-decoration','none');
    $("a:contains('Редактировать')").addClass('btn-sm btn-warning')
        .css('text-decoration','none').css('color', 'black').css('border-radius','4px');
    $("a:contains('Удалить')").addClass('btn-sm btn-danger')
        .css('text-decoration','none').css('border-radius','4px');
});