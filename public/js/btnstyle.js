/**
 * Created by Simanov on 27.11.2017.
 */
$(function () {
    $("a:contains('PDF'), a:contains('Добавить'), a:contains('Скачать архив'), a:contains('Посмотреть')").addClass('btn-sm btn-success').css('border-radius','4px')
        .css('text-decoration','none');
    $("a:contains('Редактировать')").addClass('btn-sm btn-warning')
        .css('text-decoration','none').css('color', 'black').css('border-radius','4px');
    $("a:contains('Удалить')").addClass('btn-sm btn-danger')
        .css('text-decoration','none').css('border-radius','4px');
});
$(function () {
    $("td:contains('Подготовлен')").html('<svg width="10" height="10"><circle cx="5" cy="5" r="5" fill="yellow" /></svg>');
    $("td:contains('Действующий')").html('<svg width="10" height="10"><circle cx="5" cy="5" r="5" fill="green" /></svg>');
    $("td:contains('Архивный')").html('<svg width="10" height="10"><circle cx="5" cy="5" r="5" fill="red" /></svg>');
});