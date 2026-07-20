var arr = [];
$(document).ready(function(){
  $('input[name="check-student[]"]').change(function() {
    var arr_st = $('input[name="check-student[]"]:checked');
    arr = [];
    arr_st.each(function() {
      arr.push($(this).val());
    });
    $('input[name="check-st"]').val(arr);
  });

  $('input[name="check-all"]').change(function() {
    if($('input[name="check-all"]').is(':checked')){
      $('input[name="check-student[]"]').prop('checked', true);
    } else {
      $('input[name="check-student[]"]').prop('checked', false);
    }
  });

  var flag = true;
  var lang = 'thailand';
  $('input[name="uCode"]').focusin(function() {
    flag = true;
    lang = $("input[name='tabs']:checked").val();
  });
  $('input[name="uCode"]').focusout(function() {
    if (flag) {
      var ucode = $('input[name="uCode"].'+lang).val();
      //AJAX Query PART
      var path = base_url+'/course/checkUcode';
      var formData = new FormData();
      formData.append('ucode',  ucode);
      $.ajax( path, {
        type: 'POST',
        data: formData ,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function ( arresult ) {
          var result = arresult.rs;
          alert(result);
        }
      });
      //AJAX Query PART
      flag = false;
    }
  });

  $('a.check').click(function() {
    var r=confirm("Do you want to delete this?");//Bug
    if (r){
      //AJAX Query PART
      var path = base_url+'/course/removeStudent';
      var formData = new FormData();
      formData.append('emp_c',  this.id);
      formData.append('ccode',  $('[name="ccode"]').val());
      $.ajax( path, {
        type: 'POST',
        data: formData ,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function ( arresult ) {
          var result = arresult.rs;
          alert(result);
          if (result){
            location.reload();
          }
        }
      });
      //AJAX Query PART*/
    }
  });
});
