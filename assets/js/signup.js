$(function () {
    $('input, select').on('focus', function () {
        $(this).parent().find('.input-group-text').css('border-color', '#80bdff');
    });
    $('input, select').on('blur', function () {
        $(this).parent().find('.input-group-text').css('border-color', '#ced4da');
    });
});


// datepicker
$(function () {
    var bindDatePicker = function() {
        var date = new Date();
        date.setDate(date.getDate());
        $("#birthday").datepicker({
            autoclose:true,
            todayHighlight:true,
            format:'dd/mm/yyyy',
            endDate: date
        })
    }
    
    bindDatePicker();
});