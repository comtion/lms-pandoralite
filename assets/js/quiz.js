var checkAns = true;
$(function() {
  var clang = "."+lang;
  var flag = $(clang+".number:checked").val();
  var num = $(clang+"[name='n_attempts']").val();
  if (flag == 1){
    $(clang+".attempted").show();
  }
});

function allowDrop(ev) {
  ev.preventDefault();
}

function drag(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  ev.dataTransfer.setDragImage(ev.target,0,0);
  ev.dropEffect = "move";
}

function dragover_handler(ev) {
 ev.preventDefault();
 // Set the dropEffect to move
 ev.dataTransfer.dropEffect = "move"
}

function drop(ev) {
  ev.preventDefault();
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
  var src = document.getElementById(data).src;
  var field = src.split('/uploads/');
  $("[name='dropAnswer']").val(field[1]);
}
$(document).ready(function(){
  $(".pre_ans").change(function() {
    var ids = $(this).prop('id');
    var id = ids.split('cut');
    var path = $(this).val()+id[0];
    var value = $('#'+path).text();
    var element = document.getElementById(path);
    var text = element.innerText;
    $('#id'+ids).val(text);
  });

  $(".number").change(function() {
    if ($(".attempted").is(":visible")){
      $(".attempted").hide();
    } else {
      $(".attempted").show();
    }
  });

  $('.bt-sendAnswer').click( function(){
    console.log('click send');
    checkAns = true;
    check = true;
    var url_submit = $('input[name="url_submit"]').val();
    var qcode = $(this).attr('data-qcode');
    $('input[name="save"]').each( function( index , value ){
      console.log( $(this).prop('id') );
      if( checkAns ){
        $(this).trigger('click');
      }else{
        check = false;
        return false;
      }
    });
    console.log( check );
    if( check ){
      console.log('send complete')
      window.location.href = url_submit;
    }else{
      alert('กรุณาตอบคำถามให้ครบทุกข้อ');
    }
  });

  var arrcheck = [];
  $('.bt-allsave').click( function(){
    //console.log('click save');
    checkAns = true;
    count = 0;
      var checkSelectAns = true;
    var url_submit = $('input[name="url_submit"]').val();
    var qcode = $(this).attr('data-qcode');
    var size_save = $('input[name="save"]').size();
    console.log("86:"+size_save);
    $('input[name="save"]').each( function( index , value ){
      //console.log( $(this).prop('id') );
      var class_name = '.'+$(this).prop('id');

      var type = $('input[name="type"]'+class_name).val();
      var emp_c = $('input[name="emp_c"]'+class_name).val();
      var code = $('input[name="code"]'+class_name).val();
      var id = $('input[name="quest_id"]'+class_name).val();
      if (type == 'multi') {
        var limit = parseInt( $('input[name="limit"]'+class_name).val() );
        if (limit > 1){
          type = 'multi2';
        }
      }
      var formData = new FormData();
      formData.append('emp_c',  emp_c);
      formData.append('code', code);
      formData.append('type', type);
      if (type == 'twoChoice' || type == 'multi'){
        var obj_path = 'input' + class_name + ":checked";
        var answer = $(obj_path).val();
        if( answer == "" )checkSelectAns = false;
        formData.append('answer', answer );
      } else if (type == 'dd'){
        var obj_path = 'input[name="dropAnswer"]' + class_name;
        var answer = $(obj_path).val();
        if( answer == "" )checkSelectAns = false;
        formData.append('dropAnswer', answer );
      } else if (type == 'sa' || type == 'sub'){
        var obj_path = '[name="answer"]' + class_name;
        var answer = $(obj_path).val();
        if( answer == "" )checkSelectAns = false;
        formData.append('answer', answer );
      } else {
        var obj_path = 'input[name="ans[]"]'+class_name;
        if (type == 'multi2'){
          obj_path += ":checked";
        }
        $(obj_path).each(function(i, item) {
          formData.append('ans[]', item.value);
        });
      }
      console.log(checkSelectAns);
      if( checkSelectAns ){
        $.ajax( base_url+'quiz/saveQuestion/'+id, {
          type: 'POST',
          data: formData ,
          dataType: 'json',
          processData: false,
          contentType: false,
          success: function ( arresult ) {
            console.log(arresult.rs+" 149::"+arresult.val);
            if(arresult.rs){
              console.log("151");
              arrcheck = [];
              console.log("143:"+arrcheck.length);
              //alert('บันทึกคำตอบเรียบร้อย');
            }else{
              console.log("144+++");
              arrcheck.push("False");
              //check = false;
              //alert('กรุณาตอบคำถามให้ครบทุกข้อ');
            }

            count++;
        console.log(size_save+" ---- "+ count);
        if(size_save==count){
          console.log("155:"+arrcheck.length);
            if(arrcheck.length>0){

              alert('กรุณาตอบคำถามให้ครบทุกข้อ');
            }else{
              alert('บันทึกคำตอบเรียบร้อย');
            }
        }else{
          console.log("163:"+arrcheck.length);
        }
            //console.log(check_status);
          }
        });
      }else{
        checkSelectAns = false;
      }
    
    console.log("93");
    });
  });
});
