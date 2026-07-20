window.onclick = function(event) {
  if (event.target == document.getElementById('del-ext')) {
    document.getElementById('del-ext').style.display = "none";
  }
};
$(document).ready(function() {
  $('.collapse').on('show.bs.collapse', function() {
    var id = $(this).attr('id');
    $('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
    $('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-minus"></i>');
  });
  $('.collapse').on('hide.bs.collapse', function() {
    var id = $(this).attr('id');
    $('a[href="#' + id + '"]').closest('.panel-heading').removeClass('active-faq');
    $('a[href="#' + id + '"] .panel-title span').html('<i class="glyphicon glyphicon-plus"></i>');
  });

  $('.del-st').click(function functionName() {
    $('#del-ext').show();
    $('#del-ext').addClass($(this).attr('id'));
  });

  $('.sub-ext').click(function functionName() {
    var emp_c = $('#del-ext').attr('class');
    var ccode = $('[name="ccode"]').val();
    var type = $('[name="type"]').val();
    var note = $('[name="note"]').val();
    var r = confirm("Are you sure to delete this user ?");
    if (r) {
      var formData = new FormData();
      formData.append('emp',  emp_c);
      formData.append('ccode', ccode);
      formData.append('note', note);
      formData.append('type', type);
      $.ajax( base_url+'/course/removeStudent', {
        type: 'POST',
        data: formData ,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function ( arresult ) {
          if(arresult.rs){
            alert('Succesful delete.');
          }
        }
      });
    }
    window.location = base_url+'/course/detail/'+ccode;
  });

  $('.add-st').click(function functionName() {
    var emp_c = $(this).attr('id');
    var ccode = $('[name="ccode"]').val();

    var formData = new FormData();
    formData.append('emp',  emp_c);
    formData.append('ccode', ccode);
    $.ajax( base_url+'/course/addStudent', {
      type: 'POST',
      data: formData ,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function ( arresult ) {
        if(typeof(arresult.rs) == typeof(true)){
          window.location = base_url+'/course/detail/'+ccode;
        } else {
          alert(arresult.rs);
        }
      }
    });

  });

  /*========= Preview Badges ==================*/
  $('.preview-badges').click( function(){
    var img = $(this).find('img').attr('src');
    $('.show-badges img').attr('src', img );
    $('.show-badges-overlay').fadeIn();
  });
  $('.show-badges-overlay').click( function(){
    $(this).fadeOut();
  });
});
