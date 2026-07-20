$(function() {
  var pos_mon = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  var n_days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
  var smon = $("[name='smonth']").val();
  var year = $("[name='syear']").val();
  var emon = $("[name='emonth']").val();
  var year = $("[name='eyear']").val();
  $(".sday-off").hide();
  $(".eday-off").hide();
  if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) {
    n_days[2] = 29;
  }
  var sindex = pos_mon.indexOf(smon);
  var eindex = pos_mon.indexOf(emon);
  for (var i = 28; i <= n_days[sindex]; i++) {
    $(".sday-off[value='"+i+"']").show();
  }
  for (var i = 28; i <= n_days[eindex]; i++) {
    $(".eday-off[value='"+i+"']").show();
  }
});
$(document).ready(function(){
  $("[name='smonth']").change(function() {
    var pos_mon = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    var n_days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var mon = $("[name='smonth']").val();
    var year = $("[name='syear']").val();
    $(".sday-off").hide();
    if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) {
      n_days[2] = 29;
    }
    var index = pos_mon.indexOf(mon);
    for (var i = 28; i <= n_days[index]; i++) {
      $(".sday-off[value='"+i+"']").show();
    }
  });

  $("[name='emonth']").change(function() {
    var pos_mon = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    var n_days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    var mon = $("[name='emonth']").val();
    var year = $("[name='eyear']").val();
    $(".eday-off").hide();
    if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) {
      n_days[2] = 29;
    }
    var index = pos_mon.indexOf(mon);
    for (var i = 28; i <= n_days[index]; i++) {
      $(".eday-off[value='"+i+"']").show();
    }
  });
});
