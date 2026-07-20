
    <script type="text/javascript">
        $.fn.dataTable.ext.errMode = "none";
        $('.select2').select2();
        $('.clockpicker').clockpicker({
            placement: 'top',
            align: 'left',
            autoclose: true,
            donetext: 'Done',
        }).find('input').change(function() {
        });
        $("#sv_userapprove").select2({
            maximumSelectionLength: 5,
            language: {
                // You can find all of the options in the language files provided in the
                // build. They all must be functions that return the string that should be
                // displayed.
                maximumSelected: function (e) {
                    var t = "<?php echo label('select_approver'); ?>";
                    return t.replace("_", e.maximum);
                }
            }
        });
           $('#export_button').click(function(){
                var com_id = $('#com_id_search').val();
                window.open('<?php echo base_url(); ?>exportdata/export_managesurvey/'+com_id);
            });
        function textarea_tinymce(id, disabledTexteditor = false){
            if ($("#"+id).length > 0) {
                tinymce.init({
                    setup: function (ed) {
                        ed.on('keyup', function (e) { 
                            var count = CountCharacters(id);
                            if(count>10000){
                              var plain_text = $(tinymce.get(id).getBody()).text();
                              $(tinymce.get(id).getBody()).html(plain_text.substring(0, 9999));
                            }
                        });
                    },
                    selector: "textarea#"+id,
                    theme: "modern",
                    height: 300,
                    plugins: [
                        "advlist autolink link image lists charmap hr anchor pagebreak",
                        "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                        "save table contextmenu directionality paste textcolor"
                    ],
                    content_style: disabledTexteditor ? "body { background-color: #f0f0f0 !important; color: #000 !important; }" : "",
                    toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor ",

                    images_upload_url : '<?=base_url()?>index.php/setting/upload_img_texteditor',
                    automatic_uploads : false,

                    images_upload_handler : function(blobInfo, success, failure) {
                      var xhr, formData;

                      xhr = new XMLHttpRequest();
                      xhr.withCredentials = false;
                      xhr.open('POST', '<?=base_url()?>index.php/setting/upload_img_texteditor');

                      xhr.onload = function() {
                        var json;

                        if (xhr.status != 200) {
                          if(xhr.status==400){
                            failure('Please use English filename');
                          }else{
                            failure('HTTP Error: ' + xhr.status);
                          }
                          return;
                        }

                        json = JSON.parse(xhr.responseText);

                        if (!json || typeof json.file_path != 'string') {
                          failure('Invalid JSON: ' + xhr.responseText);
                          return;
                        }

                        success(json.file_path);
                      };

                      formData = new FormData();
                      formData.append('file', blobInfo.blob(), blobInfo.filename());

                      xhr.send(formData);
                    },

                });
            }
        }
        function CountCharacters(id) {
            var body = tinymce.get(id).getBody();
            var content = tinymce.trim(body.innerText || body.textContent);

            return content.length;
        };
        fetch_data_main(0);
        function fetch_data_main(page_num=0)
        {
            $('#myTable').DataTable().destroy();
            var com_id = $('#com_id_search').val();
            var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable", message);
            }).DataTable({

            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/fetchdata/fetch_public_survey/',
                    type : 'GET',
                    data : {
                      com_id:com_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                "columns": [
                    { data: "buttonall" },
                    { data: "num" },
                    { data: "sv_lang_txt" },
                    { data: "sv_title" },
                    { data: "sv_createby" },
                    { data: "sv_period" },
                    { data: "sv_approve" },
                    { data: {
                        _:    "sv_approvedate.display",
                        sort: "sv_approvedate.timestamp"
                    } },
                    { data: "sv_approveby" },
                    { data: "status_cos" },
                    { data: {
                        _:    "sv_modifieddate.display",
                        sort: "sv_modifieddate.timestamp"
                    } }
                ],
                /*<?php if($btn_print=="1"){ ?>
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]
                        }
                    },
                    //'copy', 'excel', 'print'
                ],
                <?php } ?>*/
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
        }

        function fetch_data_detail(page_num=0)
        {
            $('#myTable_question').DataTable().destroy();
            var sv_id = $('#sv_id_question').val();
            var table = $('#myTable_question').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_question", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/fetchdata/fetch_public_survey_detail/',
                    type : 'GET',
                    data : {
                      sv_id:sv_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });
        }

        function fetch_data_user(page_num=0)
        {
            $('#myTable_listuser').DataTable().destroy();
            var sv_id = $('#sv_id_listuser').val();
            var table = $('#myTable_listuser').on('error.dt', function(e, settings, techNote, message) {
              notificationForDatatableError("myTable_listuser", message);
            }).DataTable({
            "language": {
              "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
              "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
              "sInfo":           "<?php echo label('sInfo'); ?>",
              "sInfoEmpty":      "<?php echo label('sInfoEmpty'); ?>",
              "decimal":        "",
              "emptyTable":     "<?php echo label('wg_datanotfound'); ?>",
              "infoPostFix":    "",
              "thousands":      ",",
              //"lengthMenu":     "แสดง _MENU_ รายการ",
              "lengthMenu":     "<?php echo label('lengthMenu'); ?>",
              "loadingRecords": "<?php echo label('loadingRecords'); ?>",
              "processing":     "<?php echo label('processing'); ?>",
              "search":         "<?php echo label('filter_bar'); ?>",
              "zeroRecords":    "<?php echo label('wg_datanotfound'); ?>",
              "paginate": {
                  "first":      "<?php echo label('firstpage'); ?>",
                  "last":       "<?php echo label('last'); ?>",
                  "next":       "<?php echo label('lrn_btn_next'); ?>",
                  "previous":   "<?php echo label('previous'); ?>"
                       },
            },
            "scrollX": true,
                "ajax": {
                    url : '<?=base_url()?>index.php/fetchdata/fetch_public_survey_listuser/',
                    type : 'GET',
                    data : {
                      sv_id:sv_id,
                      lang: "<?php echo $lang; ?>"
                    }
                },
                  "initComplete": function () {
                    setTimeout( function () {
                      var info = table.page.info();
                      var length = info.pages;
                      var page_current = info.page;
                      if((page_num+1)>length){
                        page_num = length-1;
                      }
                      table.page(page_num).draw(false);
                    }, 10 );
                  }
            });

           // Handle click on "Select all" control
           $('#chkcolall_view').on('click', function(){
              // Check/uncheck all checkboxes in the table
              var rows = table.rows({ 'search': 'applied' }).nodes();
              $('.chkall_row', rows).prop('checked', this.checked);
           });
        }
        $('.btn_sentmailmulti').click(function(){
          var values = $("input[name='selectuser[]']:checked")
              .map(function(){return $(this).val();}).get();
          var status_output = 0;
          if(values.length>0){
              $( document.body ).css( 'pointer-events', 'none' );
              for (var i = values.length - 1; i >= 0; i--) {
                svtc_id = values[i];
                if(svtc_id!=""){
                    $.ajax({
                        url:"<?=base_url()?>index.php/sendmail/sentmail_svuser_single",
                        method:"POST",
                        data:{svtc_id:svtc_id},
                        dataType:"json",
                        success:function(data)
                        {
                          if(data.status == "2"){
                            status_output++;
                          }
                        }
                    });
                }
              }
              $( document.body ).css( 'pointer-events', '' );
              swal(
                  '<?php echo label("sentmail_success"); ?>',
                  '',
                  'success'
              ).then(function () {
                  fetch_data_user(0);
              })
          }else{
                            swal({
                                title: '<?php echo label('sentmail_error'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                            })
          }
        });
        $('.btn_deletemulti').click(function(){
          var values = $("input[name='selectuser[]']:checked")
              .map(function(){return $(this).val();}).get();
          var status_output = 0;
          if(values.length>0){
              for (var i = values.length - 1; i >= 0; i--) {
                svtc_id = values[i];
                if(svtc_id!=""){
                    $.ajax({
                        url:"<?=base_url()?>index.php/manage/delete_svtc_data",
                        method:"POST",
                        data:{svtc_id:svtc_id},
                        dataType:"json",
                        success:function(data)
                        {
                          if(data.status == "2"){
                            status_output++;
                          }
                        }
                    });   
                }
              }    
            swal({
                title: '<?php echo label('wg_delete_msg'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                            swal(
                                '<?php echo label("com_msg_delete"); ?>',
                                '',
                                'success'
                            ).then(function () {
                                fetch_data_user(0);
                            })
              }
            });
          }else{
                            swal({
                                title: '<?php echo label('wg_datanotfound'); ?>',
                                text: "",
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonClass: 'btn btn-primary',
                                confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                            })
          }
        });


        $('select[name="com_id_search"]').on('change', function(){
          var com_id = $(this).val();
          fetch_data_main(0);
          $('#com_id').val(com_id);
        });
        function changedate(value){
            var res_date = value.split("/");
            <?php if($lang=="thai"){ ?>
            return (parseInt(res_date[2])-543)+"-"+res_date[1]+"-"+res_date[0];
            <?php }else{ ?>
            return (parseInt(res_date[2]))+"-"+res_date[1]+"-"+res_date[0];
            <?php } ?>
        }
        function clear_dropify(id){
                    var imagenUrl = "";
                    var drEvent = $('#'+id).dropify(
                    {
                      defaultFile: imagenUrl
                    });
                    drEvent = drEvent.data('dropify');
                    drEvent.resetPreview();
                    drEvent.clearElement();
                    drEvent.settings.defaultFile = imagenUrl;
                    drEvent.destroy();
                    drEvent.init();
        }

        function caldate(id){
            var val_change = changedate($('#'+id).val());  
            $('#'+id+'_var').val(val_change);
        }
        $('#add_button').click(function(){
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("sv_btn_survey_create"); ?>');
                $('#survey_form')[0].reset();
                $('#operation').val("Add");
                clear_dropify('sv_cover');
                var com_id = $('#com_id_search').val();
                $('#com_id').val(com_id);
                /*<?php if($lang=="thai"){ ?>
                  chkbox_lang('sv_lang_th','input_th');
                <?php }else if($lang=="english"){ ?>
                  chkbox_lang('sv_lang_eng','input_eng');
                <?php }else{ ?>
                  chkbox_lang('sv_lang_jp','input_jp');
                <?php } ?>*/
                $('.input_th').hide();
                $('.input_eng').hide();
                $('.input_jp').hide();
                $.ajax({
                  url:"<?=base_url()?>index.php/querydata/user_approve",
                  method:"POST",
                  data:{sv_id:'',com_id:com_id},
                  success:function(data)
                  {
                      $('#sv_userapprove').html(data);
                  }
                });

              document.getElementById('sv_title_th').required = false;
              document.getElementById('sv_title_eng').required = false;
              document.getElementById('sv_title_jp').required = false;

            document.getElementById("sv_explanation_th").required = false;
            document.getElementById("sv_detail_th").required = false;
            document.getElementById("sv_explanation_eng").required = false;
            document.getElementById("sv_detail_eng").required = false;
            document.getElementById("sv_explanation_jp").required = false;
            document.getElementById("sv_detail_jp").required = false;
                $("input[name='sv_lang[]']:checked").each(function ()
                {
                  if($(this).val()=="th"){
                    chkbox_lang('sv_lang_th','input_th');
                  }else if($(this).val()=="eng"){
                    chkbox_lang('sv_lang_eng','input_eng');
                  }else if($(this).val()=="jp"){
                    chkbox_lang('sv_lang_jp','input_jp');
                  }
                });
                $(function () {

                  from = $('#survey_open').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#survey_end').datepicker('setStartDate', selected.date);
                      $("#survey_end").datepicker( "setDate", selected.date);
                      /*$('#date_end_les').val('');
                      $('#date_start_les').datepicker("update", selected.date);
                           to = $('#date_end_les').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#date_start_les').datepicker('setEndDate', maxDate);
                              });*/
                  });
                  /*from = $('#survey_open').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  }).on('changeDate', function (selected) {
                      $('#survey_end').val('');
                      $('#survey_open').datepicker("update", selected.date);
                           to = $('#survey_end').datepicker({
                                  language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                  thaiyear: true,  
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#survey_open').datepicker('setEndDate', maxDate);
                              });
                  });*/
                   to = $('#survey_end').datepicker({
                                  <?php if($lang=="thai"){ ?>
                                        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                        thaiyear: true,  
                                  <?php } ?>
                          format: 'dd/mm/yyyy',
                          autoclose: true
                  })
                    var startDate = new Date();
                    $('#survey_open').datepicker('setStartDate', startDate);
                    $('#survey_end').datepicker('setStartDate', startDate);
                   /*.on('changeDate', function (selected) {
                      $('#survey_end').datepicker("update", selected.date);
                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                          var res_date = date_val.split("-");
                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                          $('#survey_open').datepicker('setEndDate', maxDate);
                      });*/
                })
        });

        $(document).on('submit', '#survey_form', function(event){
              event.preventDefault(); 

                var value = $('#sv_expire_noti').val();
                if(value!=""){
                  var res = parseInt(value.charAt(value.length-1)); 
                  if(!Number.isInteger(res)){
                          $('#sv_expire_noti').val(remove_character(value, value.length-1));
                  }
                }
                var survey_open_var = $('#survey_open_var').val();
                var time_start = $('#time_start_survey').val();
                if(time_start==""){
                  time_start = "00:00";
                }
                var survey_end_var = $('#survey_end_var').val();
                var time_end = $('#time_end_survey').val();
                if(time_end==""){
                  time_end = "00:00";
                }
                var val_chk = 1;
                var survey_open = $('#survey_open').val();
                var survey_end = $('#survey_end').val();
                if(survey_open!=""||survey_end!=""){
                  if(survey_open!=""&&survey_end!=""){
                    survey_open_var = survey_open_var+" "+time_start+":00";
                    survey_end_var = survey_end_var+" "+time_end+":00";
                    start_date = survey_open_var.replace(/-/g, "/");
                    end_date = survey_end_var.replace(/-/g, "/");
                    var d_start = new Date(start_date);
                    var d_end = new Date(end_date);
                    if(survey_open_var==survey_end_var){
                      $('#time_end_survey').focus();
                      val_chk = 0;
                    }else if(d_start>d_end){
                      $('#survey_end').val("");
                      $('#survey_end_var').val("");
                      $('#survey_end').focus();
                      val_chk = 0;
                    }
                  }else if(survey_open==""&&survey_end!=""){
                    $('#survey_open').focus();
                    val_chk = 0;
                  }else if(survey_open!=""&&survey_end==""){
                    $('#survey_end').focus();
                    val_chk = 0;
                  }
                }

                var varchk=1;
                var sv_cover = $('#sv_cover').val();
                var fileExtension = ['jpg','png','gif'];
                if(sv_cover!=""){
                  if($.inArray($('#sv_cover').val().split('.').pop().toLowerCase(), fileExtension) == -1){
                          varchk = 3;
                                              swal({
                                                  title: '<?php echo label("media_type_dontmatch"); ?>',
                                                  text: "",
                                                  type: 'warning',
                                                  showCancelButton: false,
                                                  confirmButtonClass: 'btn btn-primary',
                                                  confirmButtonText: '<?php echo label("m_ok"); ?>'
                                              })
                  }
                }
                if(val_chk==1&&varchk==1){
                  $("#myModal_process").modal('show');
                  $( document.body ).css( 'pointer-events', 'none' );
                      $.ajax({
                        url:"<?=base_url()?>index.php/insertdata/insert_sv_main",
                        method:'POST',
                        data:new FormData(this),
                        contentType:false,
                        processData:false,
                        dataType:"json",
                        xhr: function() {
                          /*document.getElementById("progress_publicsurvey_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                      $('#txt_progress_publicsurvey').text(percentComplete.toFixed(2) + '%');

                                       $('.progress-bar-publicsurvey').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;*/
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                  var percentComplete = (evt.loaded / evt.total) * 100;
                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(0),
                                            fill: {
                                            color: '#ffa500'
                                          }
                                        }

                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                                  }
                             }, false);
                             return xhr;
                        },
                        success:function(data)
                        {
                          /*document.getElementById("progress_publicsurvey_div").style.display = "none";
                          $( document.body ).css( 'pointer-events', '' );*/
                        $( document.body ).css( 'pointer-events', '' );
                        $('#myModal_process').modal('hide');
                        $("#myModal_process").removeClass("in");
                        $("#myModal_process").css("display","none");
                          if(data.status=="2"){
                              $('#survey_form')[0].reset();
                              $('#modal-default').modal('hide');
                              swal(
                                  '<?php echo label("com_msg_success"); ?>!',
                                  '',
                                  'success'
                              ).then(function () {
                                            var table = $('#myTable').DataTable();
                                            var info = table.page.info();
                                            var length = info.pages;
                                            var page_current = info.page;
                                            fetch_data_main(page_current);
                              })
                          }else if(data.status=="1"){
                              swal({
                                  title: '<?php echo label("surveyname_duplicate"); ?>',
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: false,
                                  confirmButtonClass: 'btn btn-primary',
                                  confirmButtonText: '<?php echo label("lrn_btn_ok"); ?>'
                              }).then(function () {
                                $("body").addClass("modal-open");
                              })
                          }else{
                              swal({
                                  title: '<?php echo label("com_msg_error_save"); ?>',
                                  text: "",
                                  type: 'warning',
                                  showCancelButton: false,
                                  confirmButtonClass: 'btn btn-primary',
                                  confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                              })
                          }
                         
                        }
                      });
                }else{
                         
                        $( document.body ).css( 'pointer-events', '' );
                        $('#myModal_process').modal('hide');
                        $("#myModal_process").removeClass("in");
                        $("#myModal_process").css("display","none");
                        topFunction();
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                }
            });
        $(document).on('click', '.update', function(){
            var sv_id = $(this).attr("id");
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("sv_btn_survey_edit"); ?>');
                $('#survey_form')[0].reset();
                $('#operation').val("Edit");
                clear_dropify('sv_cover');
                $('#sv_id').val(sv_id);

                $('.input_th').hide();
                $('.input_eng').hide();
                $('.input_jp').hide();
              document.getElementById('sv_title_th').required = false;
              document.getElementById('sv_explanation_th').required = false;
              document.getElementById('sv_title_eng').required = false;
              document.getElementById('sv_explanation_eng').required = false;
              document.getElementById('sv_title_jp').required = false;
              document.getElementById('sv_explanation_jp').required = false;
                        $(function () {

                          from = $('#survey_open').datepicker({
                                          <?php if($lang=="thai"){ ?>
                                                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                                thaiyear: true,  
                                          <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).on('changeDate', function (selected) {
                              $('#survey_end').datepicker('setStartDate', selected.date);
                              $("#survey_end").datepicker( "setDate", selected.date);
                              /*$('#date_end_les').val('');
                              $('#date_start_les').datepicker("update", selected.date);
                                   to = $('#date_end_les').datepicker({
                                          <?php if($lang=="thai"){ ?>
                                                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                                thaiyear: true,  
                                          <?php } ?>
                                          format: 'dd/mm/yyyy',
                                          autoclose: true
                                  }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                                          var res_date = date_val.split("-");
                                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                          $('#date_start_les').datepicker('setEndDate', maxDate);
                                      });*/
                          });
                          /*from = $('#survey_open').datepicker({
                                  language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                  thaiyear: true,  
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          }).on('changeDate', function (selected) {
                              $('#survey_end').val('');
                              $('#survey_open').datepicker("update", selected.date);
                                   to = $('#survey_end').datepicker({
                                          language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                          thaiyear: true,  
                                          format: 'dd/mm/yyyy',
                                          autoclose: true
                                  }).datepicker('setStartDate', selected.date).focus().on('changeDate', function (selected) {
                                          var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                          var date_val = moment(maxDate).format('YYYY-MM-DD');
                                          var res_date = date_val.split("-");
                                          maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                          $('#survey_open').datepicker('setEndDate', maxDate);
                                      });
                          });*/
                           to = $('#survey_end').datepicker({
                                          <?php if($lang=="thai"){ ?>
                                                language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
                                                thaiyear: true,  
                                          <?php } ?>
                                  format: 'dd/mm/yyyy',
                                  autoclose: true
                          });
                           /*.on('changeDate', function (selected) {
                              $('#survey_end').datepicker("update", selected.date);
                                  var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {timeZone: "Asia/Bangkok"});
                                  var date_val = moment(maxDate).format('YYYY-MM-DD');
                                  var res_date = date_val.split("-");
                                  maxDate = res_date[2]+"/"+res_date[1]+"/"+(parseInt(res_date[0]));
                                  $('#survey_open').datepicker('setEndDate', maxDate);
                              });*/
                        })

            document.getElementById("sv_explanation_th").required = false;
            document.getElementById("sv_detail_th").required = false;
            document.getElementById("sv_explanation_eng").required = false;
            document.getElementById("sv_detail_eng").required = false;
            document.getElementById("sv_explanation_jp").required = false;
            document.getElementById("sv_detail_jp").required = false;
            $.ajax({
                  url:"<?=base_url()?>index.php/querydata/update_survey_data",
                  method:"POST",
                  data:{sv_id:sv_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('#com_id').val(data.com_id);
                        /*if(data.sv_suggestion_status=="0"){
                            document.getElementById("sv_suggestion_status").checked = false;
                        }else{
                            document.getElementById("sv_suggestion_status").checked = true;
                        }*/

                        $.ajax({
                          url:"<?=base_url()?>index.php/querydata/user_approve",
                          method:"POST",
                          data:{sv_id:sv_id,com_id:data.com_id},
                          success:function(data_userapprove)
                          {
                              $('#sv_userapprove').html(data_userapprove);
                          }
                        });
                        if(data.isTH=="1"){
                            document.getElementById("sv_lang_th").checked = true;
                            chkbox_lang('sv_lang_th','input_th');
                            $('#sv_title_th').val(data.sv_title_th);
                                    $('#sv_explanation_th').val(data.sv_explanation_th);
                                    $('#sv_detail_th').val(data.sv_detail_th);
                            textarea_tinymce('sv_explanation_th');
                            textarea_tinymce('sv_detail_th');
                            $(tinymce.get('sv_explanation_th').getBody()).html(data.sv_explanation_th);
                            $(tinymce.get('sv_detail_th').getBody()).html(data.sv_detail_th);
                        }else{
                            document.getElementById("sv_lang_th").checked = false;
                            $('.input_th').hide();
                        }
                        if(data.isENG=="1"){
                            document.getElementById("sv_lang_eng").checked = true;
                            chkbox_lang('sv_lang_eng','input_eng');
                                    $('#sv_explanation_eng').val(data.sv_explanation_eng);
                                    $('#sv_detail_eng').val(data.sv_detail_eng);
                            $('#sv_title_eng').val(data.sv_title_eng);
                            textarea_tinymce('sv_explanation_eng');
                            textarea_tinymce('sv_detail_eng');
                            $(tinymce.get('sv_explanation_eng').getBody()).html(data.sv_explanation_eng);
                            $(tinymce.get('sv_detail_eng').getBody()).html(data.sv_detail_eng);
                        }else{
                            document.getElementById("sv_lang_eng").checked = false;
                            $('.input_eng').hide();
                        }
                        if(data.isJP=="1"){
                            document.getElementById("sv_lang_jp").checked = true;
                            chkbox_lang('sv_lang_jp','input_jp');
                            $('#sv_title_jp').val(data.sv_title_jp);
                                    $('#sv_explanation_jp').val(data.sv_explanation_jp);
                                    $('#sv_detail_jp').val(data.sv_detail_jp);
                            textarea_tinymce('sv_explanation_jp');
                            textarea_tinymce('sv_detail_jp');
                            $(tinymce.get('sv_explanation_jp').getBody()).html(data.sv_explanation_jp);
                            $(tinymce.get('sv_detail_jp').getBody()).html(data.sv_detail_jp);
                        }else{
                            document.getElementById("sv_lang_jp").checked = false;
                            $('.input_jp').hide();
                        }

                        if(data.sv_status=="0"){
                            document.getElementById("sv_status").checked = false;
                        }else{
                            document.getElementById("sv_status").checked = true;
                        }

                        if(data.sv_isHeader=="0"){
                            document.getElementById("sv_isHeader").checked = false;
                        }else{
                            document.getElementById("sv_isHeader").checked = true;
                        }
                        if(data.sv_type=="2"){
                            document.getElementById("sv_type").checked = false;
                        }else{
                            document.getElementById("sv_type").checked = true;
                        }

                        //$('#sv_lang').val(data.sv_lang).trigger('change');
                        $('#survey_open_var').val(data.sv_open_var);
                        $('#survey_end_var').val(data.sv_end_var);
                        $('#time_start_survey').val(data.time_start);
                        $('#time_end_survey').val(data.time_end);
                        $('#sv_expire_noti').val(data.sv_expire_noti);

                        if(data.sv_open!="0000-00-00 00:00:00"&&data.sv_end!="0000-00-00 00:00:00"){
                          
                          $('#survey_open').datepicker('setStartDate', data.sv_open);
                          $('#survey_end').datepicker('setStartDate', data.sv_open);
                          $("#survey_open").datepicker("update", data.sv_open); 
                          $("#survey_end").datepicker("update", data.sv_end); 
                        }else{      
                              var startDate = new Date();
                          $('#survey_open').datepicker('setStartDate',startDate);
                          $('#survey_end').datepicker('setStartDate',startDate);
                          $('#survey_open').val('');
                          $('#survey_end').val('');
                        }
                        if(data.sv_cover!=""){
                            var nameImage = "<?php echo REAL_PATH;?>/uploads/publicsv/"+data.sv_cover
                            var drEvent = $('#sv_cover').dropify(
                            {
                              defaultFile: nameImage
                            });
                            drEvent = drEvent.data('dropify');
                            drEvent.resetPreview();
                            drEvent.clearElement();
                            drEvent.settings.defaultFile = nameImage;
                            drEvent.destroy();
                            drEvent.init();

                            var drEvent = $('.dropify_main').dropify({
                                defaultFile: "<?php echo REAL_PATH;?>/uploads/publicsv/"+data.cos_pic ,
                            });

                            drEvent.on('dropify.beforeClear', function(event, element){
                                    $('#sv_cover_ori').val("");
                                    return true; 
                            });
                        }else{
                            $('.dropify_main').dropify();
                        }
                        $('#sv_cover_ori').val(data.sv_cover);
                  }
            });
        });

          function createButton(text,classs,style,id, cb) {
            return $(' <button class="'+classs+'" style="'+style+'" id="'+id+'">' + text + '</button>').on('click', cb);
          }
          
          $(document).on('click', '.btnrefresh', function(e) {
              e.preventDefault();
              location.reload();
          });
          
          $(document).on('click', '.approve', function(e){
            var sv_id = $(this).attr("id");

            $.ajax({
                  url:"<?=base_url()?>index.php/querydata/rechk_survey_period",
                  method:"POST",
                  data:{sv_id:sv_id},
                  dataType:"json",
                  success:function(data)
                  {
                    var title_val = '';
                    if(data.isApprove=="1"){
                      title_val = "<?php echo label('approve_is'); ?>";
                      var buttons = $('<div>')
                      .append(createButton('<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>','btn btn-flat btnapprove_psv','background-color:#1abc9c;',sv_id, function() {
                      })).append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject','background-color:#DD6B55;',sv_id, function() {
                         swal.close();
                      })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                         swal.close();
                      }));
                    }else{
                      title_val = "<?php echo label('cantapprove_is'); ?>";
                      var buttons = $('<div>').append(createButton('<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>','btn btn-flat btnreject','background-color:#DD6B55;',sv_id, function() {
                         swal.close();
                      })).append(createButton('<?php echo label('cancel'); ?>','btn btn-flat btnrefresh','','', function() {
                         swal.close();
                      }));
                    }
                    e.preventDefault();
                    swal({
                      title: title_val,
                      html: buttons,
                      type: "warning",
                      showConfirmButton: false,
                      showCancelButton: false
                    });
                  }
            });
          });

          $(document).on('click', '.btnapprove_psv', function(e) {
              e.preventDefault();
              var sv_id = $(this).attr("id");
              $("#myModal_process").modal('show');
              $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    xhr: function() {
                      //document.getElementById("progress_div").style.display = "";
                          var xhr = new window.XMLHttpRequest();
                          xhr.upload.addEventListener("progress", function(evt) {
                              if (evt.lengthComputable) {
                                  var percentComplete = (evt.loaded / evt.total) * 100;
                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(0),
                                            fill: {
                                            color: '#ffa500'
                                          }
                                        }
                                        console.log(percentComplete.toFixed(0));
                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                              }
                         }, false);
                         return xhr;
                    },
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      $('#myModal_process').modal('hide');
                        
                      $("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                          location.reload();
                                 /*     var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        }).then(function () {
                          location.reload();
                                 /*     var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        }).then(function () {
                          location.reload();
                                 /*     var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);*/
                        })
                      }
                    }
                });
          });

        /* $(document).on('click', '.approve', function(){
            var sv_id = $(this).attr("id");
            swal({
                title: '<?php echo label('approve_is'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#16a085",   
                confirmButtonText: '<i class="mdi mdi-check"></i> <?php echo label('d_approve'); ?>',
                cancelButtonText: '<i class="mdi mdi-window-close"></i> <?php echo label('cancel'); ?>',
                footer: '<button type="button" class="btn btn-info btn-block btnreject" style="background-color:#DD6B55;" id="'+sv_id+'"><i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?></button>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/approve_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("approve_msg_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });*/

          $(document).on('click', '.btnreject', function(e) {
              e.preventDefault();
              var sv_id = $(this).attr("id");
              swal({
                title: '<i class="mdi mdi-close-octagon"></i> <?php echo label("d_reject"); ?>',
                text: "",
                input: 'text',
                showCancelButton: true,
                confirmButtonColor: "#1abc9c",   
                cancelButtonColor: "#DD6B55",     
                confirmButtonText: '<?php echo label('sv_btn_save'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>',
                inputPlaceholder: "<?php echo label('preNote'); ?>: ",
                inputValidator: (value) => {
                  if (!value) {
                    // หากไม่กรอกข้อมูล
                    return '<?php echo label("pls_enter_reason"); ?>';
                  }
                }
              }).then(function (isChk) {
                  if(isChk.value){
                    $("#myModal_process").modal({backdrop: false});
                    $.ajax({
                      url:"<?=base_url()?>index.php/querydata/reject_publicsurvey",
                      method:"POST",
                      data:{sv_id:sv_id,sva_note:isChk.value},
                      dataType:"json",
                      success:function(data)
                      {
                        if(data.status == "2"){
                          location.reload();
                        }
                      }
                    });
                  }
               /* if (inputValue === "") {
                  swal.showInputError("You need to write something!");
                  return false
                }else{
                swal("Nice!", "You wrote: " + inputValue, "success");
                }*/
              });
          });

         $(document).on('click', '.delete', function(){
            var sv_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_sv_data",
                    method:"POST",
                    data:{id_delete:sv_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });


         $(document).on('click', '.delete_question', function(){
            var svde_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_sv_question_data",
                    method:"POST",
                    data:{id_delete:svde_id},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable_question').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_detail(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });
        function onSpecify(){
            var svde_isSpecify = document.getElementById('svde_isSpecify');
            var sv_id = $('#sv_id_question').val();
            if(svde_isSpecify.checked){
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                              type: 'POST',
                              data:{sv_id:sv_id,les_lang:''},
                              dataType:"json",
                              success: function(data_lang){
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    document.getElementById("svde_specify_name_th").required = true;
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    document.getElementById("svde_specify_name_eng").required = true;
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    document.getElementById("svde_specify_name_jp").required = true;
                                  }
                                }
                              }
                        });
                document.getElementById('div_svde_isSpecify').style.display = "";
            }else{
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                              type: 'POST',
                              data:{sv_id:sv_id,les_lang:''},
                              dataType:"json",
                              success: function(data_lang){
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    document.getElementById("svde_specify_name_th").required = false;
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    document.getElementById("svde_specify_name_eng").required = false;
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    document.getElementById("svde_specify_name_jp").required = false;
                                  }
                                }
                              }
                        });
                document.getElementById('div_svde_isSpecify').style.display = "none";
            }
        }
        $(document).on('click', '.question', function(){
            var sv_id = $(this).attr("id");
            $("#modal-question").modal({backdrop: false});
            $('.modal-titleques').html('<i class="mdi mdi-comment-question-outline"></i> <?php echo label("question"); ?>');
            $('#sv_id_question').val(sv_id);
            $('#sv_id_question_create').val(sv_id);
            
            document.getElementById('div_question').style.display = "";
            document.getElementById('div_add_question').style.display = "none";
            fetch_data_detail(0);
        });
        $(document).on('click', '.add_button_question', function(){
            $('#quiz_name_txt').html('<i class="mdi mdi-plus"></i> <?php echo label("create_question"); ?>');
            var sv_id = $('#sv_id_question').val();
            $('#question_form')[0].reset();
            $('#sv_id_question_create').val(sv_id);
            $('#operation_question').val('Add');
            val_lang('0','input_svde_th','svde_name_','th');
            val_lang('0','input_svde_eng','svde_name_','eng');
            val_lang('0','input_svde_jp','svde_name_','jp');
            document.getElementById("svde_header_th").required = false;
            document.getElementById("svde_header_eng").required = false;
            document.getElementById("svde_header_jp").required = false;
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                              type: 'POST',
                              data:{sv_id:sv_id,les_lang:''},
                              dataType:"json",
                              success: function(data_lang){
                                if(data_lang.sv_isHeader=="1"){
                                  $('.svde_header').show();
                                }else{
                                  $('.svde_header').hide();
                                }
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    val_lang('1','input_svde_th','svde_name_','th');
                                    textarea_tinymce('svde_name_th');
                                    textarea_tinymce('svde_info_th');
                                    $(tinymce.get('svde_name_th').getBody()).html('');
                                    $(tinymce.get('svde_info_th').getBody()).html('');
                                    if(data_lang.sv_isHeader=="1"){
                                      document.getElementById("svde_header_th").required = true;
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    val_lang('1','input_svde_eng','svde_name_','eng');
                                    textarea_tinymce('svde_name_eng');
                                    textarea_tinymce('svde_info_eng');
                                    $(tinymce.get('svde_name_eng').getBody()).html('');
                                    $(tinymce.get('svde_info_eng').getBody()).html('');
                                    if(data_lang.sv_isHeader=="1"){
                                      document.getElementById("svde_header_eng").required = true;
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    val_lang('1','input_svde_jp','svde_name_','jp');
                                    textarea_tinymce('svde_name_jp');
                                    textarea_tinymce('svde_info_jp');
                                    $(tinymce.get('svde_name_jp').getBody()).html('');
                                    $(tinymce.get('svde_info_jp').getBody()).html('');
                                    if(data_lang.sv_isHeader=="1"){
                                      document.getElementById("svde_header_jp").required = true;
                                    }
                                  }
                                }
                              }
                        });
            document.getElementById("svde_specify_name_th").required = false;
            document.getElementById("svde_specify_name_eng").required = false;
            document.getElementById("svde_specify_name_jp").required = false;
            document.getElementById('div_question').style.display = "none";
            document.getElementById('div_add_question').style.display = "";
            document.getElementById('div_import_question').style.display = "none";
            document.getElementById('div_question_mul').style.display = 'none';
        });

        $(document).on('click', '.import_button_question', function(){
            var sv_id = $('#sv_id_question').val();
            $('#question_import_form')[0].reset();
            $('#sv_id_question_import').val(sv_id);
            $('#operation_import_question').val('Add');
            $('#result_import_question').html('');
            clear_dropify('file_import_question');

            document.getElementById('div_question').style.display = "none";
            document.getElementById('div_add_question').style.display = "none";
            document.getElementById('div_import_question').style.display = "";
            document.getElementById('div_question_mul').style.display = 'none';
        });

        $(document).on('submit', '#question_import_form', function(event){
              event.preventDefault();
                /*$("#myModal_process").addClass("in");
                $("body").addClass("modal-open");
                $("#myModal_process").css("display","block");
                $("#myModal_process").modal({backdrop: false});*/
                $( document.body ).css( 'pointer-events', 'none' );
              var sv_id = $('#sv_id_question_import').val();
              var file_import = $('#file_import_question').val();
              if(file_import!=""){
                $("#myModal_process").modal('show');
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/import_question_survey",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  dataType:"json",
                        xhr: function() {
                          /*document.getElementById("progress_publicsurveyimport_div").style.display = "";
                              var xhr = new window.XMLHttpRequest();
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                      $('#txt_progress_publicsurveyimport').text(percentComplete.toFixed(2) + '%');

                                       $('.progress-bar-publicsurveyimport').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;*/
                              var xhr = new window.XMLHttpRequest();
                                console.log(xhr);
                              xhr.upload.addEventListener("progress", function(evt) {
                                  if (evt.lengthComputable) {
                                      var percentComplete = (evt.loaded / evt.total) * 100;

                                        var progressBarOptions = {
                                          startAngle: -1.55,
                                          size: 200,
                                            value: percentComplete.toFixed(2),
                                            fill: {
                                            color: '#ffa500'
                                          }
                                        }

                                        $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                          $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                        });

                                        $('#circle-b').circleProgress({
                                          value : percentComplete.toFixed(0),
                                          fill: {
                                            color: '#FF0000'
                                          }
                                        });
                                      /*$(".progress").each(function() {

                                        //var value = $(this).attr('data-value');
                                        var left = $(this).find('.progress-left .progress-bar');
                                        var right = $(this).find('.progress-right .progress-bar');
                                        console.log(percentComplete.toFixed(2));
                                        if (percentComplete.toFixed(2) > 0) {
                                          if (percentComplete.toFixed(2) <= 50) {
                                            right.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2)) + 'deg)')
                                          } else {
                                            right.css('transform', 'rotate(180deg)')
                                            left.css('transform', 'rotate(' + percentageToDegrees(percentComplete.toFixed(2) - 50) + 'deg)')
                                          }
                                        }
                                        $('#txt_progress_cosmain').text(percentComplete.toFixed(2) + '%');
                                      })*/
                                      /*
                                      console.log(percentComplete.toFixed(2));
                                      //$('.progress-bar-cosmain').data('percentage', percentComplete);
                                       $('.progress-bar-cosmain').animate({
                                        width: percentComplete + '%'
                                       }, {
                                        duration: 100
                                       });*/
                                      //Do something with upload progress here
                                  }
                             }, false);
                             return xhr;
                        },
                  success:function(data)
                  {/*
                    document.getElementById("progress_publicsurveyimport_div").style.display = "none";
                    $( document.body ).css( 'pointer-events', '' );*/
                    $( document.body ).css( 'pointer-events', '' );
                      $('#myModal_process').modal('hide');
                      $("#myModal_process").removeClass("in");
                      $("#myModal_process").css("display","none");
                      var body = document.body;
                      body.classList.add("modal-open");
                    topFunction();
                    if(data.status=="2"){
                        $('#question_import_form')[0].reset();
                        swal(
                            '<?php echo label("after_upload_file"); ?>',
                            ''
                        ).then(function () {
                            $('#result_import_question').html(data.result);
                            $('#sv_id_question_import').val(sv_id);
                            clear_dropify('file_import_question');
                            fetch_data_detail(0);
                        })
                    }else if(data.status=="1"){
                        swal({
                            title: '<?php echo label("manageimport_msgerror"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            document.getElementById("file_import_question").focus();
                        })
                    }else{
                        swal({
                            title: '<?php echo label("com_msg_error_save"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        })
                    }
                  },
                      error: function (jqXHR, exception) {
                    document.getElementById("progress_publicsurveyimport_div").style.display = "none";
                    $( document.body ).css( 'pointer-events', '' );
                          topFunction();
                          var msg = '';
                          if (jqXHR.status === 0) {
                              msg = 'Not connect.\n Verify Network.';
                          } else if (jqXHR.status == 404) {
                              msg = 'Requested page not found. [404]';
                          } else if (jqXHR.status == 500) {
                              msg = 'Internal Server Error [500].';
                          } else if (exception === 'parsererror') {
                              msg = 'Requested JSON parse failed.';
                          } else if (exception === 'timeout') {
                              msg = 'Time out error.';
                          } else if (exception === 'abort') {
                              msg = 'Ajax request aborted.';
                          } else {
                              msg = 'Uncaught Error.\n' + jqXHR.responseText;
                          }
                          swal({
                              title: msg,
                              text: "",
                              type: 'warning',
                              showCancelButton: false,
                              confirmButtonClass: 'btn btn-primary',
                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                          })
                      },
                });
              }else{

                        swal({
                            title: '<?php echo label("manageimport_msgerror"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
                            document.getElementById("file_import_question").focus();
                        })
              }
         });

        $(document).on('click', '.publicsv', function(){
            var sv_id = $(this).attr("id");

            swal({
                title: '<?php echo label('isPublic_sv'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#16a085",   
                confirmButtonText: '<i class="mdi mdi-check"></i> <?php echo label('m_ok'); ?>',
                cancelButtonText: '<i class="mdi mdi-window-close"></i> <?php echo label('cancel'); ?>',
            }).then(function (isChk) {
              if(isChk.value){
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/public_survey_data",
                    method:"POST",
                    data:{sv_id:sv_id},
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_success"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_main(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
        });
        $(document).on('click', '.update_question', function(){
            var svde_id = $(this).attr("id");
            $('#svde_id').val(svde_id);
            $('#operation_question').val('Edit');
            textarea_tinymce('svde_name');
            textarea_tinymce('svde_info');
            document.getElementById('div_question').style.display = "none";
            document.getElementById('div_add_question').style.display = "";
            $('#quiz_name_txt').html('<i class="mdi mdi-lead-pencil"></i> <?php echo label("edit_question"); ?>');

            val_lang('0','input_svde_th','svde_name_','th');
            val_lang('0','input_svde_eng','svde_name_','eng');
            val_lang('0','input_svde_jp','svde_name_','jp');
            val_lang('0','input_svdedetail_th','','th');
            val_lang('0','input_svdedetail_eng','','eng');
            val_lang('0','input_svdedetail_jp','','jp');
            textarea_tinymce('svde_name_th');
            textarea_tinymce('svde_info_th');
            textarea_tinymce('svde_name_eng');
            textarea_tinymce('svde_info_eng');
            textarea_tinymce('svde_name_jp');
            textarea_tinymce('svde_info_jp');

            document.getElementById("svde_header_th").required = false;
            document.getElementById("svde_header_eng").required = false;
            document.getElementById("svde_header_jp").required = false;
            $.ajax({
                url:"<?=base_url()?>index.php/querydata/update_sdve_question_detail_data",
                method:"POST",
                data:{svde_id:svde_id},
                dataType:"json",
                success:function(data)
                {
                        $.ajax({
                              url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                              type: 'POST',
                              data:{sv_id:data.sv_id,les_lang:''},
                              dataType:"json",
                              success: function(data_lang){
                                if(data_lang.sv_isHeader=="1"){
                                  $('.svde_header').show();
                                }else{
                                  $('.svde_header').hide();
                                }
                                for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                  if(data_lang.arr_lang[i]=="th"){
                                    val_lang('1','input_svde_th','svde_name_','th');
                                    $('#svde_name_th').val(data.svde_name_th);
                                    $('#svde_info_th').val(data.svde_info_th);
                                    textarea_tinymce('svde_name_th');
                                    textarea_tinymce('svde_info_th');
                                    $(tinymce.get('svde_name_th').getBody()).html(data.svde_name_th);
                                    $(tinymce.get('svde_info_th').getBody()).html(data.svde_info_th);
                                    $('#svde_specify_name_th').val(data.svde_specify_name_th);
                                    if(data_lang.sv_isHeader=="1"){
                                        $('#svde_header_th').val(data.svde_header_th);
                                        document.getElementById("svde_header_th").required = true;
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="eng"){
                                    val_lang('1','input_svde_eng','svde_name_','eng');
                                    $('#svde_name_eng').val(data.svde_name_eng);
                                    $('#svde_info_eng').val(data.svde_info_eng);
                                    textarea_tinymce('svde_name_eng');
                                    textarea_tinymce('svde_info_eng');
                                    $(tinymce.get('svde_name_eng').getBody()).html(data.svde_name_eng);
                                    $(tinymce.get('svde_info_eng').getBody()).html(data.svde_info_eng);
                                    $('#svde_specify_name_eng').val(data.svde_specify_name_eng);
                                    if(data_lang.sv_isHeader=="1"){
                                        $('#svde_header_eng').val(data.svde_header_eng);
                                        document.getElementById("svde_header_eng").required = true;
                                    }
                                  }
                                  if(data_lang.arr_lang[i]=="jp"){
                                    val_lang('1','input_svde_jp','svde_name_','jp');
                                    $('#svde_name_jp').val(data.svde_name_jp);
                                    $('#svde_info_jp').val(data.svde_info_jp);
                                    textarea_tinymce('svde_name_jp');
                                    textarea_tinymce('svde_info_jp');
                                    $(tinymce.get('svde_name_jp').getBody()).html(data.svde_name_jp);
                                    $(tinymce.get('svde_info_jp').getBody()).html(data.svde_info_jp);
                                    $('#svde_specify_name_jp').val(data.svde_specify_name_jp);
                                    if(data_lang.sv_isHeader=="1"){
                                        $('#svde_header_jp').val(data.svde_header_jp);
                                        document.getElementById("svde_header_jp").required = true;
                                    }
                                  }
                                }

                              }
                        });
                        $('#svde_type').val(data.svde_type);

                        if(data.svde_status=="0"){
                            document.getElementById("svde_status").checked = false;
                        }else{
                            document.getElementById("svde_status").checked = true;
                        }
                        if(data.svde_isMultichoice=="0"){
                            document.getElementById("svde_isMultichoice").checked = false;
                        }else{
                            document.getElementById("svde_isMultichoice").checked = true;
                        }


                        if(data.svde_type=='multi'||data.svde_type=='2choice'){
                            if(data.svde_type=='multi'){
                                $('.isMultichoice').show();
                            }else{
                                $('.isMultichoice').hide();
                            }
                            document.getElementById('div_question_mul').style.display = '';

                            if(data.svde_type=='multi'){
                                  $('.multiple_choice').show();
                                  if(data.svde_isSpecify=="1"){
                                      document.getElementById("svde_isSpecify").checked = true;
                                      document.getElementById('div_svde_isSpecify').style.display = "";
                                      
                                      $.ajax({
                                            url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                            type: 'POST',
                                            data:{sv_id:data.sv_id,les_lang:''},
                                            dataType:"json",
                                            success: function(data_lang){
                                              for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                                if(data_lang.arr_lang[i]=="th"){
                                                  document.getElementById("svde_specify_name_th").required = true;
                                                }
                                                if(data_lang.arr_lang[i]=="eng"){
                                                  document.getElementById("svde_specify_name_eng").required = true;
                                                }
                                                if(data_lang.arr_lang[i]=="jp"){
                                                  document.getElementById("svde_specify_name_jp").required = true;
                                                }
                                              }
                                            }
                                      });
                                  }else{
                                      document.getElementById("svde_isSpecify").checked = false;
                                      document.getElementById('div_svde_isSpecify').style.display = "none";
                                      
                                      $.ajax({
                                            url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                            type: 'POST',
                                            data:{sv_id:data.sv_id,les_lang:''},
                                            dataType:"json",
                                            success: function(data_lang){
                                              for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                                if(data_lang.arr_lang[i]=="th"){
                                                  document.getElementById("svde_specify_name_th").required = false;
                                                }
                                                if(data_lang.arr_lang[i]=="eng"){
                                                  document.getElementById("svde_specify_name_eng").required = false;
                                                }
                                                if(data_lang.arr_lang[i]=="jp"){
                                                  document.getElementById("svde_specify_name_jp").required = false;
                                                }
                                              }
                                            }
                                      });
                                  }
                                }else{
                                  $('.multiple_choice').hide();
                                      document.getElementById("svde_isSpecify").checked = false;
                                      document.getElementById('div_svde_isSpecify').style.display = "none";
                                      
                                      $.ajax({
                                            url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                            type: 'POST',
                                            data:{sv_id:data.sv_id,les_lang:''},
                                            dataType:"json",
                                            success: function(data_lang){
                                              for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                                if(data_lang.arr_lang[i]=="th"){
                                                  document.getElementById("svde_specify_name_th").required = false;
                                                }
                                                if(data_lang.arr_lang[i]=="eng"){
                                                  document.getElementById("svde_specify_name_eng").required = false;
                                                }
                                                if(data_lang.arr_lang[i]=="jp"){
                                                  document.getElementById("svde_specify_name_jp").required = false;
                                                }
                                              }
                                            }
                                      });
                                }

                            $('.mul_c1').hide();
                            $('.mul_c2').hide();
                            $('.mul_c3').hide();
                            $('.mul_c4').hide();
                            $('.mul_c5').hide();
                            $('.mul_c6').hide();
                            $('.mul_c7').hide();
                            $('.mul_c8').hide();
                            $('.mul_c9').hide();
                            $('.mul_c10').hide();
                            $('.mul_c11').hide();
                            $('.mul_c12').hide();
                            $('.mul_c13').hide();
                            $('.mul_c14').hide();
                            $('.mul_c15').hide();
                            if(data.svde_type=='multi'){
                                $('.mul_c1').show();
                                $('.mul_c2').show();
                                $('.mul_c3').show();
                                $('.mul_c4').show();
                                $('.mul_c5').show();
                                $('.mul_c6').show();
                                $('.mul_c7').show();
                                $('.mul_c8').show();
                                $('.mul_c9').show();
                                $('.mul_c10').show();
                                $('.mul_c11').show();
                                $('.mul_c12').show();
                                $('.mul_c13').show();
                                $('.mul_c14').show();
                                $('.mul_c15').show();
                                $.ajax({
                                    url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                    type: 'POST',
                                    data:{sv_id:data.sv_id,les_lang:''},
                                    dataType:"json",
                                    success: function(data_lang){
                                      for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                        if(data_lang.arr_lang[i]=="th"){
                                          val_lang('1','input_svdedetail_th','','th');
                                          textarea_tinymce('mul_c1_th');
                                          textarea_tinymce('mul_c2_th');
                                          textarea_tinymce('mul_c3_th');
                                          textarea_tinymce('mul_c4_th');
                                          textarea_tinymce('mul_c5_th');
                                          textarea_tinymce('mul_c6_th');
                                          textarea_tinymce('mul_c7_th');
                                          textarea_tinymce('mul_c8_th');
                                          textarea_tinymce('mul_c9_th');
                                          textarea_tinymce('mul_c10_th');
                                          textarea_tinymce('mul_c11_th');
                                          textarea_tinymce('mul_c12_th');
                                          textarea_tinymce('mul_c13_th');
                                          textarea_tinymce('mul_c14_th');
                                          textarea_tinymce('mul_c15_th');
                                          $(tinymce.get('mul_c1_th').getBody()).html(data.multi['mul_c1_th']);
                                          $(tinymce.get('mul_c2_th').getBody()).html(data.multi['mul_c2_th']);
                                          $(tinymce.get('mul_c3_th').getBody()).html(data.multi['mul_c3_th']);
                                          $(tinymce.get('mul_c4_th').getBody()).html(data.multi['mul_c4_th']);
                                          $(tinymce.get('mul_c5_th').getBody()).html(data.multi['mul_c5_th']);
                                          $(tinymce.get('mul_c6_th').getBody()).html(data.multi['mul_c6_th']);
                                          $(tinymce.get('mul_c7_th').getBody()).html(data.multi['mul_c7_th']);
                                          $(tinymce.get('mul_c8_th').getBody()).html(data.multi['mul_c8_th']);
                                          $(tinymce.get('mul_c9_th').getBody()).html(data.multi['mul_c9_th']);
                                          $(tinymce.get('mul_c10_th').getBody()).html(data.multi['mul_c10_th']);
                                          $(tinymce.get('mul_c11_th').getBody()).html(data.multi['mul_c11_th']);
                                          $(tinymce.get('mul_c12_th').getBody()).html(data.multi['mul_c12_th']);
                                          $(tinymce.get('mul_c13_th').getBody()).html(data.multi['mul_c13_th']);
                                          $(tinymce.get('mul_c14_th').getBody()).html(data.multi['mul_c14_th']);
                                          $(tinymce.get('mul_c15_th').getBody()).html(data.multi['mul_c15_th']);
                                          $('#mul_c1_th').val(data.multi['mul_c1_th']);
                                          $('#mul_c2_th').val(data.multi['mul_c2_th']);
                                          $('#mul_c3_th').val(data.multi['mul_c3_th']);
                                          $('#mul_c4_th').val(data.multi['mul_c4_th']);
                                          $('#mul_c5_th').val(data.multi['mul_c5_th']);
                                          $('#mul_c6_th').val(data.multi['mul_c6_th']);
                                          $('#mul_c7_th').val(data.multi['mul_c7_th']);
                                          $('#mul_c8_th').val(data.multi['mul_c8_th']);
                                          $('#mul_c9_th').val(data.multi['mul_c9_th']);
                                          $('#mul_c10_th').val(data.multi['mul_c10_th']);
                                          $('#mul_c11_th').val(data.multi['mul_c11_th']);
                                          $('#mul_c12_th').val(data.multi['mul_c12_th']);
                                          $('#mul_c13_th').val(data.multi['mul_c13_th']);
                                          $('#mul_c14_th').val(data.multi['mul_c14_th']);
                                          $('#mul_c15_th').val(data.multi['mul_c15_th']);
                                        }
                                        if(data_lang.arr_lang[i]=="eng"){
                                          val_lang('1','input_svdedetail_eng','','eng');
                                          textarea_tinymce('mul_c1_eng');
                                          textarea_tinymce('mul_c2_eng');
                                          textarea_tinymce('mul_c3_eng');
                                          textarea_tinymce('mul_c4_eng');
                                          textarea_tinymce('mul_c5_eng');
                                          textarea_tinymce('mul_c6_eng');
                                          textarea_tinymce('mul_c7_eng');
                                          textarea_tinymce('mul_c8_eng');
                                          textarea_tinymce('mul_c9_eng');
                                          textarea_tinymce('mul_c10_eng');
                                          textarea_tinymce('mul_c11_eng');
                                          textarea_tinymce('mul_c12_eng');
                                          textarea_tinymce('mul_c13_eng');
                                          textarea_tinymce('mul_c14_eng');
                                          textarea_tinymce('mul_c15_eng');
                                          $(tinymce.get('mul_c1_eng').getBody()).html(data.multi['mul_c1_eng']);
                                          $(tinymce.get('mul_c2_eng').getBody()).html(data.multi['mul_c2_eng']);
                                          $(tinymce.get('mul_c3_eng').getBody()).html(data.multi['mul_c3_eng']);
                                          $(tinymce.get('mul_c4_eng').getBody()).html(data.multi['mul_c4_eng']);
                                          $(tinymce.get('mul_c5_eng').getBody()).html(data.multi['mul_c5_eng']);
                                          $(tinymce.get('mul_c6_eng').getBody()).html(data.multi['mul_c6_eng']);
                                          $(tinymce.get('mul_c7_eng').getBody()).html(data.multi['mul_c7_eng']);
                                          $(tinymce.get('mul_c8_eng').getBody()).html(data.multi['mul_c8_eng']);
                                          $(tinymce.get('mul_c9_eng').getBody()).html(data.multi['mul_c9_eng']);
                                          $(tinymce.get('mul_c10_eng').getBody()).html(data.multi['mul_c10_eng']);
                                          $(tinymce.get('mul_c11_eng').getBody()).html(data.multi['mul_c11_eng']);
                                          $(tinymce.get('mul_c12_eng').getBody()).html(data.multi['mul_c12_eng']);
                                          $(tinymce.get('mul_c13_eng').getBody()).html(data.multi['mul_c13_eng']);
                                          $(tinymce.get('mul_c14_eng').getBody()).html(data.multi['mul_c14_eng']);
                                          $(tinymce.get('mul_c15_eng').getBody()).html(data.multi['mul_c15_eng']);
                                          $('#mul_c1_eng').val(data.multi['mul_c1_eng']);
                                          $('#mul_c2_eng').val(data.multi['mul_c2_eng']);
                                          $('#mul_c3_eng').val(data.multi['mul_c3_eng']);
                                          $('#mul_c4_eng').val(data.multi['mul_c4_eng']);
                                          $('#mul_c5_eng').val(data.multi['mul_c5_eng']);
                                          $('#mul_c6_eng').val(data.multi['mul_c6_eng']);
                                          $('#mul_c7_eng').val(data.multi['mul_c7_eng']);
                                          $('#mul_c8_eng').val(data.multi['mul_c8_eng']);
                                          $('#mul_c9_eng').val(data.multi['mul_c9_eng']);
                                          $('#mul_c10_eng').val(data.multi['mul_c10_eng']);
                                          $('#mul_c11_eng').val(data.multi['mul_c11_eng']);
                                          $('#mul_c12_eng').val(data.multi['mul_c12_eng']);
                                          $('#mul_c13_eng').val(data.multi['mul_c13_eng']);
                                          $('#mul_c14_eng').val(data.multi['mul_c14_eng']);
                                          $('#mul_c15_eng').val(data.multi['mul_c15_eng']);
                                        }
                                        if(data_lang.arr_lang[i]=="jp"){
                                          val_lang('1','input_svdedetail_jp','','jp');
                                          textarea_tinymce('mul_c1_jp');
                                          textarea_tinymce('mul_c2_jp');
                                          textarea_tinymce('mul_c3_jp');
                                          textarea_tinymce('mul_c4_jp');
                                          textarea_tinymce('mul_c5_jp');
                                          textarea_tinymce('mul_c6_jp');
                                          textarea_tinymce('mul_c7_jp');
                                          textarea_tinymce('mul_c8_jp');
                                          textarea_tinymce('mul_c9_jp');
                                          textarea_tinymce('mul_c10_jp');
                                          textarea_tinymce('mul_c11_jp');
                                          textarea_tinymce('mul_c12_jp');
                                          textarea_tinymce('mul_c13_jp');
                                          textarea_tinymce('mul_c14_jp');
                                          textarea_tinymce('mul_c15_jp');
                                          $(tinymce.get('mul_c1_jp').getBody()).html(data.multi['mul_c1_jp']);
                                          $(tinymce.get('mul_c2_jp').getBody()).html(data.multi['mul_c2_jp']);
                                          $(tinymce.get('mul_c3_jp').getBody()).html(data.multi['mul_c3_jp']);
                                          $(tinymce.get('mul_c4_jp').getBody()).html(data.multi['mul_c4_jp']);
                                          $(tinymce.get('mul_c5_jp').getBody()).html(data.multi['mul_c5_jp']);
                                          $(tinymce.get('mul_c6_jp').getBody()).html(data.multi['mul_c6_jp']);
                                          $(tinymce.get('mul_c7_jp').getBody()).html(data.multi['mul_c7_jp']);
                                          $(tinymce.get('mul_c8_jp').getBody()).html(data.multi['mul_c8_jp']);
                                          $(tinymce.get('mul_c9_jp').getBody()).html(data.multi['mul_c9_jp']);
                                          $(tinymce.get('mul_c10_jp').getBody()).html(data.multi['mul_c10_jp']);
                                          $(tinymce.get('mul_c11_jp').getBody()).html(data.multi['mul_c11_jp']);
                                          $(tinymce.get('mul_c12_jp').getBody()).html(data.multi['mul_c12_jp']);
                                          $(tinymce.get('mul_c13_jp').getBody()).html(data.multi['mul_c13_jp']);
                                          $(tinymce.get('mul_c14_jp').getBody()).html(data.multi['mul_c14_jp']);
                                          $(tinymce.get('mul_c15_jp').getBody()).html(data.multi['mul_c15_jp']);
                                          $('#mul_c1_jp').val(data.multi['mul_c1_jp']);
                                          $('#mul_c2_jp').val(data.multi['mul_c2_jp']);
                                          $('#mul_c3_jp').val(data.multi['mul_c3_jp']);
                                          $('#mul_c4_jp').val(data.multi['mul_c4_jp']);
                                          $('#mul_c5_jp').val(data.multi['mul_c5_jp']);
                                          $('#mul_c6_jp').val(data.multi['mul_c6_jp']);
                                          $('#mul_c7_jp').val(data.multi['mul_c7_jp']);
                                          $('#mul_c8_jp').val(data.multi['mul_c8_jp']);
                                          $('#mul_c9_jp').val(data.multi['mul_c9_jp']);
                                          $('#mul_c10_jp').val(data.multi['mul_c10_jp']);
                                          $('#mul_c11_jp').val(data.multi['mul_c11_jp']);
                                          $('#mul_c12_jp').val(data.multi['mul_c12_jp']);
                                          $('#mul_c13_jp').val(data.multi['mul_c13_jp']);
                                          $('#mul_c14_jp').val(data.multi['mul_c14_jp']);
                                          $('#mul_c15_jp').val(data.multi['mul_c15_jp']);
                                        }
                                      }
                                    }
                              });
                            }else{
                                $('.mul_c1').show();
                                $('.mul_c2').show();
                               
                              $.ajax({
                                    url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                    type: 'POST',
                                    data:{sv_id:data.sv_id,les_lang:''},
                                    dataType:"json",
                                    success: function(data_lang){
                                      for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                        if(data_lang.arr_lang[i]=="th"){
                                          val_lang('1','input_svdedetail_th','','th');
                                          textarea_tinymce('mul_c1_th');
                                          textarea_tinymce('mul_c2_th');
                                          $(tinymce.get('mul_c1_th').getBody()).html(data.multi['mul_c1_th']);
                                          $(tinymce.get('mul_c2_th').getBody()).html(data.multi['mul_c2_th']);
                                          $('#mul_c1_th').val(data.multi['mul_c1_th']);
                                          $('#mul_c2_th').val(data.multi['mul_c2_th']);
                                        }
                                        if(data_lang.arr_lang[i]=="eng"){
                                          val_lang('1','input_svdedetail_eng','','eng');
                                          textarea_tinymce('mul_c1_eng');
                                          textarea_tinymce('mul_c2_eng');
                                          $(tinymce.get('mul_c1_eng').getBody()).html(data.multi['mul_c1_eng']);
                                          $(tinymce.get('mul_c2_eng').getBody()).html(data.multi['mul_c2_eng']);
                                          $('#mul_c1_eng').val(data.multi['mul_c1_eng']);
                                          $('#mul_c2_eng').val(data.multi['mul_c2_eng']);
                                        }
                                        if(data_lang.arr_lang[i]=="jp"){
                                          val_lang('1','input_svdedetail_jp','','jp');
                                          textarea_tinymce('mul_c1_jp');
                                          textarea_tinymce('mul_c2_jp');
                                          $(tinymce.get('mul_c1_jp').getBody()).html(data.multi['mul_c1_jp']);
                                          $(tinymce.get('mul_c2_jp').getBody()).html(data.multi['mul_c2_jp']);
                                          $('#mul_c1_jp').val(data.multi['mul_c1_jp']);
                                          $('#mul_c2_jp').val(data.multi['mul_c2_jp']);
                                        }
                                      }
                                    }
                              });
                            }
                        }else{
                            document.getElementById("svde_specify_name_th").required = false;
                            document.getElementById("svde_specify_name_eng").required = false;
                            document.getElementById("svde_specify_name_jp").required = false;
                            document.getElementById('div_question_mul').style.display = 'none';
                        }
                }
            });
        });

        $('select[name="svde_type"]').on('change', function(){
          var svde_type = $(this).val();
          var sv_id = $('#sv_id_question').val();
                            $('.mul_c1').hide();
                            $('.mul_c2').hide();
                            $('.mul_c3').hide();
                            $('.mul_c4').hide();
                            $('.mul_c5').hide();
                            $('.mul_c6').hide();
                            $('.mul_c7').hide();
                            $('.mul_c8').hide();
                            $('.mul_c9').hide();
                            $('.mul_c10').hide();
                            $('.mul_c11').hide();
                            $('.mul_c12').hide();
                            $('.mul_c13').hide();
                            $('.mul_c14').hide();
                            $('.mul_c15').hide();
          val_lang('0','input_svdedetail_th','','th');
          val_lang('0','input_svdedetail_eng','','eng');
          val_lang('0','input_svdedetail_jp','','jp');
          document.getElementById('div_svde_isSpecify').style.display = "";
            $('.multiple_choice').hide();
          if(svde_type=='multi'){ 
                                $('.mul_c1').show();
                                $('.mul_c2').show();
                                $('.mul_c3').show();
                                $('.mul_c4').show();
                                $('.mul_c5').show();
                                $('.mul_c6').show();
                                $('.mul_c7').show();
                                $('.mul_c8').show();
                                $('.mul_c9').show();
                                $('.mul_c10').show();
                                $('.mul_c11').show();
                                $('.mul_c12').show();
                                $('.mul_c13').show();
                                $('.mul_c14').show();
                                $('.mul_c15').show();

            $('.isMultichoice').show();
                                $.ajax({
                                    url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                    type: 'POST',
                                    data:{sv_id:sv_id,les_lang:''},
                                    dataType:"json",
                                    success: function(data_lang){
                                      for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                        if(data_lang.arr_lang[i]=="th"){
                                          val_lang('1','input_svdedetail_th','','th');
                                          textarea_tinymce('mul_c1_th');
                                          textarea_tinymce('mul_c2_th');
                                          textarea_tinymce('mul_c3_th');
                                          textarea_tinymce('mul_c4_th');
                                          textarea_tinymce('mul_c5_th');
                                          textarea_tinymce('mul_c6_th');
                                          textarea_tinymce('mul_c7_th');
                                          textarea_tinymce('mul_c8_th');
                                          textarea_tinymce('mul_c9_th');
                                          textarea_tinymce('mul_c10_th');
                                          textarea_tinymce('mul_c11_th');
                                          textarea_tinymce('mul_c12_th');
                                          textarea_tinymce('mul_c13_th');
                                          textarea_tinymce('mul_c14_th');
                                          textarea_tinymce('mul_c15_th');
                                          $(tinymce.get('mul_c1_th').getBody()).html('');
                                          $(tinymce.get('mul_c2_th').getBody()).html('');
                                          $(tinymce.get('mul_c3_th').getBody()).html('');
                                          $(tinymce.get('mul_c4_th').getBody()).html('');
                                          $(tinymce.get('mul_c5_th').getBody()).html('');
                                          $(tinymce.get('mul_c6_th').getBody()).html('');
                                          $(tinymce.get('mul_c7_th').getBody()).html('');
                                          $(tinymce.get('mul_c8_th').getBody()).html('');
                                          $(tinymce.get('mul_c9_th').getBody()).html('');
                                          $(tinymce.get('mul_c10_th').getBody()).html('');
                                          $(tinymce.get('mul_c11_th').getBody()).html('');
                                          $(tinymce.get('mul_c12_th').getBody()).html('');
                                          $(tinymce.get('mul_c13_th').getBody()).html('');
                                          $(tinymce.get('mul_c14_th').getBody()).html('');
                                          $(tinymce.get('mul_c15_th').getBody()).html('');
                                        }
                                        if(data_lang.arr_lang[i]=="eng"){
                                          val_lang('1','input_svdedetail_eng','','eng');
                                          textarea_tinymce('mul_c1_eng');
                                          textarea_tinymce('mul_c2_eng');
                                          textarea_tinymce('mul_c3_eng');
                                          textarea_tinymce('mul_c4_eng');
                                          textarea_tinymce('mul_c5_eng');
                                          textarea_tinymce('mul_c6_eng');
                                          textarea_tinymce('mul_c7_eng');
                                          textarea_tinymce('mul_c8_eng');
                                          textarea_tinymce('mul_c9_eng');
                                          textarea_tinymce('mul_c10_eng');
                                          textarea_tinymce('mul_c11_eng');
                                          textarea_tinymce('mul_c12_eng');
                                          textarea_tinymce('mul_c13_eng');
                                          textarea_tinymce('mul_c14_eng');
                                          textarea_tinymce('mul_c15_eng');
                                          $(tinymce.get('mul_c1_eng').getBody()).html('');
                                          $(tinymce.get('mul_c2_eng').getBody()).html('');
                                          $(tinymce.get('mul_c3_eng').getBody()).html('');
                                          $(tinymce.get('mul_c4_eng').getBody()).html('');
                                          $(tinymce.get('mul_c5_eng').getBody()).html('');
                                          $(tinymce.get('mul_c6_eng').getBody()).html('');
                                          $(tinymce.get('mul_c7_eng').getBody()).html('');
                                          $(tinymce.get('mul_c8_eng').getBody()).html('');
                                          $(tinymce.get('mul_c9_eng').getBody()).html('');
                                          $(tinymce.get('mul_c10_eng').getBody()).html('');
                                          $(tinymce.get('mul_c11_eng').getBody()).html('');
                                          $(tinymce.get('mul_c12_eng').getBody()).html('');
                                          $(tinymce.get('mul_c13_eng').getBody()).html('');
                                          $(tinymce.get('mul_c14_eng').getBody()).html('');
                                          $(tinymce.get('mul_c15_eng').getBody()).html('');
                                        }
                                        if(data_lang.arr_lang[i]=="jp"){
                                          val_lang('1','input_svdedetail_jp','','jp');
                                          textarea_tinymce('mul_c1_jp');
                                          textarea_tinymce('mul_c2_jp');
                                          textarea_tinymce('mul_c3_jp');
                                          textarea_tinymce('mul_c4_jp');
                                          textarea_tinymce('mul_c5_jp');
                                          textarea_tinymce('mul_c6_jp');
                                          textarea_tinymce('mul_c7_jp');
                                          textarea_tinymce('mul_c8_jp');
                                          textarea_tinymce('mul_c9_jp');
                                          textarea_tinymce('mul_c10_jp');
                                          textarea_tinymce('mul_c11_jp');
                                          textarea_tinymce('mul_c12_jp');
                                          textarea_tinymce('mul_c13_jp');
                                          textarea_tinymce('mul_c14_jp');
                                          textarea_tinymce('mul_c15_jp');
                                          $(tinymce.get('mul_c1_jp').getBody()).html('');
                                          $(tinymce.get('mul_c2_jp').getBody()).html('');
                                          $(tinymce.get('mul_c3_jp').getBody()).html('');
                                          $(tinymce.get('mul_c4_jp').getBody()).html('');
                                          $(tinymce.get('mul_c5_jp').getBody()).html('');
                                          $(tinymce.get('mul_c6_jp').getBody()).html('');
                                          $(tinymce.get('mul_c7_jp').getBody()).html('');
                                          $(tinymce.get('mul_c8_jp').getBody()).html('');
                                          $(tinymce.get('mul_c9_jp').getBody()).html('');
                                          $(tinymce.get('mul_c10_jp').getBody()).html('');
                                          $(tinymce.get('mul_c11_jp').getBody()).html('');
                                          $(tinymce.get('mul_c12_jp').getBody()).html('');
                                          $(tinymce.get('mul_c13_jp').getBody()).html('');
                                          $(tinymce.get('mul_c14_jp').getBody()).html('');
                                          $(tinymce.get('mul_c15_jp').getBody()).html('');
                                        }
                                      }
                                     
                                    }
                              });
            $('.multiple_choice').show();
            document.getElementById('div_question_mul').style.display = '';
            onSpecify();
          }else if(svde_type=='2choice'){
                                $('.mul_c1').show();
                                $('.mul_c2').show();
            textarea_tinymce('mul_c1');
            textarea_tinymce('mul_c2');
            $('.isMultichoice').hide();
                                $.ajax({
                                    url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                    type: 'POST',
                                    data:{sv_id:sv_id,les_lang:''},
                                    dataType:"json",
                                    success: function(data_lang){
                                      for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                        if(data_lang.arr_lang[i]=="th"){
                                          val_lang('1','input_svdedetail_th','','th');
                                          textarea_tinymce('mul_c1_th');
                                          textarea_tinymce('mul_c2_th');
                                          $(tinymce.get('mul_c1_th').getBody()).html('');
                                          $(tinymce.get('mul_c2_th').getBody()).html('');
                                        }
                                        if(data_lang.arr_lang[i]=="eng"){
                                          val_lang('1','input_svdedetail_eng','','eng');
                                          textarea_tinymce('mul_c1_eng');
                                          textarea_tinymce('mul_c2_eng');
                                          $(tinymce.get('mul_c1_eng').getBody()).html('');
                                          $(tinymce.get('mul_c2_eng').getBody()).html('');
                                        }
                                        if(data_lang.arr_lang[i]=="jp"){
                                          val_lang('1','input_svdedetail_jp','','jp');
                                          textarea_tinymce('mul_c1_jp');
                                          textarea_tinymce('mul_c2_jp');
                                          $(tinymce.get('mul_c1_jp').getBody()).html('');
                                          $(tinymce.get('mul_c2_jp').getBody()).html('');
                                        }
                                      }
                                    }
                              });
                document.getElementById('div_question_mul').style.display = '';
                document.getElementById('div_svde_isSpecify').style.display = "none";
          }else{
                document.getElementById('div_svde_isSpecify').style.display = "none";
                document.getElementById('div_question_mul').style.display = 'none';
          }
        });
        $(document).on('click', '.previous_survey', function(){
            var sv_id = $('#sv_id_question').val();
            $('#sv_id_question_create').val(sv_id);
            
            document.getElementById('div_question').style.display = "";
            document.getElementById('div_add_question').style.display = "none";
            document.getElementById('div_import_question').style.display = "none";
            fetch_data_detail(0);
        });

        $(document).on('submit', '#question_form', function(event){
              event.preventDefault();
              var sv_id = $('#sv_id_question').val();
              var svde_type = $('#svde_type').val();
              var rechk_val = 1;
              var rechk_null = 0;
              var svde_isSpecify = document.getElementById('svde_isSpecify');
        
            // console.log(svde_isSpecify.checked);
              var form = $('#question_form')[0];
                                $.ajax({
                                    url: '<?=base_url()?>index.php/querydata/select_lang_survey',
                                    type: 'POST',
                                    data:{sv_id:sv_id,les_lang:''},
                                    dataType:"json",
                                    success: function(data_lang){
                                      // console.log(data_lang);
                                      for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                                        if(data_lang.arr_lang[i]=="th"){
                                          var svde_name_th = $('#svde_name_th').val();
                                          tinymce.get('svde_name_th').focus();
                                          if(svde_name_th==""){
                                            rechk_null++;
                                          }
                                          if(svde_type == "2choice"){
                                            var mul_c1_th = $('#mul_c1_th').val();
                                            var mul_c2_th = $('#mul_c2_th').val();
                                            if(mul_c1_th==""){
                                              rechk_null++;
                                            }
                                            if(mul_c2_th==""){
                                                rechk_null++;
                                            }
                                          }
                                         
                                          if(svde_type=="multi" && svde_isSpecify.checked == true){
                                            var mul_c1_th = $('#mul_c1_th').val();
                                            if(mul_c1_th==""){
                                              rechk_null++;
                                            }
                                          }else{
                                            var mul_c1_th = $('#mul_c1_th').val();
                                            var mul_c2_th = $('#mul_c2_th').val();
                                            if(mul_c1_th==""){
                                              rechk_null++;
                                            }
                                            if(mul_c2_th==""){
                                                rechk_null++;
                                            }
                                          }
                                          if(svde_type == "sa" || svde_type=="sub" || svde_type =="scale"){
                                            rechk_null = 0
                                          }
                                        }
                                        if(data_lang.arr_lang[i]=="eng"){
                                          var svde_name_eng = $('#svde_name_eng').val();
                                          tinymce.get('svde_name_eng').focus();
                                          if(svde_name_eng==""){
                                              rechk_null++;
                                          }
                                          if(svde_type == "2choice"){
                                            var mul_c1_eng = $('#mul_c1_eng').val();
                                            var mul_c2_eng = $('#mul_c2_eng').val();
                                            if(mul_c1_eng==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_eng==""){
                                                rechk_null++;
                                            }
                                          }
   
                                          if(svde_type=="multi" && svde_isSpecify.checked == true){
                                            var mul_c1_eng = $('#mul_c1_eng').val();
                                            if(mul_c1_eng==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_eng==""){
                                                rechk_null++;
                                            }
                                          }else{
                                            var mul_c1_eng = $('#mul_c1_eng').val();
                                            var mul_c2_eng = $('#mul_c2_eng').val();
                                            if(mul_c1_eng==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_eng==""){
                                                rechk_null++;
                                            }
                                          }
                                          if(svde_type == "sa" || svde_type=="sub" || svde_type =="scale"){
                                            rechk_null = 0
                                          }
                                        }
                                        if(data_lang.arr_lang[i]=="jp"){
                                          var svde_name_jp = $('#svde_name_jp').val();
                                          tinymce.get('svde_name_jp').focus();
                                          if(svde_name_jp==""){
                                              rechk_null++;
                                          }
                                          if(svde_type == "2choice"){
                                            var mul_c1_jp = $('#mul_c1_jp').val();
                                            var mul_c2_jp = $('#mul_c2_jp').val();
                                            if(mul_c1_jp==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_jp==""){
                                                rechk_null++;
                                            }
                                          }
                                         
                                          if(svde_type=="multi" && svde_isSpecify.checked == true){
                                            var mul_c1_jp = $('#mul_c1_jp').val();
                                            // var mul_c2_jp = $('#mul_c2_jp').val();
                                            if(mul_c1_jp==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_jp==""){
                                                rechk_null++;
                                            }
                                          }else{
                                            var mul_c1_jp = $('#mul_c1_jp').val();
                                            var mul_c2_jp = $('#mul_c2_jp').val();
                                            if(mul_c1_jp==""){
                                                rechk_null++;
                                            }
                                            if(mul_c2_jp==""){
                                                rechk_null++;
                                            }
                                          }
                                          if(svde_type == "sa" || svde_type=="sub" || svde_type =="scale"){
                                            rechk_null = 0
                                          }
                                        }
                                      }
                                     
                                      // console.log(rechk_null);
                                      // console.log(svde_type);
                                        if(rechk_null > 0 ){
                                            rechk_val = 0;
                                        }else{
                                          rechk_val = 1;
                                        }
                                        
                                       
                                        if(rechk_val==1){
                                                $("#myModal_process").modal('show');
                                                  $( document.body ).css( 'pointer-events', 'none' );
                                                     $.ajax({
                                                        url:"<?=base_url()?>index.php/insertdata/insert_question_survey",
                                                        method:'POST',
                                                        data:new FormData(form),
                                                        contentType:false,
                                                        processData:false,
                                                        dataType:"json",
                                                        xhr: function() {/*
                                                          document.getElementById("progress_publicsurveyquestion_div").style.display = "";
                                                              var xhr = new window.XMLHttpRequest();
                                                              xhr.upload.addEventListener("progress", function(evt) {
                                                                  if (evt.lengthComputable) {
                                                                      var percentComplete = (evt.loaded / evt.total) * 100;
                                                                      $('#txt_progress_publicsurveyquestion').text(percentComplete.toFixed(2) + '%');

                                                                       $('.progress-bar-publicsurveyquestion').animate({
                                                                        width: percentComplete + '%'
                                                                       }, {
                                                                        duration: 100
                                                                       });
                                                                      //Do something with upload progress here
                                                                  }
                                                             }, false);
                                                             return xhr;*/
                          //document.getElementById("progress_cosmain_div").style.display = "";
                                                            var xhr = new window.XMLHttpRequest();
                                                            xhr.upload.addEventListener("progress", function(evt) {
                                                                if (evt.lengthComputable) {
                                                                    var percentComplete = (evt.loaded / evt.total) * 100;

                                                                      var progressBarOptions = {
                                                                        startAngle: -1.55,
                                                                        size: 200,
                                                                          value: percentComplete.toFixed(2),
                                                                          fill: {
                                                                          color: '#ffa500'
                                                                        }
                                                                      }

                                                                      $('.circle').circleProgress(progressBarOptions).on('circle-animation-progress', function(event, progress, stepValue) {
                                                                        $(this).find('strong').html("LOADING...<br/>"+percentComplete.toFixed(0)+"%");
                                                                      });

                                                                      $('#circle-b').circleProgress({
                                                                        value : percentComplete.toFixed(0),
                                                                        fill: {
                                                                          color: '#FF0000'
                                                                        }
                                                                      });

                                                                }
                                                           }, false);
                                                           return xhr;
                                                        },
                                                        success:function(data)
                                                        {
                                                          $( document.body ).css( 'pointer-events', '' );
                                                          $('#myModal_process').modal('hide');
                                                          $("#myModal_process").removeClass("in");
                                                          $("#myModal_process").css("display","none");
                                                          var body = document.body;

                                                          body.classList.add("modal-open");
                                                          /*$( document.body ).css( 'pointer-events', '' );
                                                          document.getElementById("progress_publicsurveyquestion_div").style.display = "none";*/
                                                          if(data.status=="2"){
                                                              swal(
                                                                  '<?php echo label("com_msg_success"); ?>!',
                                                                  '',
                                                                  'success'
                                                              ).then(function () {
                                                                  $('#question_form')[0].reset();
                                                                  $('#sv_id_question').val(sv_id);
                                                                  $('#sv_id_question_create').val(sv_id);
                                                                  
                                                                  document.getElementById('div_question').style.display = "";
                                                                  document.getElementById('div_add_question').style.display = "none";
                                                                  fetch_data_detail(0);
                                                                  fetch_data_main(0);
                                                              })
                                                          }else if(data.status=="1"){
                                                              swal({
                                                                  title: '<?php echo label("course_msg_duplicate"); ?>',
                                                                  text: "",
                                                                  type: 'warning',
                                                                  showCancelButton: false,
                                                                  confirmButtonClass: 'btn btn-primary',
                                                                  confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                                                              }).then(function () {
                                                                  $('#question_form')[0].reset();
                                                              })
                                                          }else{
                                                              swal({
                                                                  title: '<?php echo label("com_msg_error_save"); ?>',
                                                                  text: "",
                                                                  type: 'warning',
                                                                  showCancelButton: false,
                                                                  confirmButtonClass: 'btn btn-primary',
                                                                  confirmButtonText: '<?php echo label("sv_btn_save"); ?>'
                                                              })
                                                          }

                                                        }
                                                      });
                                        }else{
                                          $( document.body ).css( 'pointer-events', '' );
                                          $('#myModal_process').modal('hide');
                                          $("#myModal_process").removeClass("in");
                                          $("#myModal_process").css("display","none");
                                        
                                          swal({
                                              title: '<?php echo label("com_msg_form_error"); ?>',
                                              text: "",
                                              type: 'warning',
                                              showCancelButton: false,
                                              confirmButtonClass: 'btn btn-primary',
                                              confirmButtonText: '<?php echo label("m_ok"); ?>'
                                          }).then(function () {
                                                        //topFunction();
                                                    })
                                        }
                                     
                                    }
                              });
               
        });
        $(document).on('click', '.list_user', function(){
            var sv_id = $(this).attr("id");
            $("#modal-surveylistuser").modal({backdrop: false});
            $('#sv_id_listuser').val(sv_id);
            $.ajax({
                  url:"<?=base_url()?>index.php/querydata/update_survey_data",
                  method:"POST",
                  data:{sv_id:sv_id},
                  dataType:"json",
                  success:function(data)
                  {
                        $('.modal-titlelistuser').html('<i class="mdi mdi-format-list-bulleted"></i> <?php echo label("sv_btn_adduser"); ?>: '+data.sv_titlename);
                        //$('.modal-titlelistuser').html('<i class="mdi mdi-format-list-bulleted"></i> <?php echo label("list_userofsv"); ?>: '+data.sv_titlename);
                        if(data.sv_approve=="1"){
                            $('.btn_sentmailmulti').show();
                            document.getElementById('btnsendmail').style.display = "";
                        }else{
                            $('.btn_sentmailmulti').hide();
                            document.getElementById('btnsendmail').style.display = "none";
                        }
                  }
              });

            document.getElementById('div_listuser').style.display = "";
            document.getElementById('div_permission').style.display = "none";
            document.getElementById('div_adduser').style.display = "none";
            fetch_data_user(0);
        });
        $(document).on('click', '.demo_sv', function(){
            var sv_id = $(this).attr("id");
            window.location.href = "<?php echo REAL_PATH.'/survey/demo/'; ?>"+sv_id;
        });

         $(document).on('click', '.delete_user', function(){
            var svtc_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/delete_svtc_data",
                    method:"POST",
                    data:{svtc_id:svtc_id},
                    dataType:"json",
                    success:function(data)
                    {
                      if(data.status == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_user(0);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.reset_user', function(){
            var svtc_id = $(this).attr("id");
            swal({
                title: '<?php echo label('sv_isresetuser'); ?> ',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('reset'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                    url:"<?=base_url()?>index.php/manage/reset_svtc_data",
                    method:"POST",
                    data:{svtc_id:svtc_id},
                    dataType:"json",
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      if(data.status == "2"){
                        swal(
                            '<?php echo label("sv_reset_success"); ?> !',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_user(0);
                        })
                      }else if(data == "1"){
                         swal({
                            title: '<?php echo label("wg_msg_use"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }else{
                         swal({
                            title: '<?php echo label('com_msg_error_save'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            })
          });

         $(document).on('click', '.sendmail_user', function(){
            var svtc_id = $(this).attr("id");

            swal({
                title: '<?php echo label('sendmail_noti'); ?> ?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#27ae60",   
                confirmButtonText: '<?php echo label('m_ok'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $( document.body ).css( 'pointer-events', 'none' );
                $.ajax({
                    url:"<?=base_url()?>index.php/sendmail/sentmail_svuser_single",
                    method:"POST",
                    data:{svtc_id:svtc_id},
                    dataType:"json",
                    success:function(data)
                    {
                      $( document.body ).css( 'pointer-events', '' );
                      if(data.status == "2"){
                        swal(
                            '<?php echo label("sentmail_success"); ?>',
                            '',
                            'success'
                        ).then(function () {
                            fetch_data_user(0);
                        })
                      }else{
                         swal({
                            title: '<?php echo label('sentmail_error'); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('sv_btn_save'); ?>'
                        })
                      }
                    }
                });
              }
            });
          });
    </script>