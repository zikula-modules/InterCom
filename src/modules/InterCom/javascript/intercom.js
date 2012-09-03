function CheckAll() {
    $$('.msg_check input[type="checkbox"]').each(function(el) { el.checked = $('allbox').checked; });
}

function CheckCheckAll() {
    $('allbox').checked = ($$('.msg_check input:checked').length == $$('.msg_check input[type="checkbox"]').length -1) & !$('allbox').checked;
}
