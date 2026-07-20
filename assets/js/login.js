$(document).ready(function(){
  $('.submit-reset').click( function(){
    //$('#emp-form').addClass('has-success');
    $('#emp-error').removeClass('text-danger');
    //$('#emp-error').addClass('text-success');
    var user = $('input[name="useri"]').val();
    if( user != "" ){
      $.ajax({
        method: 'post',
        url: base_url + '/manage/checkUser',
        data: {'user': user},
        async: false,
        dataType: 'json',
        success: function(response) {
          if(response.text === 'TRUE') {
            i_user = true;
            $('#emp-error').addClass('text-danger');
            $('#emp-error').html('สิทธิ์ของคุณไม่สามารถตั้งค่ารหัสผ่านของผู้ใช้นี้ได้');
          }else {
            $.ajax({
              method: 'post',
              url: base_url + '/dashboard/resetPassSubmit',
              data: {'useri': user},
              async: false,
              dataType: 'json',
              success: function(response) {
                if(response.rs) {
                  $('#emp-error').addClass('text-success');
                  $('#emp-error').html('รหัสผ่านถูกตั้งค่าใหม่เรียบร้อย');
                }else {
                  $('#emp-error').addClass('text-danger');
                  $('#emp-error').html(response.msg);
                }
              },
              error: function(){
                //alert('Error: Cannot validate! 1');
              }
            });
          }
        },
        error: function(){
          alert('Error: Cannot validate! 2');
        }
      });
    }else{
      $('#emp-error').addClass('text-danger');
      $('#emp-error').html('กรุณากรอกรหัสผู้ใช้');
    }
    //return false;
    //$('#emp-error').html(response.text);
  });
  //console.log( base_url );
  $('.pass-confirm').click( function(){
    if( validPass() ){
      $('.loading').fadeIn();
      $.ajax( base_url+'dashboard/updatePass', {
        type: 'POST',
        dataType: 'json',
        data:  { newpass : $('input[name="newpass"]').val() } ,
        success: function(result){
          console.log(result);
          if(result.rs){
            alert("เปลี่ยนรหัสผ่านเสร็จสิ้น \nกรุณาล็อกอินอีกครั้ง");
            window.location = base_url+'/dashboard/login';
            //setTimeout(function(){ window.location = base_url+'dashboard/login' }, 2000);
            $('.loading').fadeOut();
          }else{
            $('.loading').fadeOut();
            alert( result.msg );
            //popin_msg('section-alert',"กรุณาแชร์เพื่อรับส่วนลด");
          }
        }
      }); /// end ajax add customer
    }else{
      //
    }
  });

  $('.forgot-pass').click( function(){
    var emp_c = $('input[name="inpUname"]').val();
    console.log( 'User name : '+emp_c );
    if( emp_c == "" ){
      alert("กรุณาใส่รหัสผู้ใช้งาน");
    }else{
      $('.loading').fadeIn();
      $.ajax( base_url+'/dashboard/forgotpass', {
        type: 'POST',
        dataType: 'json',
        data:  { emp_c : $('input[name="inpUname"]').val() } ,
        success: function(result){
          if(result.rs){
            alert("รหัสผ่านถูกส่งไปยังอีเมลของคุณเรียบร้อยแล้ว");
            $('.loading').fadeOut();
          }else{
            $('.loading').fadeOut();
            alert( result.msg );
          }
        }
      }); /// end ajax add customer
    }
  });

});
function validPass(){
  var check = true;
  $newpass = $('input[name="newpass"]');
  $confirmpass = $('input[name="confirmpass"]');
  $newpass.css({border:'2px solid #ccc'});
  $confirmpass.css({border:'2px solid #ccc'});

  if( $newpass.val() == "" ){
    $newpass.css({border:'1px solid red'});
    check = false;
  }else if( $newpass.val().length < 8 ){
    $newpass.css({border:'1px solid red'});
    check = false;
  }else if( !checkCase( $newpass.val() ) ){
    $newpass.css({border:'1px solid red'});
    check = false;
  }

  if( $confirmpass.val() == "" ){
    $confirmpass.css({border:'1px solid red'});
    check = false;
  }else if( $confirmpass.val() !== $newpass.val() ){
    $confirmpass.css({border:'1px solid red'});
    check = false;
  }
  return check;
}
function checkCase( str ){
  var upperCase= new RegExp('[A-Z]');
  //var lowerCase= new RegExp('[a-z]');
  var numbers = new RegExp('[0-9]');

  if( str.match(upperCase) &&  str.match(numbers) )
  {
    return true;
      //$("#passwordErrorMsg").html("OK")
  }
  else
  {
      return false;
      //$("#passwordErrorMsg").html("Your password must be between 6 and 20 characters.     It must contain a mixture of upper and lower case letters, and at least one number or symbol.");
  }
}
