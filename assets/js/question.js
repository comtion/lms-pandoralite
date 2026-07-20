 $(document).ready(function(){
  $('#thailand_limit').change(function() {
    for(var n = 1; n <= 5; n++) {
      $('#thailand_qans'+n).hide();
      $("#thailand_ans"+n).hide();
      $('#thailand_qimg'+n).hide();
      $("#thailand_img"+n).hide();
    }
    var type = $('#thailand_type').val();
    var limit = parseInt( $("#thailand_limit").val() );
    for(var n = 1; n <= limit; n++) {
      if (type == 'multi'){
        $('#thailand_qans'+n).show();
        $("#thailand_ans"+n).show();
      } if (type == 'dd'){
        $('#thailand_qimg'+n).show();
        $("#thailand_img"+n).show();
      }
    }
  });
  $('#thailand_type').change(function(){
    var type = $('#thailand_type').val();
    for(var n = 1; n <= 5; n++) {
      $('#thailand_qc'+n).hide();
      $('#thailand_c'+n).hide();
      $('#thailand_qa'+n).hide();
      $("#thailand_a"+n).hide();
      $('#thailand_qans'+n).hide();
      $("#thailand_ans"+n).hide();
      $('#thailand_qimg'+n).hide();
      $("#thailand_img"+n).hide();
    }
    $("#thailand_qans").hide();
    $("#thailand_ans").hide();
    $("#thailand_qlimit").hide();
    $("#thailand_limit").hide();
    $("#thailand_llimit").hide();
    $("#thailand_linfo").hide();
    $("#thailand_ddinfo").hide();
    $('#thailand_qimga').hide();
    $("#thailand_imga").hide();
    $("#thailand_type_detail").hide();
    if (type != 'sa' && type != 'sub' && type != '.'){
      $('#thailand_type_detail').show();
      if (type == 'match'){
        for(var n = 1; n <= 5; n++) {
          $('#thailand_qc'+n).show();
          $('#thailand_c'+n).show();
          $('#thailand_qa'+n).show();
          $("#thailand_a"+n).show();
          $('#thailand_qans'+n).show();
          $("#thailand_ans"+n).show();
        }
      }
      else if (type == 'multi'){
        $("#thailand_qlimit").show();
        $("#thailand_limit").show();
        $("#thailand_llimit").show();
        var limit = parseInt( $("#thailand_limit").val() );
        for(var n = 1; n <= limit; n++) {
          $('#thailand_qans'+n).show();
          $("#thailand_ans"+n).show();
        }
        for(var n = 1; n <= 5; n++) {
          $('#thailand_qc'+n).show();
          $('#thailand_c'+n).show();
        }
      }
      else if (type == 'twoChoice'){
        for(var n = 1; n <= 2; n++) {
          $('#thailand_qc'+n).show();
          $('#thailand_c'+n).show();
        }
        $("#thailand_qans").show();
        $("#thailand_ans").show();
      } else if (type == 'dd'){
        $("#thailand_qlimit").show();
        $("#thailand_limit").show();
        $("#thailand_llimit").show();
        $("#thailand_linfo").show();
        $("#thailand_ddinfo").show();
        $('#thailand_qimga').show();
        $("#thailand_imga").show();
      }
    } else {
      $('#thailand_type_detail').hide();
      $("#thailand_break").hide();
    }
  });

  $('#english_limit').change(function() {
    for(var n = 1; n <= 5; n++) {
      $('#english_qans'+n).hide();
      $("#english_ans"+n).hide();
      $('#english_qimg'+n).hide();
      $("#english_img"+n).hide();
    }
    var type = $('#english_type').val();
    var limit = parseInt( $("#english_limit").val() );
    for(var n = 1; n <= limit; n++) {
      if (type == 'multi'){
        $('#english_qans'+n).show();
        $("#english_ans"+n).show();
      } if (type == 'dd'){
        $('#english_qimg'+n).show();
        $("#english_img"+n).show();
      }
    }
  });
  $('#english_type').change(function(){
    var type = $('#english_type').val();
    for(var n = 1; n <= 5; n++) {
      $('#english_qc'+n).hide();
      $('#english_c'+n).hide();
      $('#english_qa'+n).hide();
      $("#english_a"+n).hide();
      $('#english_qans'+n).hide();
      $("#english_ans"+n).hide();
      $('#english_qimg'+n).hide();
      $("#english_img"+n).hide();
    }
    $("#english_qans").hide();
    $("#english_ans").hide();
    $("#english_qlimit").hide();
    $("#english_limit").hide();
    $("#english_llimit").hide();
    $("#english_linfo").hide();
    $("#english_ddinfo").hide();
    $('#english_qimga').hide();
    $("#english_imga").hide();
    $("#english_type_detail").hide();
    if (type != 'sa' && type != 'sub' && type != '.'){
      $('#english_type_detail').show();
      if (type == 'match'){
        for(var n = 1; n <= 5; n++) {
          $('#english_qc'+n).show();
          $('#english_c'+n).show();
          $('#english_qa'+n).show();
          $("#english_a"+n).show();
          $('#english_qans'+n).show();
          $("#english_ans"+n).show();
        }
      }
      else if (type == 'multi'){
        $("#english_qlimit").show();
        $("#english_limit").show();
        $("#english_llimit").show();
        var limit = parseInt( $("#english_limit").val() );
        for(var n = 1; n <= limit; n++) {
          $('#english_qans'+n).show();
          $("#english_ans"+n).show();
        }
        for(var n = 1; n <= 5; n++) {
          $('#english_qc'+n).show();
          $('#english_c'+n).show();
        }
      }
      else if (type == 'twoChoice'){
        for(var n = 1; n <= 2; n++) {
          $('#english_qc'+n).show();
          $('#english_c'+n).show();
        }
        $("#english_qans").show();
        $("#english_ans").show();
      } else if (type == 'dd'){
        $("#english_qlimit").show();
        $("#english_limit").show();
        $("#english_llimit").show();
        $("#english_linfo").show();
        $("#english_ddinfo").show();
        $('#english_qimga').show();
        $("#english_imga").show();
      }
    } else {
      $('#english_type_detail').hide();
      $("#english_break").hide();
    }
  });
});
