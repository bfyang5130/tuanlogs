function showDetaildiv(obj) {
    if ($("#tr" + obj).length > 0) {
        $(".newaddtr").remove();
        return false;
    }
    var showHtml = $("#" + obj);
    $(".newaddtr").remove();
    showHtml.parent().parent().after('<tr class="newaddtr" id="tr' + obj + '"><td colspan="7" style="word-break:break-all;padding:25px 10px;"><div class="well">' + showHtml.val() + '</div></td></tr>');
    return false;
}

