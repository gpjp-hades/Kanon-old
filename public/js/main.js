function checkIE () {
    let ua = window.navigator.userAgent
    return (
        ua.indexOf('MSIE ') > -1 ||
        ua.indexOf('Trident/') > -1 ||
        ua.indexOf('Edge/') > -1)
}
function send (type, value) {
    let form = $("#form")
    if (checkIE()) {
        $("#form_state").attr('value', type)
        if (value)
            $("#form_value").attr('value', value)
        form.submit()
    } else if (form.get(0).reportValidity()) {
        $("#form_state").attr('value', type)
        if (value)
            $("#form_value").attr('value', value)
        form.submit()
    }
    return false
}
$.fn.resizeBook = function() {
    $('#book').attr("size", $(window).width() < 768 ? "0" : "25")
    return this
}
$.fn.scrollPosReaload = function() {
    if (localStorage) {
        let posReader = localStorage["posStorage"]
        if (posReader) {
            $(this).scrollTop(posReader);
            localStorage.removeItem("posStorage")
        }
        $(this).click(function(e) {
            localStorage["posStorage"] = $(this).scrollTop()
        })
    }
    return this
}
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()
    $("#search").on("keyup", function() {
        let value = $(this).val().toLowerCase()
        $("#book option").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        })
    })
    $('#myModal').modal('show')
    $('#book').scrollPosReaload().resizeBook()
    $(window).resize(function() {
        $('#book').resizeBook()
    })
    $("#alert").fadeTo(3000, 500).slideUp(300, function() {
        $("#alert").slideUp(300);
    });
})