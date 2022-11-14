/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    var startDate = new Date(2019, 1, 20);
    var endDate = new Date(2019, 1, 19);
    
  //   $("#lastpermissionDate").calendarsPicker({calendar: $.calendars.instance('ethiopian')});
    
    
    $('#date-start')
            .datepicker()
            .on('changeDate', function (ev) {
                if (ev.date.valueOf() > endDate.valueOf()) {
                    $('#alert').show().find('strong').text('The start date must be before the end date.');
                    $("#submitduration").prop("disabled", true);
                } else {
                    $('#alert').hide();
                     $("#submitduration").prop("disabled", false);
                    startDate = new Date(ev.date);
                    $('#date-start-display').text($('#date-start').data('date'));
                }
                $('#date-start').datepicker('hide');
            });
    $('#date-end')
            .datepicker()
            .on('changeDate', function (ev) {
                endDate = new Date($('#date-end').val());
                startDate = new Date($('#date-start').val());

                if (endDate.getTime() < startDate.getTime()) {
                    // alert();
                     $("#submitduration").prop("disabled", true);
                    $('#alert').show().find('strong').text('The end date must be after the start date.');
                } else {
                    //  alert("enddate is larger");
                    $('#alert').hide();
                     $("#submitduration").prop("disabled", false);
                    endDate = new Date(ev.date);
                    //   $('#date-end-display').text($('#date-end').data('date'));
                }
                $('#date-end').datepicker('hide');
            });

    $('#depd').datepicker().on('changeDate', function (ev) {
        $('#depd').datepicker('hide');
    })
    $('#tbd').datepicker().on('changeDate', function (ev) {
        $('#tbd').datepicker('hide');
    })
    $("#lastpermissionDate").datepicker()
            .on('changeDate', function (ev) {
                endDate = new Date($('#lastpermissionDate').val());
                startDate = new Date();

                if (endDate.getTime() < startDate.getTime()) {
                    // alert();
                    // $('#alert').show().find('strong').text('The end date must be after the start date.');
                    alert("you can not set expired date");

                } else {
                    //  alert("enddate is larger");

                  endDate = new Date(ev.date);
                    
                  //  $("#t1").val(endDate);
                    //   $('#date-end-display').text($('#date-end').data('date'));
                }
                $("#lastpermissionDate").datepicker('hide');
            });

})
