 function addVideo(lang) {
  var type = $("[name='vid_type']."+lang).val();
  if (type != 0) {
      if (type == 'vid_url'){
        var video = '<div class="vid_inp vid_url"><label class="col-sm-3 control-label" for="inputSuccess">URL </label><div class="col-sm-8 form-group"><div class="input-group"><input type="text" class="form-control" multiple placeholder="Please input your url" name="vid_url[]"><div class="input-group-addon calendar" onclick="removeUrl(\'create\')"><i class="fa fa-minus" aria-hidden="true"></i></div></div></div></div>';
      } else {
        var video = '<div class="vid_inp vid_file"><label class="col-sm-3 control-label" for="inputSuccess">Upload your video</label><div class="col-sm-8 form-group"><div class="input-group"><input type="file" name="vid_file[]" accept=".mp4"><div class="input-group-addon calendar" onclick="removeVidFile()"><i class="fa fa-minus" aria-hidden="true"></i></div></div></div></div>';
      }
      $('.all_vid').append(video);
    }
}

function addFile(lang) {
  var file = '<div class="files"><div class="col-sm-8 form-group"><div class="input-group"><input type="file" name="files[]"><div class="input-group-addon calendar" onclick="removeFile()"><i class="fa fa-minus" aria-hidden="true"></i></div></div></div></div>';
  $('.all_file').append(file);
}

function removeUrl(elm) {
  if (elm == 'create'){
    elm = $('.vid_url');
  }
  $(elm).remove();
}
function removeVidFile() {
  $(".vid_file").remove();
}

function removeFile() {
  $(".files").remove();
}
