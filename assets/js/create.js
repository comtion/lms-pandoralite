function go(url) {
  window.location = url;
}
 $(document).ready(function(){
  //when change language. it will get radio value and set it at inp_lang in form.
  $(".lang_tab").change(function(){
    var page =  $("input[name='page']").val();
    var lang = $("[name='lang']").val();
    var inp = $("input[name='tabs']:checked").val();
    $("[name='lang']").val(inp);
  });

  $("#sh_english").click(function() {
    var page =  $("input[name='page']").val();
      $('#content_thailand').hide();
      $("#content_english").show();
  });

  $("#sh_thailand").click(function() {
    var page =  $("input[name='page']").val();
      $('#content_english').hide();
      $("#content_thailand").show();
  });

  $("#sDate").change(function() {
    $("#time_open").val($('#sDate').val());
  });

  $("[name='sDate']").change(function() {
    $("[name='time_open']").val($("[name='sDate']").val());
  });

  $("[name='eDate']").change(function() {
    $("[name='time_close']").val($("[name='eDate']").val());
  });

  $("#eDate").change(function() {
    $("#time_close").val($('#eDate').val());
  });



});