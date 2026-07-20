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

    function fetchLessonView(cosId, page_num) {
        $('#myTable_lesson_view').DataTable().destroy();
        var table = $('#myTable_lesson_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_lesson_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_lesson_view/'); ?>',
                data: {
                    cos_id: cosId,
                    status_user: '',
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "columnDefs": [{
                "orderable": false,
                "targets": [4, 5]
            }],
            "initComplete": function() {
                setTimeout(function() {
                    var info = table.page.info();
                    var length = info.pages;
                    var page_current = info.page;
                    if ((page_num + 1) > length) {
                    page_num = length - 1;
                    }
                    table.page(page_num).draw(false);
                }, 10);
            }
        });
    }

    function fetch_data_media_view(les_id, page_num) {
        $('#myTable_media_view').DataTable().destroy();
        var table = $('#myTable_media_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_media_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('course/fetch_lesson_media_view/') ?>',
                data: {
                    les_id: les_id,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
            setTimeout(function() {
                var info = table.page.info();
                var length = info.pages;
                var page_current = info.page;
                if ((page_num + 1) > length) {
                page_num = length - 1;
                }
                table.page(page_num).draw(false);
            }, 10);
            }
        });
    }

    function fetch_data_quiz_view(cos_id, page_num) {
        $('#myTable_quiz_view').DataTable().destroy();
        var table = $('#myTable_quiz_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_quiz_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_quiz_view/'); ?>',
                data: {
                    cos_id: cos_id,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            }
        });
    }

    function fetch_data_question_view(quiz, page_num) {
        $('#myTable_quiz_question_view').DataTable().destroy();
        var table = $('#myTable_quiz_question_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_quiz_question_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_question_view/'); ?>',
                data: {
                    quiz: quiz,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
                setTimeout(function() {
                    var info = table.page.info();
                    var length = info.pages;
                    var page_current = info.page;
                    if ((page_num + 1) > length) {
                        page_num = length - 1;
                    }
                    table.page(page_num).draw(false);
                }, 10);
            }
        });
    }

    function fetch_data_survey_view(cos_id, page_num) {
        $('#myTable_cos_id_survey_view').DataTable().destroy();
        var table = $('#myTable_cos_id_survey_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_cos_id_survey_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_survey_view/') ?>',
                data: {
                    cos_id: cos_id,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
                setTimeout(function() {
                    var info = table.page.info();
                    var length = info.pages;
                    var page_current = info.page;
                    if ((page_num + 1) > length) {
                        page_num = length - 1;
                    }
                    table.page(page_num).draw(false);
                }, 10);
            }
        });
    }

    function fetch_data_survey_detail_view(sv_id, page_num) {
        $('#myTable_survey_detail_view').DataTable().destroy();
        var table = $('#myTable_survey_detail_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_survey_detail_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_survey_detail_view/') ?>',
                data: {
                    sv_id: sv_id,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
            setTimeout(function() {
                var info = table.page.info();
                var length = info.pages;
                var page_current = info.page;
                if ((page_num + 1) > length) {
                page_num = length - 1;
                }
                table.page(page_num).draw(false);
            }, 10);
            }
        });
    }

    function fetch_data_enroll_view (cos_id, page_num) {
        $('#myTable_enroll_view').DataTable().destroy();
        var table = $('#myTable_enroll_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_enroll_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_course_enroll_view/') ?>',
                data: {
                    cos_id: cos_id,
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
                setTimeout(function() {
                    var info = table.page.info();
                    var length = info.pages;
                    var page_current = info.page;
                    if ((page_num + 1) > length) {
                    page_num = length - 1;
                    }
                    table.page(page_num).draw(false);
                }, 10);
            }
        });
    }

    function fetch_data_document_cos_view(cos_id, page_num) {
        $('#myTable_cos_document_view').DataTable().destroy();
        var table = $('#myTable_cos_document_view').on('error.dt', function(e, settings, techNote, message) {
            notificationForDatatableError("myTable_cos_document_view", message);
        }).DataTable({
            "language": langOfDataTable,
            "ajax": {
                url: '<?= base_url('fetchdata/fetch_cos_document_view/') ?>',
                data: {
                    cos_id: cos_id,
                    status_user: '100',
                    lang: "<?php echo $lang; ?>"
                },
                type: 'GET'
            },
            "initComplete": function() {
                setTimeout(function() {
                    var info = table.page.info();
                    var length = info.pages;
                    var page_current = info.page;
                    if ((page_num + 1) > length) {
                    page_num = length - 1;
                    }
                    table.page(page_num).draw(false);
                }, 10);
            }
        });
    }

    $(document).on('click', '.detail_course_cannot_edit', function() {
        const cosId = $(this).attr("id");
        $("#modal-detail-course").modal('show');
        $('.nav-tabs-detail-course a[href="#main_course_tab"]').tab('show');
        $.ajax({
            url: "<?= base_url('querydata/query_course'); ?>",
            method: "POST",
            data: {
            cos_id: cosId
            },
            dataType: "json",
            success: function(dataCos) {
            $(".modal-title-detail-course").text(dataCos.courseName);
            if (dataCos.isTH == "1") {
                textarea_tinymce('cdesc_th_view', '1', true);
                $('#cdesc_th_view').html('');
                document.getElementById("cos_lang_th_view").checked = true;
                $('.input_th_view').show();
                $('#cname_th_view').val(dataCos.cname_th);
                $('#cdesc_th_view').html(dataCos.cdesc_th);
                $(tinymce.get('cdesc_th_view').getBody()).html(dataCos.cdesc_th);
            } else {
                document.getElementById("cos_lang_th_view").checked = false;
                $('.input_th_view').hide();
            }
            if (dataCos.isENG == "1") {
                textarea_tinymce('cdesc_eng_view', '1', true);
                $('#cdesc_eng_view').html('');
                document.getElementById("cos_lang_eng_view").checked = true;
                $('.input_eng_view').show();
                $('#cname_eng_view').val(dataCos.cname_eng);
                $('#cdesc_eng_view').html(dataCos.cdesc_eng_view);
                $(tinymce.get('cdesc_eng_view').getBody()).html(dataCos.cdesc_eng_view);
            } else {
                document.getElementById("cos_lang_eng_view").checked = false;
                $('.input_eng_view').hide();
            }
            if (dataCos.isJP == "1") {
                textarea_tinymce('cdesc_jp_view', '1', true);
                $('#cdesc_jp_view').html('');
                document.getElementById("cos_lang_jp_view").checked = true;
                $('.input_jp_view').show();
                $('#cname_jp_view').val(dataCos.cname_jp);
                $('#cdesc_jp_view').html(dataCos.cdesc_jp);
                $(tinymce.get('cdesc_jp_view').getBody()).html(dataCos.cdesc_jp);
            } else {
                document.getElementById("cos_lang_jp_view").checked = false;
                $('.input_jp_view').hide();
            }

            if (dataCos.cos_status == "1") {
                document.getElementById("cos_status_view").checked = true;
            } else {
                document.getElementById("cos_status_view").checked = false;
            }

            $.ajax({
                url: '<?= base_url('querydata/recheckgroupcosmulti'); ?>',
                type: 'POST',
                data: {
                cg_id: dataCos.cg_id,
                com_id: dataCos.com_id,
                cos_id: cosId
                },
                success: function(data_cg) {
                    $('#cg_id_view').html(data_cg);
                    $.ajax({
                        url: '<?= base_url('querydata/recheckcondition'); ?>',
                        type: 'POST',
                        data: {
                        com_id: dataCos.com_id,
                        cos_id: cosId,
                        condition: dataCos.condition
                        },
                        success: function(datacondition) {
                            $('#condition_view').html(datacondition);
                        }
                    });
                }
            });
            $.ajax({
                url: '<?= base_url('querydata/rechecktypecos'); ?>',
                type: 'POST',
                data: {
                tc_id: dataCos.tc_id,
                com_id: dataCos.com_id
                },
                success: function(data_typecos) {
                    $('#tc_id_view').html(data_typecos);
                    $('#tc_id_view').val(dataCos.tc_id).trigger('change');
                }
            });
            
            $('#goal_score_view').val(dataCos.goal_score);
            $('#seat_count_view').val(dataCos.seat_count);
            $('#cos_expire_noti_view').val(dataCos.cos_expire_noti);
            if (dataCos.is_survey_required == "1") {
                document.getElementById("is_survey_required_view").checked = true;
            } else {
                document.getElementById("is_survey_required_view").checked = false;
            }

            $('#cos_hour_view').val(dataCos.cos_hour);
            $('#cos_typegrading_view').val(dataCos.cos_typegrading);
            
            $("#badges_condition_view").html('');
            if (dataCos.cos_typegrading == "1") {
                $("#badges_condition_view").append('<option value="A">A</option>');
                $("#badges_condition_view").append('<option value="B">B</option>');
                $("#badges_condition_view").append('<option value="C">C</option>');
                $("#badges_condition_view").append('<option value="D">D</option>');
                $('.typegrading_a_view').show();
                $('.typegrading_b_view').hide();
            } else {
                $("#badges_condition_view").append('<option value="P"><?php echo label('pass'); ?></option>');
                $("#badges_condition_view").append('<option value="F"><?php echo label('fail'); ?></option>');
                $('.typegrading_a_view').hide();
                $('.typegrading_b_view').show();
            }
            
            if (dataCos.cos_pic != "") {
                $('#div_cospicviewDemo').show();
                $("#view_img_cos_view").attr("src", "<?php echo REAL_PATH; ?>/uploads/course/" + dataCos.cos_pic);
            } else {
                $('#div_cospicviewDemo').hide();
            }

            
            $.ajax({
                url: "<?= base_url('querydata/update_cert_data'); ?>",
                method: "POST",
                data: {
                cos_id: cosId
                },
                dataType: "json",
                success: function(datacert) {
                if (datacert != null) {
                    $('#badges_name_view').val(datacert.badges_name);
                    $('#badges_condition_view').val(datacert.badges_condition);
                    $('#badges_desc_view').val(datacert.badges_desc);
                    if (datacert.badges_img != "") {
                    $("#badges_img_view").attr("src", "<?php echo REAL_PATH; ?>/uploads/badges/" + datacert.badges_img);
                    } else {
                    $("#badges_img_view").attr("src", "");
                    }
                }
                }
            });
            $.ajax({
                url: "<?= base_url('querydata/update_score_data'); ?>",
                method: "POST",
                data: {
                cos_id: cosId
                },
                dataType: "json",
                success: function(data_score) {
                if (dataCos.cos_typegrading == "2") {
                    $('#mina_b_view').val(data_score.mina);
                } else {
                    $('#mina_view').val(data_score.mina);
                    $('#minb_view').val(data_score.minb);
                    $('#minc_view').val(data_score.minc);
                    $('#mind_view').val(data_score.mind);
                }
                }
            });

            $.ajax({
                url: "<?= base_url('querydata/query_coursemain'); ?>",
                method: "POST",
                data: {
                    cos_id: cosId
                },
                dataType: "json",
                success: function(dataCourseMain) {
                    $.ajax({
                        url: '<?= base_url('querydata/permission_course'); ?>',
                        type: 'POST',
                        data: {
                            course_id: cosId,
                            cosde_id: dataCourseMain.cosde_id,
                            isView: 1
                        },
                        success: function(data_permission) {

                        $('#view_permission_div').html(data_permission);
                        }
                    });

                    from = $('#date_start').datepicker({
                        language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                        thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                        format: 'dd/mm/yyyy',
                        autoclose: true
                    }).on('changeDate', function(selected) {
                        $('#date_end_view').val('');
                        $('#date_start_view').datepicker("update", selected.date);
                        to = $('#date_end_view').datepicker({
                            language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                            thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                            format: 'dd/mm/yyyy',
                            autoclose: true
                        }).datepicker('setStartDate', selected.date).focus().on('changeDate', function(selected) {
                            var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {
                                timeZone: "Asia/Bangkok"
                            });
                            var date_val = moment(maxDate).format('YYYY-MM-DD');
                            var res_date = date_val.split("-");
                            maxDate = res_date[2] + "/" + res_date[1] + "/" + (parseInt(res_date[0]));
                            $('#date_start_view').datepicker('setEndDate', maxDate);
                        });
                    });

                    var startDate = new Date();
                    $('#date_start_view').datepicker('setStartDate', startDate);
                    to = $('#date_end_view').datepicker({
                        language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                        thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                        format: 'dd/mm/yyyy',
                        autoclose: true
                    }).on('changeDate', function(selected) {
                        $('#date_end_view').datepicker("update", selected.date);
                        var maxDate = new Date(selected.date.valueOf()).toLocaleString("en-US", {
                        timeZone: "Asia/Bangkok"
                        });
                        var date_val = moment(maxDate).format('YYYY-MM-DD');
                        var res_date = date_val.split("-");
                        maxDate = res_date[2] + "/" + res_date[1] + "/" + (parseInt(res_date[0]));
                        $('#date_start_view').datepicker('setEndDate', maxDate);
                    });

                    $('#date_end_view').datepicker('setStartDate', startDate);
                    if (dataCourseMain.isData_period == "1") {
                        $.ajax({
                        url: "<?= base_url() ?>index.php/querydata/update_course_detail_data",
                        method: "POST",
                        data: {
                            cosde_id: dataCourseMain.cosde_id
                        },
                        dataType: "json",
                        success: function(data_pp) {
                            $('#time_start_view').val(data_pp.time_start);
                            $('#time_end_view').val(data_pp.time_end);
                            if (data_pp.date_start != "" && data_pp.date_end != "") {
                            if (data_pp.date_start_condition != "") {
                                $('#date_start_view').datepicker('setStartDate', data_pp.date_start_condition);
                                $('#date_end_view').datepicker('setStartDate', data_pp.date_start_condition);
                            } else {
                                $('#date_start_view').datepicker('setStartDate', data_pp.date_start);
                                $('#date_end_view').datepicker('setStartDate', data_pp.date_start);
                            }
                            $("#date_start_view").datepicker("update", data_pp.date_start);
                            $("#date_end_view").datepicker("update", data_pp.date_end);
                            } else {
                            $('#date_start_view').val('');
                            $('#date_end_view').val('');
                            }
                        }
                        });
                    } else {
                        $('#date_start_view').val('');
                        $('#date_end_view').val('');
                    }

                    $.ajax({
                        url: "<?= base_url('coursetype/update_coursetype_data'); ?>",
                        method: "POST",
                        data: {
                        tc_id_update: dataCourseMain.tc_id
                        },
                        dataType: "json",
                        success: function(datatype) {
                            if (datatype.tc_lesson == "0") {
                                document.getElementById('li_lesson_view').style.display = 'none';
                            } else {
                                document.getElementById('li_lesson_view').style.display = '';
                                fetchLessonView(cosId, 0);
                                $('#div_lesson_view').show();
                                $('#div_create_lesson_view').hide();
                            }
                            if (datatype.tc_pretest == "0") {
                                document.getElementById('li_quiz_view').style.display = 'none';
                            } else {
                                document.getElementById('li_quiz_view').style.display = '';
                                fetch_data_quiz_view(cosId, 0);
                                $('#div_quiz_view').show();
                                $('#div_create_quiz_view').hide();
                                $('#div_quiz_question_view').hide();
                            }
                            if (datatype.tc_questionnaire == "0") {
                                document.getElementById('li_survey_view').style.display = 'none';
                            } else {
                                document.getElementById('li_survey_view').style.display = '';
                                fetch_data_survey_view(cosId, 0);
                                $('#div_survey_view').show();
                                $('#div_create_survey_view').hide();
                                $('#div_sv_survey_detail_view').hide();
                            }
                            if (datatype.tc_student_enroll == "0") {
                                document.getElementById('li_enroll_view').style.display = 'none';
                            } else {
                                document.getElementById('li_enroll_view').style.display = '';
                                fetch_data_enroll_view(cosId, 0);
                            }
                            if (datatype.tc_doccos == "0") {
                                document.getElementById('li_document_view').style.display = 'none';
                            } else {
                                document.getElementById('li_document_view').style.display = '';
                                fetch_data_document_cos_view(cosId, 0);
                            }
                        }
                    });
                }
            });
            }
        });
    });

    

    function changeValEnableDivMediaView() {
        var les_type = document.getElementById('les_type');
        var cos_id = $('#course_id_pp').val();
        if (les_type.checked != true) {
            document.getElementById('div_media_view').style.display = '';
            document.getElementById('div_scorm_view').style.display = 'none';
        } else {
            document.getElementById('div_media_view').style.display = 'none';
            document.getElementById('div_scorm_view').style.display = '';
        }
    }

    $(document).on('click', '.view_lesson', function() {
        const les_id = $(this).attr("id");
        $('#txthead_lesson_view').text('<?php echo label("detail"); ?>');

        $('#txt_scormoriginal_view').text('');
        
        $(function() {
            from = $('#date_start_les_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            }).on('changeDate', function(selected) {
                $('#date_end_les_view').datepicker('setStartDate', selected.date);
                $("#date_end_les_view").datepicker("setDate", selected.date);
            });

            to = $('#date_end_les_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            })
        })

        $('table#myTable_document_view tr.row_document').remove();
        
        $(".input_les_th_view").hide();
        $(".input_les_eng_view").hide();
        $(".input_les_jp_view").hide();
        $.ajax({
            url: "<?= base_url('querydata/query_lesson'); ?>",
            method: "POST",
            data: {
                les_id: les_id
            },
            dataType: "json",
            success: function(data_les) {


                $.ajax({
                    url: '<?= base_url('querydata/select_lang_lesson') ?>',
                    type: 'POST',
                    data: {
                        cos_id: data_les.cos_id,
                        les_lang: ''
                    },
                    dataType: "json",
                    success: function(data) {
                        for (var i = data.arr_lang.length - 1; i >= 0; i--) {
                            if (data.arr_lang[i] == "th") {
                                $(".input_les_th_view").show();
                                textarea_tinymce('les_info_th_view', '1', true);
                                $(tinymce.get('les_info_th_view').getBody()).html(data_les.les_info_th);
                                $('#les_name_th_view').val(data_les.les_name_th);
                                $('#les_info_th_view').val(data_les.les_info_th);
                            }
                            if (data.arr_lang[i] == "eng") {
                                $(".input_les_eng_view").show();
                                textarea_tinymce('les_info_eng_view', '1', true);
                                $(tinymce.get('les_info_eng_view').getBody()).html(data_les.les_info_eng);
                                $('#les_name_eng_view').val(data_les.les_name_eng);
                                $('#les_info_eng_view').val(data_les.les_info_eng);
                            }
                            if (data.arr_lang[i] == "jp") {
                                $(".input_les_jp_view").show();
                                textarea_tinymce('les_info_jp_view', '1', true);
                                $(tinymce.get('les_info_jp_view').getBody()).html(data_les.les_info_jp);
                                $('#les_name_jp_view').val(data_les.les_name_jp);
                                $('#les_info_jp_view').val(data_les.les_info_jp);
                            }
                        }
                        $('#les_lang_view').val(data.val_lang);
                    }
                });

                $.ajax({
                    url: '<?= base_url('querydata/query_course_detail_data'); ?>',
                    type: 'POST',
                    data: {
                        cos_id: data_les.cos_id
                    },
                    dataType: "json",
                    success: function(datasetDate) {
                        if (datasetDate.isData == "1") {
                            var start_date = datasetDate.date_start_var.split("-");
                            var StartDate = start_date[2] + "/" + start_date[1] + "/" + (parseInt(start_date[0]));
                            var end_date = datasetDate.date_end_var.split("-");
                            var EndDate = end_date[2] + "/" + end_date[1] + "/" + (parseInt(end_date[0]));
                            $('#date_start_les_view').datepicker('setStartDate', StartDate);
                            $('#date_end_les_view').datepicker('setStartDate', StartDate);
                            $('#date_end_les_view').datepicker('setEndDate', EndDate);
                            $('#date_start_les_view').datepicker('setEndDate', EndDate);
                        } else {
                            var startDate = new Date();
                            $('#date_start_les_view').datepicker('setStartDate', startDate);
                            $('#date_end_les_view').datepicker('setStartDate', startDate);
                        }
                    }
                });

                if (data_les.les_status == "0") {
                    document.getElementById("status_les_view").checked = false;
                } else {
                    document.getElementById("status_les_view").checked = true;
                }

                if (data_les.scm_type == "0") {
                    document.getElementById("radio_scm_type1_view").checked = true;
                } else if (data_les.scm_type == "1") {
                    document.getElementById("radio_scm_type2_view").checked = true;
                } else if (data_les.scm_type == "2") {
                    document.getElementById("radio_scm_type3_view").checked = true;
                }

                if (data_les.les_type == "1") {
                    document.getElementById("les_type_view").checked = false;
                    document.getElementById('div_media_view').style.display = '';
                    document.getElementById('div_scorm_view').style.display = 'none';
                    if (parseInt(data_les.num_fil) == 0) {
                        $('#tb_document_view').hide();
                        $('#tb_document_body_view').html('');
                    } else {
                        $('#tb_document_view').show();
                        $.ajax({
                            url: "<?= base_url('course/query_fil_lesson'); ?>",
                            method: "POST",
                            data: {
                            les_id: les_id
                            },
                            success: function(data_doc) {
                                $('#tb_document_body_view').html(data_doc);
                            }
                        });
                    }
                } else {
                    document.getElementById("les_type_view").checked = true;
                    document.getElementById('div_media_view').style.display = 'none';
                    document.getElementById('div_scorm_view').style.display = '';
                    if (data_les.scorm['path'] != "") {
                        $('#txt_scormoriginal_view').text("File Scorm Original : " + data_les.scorm['path']);
                    } else {
                        $('#txt_scormoriginal_view').text('');
                    }
                }
                changeValEnableDivMediaView();
                $('#time_start_les_view').val(data_les.time_start_les);
                $('#time_end_les_view').val(data_les.time_end_les);
                $('#count_file_view').val(data_les.num_fil);

                if (data_les.time_start != "" && data_les.time_end != "") {
                    $("#date_start_les_view").datepicker("setDate", data_les.time_start);
                    $("#date_end_les_view").datepicker("setDate", data_les.time_end);
                } else {
                    $('#date_start_les_view').val('');
                    $('#date_end_les_view').val('');
                }


                if (data_les.url != "") {
                    $('#type_media_view').val("1");
                    $("#url_media_view").val(data_les.url);
                    document.getElementById('div_multifile_url_view').style.display = '';
                    document.getElementById('div_multifile_upload_file_view').style.display = 'none';
                }
                
                if (data_les.upload && data_les.upload.length > 0) {
                    $('#type_media_view').val("2");
                    document.getElementById('div_multifile_url_view').style.display = 'none';
                    document.getElementById('div_multifile_upload_file_view').style.display = '';
                    var table_media = $('#myTable_media_view').DataTable();
                    var info_media = table_media.page.info();
                    var length_media = info_media.pages;
                    var page_current_media = info_media.page;
                    fetch_data_media_view(les_id, page_current_media);
                    document.getElementById('tb_media_view').style.display = '';
                } else {
                    document.getElementById('tb_media_view').style.display = 'none';
                }
            }
        });
        $('#div_create_lesson_view').show();
        $('#div_lesson_view').hide();
    });


    $(document).on('click', '.view_quiz', function() {
        const quizId = $(this).attr("id");
        $('#div_create_quiz_view').show();
        $('#div_quiz_view').hide();

        document.getElementById('div_template_qize_view').style.display = '';
        
        $(".div_lastquiz_view").removeClass("col-md-4");
        $(".div_lastquiz_view").addClass("col-md-6");
        
        $(".input_quiz_th_view").hide();
        $(".input_quiz_eng_view").hide();
        $(".input_quiz_jp_view").hide();

        $(function() {
            from = $('#period_open_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            }).on('changeDate', function(selected) {
                $('#period_end_view').datepicker('setStartDate', selected.date);
                $("#period_end_view").datepicker("setDate", selected.date);
            });
            to = $('#period_end_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            })
        })

        $.ajax({
            url: "<?= base_url('querydata/update_quiz_detail_data') ?>",
            method: "POST",
            data: {
                qiz_id: quizId
            },
            dataType: "json",
            success: function(data) {
                $('#txthead_quiz_view').text('<?php echo label("detail"); ?>');
                
                $.ajax({
                    url: '<?= base_url('fetchdata/fetch_course_question/') ?>',
                    data: {
                        quiz: quizId
                    },
                    type: 'GET',
                    dataType: "json",
                    success: function(quizData) {
                        var sumQuz = 0;
                        var quiz_limitval = parseInt(data.quiz_limitval);
                        quizData.data.map((index, res) => {
                            if (index.qt_sa == 1 || index.qt_sub == 1) {
                            sumQuz += 1;
                            }
                        })
                        
                        if (sumQuz >= 1 && quiz_limitval != 0) {
                            document.getElementById("quiz_limit_view").checked = true;
                            document.getElementById("quiz_limit_view").disabled = true;
                            document.getElementById("quiz_limitval_view").disabled = true;
                            $("#quiz_limitval_view").val(1)
                        } else if (sumQuz == 0 && quiz_limitval != 0) {
                            document.getElementById("quiz_limit_view").checked = true;
                            document.getElementById("quiz_limitval_view").readOnly = false;
                            document.getElementById("quiz_limit_view").disabled = false;
                            document.getElementById("quiz_limitval_view").disabled = false;
                            $("#quiz_limitval_view").val(quiz_limitval)
                        }
                    }
                });
                
                $.ajax({
                    url: '<?= base_url('querydata/query_course_detail_data') ?>',
                    type: 'POST',
                    data: {
                        cos_id: data.cos_id
                    },
                    dataType: "json",
                    success: function(datasetDate) {
                        if (datasetDate.isData == "1") {
                            var start_date = datasetDate.date_start_var.split("-");
                            var StartDate = start_date[2] + "/" + start_date[1] + "/" + (parseInt(start_date[0]));
                            var end_date = datasetDate.date_end_var.split("-");
                            var EndDate = end_date[2] + "/" + end_date[1] + "/" + (parseInt(end_date[0]));
                            $('#period_open_view').datepicker('setStartDate', StartDate);
                            $('#period_end_view').datepicker('setStartDate', StartDate);
                            $('#period_end_view').datepicker('setEndDate', EndDate);
                            $('#period_open_view').datepicker('setEndDate', EndDate);
                        }
                    }
                });

                $.ajax({
                    url: "<?= base_url('querydata/query_course') ?>",
                    method: "POST",
                    data: {
                        cos_id: data.cos_id
                    },
                    dataType: "json",
                    success: function(data_cos) {
                        $.ajax({
                            url: '<?= base_url('workgroup/select_qize') ?>',
                            type: 'POST',
                            data: {
                                com_id: data_cos.com_id,
                                quiz_lang: data_cos.cos_lang
                            },
                            success: function(data_qize) {

                                $('#qize_id_view').html(data_qize);
                            }
                        });
                    }
                });

                $.ajax({
                    url: '<?= base_url('querydata/select_lang_lesson') ?>',
                    type: 'POST',
                    data: {
                    cos_id: data.cos_id,
                    les_lang: ''
                    },
                    dataType: "json",
                    success: function(data_lang) {
                        for (var i = data_lang.arr_lang.length - 1; i >= 0; i--) {
                            if (data_lang.arr_lang[i] == "th") {
                                $(".input_quiz_th_view").show();
                                textarea_tinymce('quiz_info_th_view', '1', true);
                                $(tinymce.get('quiz_info_th_view').getBody()).html(data.quiz_info_th);
                                $('#quiz_name_th_view').val(data.quiz_name_th);
                            }
                            if (data_lang.arr_lang[i] == "eng") {
                                $(".input_quiz_eng_view").show();
                                textarea_tinymce('quiz_info_eng_view', '1', true);
                                $(tinymce.get('quiz_info_eng_view').getBody()).html(data.quiz_info_eng);
                                $('#quiz_name_eng_view').val(data.quiz_name_eng);
                            }
                            if (data_lang.arr_lang[i] == "jp") {
                                $(".input_quiz_jp_view").show();
                                textarea_tinymce('quiz_info_jp_view', '1', true);
                                $(tinymce.get('quiz_info_jp_view').getBody()).html(data.quiz_info_jp);
                                $('#quiz_name_jp_view').val(data.quiz_name_jp);
                            }
                        }
                        $('#quiz_lang_view').val(data_lang.val_lang);
                    }
                });


                $('#time_start_quiz_view').val(data.time_start);
                $('#time_end_quiz_view').val(data.time_end);

                if (data.period_open != "" && data.period_end != "") {
                    $("#period_open_view").datepicker("setDate", data.period_open);
                    $("#period_end_view").datepicker("setDate", data.period_end);
                } else {
                    $('#period_open_view').val('');
                    $('#period_end_view').val('');
                }

                if (parseInt(data.result_ques) > 0) {
                    document.getElementById("quiz_numofshown_view").readOnly = false;
                    document.getElementById("quiz_limitval_view").readOnly = false;
                    $('#quiz_numofshown_view').val(data.quiz_numofshown);
                    var quiz_limitval = parseInt(data.quiz_limitval);
                    $("#quiz_limitval_view").val(quiz_limitval)
                    $('#totalquiz_view').val(data.result_ques);
                    $('#txt_totalquiz_view').text(" / " + data.result_ques);
                    document.getElementById("quiz_numofshown_view").max = data.result_ques;
                } else {
                    document.getElementById("quiz_numofshown_view").max = "";
                    document.getElementById("quiz_numofshown_view").readOnly = true;
                }

                if (data.quiz_random == "0") {
                    document.getElementById("quiz_random_view").checked = false;
                } else {
                    document.getElementById("quiz_random_view").checked = true;
                }

                if (data.quiz_random_choice == "0") {
                    document.getElementById("quiz_random_choice_view").checked = false;
                } else {
                    document.getElementById("quiz_random_choice_view").checked = true;
                }
                
                if (data.quiz_show == "0") {
                    document.getElementById("quiz_show_view").checked = false;
                } else {
                    document.getElementById("quiz_show_view").checked = true;
                }

                if (data.quiz_grade == "0") {
                    document.getElementById("quiz_grade_view").checked = false;
                } else {
                    document.getElementById("quiz_grade_view").checked = true;
                }

                if (data.quiz_type == "1") {
                    document.getElementById("quiz_type_view").checked = false;
                    document.getElementById("quiz_limit_view").checked = true;
                    document.getElementById('div_answer_view').style.display = 'none';
                } else {
                    document.getElementById("quiz_type_view").checked = true;
                    document.getElementById('div_answer_view').style.display = '';
                    
                    if (data.quiz_answer == "0") {
                        document.getElementById("quiz_answer_view").checked = false;
                    } else {
                        document.getElementById("quiz_answer_view").checked = true;
                    }
                }

                if (data.quiz_ishint == "0") {
                    document.getElementById("quiz_ishint_view").checked = false;
                } else {
                    document.getElementById("quiz_ishint_view").checked = true;
                }
                
                if (data.quiz_model == "0") {
                    document.getElementById("quiz_model_view").checked = false;
                } else {
                    document.getElementById("quiz_model_view").checked = true;
                }

                $('#quiz_maxscore_view').val(data.quiz_maxscore);

            }
        });
    });

    
    $(document).on('click', '.view_questions', function() {
        const quizId = $(this).attr("id");
        $('#div_quiz_question_view').show();
        $('#div_quiz_view').hide();

        fetch_data_question_view(quizId, 0);
    });
    
    $(document).on('click', '.view_survey', function() {
        const svId = $(this).attr("id");
        $('#txthead_survey_view').text('<?php echo label("detail"); ?>');

        $(function() {
            from = $('#survey_open_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            }).on('changeDate', function(selected) {
                $('#survey_end_view').datepicker('setStartDate', selected.date);
                $("#survey_end_view").datepicker("setDate", selected.date);
            });
            to = $('#survey_end_view').datepicker({
                language: '<?php echo $lang == "thai" ? "th" : "en"; ?>',
                thaiyear: <?php echo $lang=="thai" ? 'true' : 'false'; ?>,
                format: 'dd/mm/yyyy',
                autoclose: true
            })
        })

        $(".input_survey_eng_view").hide();
        $(".input_survey_th_view").hide();
        $(".input_survey_jp_view").hide();

        $.ajax({
            url: "<?= base_url('querydata/update_survey_detail_data') ?>",
            method: "POST",
            data: {
                sv_id_update: svId
            },
            dataType: "json",
            success: function(data) {

                $.ajax({
                    url: '<?= base_url('querydata/query_course_detail_data'); ?>',
                    type: 'POST',
                    data: {
                        cos_id: data.cos_id
                    },
                    dataType: "json",
                    success: function(datasetDate) {
                        if (datasetDate.isData == "1") {
                            var start_date = datasetDate.date_start_var.split("-");
                            var StartDate = start_date[2] + "/" + start_date[1] + "/" + (parseInt(start_date[0]));
                            var end_date = datasetDate.date_end_var.split("-");
                            var EndDate = end_date[2] + "/" + end_date[1] + "/" + (parseInt(end_date[0]));
                            $('#survey_open_view').datepicker('setStartDate', StartDate);
                            $('#survey_end_view').datepicker('setStartDate', StartDate);
                            $('#survey_end_view').datepicker('setEndDate', EndDate);
                            $('#survey_open_view').datepicker('setEndDate', EndDate);
                        }
                    }
                });

                $.ajax({
                    url: '<?= base_url('querydata/select_lang_lesson') ?>',
                    type: 'POST',
                    data: {
                        cos_id: data.cos_id,
                        les_lang: ''
                    },
                    dataType: "json",
                    success: function(datalang) {
                        for (var i = datalang.arr_lang.length - 1; i >= 0; i--) {
                            if (datalang.arr_lang[i] == "th") {
                                $(".input_survey_th_view").show();
                                $('#sv_title_th_view').val(data.sv_title_th);
                                $('#sv_explanation_th_view').val(data.sv_explanation_th);
                            }
                            if (datalang.arr_lang[i] == "eng") {
                                $(".input_survey_eng_view").show();
                                $('#sv_title_eng_view').val(data.sv_title_eng);
                                $('#sv_explanation_eng_view').val(data.sv_explanation_eng);
                            }
                            if (datalang.arr_lang[i] == "jp") {
                                $(".input_survey_jp_view").show();
                                $('#sv_title_jp_view').val(data.sv_title_jp);
                                $('#sv_explanation_jp_view').val(data.sv_explanation_jp);
                            }
                        }

                        $('#sv_lang_view').val(data.val_lang);
                        $.ajax({
                            url: '<?= base_url('workgroup/recheckquestionnaire') ?>',
                            type: 'POST',
                            data: {
                                qn_id: data.qn_id,
                                cos_lang: datalang.val_lang,
                                cos_id: data.cos_id
                            },
                            success: function(dataqn) {
                                $('#qn_id_view').html(dataqn);
                            }
                        });
                    }
                });

                if (data.sv_suggestion_status == "0") {
                    document.getElementById("sv_suggestion_status_view").checked = false;
                } else {
                    document.getElementById("sv_suggestion_status_view").checked = true;
                }

                if (data.sv_status == "0") {
                    document.getElementById("sv_status_view").checked = false;
                } else {
                    document.getElementById("sv_status_view").checked = true;
                }

                $('#time_start_survey_view').val(data.time_start);
                $('#time_end_survey_view').val(data.time_end);

                if (data.survey_open != "" && data.survey_end != "") {
                    $("#survey_open_view").datepicker("setDate", data.survey_open);
                    $("#survey_end_view").datepicker("setDate", data.survey_end);
                } else {
                    $('#survey_open_view').val('');
                    $('#survey_end_view').val('');
                }
            }
        });
        display_style('div_create_survey_view', 'div_survey_view');
    });
    
    $(document).on('click', '.view_questions_of_survey', function() {
        const svId = $(this).attr("id");
        $.ajax({
            url: "<?= base_url('querydata/update_survey_detail_data') ?>",
            method: "POST",
            data: {
                sv_id_update: svId,
                type:         "detail",
                lang_select:  "<?php echo $lang; ?>"
            },
            dataType: "json",
            success: function(data) {
                $('#sv_name_txt_view').text(data.sv_title);
                $('#div_survey_view').hide();
                $('#div_sv_survey_detail_view').show();
                fetch_data_survey_detail_view(svId, 0);
            }
        });
    });
</script>