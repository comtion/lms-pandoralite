 $( function() {
  $( "#eDate" ).datepicker({
  dateFormat: "dd/mm/yy"
});
} );

$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
           $('.image-preview').popover('show');
        },
         function () {
           $('.image-preview').popover('hide');
        }
    );
});

$(document).ready(function(){
  //when change language. it will get radio value and set it at inp_lang in form.
  $(".lang_tab").change(function(){
      $("#inp_lang").val($("input[name='tabs']:checked").val());
  });

  //When Start Date is changed. It will get time and check start date is equal or more than end date.
  //if it is, alert message and set sDate to ''. Otherwise, set time open.
  $("#sDate").change(function(){
     var date = $( "#sDate" ).datepicker().val();
     var end = $("#time_close").val();
     if (end <= date && end != ""){
       alert('ไม่สามารถตั้งวันเปิดแบบสอบถามเป็นวันนี้ได้');
       $("#sDate").val("");
     }
     else{
      $("#time_open").val(date);
    }
  });

  $("#eDate").change(function(){
     var date = $( "#eDate" ).datepicker().val();
     var start = $("#time_open").val();
     if (start >= date && start != ""){
       alert('ไม่สามารถตั้งวันปิดแบบสอบถามเป็นวันนี้ได้');
       $("#eDate").val("");
     } else {
      $("#time_close").val(date);
    }
  });


});
