/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//$(document).off('.datepicker.data-api');
var curl = "cafeteria_authorization_form";
$(document).ready(function () {
    $('#dataTable').DataTable();
    // $('.datepicker').datepicker();
    //$('#datepicker').datepicker();    
     

    $("#barcodeID").focus();
    $("#bcode").focus();
    $("#bcode").focus().attr('autocomplete', 'off').keyup(function (event) {
        // if the timer is set, clear it
        if (barcode_watch_timer !== false)
            clearTimeout(barcode_watch_timer);
        // set the timer to wait 500ms for more input
        barcode_watch_timer = setTimeout(function () {
            process_barcode_input();
        }, 50);
        // optionally show a status message
        // $("#status").html('waiting for more input...').show();
        // return false so the form doesn't submit if the char is equal to "enter"
        // $("#bcode").val('');
        return false;
    });

    $("#role_options").change(function () {
        var input = $("<input>").attr("type", "hidden").attr("name", "user_group_sent").val("Yes");
         $("#role_form").append($(input));
        $("#role_form").submit();
       
    });



    //for filter report
    $("#submitduration").click(function () {
        get_filter_report()
    })
    $("#selectcafe_filter").change(function () {
        get_filter_report();
    })
    function get_filter_report() {
        var from = $("#date-start").val();
        var to = $("#date-end").val();
        var cafenum = $("#selectcafe_filter").val();

        if (from != '' && to != '')
        {
            var _from = new Date(from).getTime();
            var _to = new Date(to).getTime();

            if (_from < _to) {
                $.post("filter_from_to_report", {From: from, To: to, cafenumber: cafenum}, function (data) {
                    $("#displayfilterdreport").html(data)
                    $("#resultprint").show();
                })

            } else {
                alert("Enter start date and end date( date range)");
            }

            return false;
        }
    }

    //daily report
    $("#selectdated").change(function () {
        var message = 0;
        var year = $("#selectyeard").val();
        var month = $("#selectmonthd").val();
        var dates = $("#selectdated").val();
        var cafenum = $("#selectcafe2").val();
        if (year == '' || month == '' || dates == '') {
            message++;
        }

        if (message == 0) {
            $.post("get_one_day_report", {year1: year, month1: month, dates1: dates, cafenumber: cafenum}, function (data) {
                $("#displayDayreport").html(data);
                $("#resultprint").show();
            })
        }
    });

    //monthly report
    $("#selectmonthm").change(function () {
        get_monthly_report()
    });
    $("#selectyearm").change(function () {
        get_monthly_report()
    });

    $("#selectcafe2_m").change(function () {
        get_monthly_report()
    });
    function get_monthly_report() {
        var message = 0;
        var year = $("#selectyearm").val();
        var month = $("#selectmonthm").val();
        var cafenum = $("#selectcafe2_m").val();
        if (year == '' || month == '') {
            message = message + 1;
        }
        if (message == 0) {
            $.post("get_monthly_report", {year: year, month: month, cafenumber: cafenum}, function (data) {
                $("#displaymonthreport").html(data);
                $("#resultprint").show();

            }
            );
        }
    }


    //yearly report
    $("#selectyeary").change(function () {
        get_yearly_report();
    });

    $("#selectcafe2_y").change(function () {
        get_yearly_report()

    });

    function get_yearly_report() {
        var message = 0;
        var year = $("#selectyeary").val();
        var cafenum = $("#selectcafe2_y").val();

        if (year == '') {
            message++;
        }
        if (message == 0)
        {
            $.post("get_yearly_report", {yearonly: year, cafenumber: cafenum}, function (data) {
                $("#displayyearreport").html(data)
                $("#resultprint").show();
            });
        }
    }

    $("#bcID_library").keyup(function () {
      //  var gateType = $("#rtype").val();
        var bcID = $("#bcID_library").val();
        if (bcID===undefined) {
            alert();
            //alert("pls eneter the code");
            return false;
        } else if (bcID.length >= 13) {
            $("#displayinfo").html("");
            //{gateType: gateType, bcID: bcID, send: '1'}
            console.log(bcID);
            
            $.post("authorize_student_on_library_gate", {barcode_liberary: bcID},
                    function (data) {
                        console.log(data);
                        var message = JSON.parse(data)
                        console.log(message);
                        var bgclass = "";
                        if (message.status == 0) {
                            bgclass = "bg-gradient-danger text-gray-100";
                          //  startAlarm();
                        } else {
                            bgclass = "bg-gradient-success text-gray-100";
                        }
                        var str = "";
                        str = str + "<table class='table' style='font-size:20px;'> \n\
                    <tr>  <td> Full Name </td> <td>" + message.full_name + "  </td> <td rowspan='5'><img class='img img-profile' src='" + message.photopath + "' alt='image' width='280' height='300' />   </td> </tr> \n\
                    <tr>  <td> ID </td> <td> " + message.ID + " </td> </tr> \n\
                    <tr>  <td> Sex </td> <td> " + message.sex + " </td> </tr> \n\
                    <tr>  <td> Department </td> <td> " + message.department + " </td> </tr>\n\
                    <tr>  <td>Year </td> <td> " + message.year + " </td> </tr> \n\
                    <tr class='" + bgclass + "'>  <td>Status </td> <td colspan=2> " + message.message + "</td> </tr> \n\
                     </table>"
                        $("#displayinfo").html(str);
                        $("#barcodeID").val("");
                    });
            return false;
        }


    });

    $("#bcID_campus_gate").keyup(function () {

      //  var gateType = $("#rtype").val();
        var bcID = $("#bcID_campus_gate").val();
        if (bcID == undefined) {
            //alert("pls eneter the code");
            return false;
        } else if (bcID.length >= 13) {
            $("#displayinfo").html("");
            //{gateType: gateType, bcID: bcID, send: '1'}
            console.log(bcID);
            
            $.post("authorize_on_campus_gate", {bcID_campus_gate: bcID},
                    function (data) {
                        console.log(data);
                        var message = JSON.parse(data)
                        console.log(message);
                        var bgclass = "";
                        if (message.status == 0) {
                            bgclass = "bg-gradient-danger text-gray-100";
                            startAlarm();
                        } else {
                            bgclass = "bg-gradient-success text-gray-100";
                        }
                        var str = "";
                        str = str + "<table class='table' style='font-size:20px;'> \n\
                    <tr>  <td> Full Name </td> <td>" + message.full_name + "  </td> <td rowspan='5'><img class='img img-profile' src='" + message.photopath + "' alt='image' width='280' height='300' />   </td> </tr> \n\
                    <tr>  <td> ID </td> <td> " + message.ID + " </td> </tr> \n\
                    <tr>  <td> Sex </td> <td> " + message.sex + " </td> </tr> \n\
                    <tr>  <td> Department </td> <td> " + message.department + " </td> </tr>\n\
                    <tr>  <td>Year </td> <td> " + message.year + " </td> </tr> \n\
                    <tr class='" + bgclass + "'>  <td>Status </td> <td colspan=2> " + message.message + "</td> </tr> \n\
                     </table>"
                        $("#displayinfo").html(str);
                        $("#barcodeID").val("");
                    });
            return false;
        }


    });

    $("#studid").blur(function () {


        var id = $("#studid").val();
        if (id == '') {
            //   alert("pls eneter the code");
            return false;
        } else if (id.length > 4) {
            $.post("allow_service_by_exceptional_case", {ID: id, send: '1', get_student_by_id: 'get_student_by_id'},
                    function (data) {
                        console.log(data);
                        var _data = JSON.parse(data)
                        if (_data.status == 1) {
                            $("#studentname").val(_data.FullName);
                            $("#iderror").html("");
                        } else {
                            console.log(_data.message);
                            $("#iderror").html(_data.message);
                        }


                    });
        }


    });


$("#uploadstudentbutton").click(function(){
  // $("#uploadstudentbutton").attr("disabled","disabled") ;
  // $("#uploadprogressbar").css({"visibility":"visible"});
   return true;
    
});

});



