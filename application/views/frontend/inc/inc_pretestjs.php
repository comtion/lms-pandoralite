<script type="text/javascript">
        function updateinput(num_row,value = ""){
            $('#tc_answer_'+num_row).val(value);
            onclickradio('tc_answer_input_'+num_row,value,num_row);
        }
        var base_url = "<?php echo REAL_PATH; ?>";
        rechk_btnsentdata();
        $(window).on("scroll", function() {
            var scrollHeight = $(document).height();
            var scrollPosition = $(window).height() + $(window).scrollTop();
            var footerHeight = $('footer').outerHeight();
            if((scrollHeight - scrollPosition)<=(footerHeight+150)){
                document.getElementById('bt_saveAnswer').style.display = "";
                document.getElementById('btn_savemobile').style.display = "none";
            }else{
                document.getElementById('bt_saveAnswer').style.display = "none";
                document.getElementById('btn_savemobile').style.display = "";
            }/*
            if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
                console.log('div_bottom');
                document.getElementById('btn_savemobile').style.display = "none";
            }else{
                document.getElementById('btn_savemobile').style.display = "";
            }*/
        });
        function rechk_btnsentdata(){
            var emp_id = $('input[name="emp_id"]').val();
            var qiz_id = $('input[name="qiz_id"]').val();
            var num_row = '<?php echo countArray($question);?>';
            $.ajax( base_url+'/pretest/reTCQuestion/'+qiz_id, {
                type: 'POST',
                data: {emp_id:emp_id},
                dataType: 'json',
                success: function ( arresult ) {
                                if(arresult.warning_msg=="enable"){
                                    document.getElementById("bt_sendAnswer").disabled = false;
                                    $('#bt_sendAnswer').css('cursor', 'pointer');
                                }else{
                                    document.getElementById("bt_sendAnswer").disabled = true;
                                    $('#bt_sendAnswer').css('cursor', 'not-allowed');
                                }
                                    for(i=1;i<=num_row;i++){
                                        var tc_answer = $('#tc_answer_'+i).val();
                                        if(tc_answer==""){
                                            document.getElementById('ques_div'+i).style.border = "thin solid #d63031";
                                        }else{
                                            document.getElementById('ques_div'+i).style.border = "";
                                        }
                                    }
                }
            });
            $.ajax( base_url+'/pretest/reTCQiz/'+qiz_id, {
                type: 'POST',
                data: {emp_id:emp_id},
                dataType: 'json',
                success: function ( arresult ) {
                                if(arresult.warning_msg=="enable"){
                                    $('.div_button').show();
                                    document.getElementById('div_button_save').style.display = "";
                                     $(".radio_answer").attr('disabled', false);
                                }else{
                                    $('.div_button').hide();
                                    document.getElementById('div_button_save').style.display = "none";
                                     $(".radio_answer").attr('disabled', true);
                                }
                }
            });
        }
        function onclick_saveall(){
            var emp_id = $('input[name="emp_id"]').val();
            var qiz_id = $('input[name="qiz_id"]').val();

            var formData = new FormData();
            formData.append('emp_id',  emp_id);
            formData.append('qiz_id', qiz_id);
            var num_row = '<?php echo countArray($question);?>';
            for(i=1;i<=num_row;i++){
                var ques_id = $('#ques_id_'+i).val();
                var tc_answer = $('#tc_answer_'+i).val();
                formData.append('ques_id[]', ques_id);
                formData.append('tc_answer[]', tc_answer);
            }
            $.ajax( base_url+'/pretest/saveQuestion/'+qiz_id, {
                type: 'POST',
                data: formData ,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function ( arresult ) {
                            swal({
                                title: '<?php echo label("com_msg_success"); ?>!',
                                text: "",
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                if(arresult.warning_msg=="enable"){
                                    document.getElementById("bt_sendAnswer").disabled = false;
                                    $('#bt_sendAnswer').css('cursor', 'pointer');
                                }else{
                                    document.getElementById("bt_sendAnswer").disabled = true;
                                    $('#bt_sendAnswer').css('cursor', 'not-allowed');
                                }
                                    for(i=1;i<=num_row;i++){
                                        var tc_answer = $('#tc_answer_'+i).val();
                                        if(tc_answer==""){
                                            document.getElementById('ques_div'+i).style.border = "thin solid #d63031";
                                        }else{
                                            document.getElementById('ques_div'+i).style.border = "";
                                        }
                                    }
                            })
                }
            });
        }

        function sentans(){
            var qiz_id = $('input[name="qiz_id"]').val();
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/onchk_answer",
                      method:'POST',
                      data:{qiz_id_onchk:qiz_id},
                      success:function(data)
                      {
                        if(parseInt(data)>0){
                            swal({
                                title: '<?php echo label("error_answer_sent"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                //location.reload();
                            })
                        }else{
                            update_time_start(qiz_id,'time_finish');
                            //location.reload();
                        }
                      }
                    });
        }

        function update_time_start(qiz_id='',field=''){
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_quiz_time_start",
                  method:"POST",
                  data:{qiz_id_update:qiz_id,field:field},
                  success:function(data)
                  {
                    if(data=="3"){
                            swal(
                                '<?php echo label("save-complete"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                                window.open("<?php echo REAL_PATH; ?>/course/detail/<?php echo $qiz['cos_id']; ?>","_self");
                            })
                    }else if(data=="4"){
                      $.ajax({
                          url:"<?=base_url()?>index.php/course/update_quiz_chkscore",
                          method:"POST",
                          data:{qiz_id:qiz_id},
                          dataType:"json",
                          success:function(data)
                          {
                            if(data.status_qiz=="1"){

                              var msg_answer = '';
                              var type_noti = '';
                              if(parseFloat(data.quiz_maxscore)>0){
                                if(parseFloat(data.score)>=parseFloat(data.quiz_maxscore)){
                                  msg_answer = '<?php echo label("error_answer_pass"); ?>'+data.score+" / "+data.sum_score_total;
                                  type_noti = 'success';
                                }else{
                                  msg_answer = '<?php echo label("error_answer_notpass"); ?>'+data.score+" / "+data.sum_score_total;
                                  type_noti = 'warning';
                                }
                              }else{
                                 msg_answer = '<?php echo label("error_answer_pass"); ?>'+data.score+" / "+data.sum_score_total;
                                 type_noti = 'success';
                              }
                              swal({
                                  title: msg_answer,
                                  text: "",
                                  type: type_noti,
                                  showCancelButton: false,
                                  confirmButtonClass: 'btn btn-primary',
                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                              }).then(function () {
                                window.open("<?php echo REAL_PATH; ?>/course/detail/<?php echo $qiz['cos_id']; ?>","_self");
                              })
                              
                            }else{
                              swal({
                                  title: '<?php echo label("error_answer_wait"); ?>',
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: false,
                                  confirmButtonClass: 'btn btn-primary',
                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                              }).then(function () {
                                window.open("<?php echo REAL_PATH; ?>/course/detail/<?php echo $qiz['cos_id']; ?>","_self");
                              })
                            }
                          }
                        });
                    }else if(data=="5"){
                            swal({
                                title: '<?php echo label("save-complete"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                window.open("<?php echo REAL_PATH; ?>/course/detail/<?php echo $qiz['cos_id']; ?>","_self");
                            })
                    }
                  }
                });
        }
        function onvalue_txt(num_ques = '',id = ''){
          var ques_answer = $('#'+id).val();
          if(ques_answer!=""){
            $('#value_ques'+num_ques).val(ques_answer);
            document.getElementById('ques_div'+num_ques).style.border = "";
          }else{
            document.getElementById('ques_div'+num_ques).style.border = "thin solid #d63031";
          }
        }
        function onclickradio(num_ques = '',id = '',run_number=''){
          var ques_answer = $('#'+id).val();
          if(ques_answer!=""){
            //$('#value_ques'+num_ques).val(ques_answer);
          }
          if(!$("#btn_savemobile").hasClass("btn-warning")){
            $("#btn_savemobile").removeClass("btn-default");
            $("#btn_savemobile").addClass("btn-warning");
            document.getElementById("bt_sendAnswer").disabled = true;
          }
        }
        function onBackCourse(){
          window.open("<?php echo REAL_PATH; ?>/course/detail/<?php echo $qiz['cos_id']; ?>","_self");
        }
    </script>