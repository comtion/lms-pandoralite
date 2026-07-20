$('document').ready( function(){
  $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#superwrapper").toggleClass("removeBar");
  });

  var regex = /^[a-zA-Z0-9\x08\@\-\_\.\,]$/;
  $('input[name="email"]').keypress(function(event) {
    var _event = event || window.event;
    var key = _event.keyCode || _event.which;
    key = String.fromCharCode(key);
    if(!regex.test(key)) {
      _event.returnValue = false;
      if (_event.preventDefault)
        _event.preventDefault();
    }
  });
  var regexphone = /^[0-9\x08]$/;
  $('input[name="mobile"]').keypress(function(event) {
    var _event = event || window.event;
    var key = _event.keyCode || _event.which;
    key = String.fromCharCode(key);
    if(!regexphone.test(key)) {
      _event.returnValue = false;
      if (_event.preventDefault)
        _event.preventDefault();
    }
  });

  $('.send-contact').click( function(){
    var check = true;
    $name = $('input[name="name"]');
    $email = $('input[name="email"]');
    $mobile = $('input[name="mobile"]');
    $mess = $('textarea[name="mess"]');

    $name.css({border:'1px solid #ccc'});
    $email.css({border:'1px solid #ccc'});
    $mobile.css({border:'1px solid #ccc'});
    $mess.css({border:'1px solid #ccc'});

    if( $name.val() == ""){
      $name.css({border:'1px solid red'});
      check = false;
    }
    if( $email.val() == ""){
      $email.css({border:'1px solid red'});
      check = false;
    }else{
      if( !validEmail( $email.val() ) ){
        $email.css({border:'1px solid red'});
        check = false;
      }
    }
    if( $mobile.val() == ""){
      $mobile.css({border:'1px solid red'});
      check = false;
    }else{
      if( $mobile.val().length < 9 ){
        $mobile.css({border:'1px solid red'});
        check = false;
      }
    }
    if( $mess.val() == ""){
      $mess.css({border:'1px solid red'});
      check = false;
    }
    return check;
  });

});

function validEmail( value ) {
   var r = new RegExp("[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[\.?]+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?");
   return (value.match(r) == null) ? false : true;
}
