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

$(function() {
  // Create the close button
  var closebtn = $('<button/>', {
    type:"button",
    text: 'x',
    id: 'close-preview',
    style: 'font-size: initial;',
  });
  closebtn.attr("class","close pull-right");
  // Set the popover default content
  $('.image-preview').popover({
    trigger:'manual',
    html:true,
    title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
    content: "There's no image",
    placement:'bottom'
  });
  // Clear event
  $('.image-preview-clear').click(function(){
    $('.image-preview').attr("data-content","").popover('hide');
    $('.image-preview-filename').val("");
    $('.image-preview-clear').hide();
    $('.image-preview-input input:file').val("");
    $(".image-preview-input-title").text("");
  });
  // Create the preview image
  $(".image-preview-input input:file").change(function (){
    var img = $('<img/>', {
      id: 'dynamic',
      width:250,
      height:200
    });
    var file = this.files[0];
    var reader = new FileReader();
    // Set preview image into the popover data-content
    reader.onload = function (e) {
      $(".image-preview-input-title").text("");
      $(".image-preview-clear").show();
      $(".image-preview-filename").val(file.name);
      img.attr('src', e.target.result);
      $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
      $(".prev-edit").hide();
    }
    reader.readAsDataURL(file);
  });
});

$(document).ready(function(){
  //change to normal create form
  $(".normal_form").click(function(){
    $(".inp_type").val('normal');
  });

  //change to SCORM create form
  $(".scorm_form").click(function(){
    $(".inp_type").val('scorm');
  });

  //When Start Date is changed. It will get time and check start date is equal or more than end date.
  //if it is, alert message and set sDate to ''. Otherwise, set time open.

  $("#y_approve").change(function() {
    $("#approved").show();
  });

  $("#n_approve").change(function() {
    $("#approved").hide();
  });

  $("[name='haveCert']").change(function() {
    var flag = $("[name='haveCert']").val();
    $('.cusCert').hide();
    if (flag == '1'){
      $(".cusCert").show();
    }
  });

  $('.grading').change(function() {
    var mina = parseInt($('[name="mina"]').val());
    var minbp = parseInt($('[name="minbp"]').val());
    var minb = parseInt($('[name="minb"]').val());
    var mincp = parseInt($('[name="mincp"]').val());
    var minc = parseInt($('[name="minc"]').val());
    var mindp = parseInt($('[name="mindp"]').val());
    var mind = parseInt($('[name="mind"]').val());
    if (mina > 100)
      $('[name="mina"]').val(100);
    if (mina <= minbp)
      $('[name="minbp"]').val(mina-1);
    else if (minbp <= minb)
      $('[name="minb"]').val(minbp-1);
    else if (minb <= mincp)
      $('[name="mincp"]').val(minb-1);
    else if (mincp <= minc)
      $('[name="minc"]').val(mincp-1);
    else if (minc <= mindp)
      $('[name="mindp"]').val(minc-1);
    else if (mindp <= mind)
      $('[name="mind"]').val(mindp-1);
  });

});
