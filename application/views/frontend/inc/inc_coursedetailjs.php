<script type="text/javascript">
      $.fn.dataTable.ext.errMode = "none";
                      $.ajax({
                        url:"<?=base_url()?>index.php/course/run_media_course",
                        method:"POST",
                        data:{cos_id:'<?php echo $cos_id; ?>'},
                        success:function(data)
                        {
                          
                          if(data!=""){
                            document.getElementById('div_media_course').style.display = '';
                            $('#div_media_course').html(data);
                          }else{
                            document.getElementById('div_media_course').style.display = 'none';
                          }
                        }
                      });
         $(document).on('click', '.btn_register', function(){
            var id = $(this).attr("id");
            /*var point_redeem = $('#point_redeem_hide'+id).val();
            var point_user = '<?php echo $user['usp_point']; ?>';;*/
            var enroll_seat = '<?php echo $courses['enroll_seat'] ?>';
            var seat_count = '<?php echo $courses['seat_count'] ?>';

            if((parseInt(enroll_seat)+1)<parseInt(seat_count)){
              status = "1";
            }else{
              status = "0";
            }
            if(parseInt(seat_count)==0){
              status = "1";
            }
           /* console.log(id,parseInt(point_redeem),parseInt(point_user));
            if(parseInt(point_redeem)>0){
              if(parseFloat(point_user)>=parseFloat(point_redeem)){
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/register_course",
                      method:'POST',
                      data:{cos_id:id,status:status},
                      success:function(data)
                      {
                        
                        if(data=="2"){
                            swal(
                                '<?php echo label("enroll_reuse_success"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                              window.location.href = '<?=base_url()?>index.php/course/detail/'+id;
                            })
                        }else{
                            swal({
                                title: '<?php echo label("enroll_reuse_error"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                location.reload();
                            })
                        }
                       
                      }
                    });
              }else{
                swal({
                            title: '<?php echo label('point_dontcan'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                })
              }
            }else{*/
                    $.ajax({
                      url:"<?=base_url()?>index.php/course/register_course",
                      method:'POST',
                      data:{cos_id:id,status:status},
                      success:function(data)
                      {
                        
                        if(data=="2"){
                            swal(
                                '<?php echo label("enroll_reuse_success"); ?>!',
                                '',
                                'success'
                            ).then(function () {
                              window.location.href = '<?=base_url()?>index.php/course/detail/'+id;
                            })
                        }else{
                            swal({
                                title: '<?php echo label("enroll_reuse_error"); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label("m_ok"); ?>'
                            }).then(function () {
                                location.reload();
                            })
                        }
                       
                      }
                    });
            //}
          });
      var player;

        // this function gets called when API is ready to use
        function onYouTubePlayerAPIReady() {
          // create the global player from the specific iframe (#video)
          player = new YT.Player('video_youtube', {
            events: {
              // call this function when player is ready to use
              'onReady': onPlayerReady
            }
          });
          $('#modal-viewvideo').on('hide.bs.modal', function (event) {
            if($('#video_url_view').css('display') == 'none'){ 
            } else { 
            var src_youtube = document.getElementById("video_youtube").src;
            document.getElementById("video_youtube").src = src_youtube;
            }
            if($('#video_file_view').css('display') == 'none'){ 
            } else { 
            var video_upload = document.getElementById("video_upload").src;
            document.getElementById("video_upload").src = video_upload;
            }
          });
        }

        function onPlayerReady(event) {
          
        }
        function stopVideo() {
          player.stopVideo();
        }

        // Inject YouTube API script
        var tag = document.createElement('script');
        tag.src = "//www.youtube.com/player_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


      $('#modal-viewvideo').on('hide.bs.modal', function (event) {
        console.log("modal hide 515");
        //var vid = document.getElementById("video_upload"); 
       // vid.pause(); 
      });
        <?php if(intval($survey_chk)>0){ ?>
                            $.ajax({
                                  url: '<?=base_url()?>index.php/workgroup/rechecksurvey',
                                  type: 'POST',
                                  data:{cos_id:'<?php echo $cos_id; ?>'},
                                  success: function(data_survey){
                                    $('#survey_select').html(data_survey);
                                  }
                            });
        <?php } ?>
        function onchange_survey(){
          var id = $('#survey_select').val();

                $('#modal-viewquestionnaire').modal('show');
                update_questionnaire(id);
        }
        function onpreview_document_cos_exp(fil_id){
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_fil_cos_data",
                  method:"POST",
                  data:{fil_id:fil_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                    if(data.type=="document"){
                      $('#modal-viewdocument').modal('show');
                      $('#fil_id_downloadfile').val(fil_id);
                      $('#fil_path_downloadfile').val(data.path_file);
                      PDFObject.embed("<?php echo base_url().'/uploads/document/' ?>"+data.path_file, "#iframe_document");
                      //document.getElementById("iframe_document").src = "https://docs.google.com/viewer?url="+data.link+"&embedded=true";
                    }else{
                      window.location = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                    }
                  }
                });
        }
        function onpreview_document_cos(){
          var fil_id = $('#fil_cos_id_select').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_fil_cos_data",
                  method:"POST",
                  data:{fil_id:fil_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                    if(data.type=="document"){
                      $('#modal-viewdocument').modal('show');
                      $('#fil_id_downloadfile').val(fil_id);
                      $('#fil_path_downloadfile').val(data.path_file);
                      PDFObject.embed("<?php echo base_url().'/uploads/document/' ?>"+data.path_file, "#iframe_document");
                      //document.getElementById("iframe_document").src = "https://docs.google.com/viewer?url="+data.link+"&embedded=true";
                    }else{
                      window.location = "<?php echo base_url().'/uploads/document/' ?>"+data.path_file;
                    }
                  }
                });
        }
        function chk_youtubeonplay(){
          console.log('Line:342');
        }



        function onclose_divmenu(){
                var hidden = $('#div_menu');
                if (hidden.hasClass('visible')){
                    hidden.animate({"left":"100%"}, "slow").removeClass('visible');
                }
        }
        function onopen_divmenu(){
                    var hidden = $('#div_menu');
                    if (hidden.hasClass('visible')){
                        hidden.animate({"left":"100%"}, "slow").removeClass('visible');
                    } else {
                        hidden.animate({"left":"0"}, "slow").addClass('visible');
                    }

        }
        function onchk_div_overview(id=""){
          var div_var = document.getElementById(id);
          if(div_var.style.display=="none"){
            if(id=="div_description_cos_exp"){
                      document.getElementById(id).style.display = "";
                      document.getElementById("contents_main").style.display = "";
                      document.getElementById("div_description_cos_detail_exp").style.display = "";
                      document.getElementById("div_description_qiz_challenge_exp").style.display = "none";
            }else if(id=="div_description_qiz_challenge_exp"){
                      document.getElementById(id).style.display = "";
                      document.getElementById("div_description_cos_exp").style.display = "";
                      document.getElementById("div_description_cos_detail_exp").style.display = "none";
                      document.getElementById("div_description_qiz_challenge_exp").style.display = "";
            }else if(id=="div_description_cos_detail_exp"){
                      document.getElementById(id).style.display = "";
                      document.getElementById("div_description_cos_detail_exp").style.display = "";
                      document.getElementById("div_description_qiz_challenge_exp").style.display = "none";
            }
          }else{
            var qiz_challenge = document.getElementById("div_description_qiz_challenge_exp");
            if(qiz_challenge.style.display==""){
                      document.getElementById("div_description_cos_exp").style.display = "none";
                      document.getElementById("contents_main").style.display = "";
                document.getElementById("div_description_cos_detail_exp").style.display = "none";
                document.getElementById("div_description_qiz_challenge_exp").style.display = "none";
            }else{
                document.getElementById("div_description_cos_exp").style.display = "none";
                document.getElementById("contents_main").style.display = "none";
                document.getElementById(id).style.display = "none";
            }
          }
        }

        function onchk_div(id=""){
          var div_var = document.getElementById(id);

          <?php if(isMobile()){ ?>
                  if(div_var.style.display=="none"){
                    document.getElementById(id).style.display = "";
                    if(id=="div_description_cos"){
                        location.reload();
                        $( ".qiz_onclick" ).removeClass( "active" );
                        $( ".les_onclick" ).removeClass( "active" );
                      document.getElementById("contents_main").style.display = "";
                      document.getElementById("div_description_cos_detail").style.display = "";
                      document.getElementById("div_description_qiz_challenge").style.display = "none";
                      document.getElementById("div_lesson").style.display = "none";
                    }
                    if(id=="div_description_qiz_challenge"){
                      document.getElementById("contents_main").style.display = "";
                      document.getElementById("div_description_qiz_challenge").style.display = "";
                      document.getElementById("div_description_cos_detail").style.display = "none";
                      document.getElementById("div_lesson").style.display = "none";
                    }
                  }else{
                          document.getElementById(id).style.display = "none";
                          $( ".qiz_onclick" ).removeClass( "active" );
                          $( ".les_onclick" ).removeClass( "active" );
                          document.getElementById("contents_main").style.display = "none";
                          document.getElementById("div_description_cos_detail").style.display = "none";
                          document.getElementById("div_description_qiz_challenge").style.display = "none";
                          document.getElementById("div_lesson").style.display = "none";
                  }
          <?php }else{ ?>
          if(div_var.style.display=="none"){
            document.getElementById(id).style.display = "";

            if(id=="div_description_qiz_challenge"){
                var qiz_challenge = document.getElementById("div_description_qiz_challenge");
                if(qiz_challenge.style.display==""){
                  console.log("1055");
                  document.getElementById("contents_main").style.display = "";
                  document.getElementById("div_description_qiz_challenge").style.display = "";
                  document.getElementById("div_description_cos_detail").style.display = "none";
                          document.getElementById("div_lesson").style.display = "none";
                }else{
                  console.log("1061");
                  document.getElementById("contents_main").style.display = "";
                  document.getElementById("div_description_qiz_challenge").style.display = "";
                  document.getElementById("div_description_cos_detail").style.display = "";
                          document.getElementById("div_lesson").style.display = "none";
                }
            }
            if(id=="div_menu"){
              document.getElementById("contents_main").classList.add('col-md-8');
              document.getElementById("contents_main").classList.remove('col-md-12');
              document.getElementById("contents_main").style.borderLeft = "1.5px solid";
              document.getElementById("contents_main").style.borderColor = "#ebebe0";
            }
            if(id=="div_description_cos"){
              $( ".qiz_onclick" ).removeClass( "active" );
              $( ".les_onclick" ).removeClass( "active" );
              document.getElementById("contents_main").style.display = "";
              document.getElementById("div_description_cos_detail").style.display = "";
              document.getElementById("div_description_qiz_challenge").style.display = "none";
              document.getElementById("div_lesson").style.display = "none";
            }
          }else{

            if(id=="div_description_cos"){
                var qiz_challenge = document.getElementById("div_description_qiz_challenge");
                if(qiz_challenge.style.display==""){
                          $( ".qiz_onclick" ).removeClass( "active" );
                          $( ".les_onclick" ).removeClass( "active" );
                          document.getElementById("contents_main").style.display = "";
                          document.getElementById("div_description_cos_detail").style.display = "";
                          document.getElementById("div_description_qiz_challenge").style.display = "none";
                          document.getElementById("div_lesson").style.display = "none";
                }else{
                  var div_description_cos_detail = document.getElementById("div_description_cos_detail");
                  if(div_description_cos_detail.style.display==""){
                          document.getElementById("div_description_cos_detail").style.display = "none";
                          document.getElementById("contents_main").style.display = "none";
                          document.getElementById(id).style.display = "none";
                  }else{
                          document.getElementById("div_description_cos_detail").style.display = "";
                          document.getElementById("contents_main").style.display = "";
                          document.getElementById(id).style.display = "";
                  }
                }
            }

            if(id=="div_description_qiz_challenge"){
                var qiz_challenge = document.getElementById("div_description_qiz_challenge");
                if(qiz_challenge.style.display==""){
                  document.getElementById("contents_main").style.display = "none";
                  document.getElementById("div_description_qiz_challenge").style.display = "none";
                  document.getElementById("div_description_cos_detail").style.display = "none";
                }else{
                  document.getElementById("contents_main").style.display = "";
                  document.getElementById("div_description_qiz_challenge").style.display = "";
                  document.getElementById("div_description_cos_detail").style.display = "";
                }
            }

            if(id=="div_menu"){
              document.getElementById(id).style.display = "none";
              document.getElementById("contents_main").classList.add('col-md-12');
              document.getElementById("contents_main").classList.remove('col-md-8');
              document.getElementById("contents_main").style.borderLeft = "0px solid";
              document.getElementById("contents_main").style.borderColor = "";
            }
          }
        <?php } ?>
        }
      function convert_val(val=''){
        $('#tc_answer_mul').val(val);
      }

      fetch_data_lesson('<?php echo $cos_id; ?>');
      fetch_data_quiz('<?php echo $cos_id; ?>');
      fetch_data_survey('<?php echo $cos_id; ?>');
      function detail_lesson(les_id = ''){
        console.log(les_id);
      }
      function detail_qiz(qiz_id = ''){
        console.log(qiz_id);
      }
      function detail_survey(sv_id = ''){
        console.log(sv_id);
      }

         $(document).on('click', '.certificate', function(){
            var cos_id = '<?php echo $cos_id; ?>';
            $.ajax({
                  url:"<?=base_url()?>index.php/certificate/createfile",
                  method:"POST",
                  data:{cos_id:cos_id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                    document.getElementById('obj_pdf_cert').data = "<?php echo REAL_PATH."/uploads/certificate/"; ?>"+data;
                  }
            });
          });

         $(document).on('click', '.qiz_onclick', function(){
            var id = $(this).attr("id");
            window.open("<?php echo REAL_PATH; ?>/pretest/detail/"+id,"_self");
            /*$( ".qiz_onclick" ).removeClass( "active" );
            $( ".les_onclick" ).removeClass( "active" );
            $( this ).addClass( "active" );

              document.getElementById('quiz_div').style.display = '';
              document.getElementById('div_lesson').style.display = 'none';
              document.getElementById('div_description_cos').style.display = 'none';
                          document.getElementById("div_description_qiz_challenge").style.display = "none";
                update_quiz(id);*/
          });
         $(document).on('click', '.les_onclick', function(){
            var id = $(this).attr("id");
            $( ".qiz_onclick" ).removeClass( "active" );
            $( ".les_onclick" ).removeClass( "active" );
            $( this ).addClass( "active" );
            update_lesson(id);
              document.getElementById('quiz_div').style.display = 'none';
              document.getElementById('div_lesson').style.display = '';
              document.getElementById('div_description_cos').style.display = 'none';
                          document.getElementById("div_description_qiz_challenge").style.display = "none";
          });



         $(document).on('click', '.saveans', function(){
            var ques_id = $('#ques_id').val();
            var qiz_id = $('#qiz_id').val();
            var ques_type = $("#ques_type").val();
            if(ques_type=="multi"){
              var tc_answer = $("#tc_answer_mul").val();
            }else{
              var tc_answer = $("#tc_answer").val();
            }
            update_last_ques(qiz_id,ques_id,tc_answer);
            update_time_start(qiz_id,'time_save');
                            swal(
                                '<?php echo label("com_msg_success"); ?>!',
                                '',
                                'success'
                            )
          });
         $(document).on('click', '.sentans', function(){
            var ques_id = $('#ques_id').val();
            var qiz_id = $('#qiz_id').val();
            var ques_type = $("#ques_type").val();
            if(ques_type=="multi"){
              var tc_answer = $("#tc_answer_mul").val();
            }else{
              var tc_answer = $("#tc_answer").val();
            }
            update_last_ques(qiz_id,ques_id,tc_answer);
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
          });

         $(document).on('click', '.nextqiz', function(){
            var ques_id_next = $('#ques_id_next').val();
            var ques_id = $('#ques_id').val();
            var qiz_id = $('#qiz_id').val();
            var ques_type = $("#ques_type").val();
            if(ques_type=="multi"){
              var tc_answer = $("#tc_answer_mul").val();
            }else{
              var tc_answer = $("#tc_answer").val();
            }
            var run_number = $('#run_number').val();
            if(run_number=='0'){
              update_time_start(qiz_id,'time_start');
            }
            run_number = parseInt(run_number)+1;
            $('#run_number').val(run_number);
            update_ques(qiz_id,ques_id_next,ques_id,tc_answer);
               /* $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_fil_log",
                  method:"POST",
                  data:{fil_id:id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                  }
                });*/
          });

         $(document).on('click', '.previousqiz', function(){
            var ques_id_back = $('#ques_id_back').val();
            var ques_id = $('#ques_id').val();
            var qiz_id = $('#qiz_id').val();
            var ques_type = $("#ques_type").val();
            if(ques_type=="multi"){
              var tc_answer = $("#tc_answer_mul").val();
            }else{
              var tc_answer = $("#tc_answer").val();
            }

            var run_number = $('#run_number').val();
            run_number = parseInt(run_number)-1;
            $('#run_number').val(run_number);
            if(run_number=="0"){
              document.getElementById('div_question').style.display = 'none';
              document.getElementById('div_description_quiz').style.display = '';
              $('#ques_id_back').val('');
              $('#ques_id_next').val('');
              document.getElementById('previousqiz').style.display = 'none';
              $('#run_number').val('0');
            }else{
              update_ques(qiz_id,ques_id_back,ques_id,tc_answer);
            }
               /* $.ajax({
                  url:"<?=base_url()?>index.php/course/insert_fil_log",
                  method:"POST",
                  data:{fil_id:id},
                  dataType:"json",
                  success:function(data)
                  {
                    
                  }
                });*/
          });


        function choice_inques(val_th='',val_en='',name=''){
                        if(val_th!=""||val_en!=""){
                          document.getElementById(''+name+'_div').style.display = '';
                          <?php if($lang=="thai"){ ?>
                            $('#'+name+'_txt').html(val_th);
                          <?php }else{ ?>
                            $('#'+name+'_txt').html(val_en);
                          <?php } ?>
                        }else{
                          $('#'+name+'_txt').html('');
                          document.getElementById(''+name+'_div').style.display = 'none';
                        }
        }
        function update_last_ques(qiz_id='',ques_id='',tc_answer=''){

                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_last_answer",
                  method:"POST",
                  data:{qiz_id_rechkques:qiz_id,ques_id:ques_id,tc_answer:tc_answer},
                  dataType:"json",
                  success:function(data)
                  {
                  }
                });
        }
        function clear_qiz(){
          $("#tc_answer_mul").val('');
          $('#tc_answer').val('');
          $('#msg_fromadmin').text('');
          $('#ques_type').val('');
          document.getElementById("msg_fromadmin").style.display = "none";
          document.getElementById("tc_answer_mul_sel1").checked = false;
          document.getElementById("tc_answer_mul_sel2").checked = false;
          document.getElementById("tc_answer_mul_sel3").checked = false;
          document.getElementById("tc_answer_mul_sel4").checked = false;
          document.getElementById("tc_answer_mul_sel5").checked = false;
        }

        function update_ques(qiz_id='',ques_id_future='',ques_id='',tc_answer=''){
                    console.log(qiz_id);
            var run_number = $('#run_number').val();
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_ques_detail_data",
                  method:"POST",
                  data:{qiz_id_rechkques:qiz_id,ques_id_future:ques_id_future,ques_id:ques_id,tc_answer:tc_answer},
                  dataType:"json",
                  success:function(data)
                  {
                    clear_qiz();
                    if(data!=null){

                      $('#ques_id').val(data[0].ques_id);
                      <?php if($lang=="thai"){ ?>
                        ques_name_th = data[0].ques_name_th;
                        ques_name_th = ques_name_th.replace("<p>", "");
                        ques_name_th = ques_name_th.replace("</p>", "");
                        $('#txt_question_head').html("<?php echo label('question'); ?> "+run_number+". "+ques_name_th);
                        $('#txtscore_head').html('('+data[0].ques_score+' <?php echo label("score"); ?> )');
                      <?php }else{ ?>
                        ques_name_en = data[0].ques_name_en;
                        ques_name_en = ques_name_en.replace("<p>", "");
                        ques_name_en = ques_name_en.replace("</p>", "");
                        $('#txt_question_head').html("<?php echo label('question'); ?> "+run_number+". "+data[0].ques_name_en);
                        $('#txtscore_head').html('('+data[0].ques_score+' <?php echo label("score"); ?> )');
                      <?php } ?>
                      $('#ques_type').val(data[0].ques_type);
                      if(data[0].ques_type=="multi"){
                        if(data[0].answer!=""){
                          if(data[0].answer=="mul_c1"){
                            $("#tc_answer_mul").val('mul_c1');
                            document.getElementById("tc_answer_mul_sel1").checked = true;
                          }else if(data[0].answer=="mul_c2"){
                            $("#tc_answer_mul").val('mul_c2');
                            document.getElementById("tc_answer_mul_sel2").checked = true;
                          }else if(data[0].answer=="mul_c3"){
                            $("#tc_answer_mul").val('mul_c3');
                            document.getElementById("tc_answer_mul_sel3").checked = true;
                          }else if(data[0].answer=="mul_c4"){
                            $("#tc_answer_mul").val('mul_c4');
                            document.getElementById("tc_answer_mul_sel4").checked = true;
                          }else if(data[0].answer=="mul_c5"){
                            $("#tc_answer_mul").val('mul_c5');
                            document.getElementById("tc_answer_mul_sel5").checked = true;
                          }
                        }
                        choice_inques(data[0].type_multi.mul_c1_th,data[0].type_multi.mul_c1_en,'mul_c1');
                        choice_inques(data[0].type_multi.mul_c2_th,data[0].type_multi.mul_c2_en,'mul_c2');
                        choice_inques(data[0].type_multi.mul_c3_th,data[0].type_multi.mul_c3_en,'mul_c3');
                        choice_inques(data[0].type_multi.mul_c4_th,data[0].type_multi.mul_c4_en,'mul_c4');
                        choice_inques(data[0].type_multi.mul_c5_th,data[0].type_multi.mul_c5_en,'mul_c5');
                        document.getElementById('questype_multi').style.display = '';
                        document.getElementById('questype_sub').style.display = 'none';
                      }else{
                        document.getElementById('questype_sub').style.display = '';
                        document.getElementById('questype_multi').style.display = 'none';
                        
                        if(data[0].answer!=""){
                          $('#tc_answer').val(data[0].answer);
                          if(data[0].tc_note!=""){
                            document.getElementById('msg_fromadmin').style.display = "";
                            $('#msg_fromadmin').html("<b style='color:red;'><?php echo label('msg_fromadmin').'</b> '; ?>"+data[0].tc_note);
                          }else{
                            $("#msg_fromadmin").html("");
                            document.getElementById('msg_fromadmin').style.display = "none";
                          }
                        }else{
                          $('#tc_answer').val('');
                          $("#msg_fromadmin").html("");
                          document.getElementById('msg_fromadmin').style.display = "none";
                        }
                      }

                      console.log(data[0].ques_type);
                      document.getElementById('div_question').style.display = '';
                      document.getElementById('div_description_quiz').style.display = 'none';
                      

                      $.ajax({
                          url:"<?=base_url()?>index.php/course/checknext_qiz",
                          method:"POST",
                          data:{id:data[0].ques_id,qiz_id:qiz_id},
                          dataType:"json",
                          success:function(data_next)
                          {
                            if(data_next!="0"){
                              $('#ques_id_next').val(data_next.ques_id);
                              document.getElementById('nextqiz').style.display = '';
                              document.getElementById('sentans').style.display = 'none';
                              document.getElementById('saveans').style.display = 'none';
                            }else{
                              $('#ques_id_next').val('');
                              document.getElementById('nextqiz').style.display = 'none';
                              $.ajax({
                                url:"<?=base_url()?>index.php/course/rechk_qiztc",
                                method:"POST",
                                data:{qiz_id_onchk:qiz_id},
                                dataType:"json",
                                success:function(data_rechk)
                                {
                                    if(data_rechk.qiz_status=="3"){
                                      document.getElementById('sentans').style.display = 'none';
                                      document.getElementById('saveans').style.display = 'none';
                                    }else{
                                      document.getElementById('sentans').style.display = '';
                                      document.getElementById('saveans').style.display = '';
                                    }
                                }
                              });
                            }
                          }
                        });
                        $.ajax({
                          url:"<?=base_url()?>index.php/course/checkback_qiz",
                          method:"POST",
                          data:{id:data[0].ques_id,qiz_id:qiz_id},
                          dataType:"json",
                          success:function(data_back)
                          {
                            if(data_back!="0"){
                              $('#ques_id_back').val(data_back.ques_id);
                              document.getElementById('previousqiz').style.display = '';
                            }else{
                              $('#ques_id_back').val('');
                              document.getElementById('previousqiz').style.display = 'none';
                              if(parseInt(run_number)>0){
                                description_quiz(qiz_id);
                              }
                            }
                          }
                        });
                      }else{
                        document.getElementById('div_question').style.display = 'none';
                        document.getElementById('div_description_quiz').style.display = '';
                      }
                    }
                });
        }
        function description_quiz(qiz_id=''){
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_quiz_detail_data",
                  method:"POST",
                  data:{qiz_id_update:qiz_id},
                  dataType:"json",
                  success:function(data)
                  {
                       
                    <?php if($lang=="thai"){ ?>               
                      if(data.quiz_info_th!=""){
                        document.getElementById('previousqiz').style.display = '';
                      }else{
                        document.getElementById('previousqiz').style.display = 'none';
                      } 
                    <?php }else{ ?>
                      if(data.quiz_info_en!=""){
                        document.getElementById('previousqiz').style.display = '';
                      }else{
                        document.getElementById('previousqiz').style.display = 'none';
                      } 
                    <?php } ?>
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
                                location.reload();
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
                              swal({
                                  title: '<?php echo label("error_answer_notpass"); ?>'+data.score+" / "+data.sum_score_total,
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: false,
                                  confirmButtonClass: 'btn btn-primary',
                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                              }).then(function () {
                                  location.reload();
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
                                  location.reload();
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
                                location.reload();
                            })
                    }
                  }
                });
        }
        function update_quiz(qiz_id=''){
                $('#qiz_id').val(qiz_id);
                $.ajax({
                  url:"<?=base_url()?>index.php/course/update_quiz_detail_data",
                  method:"POST",
                  data:{qiz_id_update:qiz_id},
                  dataType:"json",
                  success:function(data)
                  {
                       
                    <?php if($lang=="thai"){ ?>               
                      $('#txt_head_qiz').text(data.quiz_name_th);

                      if(data.quiz_info_th!=""){
                        $('#txt_description_quiz').html(data.quiz_info_th);
                        document.getElementById('previousqiz').style.display = 'none';
                        document.getElementById('div_question').style.display = 'none';
                        document.getElementById('div_description_quiz').style.display = '';
                      }else{
                        update_time_start(qiz_id,'time_start');
                        $('#run_number').val('1');
                        update_ques(qiz_id,'','');
                        document.getElementById('div_question').style.display = '';
                        document.getElementById('div_description_quiz').style.display = 'none';
                      } 
                    <?php }else{ ?>
                      $('#txt_head_qiz').text(data.quiz_name_en);

                      if(data.quiz_info_en!=""){
                        $('#txt_description_quiz').html(data.quiz_info_en);
                        document.getElementById('previousqiz').style.display = 'none';
                        document.getElementById('div_question').style.display = 'none';
                        document.getElementById('div_description_quiz').style.display = '';
                      }else{
                        update_time_start(qiz_id,'time_start');
                        $('#run_number').val('1');
                        update_ques(qiz_id,'','');
                        document.getElementById('div_question').style.display = '';
                        document.getElementById('div_description_quiz').style.display = 'none';
                      } 
                    <?php } ?>
                  }
                });
        }
        function fetch_data_lesson(cos_id='')
         {
            $('#myTable_lesson').DataTable().destroy();
            var table = $('#myTable_lesson').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_lesson", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_lesson/',
                    data : {
                      cos_id:cos_id,
                      status_user:'1',
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
            $('#myTable_lesson tbody').on( 'click', 'tr', function () {
                var d = table.row( this ).data();

                $('#modal-viewlesson').modal('show');
                update_lesson(d[3]);
            });
         }
        function fetch_data_quiz(cos_id='')
         {
            $('#myTable_quiz').DataTable().destroy();
            var table = $('#myTable_quiz').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_quiz", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_quiz/',
                    data : {
                      cos_id:cos_id,
                      status_user:'1',
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });
            $('#myTable_quiz tbody').on( 'click', 'tr', function () {
                var d = table.row( this ).data();

                $('#modal-viewquiz').modal('show');
                update_quiz(d[4]);
            });
         }
        function fetch_data_survey(cos_id='')
         {
            $('#myTable_cos_id_survey').DataTable().destroy();
            var table = $('#myTable_cos_id_survey').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_cos_id_survey", message);
            }).DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/course/fetch_course_survey/',
                    data : {
                      cos_id:cos_id,
                      status_user:'1',
                      lang: "<?php echo $lang; ?>"
                    },
                    type : 'GET'
                },
            });

            $('#myTable_cos_id_survey tbody').on( 'click', 'tr', function () {
                var d = table.row( this ).data();

                $('#modal-viewquestionnaire').modal('show');
                update_questionnaire(d[3]);
            });
         }
    </script>
                <script type="text/javascript">
                  <?php if($is_Enroll!=1){ ?>
                    document.getElementById('div_course_description').style.display = "";
                    document.getElementById('div_course_detail').style.display = "none";
                  <?php }else{ ?>
                    document.getElementById('div_course_description').style.display = "none";
                    document.getElementById('div_course_detail').style.display = "";
                  <?php } ?>
                </script>