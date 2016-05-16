$(function () {
    $('#sandbox-container input').datepicker({
        format: "dd/mm/yyyy",
        startDate: "0d",
        endDate: "+1m",
        language: "fr",
        todayBtn: "linked",
        daysOfWeekHighlighted: "0,6",
        todayHighlight: true
    });
});