var barcode_watch_timer = false;
function process_barcode_input() {
    // if the timer is set, clear it
    if (barcode_watch_timer !== false)
        clearTimeout(barcode_watch_timer);
    // grab the value, lock and empty the field
    var b = $("#bcode").val();
    $("#bcode").val('');
    // empty the status message
    $("#status").empty();
    // add a loading message

    if (b != '')
    {
        console.log(b);
        $.ajax({
            type: "Get",
            url: curl,
            data: "barcode="+b,
            success: function (data) {
                console.log(data);
                var message = JSON.parse(data);                             
                var bgclass = "";
                if (message.status == 0) {
                    bgclass = "bg-gradient-danger text-gray-100";
                    $("#barcodeentry").addClass(bgclass);
                    $("#barcodeentry").addClass("bg-gradient-success text-gray-100");
                    startAlarm();
                } else {
                    $("#barcodeentry").removeClass("bg-gradient-danger text-gray-100");
                    bgclass = "bg-gradient-success text-gray-100";
                     $("#barcodeentry").addClass(bgclass);
                }
                var str = "";
                str = str + "<table class='table' style='font-size:20px;color:blue'> \n\
                    <tr>  <td> Full Name </td> <td>" + message.full_name + "  </td> <td rowspan='5'><img class='img img-profile' src='" + message.photopath + "' alt='image' width='280' height='300' />   </td> </tr> \n\
                    <tr>  <td> ID </td> <td> " + message.ID + " </td> </tr> \n\
                    <tr>  <td> Sex </td> <td> " + message.sex + " </td> </tr> \n\
                    <tr>  <td> Department </td> <td> " + message.department + " </td> </tr>\n\
                    <tr>  <td>Year </td> <td> " + message.year + " </td> </tr> \n\
                    <tr class='" + bgclass + "'>  <td>Status </td> <td colspan=2> " + message.message + "   " + message.special_case + "</td>   </tr> \n\
                     </table>"
                $("#status").html(str);


            }
        });
    }
}
function PrintDivData(crtlid)
{
    var ctrlcontent = document.getElementById(crtlid);
    var printscreen = window.open('', '', 'left=1,top=1,width=1,height=1,toolbar=0,scrollbars=0,status=0?');
    printscreen.document.write(ctrlcontent.innerHTML);
    printscreen.document.close();
    printscreen.focus();
    printscreen.print();
    printscreen.close();
}
function confirmDelete() {
    var response = confirm("Are you sure to delete this record?")
    if (response) {
        return true;
    }
    return false;
}
function assure_action(){
    var response = confirm("If you restart service,  all student will be inactive. so you have to inport active students from excel sheet.   Most of the time this activity will be done at the end of each semester. Are you sure  to restart service?")
    if (response) {
        return true;
    }
    return false;  
}
function startAlarm() {
    var x = document.getElementById("alarm");
    x.play();
}
function createStudentaccountConfirm(){
    var response = confirm("Are you sure to create user accounts for all students who have no account sofar?")
    if (response) {
        return true;
    }
    return false; 
    
}

