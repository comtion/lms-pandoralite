<script>
    const langOfDataTable = {
        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
        "infoEmpty": "<?php echo label('wg_datanotfound'); ?>",
        "sInfo": "<?php echo label('sInfo'); ?>",
        "sInfoEmpty": "<?php echo label('sInfoEmpty'); ?>",
        "decimal": "",
        "emptyTable": "<?php echo label('wg_datanotfound'); ?>",
        "infoPostFix": "",
        "thousands": ",",
        //"lengthMenu":     "แสดง _MENU_ รายการ",
        "lengthMenu": "<?php echo label('lengthMenu'); ?>",
        "loadingRecords": "<?php echo label('loadingRecords'); ?>",
        "processing": "<?php echo label('processing'); ?>",
        "search": "<?php echo label('filter_bar'); ?>",
        "zeroRecords": "<?php echo label('wg_datanotfound'); ?>",
        "paginate": {
            "first": "<?php echo label('firstpage'); ?>",
            "last": "<?php echo label('last'); ?>",
            "next": "<?php echo label('lrn_btn_next'); ?>",
            "previous": "<?php echo label('previous'); ?>"
        },
    };

    function fetch_data_detail_survey(svId, page_num=0)
    {
        $('#myTable_question_survey').DataTable().destroy();
        var table = $('#myTable_question_survey').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_question_survey", message);
        }).DataTable({
            "language": langOfDataTable,
            "scrollX": true,
            "columnDefs": [
                { width: '5%', targets: 0 },
                { width: '10%', targets: 1 },
                { width: '10%', targets: 2 },
                { width: '35%', targets: 3 },
                { width: '25%', targets: 4 },
                { width: '15%', targets: 5 },
            ],
            "ajax": {
                url : '<?=base_url("fetchdata/fetch_public_survey_detail_view/")?>',
                type : 'GET',
                data : {
                    sv_id:  svId,
                    lang:   "<?php echo $lang; ?>"
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
                    table.columns.adjust().draw();
                }, 10 );
            }
        });
    }


    $(document).on('click', '.detail_survey_cannot_edit', function() {
        const svId = $(this).attr("id");
        $("#modal-detail-survey").modal('show');
        $('.nav-tabs-detail-survey a[href="#main_survey_tab"]').tab('show');
        
        $.ajax({
            url:"<?=base_url('querydata/update_survey_data'); ?>",
            method:"POST",
            data:{
                sv_id:svId
            },
            dataType:"json",
            success:function(dataSurvey)
            {
                from = $('#survey_open').datepicker({
                    language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                    thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                    format: 'dd/mm/yyyy',
                    autoclose: true
                }).on('changeDate', function (selected) {
                    $('#survey_end').datepicker('setStartDate', selected.date);
                    $("#survey_end").datepicker( "setDate", selected.date);
                });
                to = $('#survey_end').datepicker({
                    language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                    thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                    format: 'dd/mm/yyyy',
                    autoclose: true
                });

                $(".modal-title-detail-survey").text(dataSurvey.sv_titlename);
                $.ajax({
                    url:"<?=base_url('querydata/user_approve'); ?>",
                    method:"POST",
                    data:{
                        sv_id:  svId,
                        com_id: dataSurvey.com_id,
                        viewDetail: 1
                    },
                    success:function(dataUserApprove)
                    {
                        $('#sv_userapprove_view').html(dataUserApprove);
                    }
                });
                
                if(dataSurvey.isTH=="1"){
                    document.getElementById("sv_lang_th_view").checked = true;
                    chkbox_lang('sv_lang_th_view','input_th_view');
                    $('#sv_title_th_view').val(dataSurvey.sv_title_th);
                    $('#sv_explanation_th_view').val(dataSurvey.sv_explanation_th);
                    $('#sv_detail_th_view').val(dataSurvey.sv_detail_th);
                    textarea_tinymce('sv_explanation_th_view', true);
                    textarea_tinymce('sv_detail_th_view', true);
                    $(tinymce.get('sv_explanation_th_view').getBody()).html(dataSurvey.sv_explanation_th);
                    $(tinymce.get('sv_detail_th_view').getBody()).html(dataSurvey.sv_detail_th);
                }else{
                    document.getElementById("sv_lang_th_view").checked = false;
                    $('.input_th_view').hide();
                }
                if(dataSurvey.isENG=="1"){
                    document.getElementById("sv_lang_eng_view").checked = true;
                    chkbox_lang('sv_lang_eng_view','input_eng_view');
                    $('#sv_explanation_eng_view').val(dataSurvey.sv_explanation_eng);
                    $('#sv_detail_eng_view').val(dataSurvey.sv_detail_eng);
                    $('#sv_title_eng_view').val(dataSurvey.sv_title_eng);
                    textarea_tinymce('sv_explanation_eng_view', true);
                    textarea_tinymce('sv_detail_eng_view', true);
                    $(tinymce.get('sv_explanation_eng_view').getBody()).html(dataSurvey.sv_explanation_eng);
                    $(tinymce.get('sv_detail_eng_view').getBody()).html(dataSurvey.sv_detail_eng);
                }else{
                    document.getElementById("sv_lang_eng_view").checked = false;
                    $('.input_eng_view').hide();
                }
                if(dataSurvey.isJP=="1"){
                    document.getElementById("sv_lang_jp_view").checked = true;
                    chkbox_lang('sv_lang_jp_view','input_jp_view');
                    $('#sv_title_jp_view').val(dataSurvey.sv_title_jp);
                    $('#sv_explanation_jp_view').val(dataSurvey.sv_explanation_jp);
                    $('#sv_detail_jp_view').val(dataSurvey.sv_detail_jp);
                    textarea_tinymce('sv_explanation_jp_view', true);
                    textarea_tinymce('sv_detail_jp_view', true);
                    $(tinymce.get('sv_explanation_jp_view').getBody()).html(dataSurvey.sv_explanation_jp);
                    $(tinymce.get('sv_detail_jp_view').getBody()).html(dataSurvey.sv_detail_jp);
                }else{
                    document.getElementById("sv_lang_jp_view").checked = false;
                    $('.input_jp_view').hide();
                }

                if(dataSurvey.sv_status=="0"){
                    document.getElementById("sv_status_view").checked = false;
                }else{
                    document.getElementById("sv_status_view").checked = true;
                }

                if(dataSurvey.sv_isHeader=="0"){
                    document.getElementById("sv_isHeader_view").checked = false;
                }else{
                    document.getElementById("sv_isHeader_view").checked = true;
                }
                if(dataSurvey.sv_type=="2"){
                    document.getElementById("sv_type_view").checked = false;
                }else{
                    document.getElementById("sv_type_view").checked = true;
                }
                $('#survey_open_var').val(dataSurvey.sv_open_var);
                $('#survey_end_var').val(dataSurvey.sv_end_var);
                $('#time_start_survey').val(dataSurvey.time_start);
                $('#time_end_survey').val(dataSurvey.time_end);
                $('#sv_expire_noti').val(dataSurvey.sv_expire_noti);

                if (dataSurvey.sv_open!="0000-00-00 00:00:00"&&dataSurvey.sv_end!="0000-00-00 00:00:00") {                    
                    $('#survey_open').datepicker('setStartDate', dataSurvey.sv_open);
                    $('#survey_end').datepicker('setStartDate', dataSurvey.sv_open);
                    $("#survey_open").datepicker("update", dataSurvey.sv_open); 
                    $("#survey_end").datepicker("update", dataSurvey.sv_end); 
                }else{      
                    var startDate = new Date();
                    $('#survey_open').datepicker('setStartDate',startDate);
                    $('#survey_end').datepicker('setStartDate',startDate);
                    $('#survey_open').val('');
                    $('#survey_end').val('');
                }

                if (dataSurvey.sv_cover != "") {
                    $("#view_img_survey_view").attr("src", "<?php echo REAL_PATH; ?>/uploads/publicsv/" + dataSurvey.sv_cover);
                }

                fetch_data_detail_survey(svId, 0);
            }
        });
    });
</script>