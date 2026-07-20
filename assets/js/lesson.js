
function makeFullScreen() {
  $('.full-Screen').hide();
  $('.scorm-player').addClass('fullScreen');
  $('.frame-player').addClass('fullScreen');
  $('.normal-Screen').show();
}

function makeNormalScreen() {
  $('.normal-Screen').hide();
  $('.fullScreen').removeClass('fullScreen');
  $('.full-Screen').show();
}

$(document).ready(function(){
  $('input[name="pass"]').click(function functionName() {
    $('.tricker').hide();
    var id = $('input[name="lcode"]').val();
    var type = $('input[name="type"]').val();
    if (type != "upload"){
      var url = document.getElementById('player').src+'?autoplay=1';
      $("#player").prop('src', url);
    } else {
      var vid = document.getElementById("upload-vid");
      vid.autoplay = true;
      vid.load();
    }
    var formData = new FormData();

    $.ajax( base_url+'/lesson/updateTrans/'+id, {
      type: 'POST',
      data: formData ,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function ( arresult ) {
        if(arresult.rs){
          console.log('Update complete.');
        }else{
          console.log('Can not update.');
        }
      }
    });
  });
});
