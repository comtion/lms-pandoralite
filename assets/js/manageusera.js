 var i_emp, i_user, i_pass, i_cpass;
console.log(1);
$(document).ready(function() {
      $('[name="emp"]').blur(function() {
        checkEmp($(this).val());
      });
      $('[name="user"]').blur(function() {
        checkUser($(this).val());
      });
      $('[name="pass"]').blur(function() {
        checkPass($(this).val());
        checkCPass($(this).val(), $('[name="cpass"]').val());
      });
      $('[name="cpass"]').blur(function() {
        checkCPass($(this).val(), $('[name="pass"]').val());
      });
  });

function checkInput() {
  /*if(i_emp && i_user && i_pass && i_cpass) $('[name="done"]').prop('disabled', false);
  else $('[name="done"]').prop('disabled', true);
  if(i_emp && i_user ) $('[name="done"]').prop('disabled', false);*/
  if( i_user ) $('[name="done"]').prop('disabled', false);
  else $('[name="done"]').prop('disabled', true);
}

function checkEmp(emp) {
  i_emp = false;
  $.ajax({
    method: 'post',
    url: base_url + 'manage/checkEmpC',
    data: {'emp': emp},
    async: false,
    dataType: 'json',
    success: function(response) {
      if($('[name="emp"]').val() === '') {
        $('#emp-form').removeClass('has-success');
        $('#emp-form').addClass('has-error');
        $('#emp-error').empty();
      } else if(response.text !== 'FALSE') {
        i_emp = true;
        $('#emp-form').removeClass('has-error');
        $('#emp-form').addClass('has-success');
        $('#emp-error').removeClass('text-danger');
        $('#emp-error').addClass('text-success');
        $('#emp-error').html(response.text);
      }else {
        $('#emp-form').removeClass('has-success');
        $('#emp-form').addClass('has-error');
        $('#emp-error').removeClass('text-success');
        $('#emp-error').addClass('text-danger');
        $('#emp-error').html(m_e_error);
      }
    },
    error: function(){
      alert('Error: Cannot validate!');
    }
  });
  checkInput();
}

function checkUser(user) {
  i_user = false;
  if(user.length < 4) {
    $('#user-form').removeClass('has-success');
    $('#user-form').addClass('has-error');
    $('#user-error').removeClass('text-success');
    $('#user-error').addClass('text-danger');
    $('#user-error').html(m_ue_c);
  } else {
    $.ajax({
      method: 'post',
      url: base_url + 'manage/checkUser',
      data: {'user': user},
      async: false,
      dataType: 'json',
      success: function(response) {
        if($('[name="user"]').val() === '') {
          $('#user-form').removeClass('has-success');
          $('#user-form').addClass('has-error');
          $('#user-error').empty();
        } else if(response.text === 'TRUE') {
          i_user = true;
          $('#user-form').removeClass('has-error');
          $('#user-form').addClass('has-success');
          $('#user-error').removeClass('text-danger');
          $('#user-error').addClass('text-success');
          $('#user-error').html(m_u_success);
        }else {
          $('#user-form').removeClass('has-success');
          $('#user-form').addClass('has-error');
          $('#user-error').removeClass('text-success');
          $('#user-error').addClass('text-danger');
          $('#user-error').html(m_u_error);
        }
      },
      error: function(){
        alert('Error: Cannot validate!');
      }
    });
  }
  checkInput();
}

function checkNumeric(pass) {
  var i;
  for (i=0; i<pass.length; i++) {
      if(!isNaN(pass[i])) return false;
  }
  return true;
}

function checkPass(pass) {
  i_pass = false;
  if(!/^[a-zA-Z0-9]*$/.test(pass)) {
    $('#pass-form').removeClass('has-success');
    $('#pass-form').addClass('has-error');
    $('#pass-error').html(m_pe_s);
  } else if(pass.length < 8) {
    $('#pass-form').removeClass('has-success');
    $('#pass-form').addClass('has-error');
    $('#pass-error').html(m_pe_c);
  } else if(pass === pass.toLowerCase()) {
    $('#pass-form').removeClass('has-success');
    $('#pass-form').addClass('has-error');
    $('#pass-error').html(m_pe_u);
  } else if(pass === pass.toUpperCase()) {
    $('#pass-form').removeClass('has-success');
    $('#pass-form').addClass('has-error');
    $('#pass-error').html(m_pe_l);
  } else if(!/[0-9]/.test(pass)) {
    $('#pass-form').removeClass('has-success');
    $('#pass-form').addClass('has-error');
    $('#pass-error').html(m_pe_n);
  } else {
    i_pass = true;
    $('#pass-form').removeClass('has-error');
    $('#pass-form').addClass('has-success');
    $('#pass-error').empty();
  }
  checkInput();
}

function checkCPass(pass1, pass2) {
  i_cpass = false;
  if(pass1 === '' && pass2 === '') {
    $('#cpass-form').removeClass('has-success');
    $('#cpass-form').removeClass('has-error');
    $('#cpass-error').empty();
  } else if(pass1 === pass2) {
    i_cpass = true;
    $('#cpass-form').removeClass('has-error');
    $('#cpass-form').addClass('has-success');
    $('#cpass-error').empty();
  } else {
    $('#cpass-form').removeClass('has-success');
    $('#cpass-form').addClass('has-error');
    $('#cpass-error').html(m_cpe);
  }
  checkInput();
}
