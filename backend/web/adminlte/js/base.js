function showDetaildiv(obj) {
    var showHtml = $("#" + obj);
    $(".newaddtr").remove();
    showHtml.parent().parent().after('<tr class="newaddtr"><td colspan="7" style="word-break:break-all;padding:25px 10px;">' + showHtml.val() + '</td></tr>');
    return false;
}

