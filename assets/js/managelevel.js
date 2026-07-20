var i_lead, lead, lead_name;

$(document).ready(function() {
      checkInitInput();
      
      $('[name="lead"]').blur(function() {
        checkLead($(this).val());
      });
  });

function checkInitInput() {
  i_lead = true;
  lead = $('[name="lead"]').val();
  lead_name = $('#lead-error').html();
  checkInput();
}

function checkInput() {
  if(i_lead) $('[name="done"]').prop('disabled', false);
  else $('[name="done"]').prop('disabled', true);
}

function checkLead(emp) {
  i_lead = false;
  if(emp === lead) {
    i_lead = true;
    $('#lead-form').removeClass('has-success');
    $('#lead-form').removeClass('has-error');
    $('#lead-error').removeClass('text-success');
    $('#lead-error').html(lead_name);
  } else if($('[name="lead"]').val() === '') {
    i_lead = true;
    $('#lead-form').removeClass('has-success');
    $('#lead-form').removeClass('has-error');
    $('#lead-error').empty();
  } else if(emp === $('[name="emp"]').val()) {
    $('#lead-form').removeClass('has-success');
    $('#lead-form').addClass('has-error');
    $('#lead-error').empty();
  } else {
    $.ajax({
      method: 'post',
      url: base_url + 'manage/checkLead',
      data: {'emp': emp},
      async: false,
      dataType: 'json',
      success: function(response) {
        if(response.text !== 'FALSE') {
          i_lead = true;
          $('#lead-form').removeClass('has-error');
          $('#lead-form').addClass('has-success');
          $('#lead-error').addClass('text-success');
          $('#lead-error').html(response.text);
        }else {
          $('#lead-form').removeClass('has-success');
          $('#lead-form').addClass('has-error');
          $('#lead-error').empty();
        }
      },
      error: function(){
        alert('Error: Cannot validate!');
      }
    });
  }
  checkInput();
}
