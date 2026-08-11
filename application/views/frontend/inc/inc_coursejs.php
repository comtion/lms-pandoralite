<script type="text/javascript">
  $.fn.dataTable.ext.errMode = "none";
function onclose_divmenu() {
  var hidden = $('#div_menu');
  if (hidden.hasClass('visible')) {
    hidden.animate({
      "left": "100%"
    }, "slow").removeClass('visible');
  }
}

function onopen_divmenu() {
  var hidden = $('#div_menu');
  if (hidden.hasClass('visible')) {
    hidden.animate({
      "left": "100%"
    }, "slow").removeClass('visible');
  } else {
    hidden.animate({
      "left": "0"
    }, "slow").addClass('visible');
  }

}
$(document).ready(function() {
  var updateOutput = function(e) {
    var list = e.length ? e : $(e.target),
      output = list.data('output');
    var cos_id = $('#course_id_pp').val();

    if (window.JSON) {
      output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
    } else {
      output.val('JSON browser support required for this demo.');
      return;
    }

    var myObj = list.nestable('serialize');
    if (!cos_id || !myObj.length) {
      return;
    }

    $.ajax({
      url: "<?= base_url() ?>index.php/course/edit_li_lesson",
      method: 'POST',
      data: {
        arr_obj: myObj,
        cos_id: cos_id
      },
      success: function(data) {

      }
    });
  };
  $('#nestable').nestable({
    group: 1,
    maxDepth: 7,
  }).on('change', updateOutput);

  var nestable = $('#nestable');
  var nestableOutput = $('#nestable-output');
  nestable.data('output', nestableOutput);
  if (window.JSON && nestableOutput.length) {
    nestableOutput.val(window.JSON.stringify(nestable.nestable('serialize')));
  }

});

$(".checkall").each(function() {
  document.getElementById("chkcg_" + this.value).checked = true;
});

$(document).on('click', '.transfer', function() {
  var id = $(this).attr("id");
  $('#wg_id_transfer').empty();
  $('#cg_id_transfer').empty();
  $('#cos_id_transfer').val(id);
});

function clear_txt(id, a, b) {
  $('#' + id).val('');
  $('#' + a).val('0000-00-00 00:00:00');
  $('#' + b).val('0000-00-00 00:00:00');
}
$(document).on('click', '.btn_register', function() {
  var id = $(this).attr("id");
  /*var point_redeem = $('#point_redeem_hide'+id).val();
  var point_user = '<?php echo $user['usp_point']; ?>';*/
  var enroll_seat = $('#enroll_seat_hide' + id).val();
  var seat_count = $('#seat_count_hide' + id).val();

  if ((parseInt(enroll_seat) + 1) < parseInt(seat_count)) {
    status = "1";
  } else {
    status = "0";
  }
  if (parseInt(seat_count) == 0) {
    status = "1";
  }
  /* console.log(id,parseInt(point_redeem),parseInt(point_user));
   if(parseInt(point_redeem)>0){
     if(parseFloat(point_user)>=parseFloat(point_redeem)){
           $.ajax({
             url:"<?= base_url() ?>index.php/course/register_course",
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
                     window.location.href = '<?= base_url() ?>index.php/course/detail/'+id;
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
    url: "<?= base_url() ?>index.php/course/register_course",
    method: 'POST',
    data: {
      cos_id: id,
      status: status
    },
    success: function(data) {

      if (data == "2") {
        swal(
          '<?php echo label("enroll_reuse_success"); ?>!',
          '',
          'success'
        ).then(function() {
          window.location.href = '<?= base_url() ?>index.php/course/detail/' + id;
        })
      } else {
        swal({
          title: '<?php echo label("enroll_reuse_error"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          location.reload();
        })
      }

    }
  });
  //}
});

function onclickdetail(cos_id = '') {
  window.location.href = '<?= base_url() ?>index.php/course/detail/' + cos_id + '/<?php echo $loadmycos; ?>';
}

function oncheckboxall() {
  var checkBoxall = document.getElementById("chkcg_all");
  if (checkBoxall.checked == true) {
    $(".checkall").attr("checked", true);
    var sList = "";
    $(".checkall").each(function() {
      document.getElementById("chkcg_" + this.value).checked = true;
      document.getElementById("div_cg_main" + this.value).style.display = "";
    });
  } else {
    $(".checkall").attr("checked", false);

    var sList = "";
    $(".checkall").each(function() {
      document.getElementById("chkcg_" + this.value).checked = false;
      document.getElementById("div_cg_main" + this.value).style.display = "none";
    });
  }
}

function oncheckbox(id) {
  var count_cg = parseInt('<?php echo count($courses_cg); ?>');
  var checkBox = document.getElementById("chkcg_" + id);
  if (checkBox.checked == true) {
    document.getElementById("div_cg_main" + id).style.display = "";
  } else {
    document.getElementById("div_cg_main" + id).style.display = "none";
  }
  var sList = 0;
  $(".checkall").each(function() {
    if (this.checked) {
      sList++;
      document.getElementById("div_cg_main" + this.value).style.display = "";
    } else {
      document.getElementById("div_cg_main" + this.value).style.display = "none";
    }
  });
  if (sList == count_cg) {
    document.getElementById("chkcg_all").checked = true;
  } else {
    document.getElementById("chkcg_all").checked = false;
  }
}

function run_qrcode() {
  var cos_id = $('#course_id_pp').val();
  var table_registerqr = $('#myTable_register_qr').DataTable();
  var info_registerqr = table_registerqr.page.info();
  var length_registerqr = info_registerqr.pages;
  var page_current_registerqr = info_registerqr.page;
  fetch_data_register(cos_id, page_current_registerqr);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/count_of_register",
    method: 'POST',
    data: {
      cos_id: cos_id
    },
    success: function(data) {
      $('#count_register').text(data);
    }
  });
  var app = new Vue({
    el: '#app',
    data: {
      scanner: null,
      activeCameraId: null,
      cameras: [],
      scans: []
    },
    mounted: function() {
      var self = this;
      self.scanner = new Instascan.Scanner({
        video: document.getElementById('preview'),
        scanPeriod: 5
      });
      self.scanner.addListener('scan', function(content, image) {
        $.ajax({
          url: "<?= base_url() ?>index.php/course/query_data_chk_empc",
          method: 'POST',
          data: {
            emp_c: content
          },
          success: function(data) {
            if (data != 'null') {
              $.ajax({
                url: "<?= base_url() ?>index.php/course/insert_emptocourse",
                method: 'POST',
                data: {
                  emp_c: content,
                  cos_id: cos_id
                },
                success: function(data) {
                  var table_registerqr = $('#myTable_register_qr').DataTable();
                  var info_registerqr = table_registerqr.page.info();
                  var length_registerqr = info_registerqr.pages;
                  var page_current_registerqr = info_registerqr.page;
                  fetch_data_register(cos_id, page_current_registerqr);
                  $.ajax({
                    url: "<?= base_url() ?>index.php/course/count_of_register",
                    method: 'POST',
                    data: {
                      cos_id: cos_id
                    },
                    success: function(data) {

                      $('#count_register').text(data);
                    }
                  });
                }
              });
            } else {
              swal({
                title: '<?php echo label("wg_datanotfound"); ?>',
                text: "",
                type: 'warning',
                showCancelButton: false,
                confirmButtonClass: 'btn btn-primary',
                confirmButtonText: '<?php echo label("m_ok"); ?>'
              })
            }
          }
        });
        //self.scans.unshift({ date: +(Date.now()), content: content });
      });
      Instascan.Camera.getCameras().then(function(cameras) {
        self.cameras = cameras;
        if (cameras.length > 0) {
          document.getElementById('div_camera_online').style.display = '';
          document.getElementById('div_camera_offline').style.display = 'none';
          self.activeCameraId = cameras[0].id;
          self.scanner.start(cameras[0]);
        } else {
          document.getElementById('div_camera_online').style.display = 'none';
          document.getElementById('div_camera_offline').style.display = '';
        }
      }).catch(function(e) {
        document.getElementById('div_camera_online').style.display = 'none';
        document.getElementById('div_camera_offline').style.display = '';
      });
    },
    methods: {
      formatName: function(name) {
        return name || '(unknown)';
      },
      selectCamera: function(camera) {
        this.activeCameraId = camera.id;
        this.scanner.start(camera);
      }
    }
  });
}
</script>
<script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
function add_employeetocourse() {
  var cos_id = $('#course_id_pp').val();
  var emp_c = $('#emp_c').val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_emptocourse",
    method: 'POST',
    data: {
      emp_c: emp_c,
      cos_id: cos_id
    },
    success: function(data) {
      if (data == "2") {
        $('#emp_c').val('');
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          var table_enroll = $('#myTable_enroll').DataTable();
          var info_enroll = table_enroll.page.info();
          var length_enroll = info_enroll.pages;
          var page_current_enroll = info_enroll.page;
          fetch_data_enroll(cos_id, page_current_enroll);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#emp_c').val('');
        })
      } else {
        swal({
          title: '<?php echo label("add_emptocourse_error"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#emp_c').val('');
          document.getElementById("emp_c").focus();
        })
      }

    }
  });
}

function update_score_all() {
  var cos_id = $('#course_id_pp').val();
  var cosen_score_all = $('#cosen_score_all').val();
  if (qiz_score_all != "") {

    $.ajax({
      url: "<?= base_url() ?>index.php/course/insert_update_scoreall",
      method: 'POST',
      data: {
        cosen_score_all: cosen_score_all,
        cos_id: cos_id
      },
      success: function(data) {

        if (data == "2") {
          $('#cosen_score_all').val('');
          swal(
            '<?php echo label("com_msg_success"); ?>!',
            '',
            'success'
          ).then(function() {
            var table_enroll = $('#myTable_enroll').DataTable();
            var info_enroll = table_enroll.page.info();
            var length_enroll = info_enroll.pages;
            var page_current_enroll = info_enroll.page;
            fetch_data_enroll(cos_id, page_current_enroll);
          })
        } else if (data == "1") {
          swal({
            title: '<?php echo label("course_msg_duplicate"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          }).then(function() {
            $('#cosen_score_all').val('');
          })
        } else {
          swal({
            title: '<?php echo label("add_emptocourse_error"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          }).then(function() {
            $('#cosen_score_all').val('');
          })
        }

      }
    });
  } else {
    swal({
      title: '<?php echo label("com_msg_form_error"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonClass: 'btn btn-primary',
      confirmButtonText: '<?php echo label("m_ok"); ?>'
    }).then(function() {
      document.getElementById("cosen_score_all").focus();
    })
  }
}

function qiz_update_score_all() {
  var qiz_id = $('#qiz_id_enroll').val();
  var qiz_score_all = $('#qiz_score_all').val();
  if (qiz_id != "000") {
    if (qiz_score_all != "" && parseInt(qiz_score_all) != 0) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/insert_update_scoreall_quiz",
        method: 'POST',
        data: {
          qiz_score_all: qiz_score_all,
          qiz_id: qiz_id
        },
        success: function(data) {

          if (data == "2") {
            $('#qiz_score_all').val('');
            swal(
              '<?php echo label("com_msg_success"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_enrollqiz = $('#myTable_enroll_qiz').DataTable();
              var info_enrollqiz = table_enrollqiz.page.info();
              var length_enrollqiz = info_enrollqiz.pages;
              var page_current_enrollqiz = info_enrollqiz.page;
              fetch_data_enroll_qiz(qiz_id, page_current_enrollqiz);
            })
          } else if (data == "1") {
            swal({
              title: '<?php echo label("course_msg_duplicate"); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label("m_ok"); ?>'
            }).then(function() {
              $('#qiz_score_all').val('');
            })
          } else {
            swal({
              title: '<?php echo label("add_emptocourse_error"); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label("m_ok"); ?>'
            }).then(function() {
              $('#qiz_score_all').val('');
            })
          }
        }
      });
    } else {
      swal({
        title: '<?php echo label("com_msg_form_error"); ?>',
        text: "",
        type: 'warning',
        showCancelButton: false,
        confirmButtonClass: 'btn btn-primary',
        confirmButtonText: '<?php echo label("m_ok"); ?>'
      }).then(function() {
        document.getElementById("qiz_score_all").focus();
      })
    }
  } else {
    swal({
      title: '<?php echo label("no_quiz"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonClass: 'btn btn-primary',
      confirmButtonText: '<?php echo label("m_ok"); ?>'
    }).then(function() {
      $('#qiz_score_all').val('');
      $('#qiz_id_enroll').focus();
    })
  }
}

function changeScore(cosen_id) {
  var cosen_score = $('#cosen_score' + cosen_id).val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_score",
    method: 'POST',
    data: {
      cosen_id: cosen_id,
      cosen_score: cosen_score
    },
    success: function(data) {

    }
  });
}

function changeScore_qiz(id) {
  var sum_score = $('#sum_score' + id).val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_score_qiz",
    method: 'POST',
    data: {
      id: id,
      sum_score: sum_score
    },
    success: function(data) {

    }
  });
}

function reset_model(name_model, sup_model) {
  $('#' + sup_model).modal('hide');
  $('#' + name_model).modal('show');
}
$(document).on('click', '.view_document', function() {
  var file_name = $(this).attr("id");
  $('#modal-viewfiledocument').modal('show');

  if (file_name != "") {
    document.getElementById("iframe_view").src =
      "https://docs.google.com/gview?url=http://el.tripetch-isuzu.co.th/uploads/Conceptual%20Model%20FOR%20MG.ppt&embedded=true";
  }
});
$(document).on('click', '.view_video', function() {
  var file_name = $(this).attr("id");
  $('#modal-viewfilevideo').modal('show');

  if (file_name != "") {
    document.getElementById("video_player").src = "<?php echo HTTP_REAL_PATH; ?>" + file_name;
  }
});

changeValEnableDivMedia('1');

textarea_tinymce('les_info_th');
textarea_tinymce('les_info_en');
textarea_tinymce('quiz_info_th');
textarea_tinymce('quiz_info_en');
textarea_tinymce('ques_name_th');
textarea_tinymce('ques_name_en');
textarea_tinymce('ques_info_th');
textarea_tinymce('ques_info_en');
textarea_tinymce('cdesc_th');
textarea_tinymce('cdesc_en');

textarea_tinymce('mul_c1_th');
textarea_tinymce('mul_c1_en');
textarea_tinymce('mul_c2_th');
textarea_tinymce('mul_c2_en');
textarea_tinymce('mul_c3_th');
textarea_tinymce('mul_c3_en');
textarea_tinymce('mul_c4_th');
textarea_tinymce('mul_c4_en');
textarea_tinymce('mul_c5_th');
textarea_tinymce('mul_c5_en');

function textarea_tinymce(id) {
  if ($("#" + id).length > 0) {
    tinymce.init({
      selector: "textarea#" + id,
      theme: "modern",
      height: 300,
      plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality paste textcolor"
      ],
      toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor ",

    });
  }
}

function readonly(val_chk, field_name) {
  if (val_chk == "1") {
    document.getElementById(field_name).readOnly = false;
  } else {
    document.getElementById(field_name).readOnly = true;
  }
  $('#' + field_name).val('0');
}

function display(val_chk, field_name) {
  if (val_chk == "1") {
    document.getElementById(field_name).style.display = 'none';
  } else {
    document.getElementById(field_name).style.display = '';
  }
  Select_quiz_type(val_chk);
}

function display_disable(div_name, div_main) {
  var x = document.getElementById(div_name);
  var y = document.getElementById(div_main);
  x.style.display = 'none';
  y.style.display = '';
  if (div_name == 'div_create_lesson') {
    document.getElementById('div_lesson').style.display = '';
    document.getElementById('div_create_lesson').style.display = 'none';
    document.getElementById('div_order_lesson').style.display = 'none';
  }
  if (div_name == 'div_create_quiz') {
    document.getElementById('div_quiz_main').style.display = '';
    document.getElementById('div_quiz_detail').style.display = 'none';
    document.getElementById('div_question_check').style.display = 'none';
    document.getElementById('div_import_question').style.display = 'none';
  }
  if (div_name == 'div_create_survey') {
    document.getElementById('div_survey_main').style.display = '';
    document.getElementById('div_survey_detail').style.display = 'none';
  }
  if (div_name == 'div_create_videocourse') {
    document.getElementById('div_videocourse').style.display = '';
    document.getElementById('div_create_videocourse').style.display = 'none';
  }
  if (div_main == 'div_enroll_main') {
    document.getElementById('div_enroll_qiz').style.display = 'none';
    var cos_id = $('#course_id_pp').val();

    $('#myTable_enroll_qiz').DataTable().destroy();
    $('#myTable_enroll_qiz').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_enroll_qiz", message);
    }).DataTable({
      "ajax": {
        url: '<?= base_url() ?>index.php/course/fetch_course_enroll_qiz/',
        data: {
          qiz_id: '',
          lang: "<?php echo $lang; ?>",
        },
        type: 'GET'
      },
      <?php if ($btn_print == "1") { ?>
      dom: 'Bfrtip',
      buttons: [
        'copy', 'excel', 'print'
      ]
      <?php } ?>
    });
    $.ajax({
      url: '<?= base_url() ?>index.php/course/rechkquizandstudent',
      type: 'POST',
      data: {
        cos_id: cos_id
      },
      success: function(data) {

        if (data == "1") {
          document.getElementById('manage_quiz').style.display = '';
        } else {
          document.getElementById('manage_quiz').style.display = 'none';
        }
      }
    });
  }
  var com_id = $('#com_id_survey').val();
}

function display_style(div_name, div_main) {
  var x = document.getElementById(div_name);
  var y = document.getElementById(div_main);
  if (x.style.display === 'none') {
    x.style.display = '';
    y.style.display = 'none';
  } else {
    x.style.display = 'none';
    y.style.display = '';
    if (div_main == 'div_sv_survey_detail') {
      document.getElementById('div_import_survey_detail').style.display = 'none';
      var sv_id = $('#sv_id_detail_import').val();
      fetch_data_survey_detail(sv_id, 0);
    }
    if (div_name == 'div_create_pp') {
      var id = $('#course_id_pp').val();
      var table = $('#myTable_pp').DataTable();
      var info = table.page.info();
      var length = info.pages;
      var page_current = info.page;
      fetch_data_detail(id, page_current);
    }

    if (div_name == 'div_create_question') {
      document.getElementById('div_question_mul').style.display = 'none';
      document.getElementById('div_import_question').style.display = 'none';
    }

    if (div_name == 'div_import_question') {
      document.getElementById('div_create_question').style.display = 'none';
    }

    if (div_name == 'div_quiz_detail') {
      document.getElementById('div_question_check').style.display = 'none';
    }
  }

}

function create_div(div_name, div_main, form_name) {
  document.getElementById(div_name).style.display = '';
  document.getElementById(div_main).style.display = 'none';
  var com_id = $('#com_id_detail').val();
  var cos_id = $('#course_id_pp').val();
  $('#' + form_name)[0].reset();
  $('#com_id_detail').val(com_id);
  $('#course_id_pp').val(cos_id);
  $('#course_id_lesson').val(cos_id);
  $('#course_id_quiz').val(cos_id);
  $('#course_id_survey').val(cos_id);
  $('#course_id_cosv').val(cos_id);
  $('#com_id_survey').val(com_id);
  if (form_name == "period_and_permission_form") {
    var cos_id = $('#course_id_pp').val();
    var com_id = $('#com_id_detail').val();
    $.ajax({
      url: '<?= base_url() ?>index.php/course/permission_course',
      type: 'POST',
      data: {
        com_id: com_id,
        course_id: cos_id,
        cosde_id: ''
      },
      success: function(data) {

        $('#permission_div').html(data);
      }
    });
    $('#operation_pp').val("Add");

    $('#daterange_period').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: false,
        startDate: new Date(),
        endDate: new Date(),
        separator: ' to ',
        locale: {
          format: 'DD/MMMM/YYYY HH:mm:00',
          applyLabel: '<?php echo label("m_ok"); ?>',
          cancelLabel: '<?php echo label("cancel"); ?>',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom Range',
          <?php if ($lang == "thai") { ?>
          daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
          monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
          ],
          <?php } else { ?>
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
          ],
          <?php } ?>
          firstDay: 1
        }
      },
      function(start, end) {
        $('#date_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
        $('#date_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
      });
    //$("#date_start").datepicker("update", '');
    //$("#date_end").datepicker("update", '');
  } else if (form_name == "lesson_form") {
    $('#operation_lesson').val("Add");
    /*
                    $("#time_start").datepicker("update", '');
                    $("#time_end").datepicker("update", '');*/
    document.getElementById('div_media').style.display = '';
    document.getElementById('div_scorm').style.display = 'none';
    document.getElementById('div_order_lesson').style.display = 'none';
    document.getElementById('div_multifile_url').style.display = 'none';
    document.getElementById('div_multifile_upload_file').style.display = 'none';

    $('#txt_scormoriginal').text('');
    $('#daterange_lesson').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: false,
        startDate: new Date(),
        endDate: new Date(),
        separator: ' to ',
        locale: {
          format: 'DD/MMMM/YYYY HH:mm:00',
          applyLabel: '<?php echo label("m_ok"); ?>',
          cancelLabel: '<?php echo label("cancel"); ?>',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom Range',
          <?php if ($lang == "thai") { ?>
          daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
          monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
          ],
          <?php } else { ?>
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
          ],
          <?php } ?>
          firstDay: 1
        }
      },
      function(start, end) {
        $('#time_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
        $('#time_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
      });
    clear_dropify('#input-file-now-custom-cosv_thumbnail');
    clear_dropify('#input-file-now-custom-cosv_video');
    clear_dropify('#input-file-now-custom-media');
    clear_dropify('#input-file-now-custom-thumbnail_med');
    //clear_dropify('#input-file-now-custom-document');
    clear_dropify('#input-file-now-custom-scorm');

    $('table#myTable_document tr.row_document').remove();
    //fetch_data_document('');
    var table_media = $('#myTable_media').DataTable();
    var info_media = table_media.page.info();
    var length_media = info_media.pages;
    var page_current_media = info_media.page;
    fetch_data_media('', page_current_media);
  } else if (form_name == "videocourse_form") {
    $('#operation_cosv').val("Add");
    document.getElementById('div_multifile_url_videocourse').style.display = '';
    document.getElementById('div_multifile_upload_file_videocourse').style.display = 'none';
  } else if (form_name == "lesson_order_form") {
    $('#operation_lesson_order').val("Add");
    document.getElementById('div_create_lesson').style.display = 'none';
    document.getElementById('div_order_lesson').style.display = '';
    $.ajax({
      url: '<?= base_url() ?>index.php/course/li_lesson_course',
      type: 'POST',
      data: {
        cos_id: cos_id
      },
      success: function(data) {

        $('#load_li_lesson').html(data);
      }
    });
  } else if (form_name == "quiz_form") {
    $.ajax({
      url: '<?= base_url() ?>index.php/workgroup/select_qize',
      type: 'POST',
      data: {
        com_id: com_id
      },
      success: function(data) {

        $('#qize_id').html(data);
      }
    });
    document.getElementById("quiz_numofshown").readOnly = true;
    document.getElementById("quiz_numofshown").max = "";
    document.getElementById('div_template_qize').style.display = '';
    $('#operation_quiz').val("Add");
    /*
                    $("#period_open").datepicker("update", '');
                    $("#period_end").datepicker("update", '');*/

    $('#daterange_quiz').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: false,
        startDate: new Date(),
        endDate: new Date(),
        separator: ' to ',
        locale: {
          format: 'DD/MMMM/YYYY HH:mm:00',
          applyLabel: '<?php echo label("m_ok"); ?>',
          cancelLabel: '<?php echo label("cancel"); ?>',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom Range',
          <?php if ($lang == "thai") { ?>
          daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
          monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
          ],
          <?php } else { ?>
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
          ],
          <?php } ?>
          firstDay: 1
        }
      },
      function(start, end) {
        $('#period_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
        $('#period_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
      });
  } else if (form_name == "survey_form") {
    $('#operation_survey').val("Add");
    /*$("#survey_open").datepicker("update", '');
    $("#survey_end").datepicker("update", '');*/

    $('#daterange_survey').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: false,
        startDate: new Date(),
        endDate: new Date(),
        separator: ' to ',
        locale: {
          format: 'DD/MMMM/YYYY HH:mm:00',
          applyLabel: '<?php echo label("m_ok"); ?>',
          cancelLabel: '<?php echo label("cancel"); ?>',
          fromLabel: 'From',
          toLabel: 'To',
          customRangeLabel: 'Custom Range',
          <?php if ($lang == "thai") { ?>
          daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
          monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
          ],
          <?php } else { ?>
          daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
          monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
            'October', 'November', 'December'
          ],
          <?php } ?>
          firstDay: 1
        }
      },
      function(start, end) {
        $('#survey_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
        $('#survey_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
        //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
      });
  } else if (form_name == "survey_detail_form") {
    $('#operation_survey_detail').val("Add");
    document.getElementById('div_import_survey_detail').style.display = 'none';
  } else if (form_name == "survey_detail_import_form") {
    $('#operation_import_survey').val("Add");
    document.getElementById('div_create_survey_detail').style.display = 'none';
    document.getElementById('progress_import_survey_div').style.display = 'none';
    clear_dropify('#file_import_survey');
  } else if (form_name == "question_form") {
    $('#operation_question').val("Add");
    $("#mul_answer").val("");
    $("#mul_answer").trigger("change");
    $('#ques_type').val('0');
    document.getElementById('div_question_mul').style.display = 'none';
    document.getElementById('div_import_question').style.display = 'none';
  } else if (form_name == "question_import_form") {
    $('#operation_import_question').val("Add");
    document.getElementById('div_question_mul').style.display = 'none';
    document.getElementById('div_create_question').style.display = 'none';
  }
}

/*jQuery('#date-range').datepicker({
    toggleActive: true,
    format: 'dd/MM/yyyy'
});
jQuery('#date-range_les').datepicker({
    toggleActive: true,
    format: 'dd/MM/yyyy'
});
jQuery('#date-range_quiz').datepicker({
    toggleActive: true,
    format: 'dd/MM/yyyy'
});
jQuery('#date-range_survey').datepicker({
    toggleActive: true,
    format: 'dd/MM/yyyy'
});*/

$('.slimtest1').perfectScrollbar();
$('.dropify').dropify();
$('.dropify_main').dropify();
fetch_data('<?php echo $wcode; ?>', '<?php echo $cgcode; ?>', 0);

function fetch_data(wg_id, cg_id, page_num = 0) {
  $('#myTable').DataTable().destroy();
  var table = $('#myTable').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course/',
      type: 'GET',
      data: {
        wg_id: wg_id,
        cg_id: cg_id,
        lang: "<?php echo $lang; ?>"
      }
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

function run_analytic(cos_id) {
  window.location.href = '<?= base_url() ?>course/analytic_course/' + cos_id;
}

function fetch_data_enroll(cos_id, page_num = 0) {
  $('#myTable_enroll').DataTable().destroy();
  var table = $('#myTable_enroll').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_enroll", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_enroll/',
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
    },
    <?php if ($btn_print == "1") { ?>
    dom: 'Bfrtip',
    buttons: [
      'copy', 'excel', 'print'
    ]
    <?php } ?>
  });
}

function fetch_data_enroll_qiz(qiz_id, page_num = 0) {
  $('#myTable_enroll_qiz').DataTable().destroy();
  if (qiz_id != '') {
    var table = $('#myTable_enroll_qiz').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_enroll_qiz", message);
    }).DataTable({
      "ajax": {
        url: '<?= base_url() ?>index.php/course/fetch_course_enroll_qiz/',
        data: {
          qiz_id: qiz_id,
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
      },
      <?php if ($btn_print == "1") { ?>
      dom: 'Bfrtip',
      buttons: [
        'copy', 'excel', 'print'
      ]
      <?php } ?>
    });
  } else {
    var table = $('#myTable_enroll_qiz').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_enroll_qiz", message);
    }).DataTable({
      "ajax": {
        url: '<?= base_url() ?>index.php/course/fetch_course_enroll_qiz/',
        data: {
          qiz_id: '',
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
      },
      <?php if ($btn_print == "1") { ?>
      dom: 'Bfrtip',
      buttons: [
        'copy', 'excel', 'print'
      ]
      <?php } ?>
    });
  }
}

function fetch_data_register(cos_id, page_num = 0) {
  $('#myTable_register_qr').DataTable().destroy();
  $('#myTable_register_qr').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_register_qr", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_register/',
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

function fetch_data_detail(cos_id, page_num = 0) {
  $('#myTable_pp').DataTable().destroy();
  var table = $('#myTable_pp').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_pp", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_detail/',
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


function fetch_data_lesson(cos_id, page_num = 0) {
  $('#myTable_lesson').DataTable().destroy();
  var table = $('#myTable_lesson').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_lesson", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_lesson/',
      data: {
        cos_id: cos_id,
        status_user: '',
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

function fetch_data_coursevideo(cos_id, page_num = 0) {
  $('#myTable_videocourse').DataTable().destroy();
  var table = $('#myTable_videocourse').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_videocourse", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_videocourse/',
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


function fetch_data_quiz(cos_id, page_num = 0) {
  $('#myTable_quiz').DataTable().destroy();
  var table = $('#myTable_quiz').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_quiz", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_quiz/',
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

function fetch_data_survey(cos_id, page_num = 0) {
  $('#myTable_cos_id_survey').DataTable().destroy();
  var table = $('#myTable_cos_id_survey').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_cos_id_survey", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_survey/',
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

function fetch_data_survey_detail(sv_id, page_num = 0) {
  $('#myTable_survey_detail').DataTable().destroy();
  var table = $('#myTable_survey_detail').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_survey_detail", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_survey_detail/',
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

function fetch_data_question(quiz, page_num = 0) {
  $('#myTable_quiz_question').DataTable().destroy();
  var table = $('#myTable_quiz_question').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_quiz_question", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_course_question/',
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

function fetch_data_quiz_detail(qiz_id, page_num = 0) {
  $('#myTable_document').DataTable().destroy();
  $('#myTable_document').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_document", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_quiz_detail/',
      data: {
        qiz_id: qiz_id,
        lang: "<?php echo $lang; ?>"
      },
      type: 'GET'
    }
  });
}

function fetch_data_document(les_id) {
  $('#myTable_document').DataTable().destroy();
  $('#myTable_document').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_document", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_lesson_document/',
      data: {
        les_id: les_id,
        status_user: '100',
        lang: "<?php echo $lang; ?>"
      },
      type: 'GET'
    },
  });
}

function fetch_data_document_cos(cos_id, page_num = 0) {
  $('#myTable_cos_document').DataTable().destroy();
  var table = $('#myTable_cos_document').on('error.dt', function(e, settings, techNote, message) {
      notificationForDatatableError("myTable_cos_document", message);
    }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_cos_document/',
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

function fetch_data_media(les_id, page_num = 0) {
  $('#myTable_media').DataTable().destroy();
  var table = $('#myTable_media').on('error.dt', function(e, settings, techNote, message) {
    notificationForDatatableError("myTable_media", message);
  }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_lesson_media/',
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
var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
$('.js-switch').each(function() {
  new Switchery($(this)[0], $(this).data());
});
$(document).on('submit', '#transfer_form', function(event) {
  event.preventDefault();
  $.ajax({
    url: "<?= base_url() ?>index.php/transfer/loaddata",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    success: function(data) {

      if (data == "  2") {
        $('#transfer_form')[0].reset();
        $('#modal-default').modal('hide');
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          location.reload();
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});
$(document).on('submit', '#course_form', function(event) {
  event.preventDefault();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_course",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progress').text(percentComplete.toFixed(2) + '%');

          $('.progress-bar').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_div").style.display = "none";

      if (data == "2") {
        $('#course_form')[0].reset();
        $('#modal-default').modal('hide');
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          var table = $('#myTable').DataTable();
          var info = table.page.info();
          var length = info.pages;
          var page_current = info.page;
          fetch_data('<?php echo $wcode; ?>', '<?php echo $cgcode; ?>', page_current);
          //location.reload();
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#coursegroup_form')[0].reset();
          $('#cg_id').val(cg_id);
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});
$(document).on('submit', '#enroll_cancel_form', function(event) {
  event.preventDefault();
  var cos_id_enroll = $('#cos_id_enroll').val();
  var cosen_cancelnote = $('#cosen_cancelnote').val();
  var cosen_id_enroll = $('#cosen_id_enroll').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/enroll_cancel",
        method: "POST",
        data: {
          cosen_id_enroll: cosen_id_enroll,
          cosen_cancelnote: cosen_cancelnote
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              $('#cosen_cancelnote').val('');
              display_style("div_enroll_main", "div_enroll_cancel");

              var table_enroll = $('#myTable_enroll').DataTable();
              var info_enroll = table_enroll.page.info();
              var length_enroll = info_enroll.pages;
              var page_current_enroll = info_enroll.page;
              fetch_data_enroll(cos_id_enroll, page_current_enroll);
              var table_registerqr = $('#myTable_register_qr').DataTable();
              var info_registerqr = table_registerqr.page.info();
              var length_registerqr = info_registerqr.pages;
              var page_current_registerqr = info_registerqr.page;
              fetch_data_register(cos_id_enroll, page_current_registerqr);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

function formatDate(date) {
  var d = new Date(date),
    month = '' + (d.getMonth() + 1),
    day = '' + d.getDate(),
    year = d.getFullYear();

  if (month.length < 2) month = '0' + month;
  if (day.length < 2) day = '0' + day;

  return [year, month, day].join('-');
}
$(document).on('submit', '#survey_detail_form', function(event) {
  event.preventDefault();
  var course_id = $('#course_id_survey').val();
  var com_id = $('#com_id_survey').val();
  var sv_id = $('#sv_id_detail').val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_survey_detail",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_survey_detail_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progresssurvey_detail').text(percentComplete.toFixed(2) + '%');

          $('.progress-barsurvey_detail').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_survey_detail_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#survey_detail_form')[0].reset();
          display_style('div_create_survey_detail', 'div_sv_survey_detail');
          $('#course_id_survey').val(course_id);
          $('#com_id_survey').val(com_id);
          $('#sv_id_detail').val(sv_id);
          $('#sv_id_detail_import').val(sv_id);
          var table_surveydetail = $('#myTable_survey_detail').DataTable();
          var info_surveydetail = table_surveydetail.page.info();
          var length_surveydetail = info_surveydetail.pages;
          var page_current_surveydetail = info_surveydetail.page;
          fetch_data_survey_detail(sv_id, page_current_surveydetail);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#survey_detail_form')[0].reset();
          $('#course_id_survey').val(course_id);
          $('#com_id_survey').val(com_id);
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});
$(document).on('submit', '#survey_form', function(event) {
  event.preventDefault();
  var course_id = $('#course_id_survey').val();
  var com_id = $('#com_id_survey').val();
  /*var survey_open = new Date($('#survey_open').val());
  var survey_end = new Date($('#survey_end').val());
  $('#survey_open_var').val(formatDate(survey_open));
  $('#survey_end_var').val(formatDate(survey_end));
  console.log(formatDate(survey_open),formatDate(survey_end));*/
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_survey",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_survey_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progresssurvey').text(percentComplete.toFixed(2) + '%');

          $('.progress-barsurvey').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_survey_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#survey_form')[0].reset();
          display_style('div_create_survey', 'div_survey');
          $('#course_id_survey').val(course_id);
          $('#com_id_survey').val(com_id);
          var table_surveycos = $('#myTable_cos_id_survey').DataTable();
          var info_surveycos = table_surveycos.page.info();
          var length_surveycos = info_surveycos.pages;
          var page_current_surveycos = info_surveycos.page;
          fetch_data_survey(course_id, page_current_surveycos);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#survey_form')[0].reset();
          $('#course_id_survey').val(course_id);
          $('#com_id_survey').val(com_id);
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});

function clear_dropify(id) {
  var drEvent = $(id).dropify({
    defaultFile: ''
  });
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
  drEvent.settings.defaultFile = '';
  drEvent.destroy();
  drEvent.init();
}
$(document).on('submit', '#survey_detail_import_form', function(event) {
  event.preventDefault();
  var sv_id = $('#sv_id_detail_import').val();
  var file_import = $('#file_import_survey').val();
  if (file_import != "") {
    $.ajax({
      url: "<?= base_url() ?>index.php/setting/import_survey",
      method: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      xhr: function() {
        document.getElementById("progress_import_survey_div").style.display = "";
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            $('#txt_progressimport_survey').text(percentComplete.toFixed(2) + '%');

            $('.progress-bar_importsurvey').animate({
              width: percentComplete + '%'
            }, {
              duration: 100
            });
            //Do something with upload progress here
          }
        }, false);
        return xhr;
      },
      success: function(data) {

        if (data == "2") {
          document.getElementById("progress_import_survey_div").style.display = "none";
          $('#survey_detail_import_form')[0].reset();
          swal(
            '<?php echo label("import_msg_success"); ?>!',
            '',
            'success'
          ).then(function() {
            $('#sv_id_detail_import').val(sv_id);
            clear_dropify('#file_import_survey');
          })
        } else if (data == "1") {
          swal({
            title: '<?php echo label("manageimport_msgerror"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          }).then(function() {
            document.getElementById("progress_import_survey_div").style.display = "none";
            document.getElementById("file_import_survey").focus();
          })
        } else {
          swal({
            title: '<?php echo label("com_msg_error_save"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          })
        }

      }
    });
  } else {

    swal({
      title: '<?php echo label("manageimport_msgerror"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonClass: 'btn btn-primary',
      confirmButtonText: '<?php echo label("m_ok"); ?>'
    }).then(function() {
      document.getElementById("file_import_survey").focus();
    })
  }
});
$(document).on('submit', '#question_import_form', function(event) {
  event.preventDefault();
  var qiz_id = $('#qiz_id_question_import').val();
  var file_import = $('#file_import_question').val();
  if (file_import != "") {
    $.ajax({
      url: "<?= base_url() ?>index.php/setting/import_question",
      method: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      xhr: function() {
        document.getElementById("progress_import_question_div").style.display = "";
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function(evt) {
          if (evt.lengthComputable) {
            var percentComplete = (evt.loaded / evt.total) * 100;
            $('#txt_progressimport_question').text(percentComplete.toFixed(2) + '%');

            $('.progress-bar_importquestion').animate({
              width: percentComplete + '%'
            }, {
              duration: 100
            });
            //Do something with upload progress here
          }
        }, false);
        return xhr;
      },
      success: function(data) {

        if (data == "2") {
          document.getElementById("progress_import_question_div").style.display = "none";
          $('#question_import_form')[0].reset();
          swal(
            '<?php echo label("import_msg_success"); ?>!',
            '',
            'success'
          ).then(function() {
            $('#qiz_id_question_import').val(qiz_id);
            clear_dropify('#file_import_question');
          })
        } else if (data == "1") {
          swal({
            title: '<?php echo label("manageimport_msgerror"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          }).then(function() {
            document.getElementById("progress_import_question_div").style.display = "none";
            document.getElementById("file_import_question").focus();
          })
        } else {
          swal({
            title: '<?php echo label("com_msg_error_save"); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label("m_ok"); ?>'
          })
        }

      }
    });
  } else {

    swal({
      title: '<?php echo label("manageimport_msgerror"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonClass: 'btn btn-primary',
      confirmButtonText: '<?php echo label("m_ok"); ?>'
    }).then(function() {
      document.getElementById("file_import_question").focus();
    })
  }
});

$(document).on('submit', '#question_form', function(event) {
  event.preventDefault();
  var course_id = $('#cos_id_question').val();
  var qiz_id = $('#qiz_id_question').val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_question",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_question_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progressquestion').text(percentComplete.toFixed(2) + '%');

          $('.progress-barquestion').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_question_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#question_form')[0].reset();
          display_style('div_create_question', 'div_quiz_question');
          $('#cos_id_question').val(course_id);
          $('#qiz_id_question').val(qiz_id);

          $('#qiz_id_question_import').val(qiz_id);
          var table_question = $('#myTable_quiz_question').DataTable();
          var info_question = table_question.page.info();
          var length_question = info_question.pages;
          var page_current_question = info_question.page;
          fetch_data_question(qiz_id, page_current_question);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#question_form')[0].reset();
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});

$(document).on('submit', '#quiz_form', function(event) {
  event.preventDefault();
  /*
                var period_open = new Date($('#period_open').val());
                var period_end = new Date($('#period_end').val());*/
  var course_id = $('#course_id_quiz').val();
  /*
                $('#period_open_var').val(formatDate(period_open));
                $('#period_end_var').val(formatDate(period_end));
                console.log(formatDate(period_open),formatDate(period_end));*/
  document.getElementById('div_quiz_detail').style.display = 'none';
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_quiz",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_quiz_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progressquiz').text(percentComplete.toFixed(2) + '%');

          $('.progress-barquiz').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_quiz_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#quiz_form')[0].reset();
          display_style('div_create_quiz', 'div_quiz');
          $('#course_id_quiz').val(course_id);
          var table_quiz = $('#myTable_quiz').DataTable();
          var info_quiz = table_quiz.page.info();
          var length_quiz = info_quiz.pages;
          var page_current_quiz = info_quiz.page;
          fetch_data_quiz(course_id, page_current_quiz);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#quiz_form')[0].reset();
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});

$(document).on('submit', '#videocourse_form', function(event) {
  event.preventDefault();
  var course_id = $('#course_id_cosv').val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_videocourse",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_videocourse_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progressvideocourse').text(percentComplete.toFixed(2) + '%');

          $('.progress-barvideocourse').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_videocourse_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('.dropify').dropify();
          $('#videocourse_form')[0].reset();
          display_style('div_create_videocourse', 'div_videocourse');
          $('#course_id_cosv').val(course_id);
          clear_dropify('#input-file-now-custom-cosv_thumbnail');
          clear_dropify('#input-file-now-custom-cosv_video');
          var table_coursevideo = $('#myTable_videocourse').DataTable();
          var info_coursevideo = table_coursevideo.page.info();
          var length_coursevideo = info_coursevideo.pages;
          var page_current_coursevideo = info_coursevideo.page;
          fetch_data_coursevideo(course_id, page_current_coursevideo);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#videocourse_form')[0].reset();
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});

$(document).on('submit', '#lesson_form', function(event) {
  event.preventDefault();
  var time_start = new Date($('#time_start').val());
  var time_end = new Date($('#time_end').val());
  var course_id = $('#course_id_lesson').val();
  $('#time_start_var').val(formatDate(time_start));
  $('#time_end_var').val(formatDate(time_end));
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_lesson",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_lesson_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progresslesson').text(percentComplete.toFixed(2) + '%');

          $('.progress-barlesson').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_lesson_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#lesson_form')[0].reset();
          display_style('div_create_lesson', 'div_lesson');
          $('#course_id_lesson').val(course_id);

          $('.dropify').dropify();
          $('.dropify_main').dropify();
          var table_les = $('#myTable_lesson').DataTable();
          var info_les = table_les.page.info();
          var length_les = info_les.pages;
          var page_current_les = info_les.page;
          fetch_data_lesson(course_id, page_current_les);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("course_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#lesson_form')[0].reset();
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});
$(document).on('submit', '#period_and_permission_form', function(event) {
  event.preventDefault();
  var periodForm = $(this);
  var date_start = periodForm.find('[name="date_start"]').val();
  var date_end = periodForm.find('[name="date_end"]').val();
  var course_id = $('#course_id_pp').val();
  if (date_start == "") {
    periodForm.find('[name="date_start_var"]').val("");
  }
  if (date_end == "") {
    periodForm.find('[name="date_end_var"]').val("");
  }
  var val_chk = 1;
  if (date_start != "" && date_end != "") {
    var d_start = new Date(periodForm.find('[name="date_start_var"]').val().replace(/-/g, "/"));
    var d_end = new Date(periodForm.find('[name="date_end_var"]').val().replace(/-/g, "/"));
    if (d_start > d_end) {
      $('#date_end').val("");
      $('#date_end_var').val("");
      $('#date_end').focus();
      val_chk = 0;
    }
  }
  if (val_chk != 1) {
    return;
  }
  $.ajax({
    url: "<?= base_url() ?>index.php/course/insert_period_and_permission",
    method: 'POST',
    data: new FormData(this),
    contentType: false,
    processData: false,
    xhr: function() {
      document.getElementById("progress_pp_div").style.display = "";
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", function(evt) {
        if (evt.lengthComputable) {
          var percentComplete = (evt.loaded / evt.total) * 100;
          $('#txt_progresspp').text(percentComplete.toFixed(2) + '%');

          $('.progress-barpp').animate({
            width: percentComplete + '%'
          }, {
            duration: 100
          });
          //Do something with upload progress here
        }
      }, false);
      return xhr;
    },
    success: function(data) {
      document.getElementById("progress_pp_div").style.display = "none";

      if (data == "2") {
        swal(
          '<?php echo label("com_msg_success"); ?>!',
          '',
          'success'
        ).then(function() {
          $('#period_and_permission_form')[0].reset();
          display_style('div_create_pp', 'div_pp');
          $('#course_id_pp').val(course_id);
          var table = $('#myTable_pp').DataTable();
          var info = table.page.info();
          var length = info.pages;
          var page_current = info.page;
          fetch_data_detail(course_id, page_current);
        })
      } else if (data == "1") {
        swal({
          title: '<?php echo label("data_msg_duplicate"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        }).then(function() {
          $('#period_and_permission_form')[0].reset();
          display_style('div_create_pp', 'div_pp');
          $('#course_id_pp').val(course_id);

          var table = $('#myTable_pp').DataTable();
          var info = table.page.info();
          var length = info.pages;
          var page_current = info.page;
          fetch_data_detail(course_id, page_current);
        })
      } else {
        swal({
          title: '<?php echo label("com_msg_error_save"); ?>',
          text: "",
          type: 'warning',
          showCancelButton: false,
          confirmButtonClass: 'btn btn-primary',
          confirmButtonText: '<?php echo label("m_ok"); ?>'
        })
      }

    }
  });
});

function changeValEnableDivMedia(les_type) {
  if (les_type == '1') {
    document.getElementById('div_media').style.display = '';
    document.getElementById('div_scorm').style.display = 'none';
  } else {
    document.getElementById('div_media').style.display = 'none';
    document.getElementById('div_scorm').style.display = '';
  }
}




$('select[name="qiz_id_enroll"]').on('change', function() {
  var qiz_id = $(this).val();
  if (qiz_id != "000") {
    var table_enrollqiz = $('#myTable_enroll_qiz').DataTable();
    var info_enrollqiz = table_enrollqiz.page.info();
    var length_enrollqiz = info_enrollqiz.pages;
    var page_current_enrollqiz = info_enrollqiz.page;
    fetch_data_enroll_qiz(qiz_id, page_current_enrollqiz);
  } else {
    $('#myTable_enroll_qiz').DataTable().destroy();
    var table_enrollqiz = $('#myTable_enroll_qiz').DataTable();
    var info_enrollqiz = table_enrollqiz.page.info();
    var length_enrollqiz = info_enrollqiz.pages;
    var page_current_enrollqiz = info_enrollqiz.page;
    fetch_data_enroll_qiz('', page_current_enrollqiz);
  }
});

$('select[name="qn_id"]').on('change', function() {
  var qn_id = $(this).val();
  if (qn_id != "000") {
    $.ajax({
      url: "<?= base_url() ?>index.php/questionnaire/update_questionnaire_data",
      method: "POST",
      data: {
        id_update: qn_id
      },
      dataType: "json",
      success: function(data) {

        $('#sv_title_th').val(data.qn_title_th);
        $('#sv_title_en').val(data.qn_title_en);
        $('#sv_explanation_th').val(data.qn_explanation_th);
        $('#sv_explanation_en').val(data.qn_explanation_en);
        if (data.qn_suggestion_status == "1") {
          document.getElementById("radio_sv_suggestion_status1").checked = true;
        } else {
          document.getElementById("radio_sv_suggestion_status2").checked = true;
        }
      }
    });
  }
});

$('select[name="ques_type"]').on('change', function() {
  var ques_type = $(this).val();
  if (ques_type == 'multi') {
    document.getElementById('div_question_mul').style.display = '';
  } else {
    document.getElementById('div_question_mul').style.display = 'none';
  }
});
$('select[name="type_media_cosv"]').on('change', function() {
  var type_media_cosv = $(this).val();
  if (type_media_cosv == '1') {
    document.getElementById('div_multifile_url_videocourse').style.display = '';
    document.getElementById('div_multifile_upload_file_videocourse').style.display = 'none';
  } else if (type_media_cosv == '2') {
    //$('#url_media').val('');
    document.getElementById('div_multifile_url_videocourse').style.display = 'none';
    document.getElementById('div_multifile_upload_file_videocourse').style.display = '';
  } else {
    document.getElementById('div_multifile_url_videocourse').style.display = 'none';
    document.getElementById('div_multifile_upload_file_videocourse').style.display = 'none';
  }
});
$('select[name="type_media"]').on('change', function() {
  var type_media = $(this).val();
  if (type_media == '1') {
    $('#input-file-now-custom-document').val('');
    document.getElementById('div_multifile_url').style.display = '';
    document.getElementById('div_multifile_upload_file').style.display = 'none';
  } else if (type_media == '2') {
    //$('#url_media').val('');
    document.getElementById('div_multifile_url').style.display = 'none';
    document.getElementById('div_multifile_upload_file').style.display = '';
  } else {
    document.getElementById('div_multifile_url').style.display = 'none';
    document.getElementById('div_multifile_upload_file').style.display = 'none';
  }
});
$('select[name="wg_id"]').on('change', function() {
  var wg_id = $(this).val();
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcoursegroup',
    type: 'POST',
    data: {
      wg_id: wg_id,
      course_id: ''
    },
    success: function(data) {

      $('#cg_id').html(data);
    }
  });
});
$('select[name="wg_id_transfer"]').on('change', function() {
  var wg_id = $(this).val();
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcoursegroup',
    type: 'POST',
    data: {
      wg_id: wg_id,
      course_id: ''
    },
    success: function(data) {

      $('#cg_id_transfer').html(data);
    }
  });
});
$('select[name="com_id_transfer"]').on('change', function() {
  var com_id = $(this).val();
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckworkgroup',
    type: 'POST',
    data: {
      com_id: com_id,
      wg_id: ''
    },
    success: function(data) {

      $('#wg_id_transfer').html(data);
    }
  });
});
$('select[name="com_id"]').on('change', function() {
  var com_id = $(this).val();
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckworkgroup',
    type: 'POST',
    data: {
      com_id: com_id,
      wg_id: ''
    },
    success: function(data) {

      $('#wg_id').html(data);
    }
  });
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcostype',
    type: 'POST',
    data: {
      com_id: com_id,
      tc_id: ''
    },
    success: function(data) {

      $('#tc_id').html(data);
    }
  });

  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcondition',
    type: 'POST',
    data: {
      com_id: com_id,
      condition: ''
    },
    success: function(data) {

      $('#condition').html(data);
    }
  });
});
<?php if ($com_admin == "CUSTOMER") { ?>
var com_id = '<?php echo $com_id; ?>';
$.ajax({
  url: '<?= base_url() ?>index.php/workgroup/recheckworkgroup',
  type: 'POST',
  data: {
    com_id: com_id,
    wg_id: ''
  },
  success: function(data) {

    $('#wg_id').html(data);
  }
});

$.ajax({
  url: '<?= base_url() ?>index.php/workgroup/recheckcostype',
  type: 'POST',
  data: {
    com_id: com_id,
    tc_id: ''
  },
  success: function(data) {

    $('#tc_id').html(data);
  }
});

$.ajax({
  url: '<?= base_url() ?>index.php/workgroup/recheckcondition',
  type: 'POST',
  data: {
    com_id: com_id,
    condition: ''
  },
  success: function(data) {

    $('#condition').html(data);
  }
});
<?php } ?>
$(document).ready(function() {
  $('.select2').select2();
});
$('#add_button').click(function() {
  $('.modal-title').text('<?php echo label("create") . label("course"); ?>');
  $('#operation').val("Add");
  $('#course_form')[0].reset();
  $('#tc_id').empty();
  $('#cg_id').empty();
  $('#wg_id').empty();
  $('.dropify').dropify();
  $('.dropify_main').dropify();
  document.getElementById('cos_filedocument').style.display = "";
  <?php if ($com_admin == "CUSTOMER") { ?>
  var com_id = '<?php echo $com_id; ?>';
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckworkgroup',
    type: 'POST',
    data: {
      com_id: com_id,
      wg_id: ''
    },
    success: function(data) {

      $('#wg_id').html(data);
    }
  });

  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcostype',
    type: 'POST',
    data: {
      com_id: com_id,
      tc_id: ''
    },
    success: function(data) {

      $('#tc_id').html(data);
    }
  });

  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckcondition',
    type: 'POST',
    data: {
      com_id: com_id,
      condition: ''
    },
    success: function(data) {

      $('#condition').html(data);
    }
  });
  <?php } ?>
  clear_dropify('#input-file-now-custom-1');
  clear_dropify('#input-file-now-custom-2');

  document.getElementById('nav-item_document').style.display = 'none';
});

$(document).on('click', '.course_detail', function() {
  var id = $(this).attr("id");
  var com_id = $('#com_id' + id).val();

  $('#com_id_detail').val(com_id);
  $('#modal-coursedetail').modal('show');
  $('[href="#period_and_permission"]').tab('show');
  $('#operation_pp').val("Add");
  $('#course_id_pp').val(id);
  $('#course_id_lesson').val(id);
  $('#course_id_quiz').val(id);
  $('#course_id_survey').val(id);
  $('#course_id_cosv').val(id);
  $('#com_id_survey').val(com_id);

  document.getElementById('div_create_pp').style.display = 'none';
  document.getElementById('div_create_lesson').style.display = 'none';
  document.getElementById('div_create_quiz').style.display = 'none';
  document.getElementById('div_quiz_detail').style.display = 'none';
  document.getElementById('div_create_survey').style.display = 'none';
  document.getElementById('div_enroll_cancel').style.display = 'none';
  document.getElementById('div_create_videocourse').style.display = 'none';

  document.getElementById('div_videocourse').style.display = '';
  document.getElementById('div_pp').style.display = '';
  document.getElementById('div_lesson').style.display = '';
  document.getElementById('div_quiz').style.display = '';
  document.getElementById('div_survey').style.display = '';
  document.getElementById('div_enroll_main').style.display = '';
  $.ajax({
    url: '<?= base_url() ?>index.php/course/permission_course',
    type: 'POST',
    data: {
      com_id: com_id,
      course_id: id,
      cosde_id: ''
    },
    success: function(data_permiss) {
      $('#permission_div').html(data_permiss);
    }
  });

  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckquestionnaire',
    type: 'POST',
    data: {
      com_id: com_id,
      qn_id: ''
    },
    success: function(data_qn) {
      $('#qn_id').html(data_qn);
    }
  });

  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_course_data",
    method: "POST",
    data: {
      id_update: id
    },
    dataType: "json",
    success: function(data) {
      $.ajax({
        url: "<?= base_url() ?>index.php/coursetype/update_coursetype_data",
        method: "POST",
        data: {
          tc_id_update: data.tc_id
        },
        dataType: "json",
        success: function(datatype) {
          if (datatype.tc_lesson == "0") {
            document.getElementById('li_lesson').style.display = 'none';
          } else {
            document.getElementById('li_lesson').style.display = '';
          }
          if (datatype.tc_pretest == "0") {
            document.getElementById('li_quiz').style.display = 'none';
          } else {
            document.getElementById('li_quiz').style.display = '';
          }
          if (datatype.tc_questionnaire == "0") {
            document.getElementById('li_survey').style.display = 'none';
          } else {
            document.getElementById('li_survey').style.display = '';
          }
          if (datatype.tc_student_enroll == "0") {
            document.getElementById('li_enroll').style.display = 'none';
          } else {
            document.getElementById('li_enroll').style.display = '';
          }
          if (datatype.tc_qrcode == "0") {
            document.getElementById('li_qrcode').style.display = 'none';
          } else {
            document.getElementById('li_qrcode').style.display = '';
          }
          if (datatype.tc_videocourse == "0") {
            document.getElementById('li_videocourse').style.display = 'none';
          } else {
            document.getElementById('li_videocourse').style.display = '';
          }
        }



      });
    }
  });
  var table_pp = $('#myTable_pp').DataTable();
  var info_pp = table_pp.page.info();
  var length_pp = info_pp.pages;
  var page_current_pp = info_pp.page;
  fetch_data_detail(id, page_current_pp);
  var table_les = $('#myTable_lesson').DataTable();
  var info_les = table_les.page.info();
  var length_les = info_les.pages;
  var page_current_les = info_les.page;
  fetch_data_lesson(id, page_current_les);
  var table_quiz = $('#myTable_quiz').DataTable();
  var info_quiz = table_quiz.page.info();
  var length_quiz = info_quiz.pages;
  var page_current_quiz = info_quiz.page;
  fetch_data_quiz(id, page_current_quiz);
  var table_surveycos = $('#myTable_cos_id_survey').DataTable();
  var info_surveycos = table_surveycos.page.info();
  var length_surveycos = info_surveycos.pages;
  var page_current_surveycos = info_surveycos.page;
  fetch_data_survey(id, page_current_surveycos);
  var table_enroll = $('#myTable_enroll').DataTable();
  var info_enroll = table_enroll.page.info();
  var length_enroll = info_enroll.pages;
  var page_current_enroll = info_enroll.page;
  fetch_data_enroll(id, page_current_enroll);
  var table_coursevideo = $('#myTable_videocourse').DataTable();
  var info_coursevideo = table_coursevideo.page.info();
  var length_coursevideo = info_coursevideo.pages;
  var page_current_coursevideo = info_coursevideo.page;
  fetch_data_coursevideo(id, page_current_coursevideo);
});


$(document).on('click', '.back_quiz', function() {
  var cos_id = $('#course_id_quiz').val();
  document.getElementById('div_quiz').style.display = '';
  document.getElementById('div_create_quiz').style.display = 'none';
  document.getElementById('div_quiz_main').style.display = '';
  document.getElementById('div_quiz_detail').style.display = 'none';
  var table_quiz = $('#myTable_quiz').DataTable();
  var info_quiz = table_quiz.page.info();
  var length_quiz = info_quiz.pages;
  var page_current_quiz = info_quiz.page;
  fetch_data_quiz(cos_id, page_current_quiz);

});

$(document).on('click', '.back_survey_detail', function() {
  var cos_id = $('#course_id_survey').val();
  document.getElementById('div_survey').style.display = '';
  document.getElementById('div_create_survey').style.display = 'none';
  document.getElementById('div_survey_main').style.display = '';
  document.getElementById('div_survey_detail').style.display = 'none';
  document.getElementById('div_import_survey_detail').style.display = 'none';
  var table_surveycos = $('#myTable_cos_id_survey').DataTable();
  var info_surveycos = table_surveycos.page.info();
  var length_surveycos = info_surveycos.pages;
  var page_current_surveycos = info_surveycos.page;
  fetch_data_survey(cos_id, page_current_surveycos);
  display_disable('div_create_survey', 'div_survey');

});

$(document).on('click', '.quiz_detail', function() {
  var qiz_id = $(this).attr("id");
  var cos_id = $('#course_id_quiz').val();
  var lang = '<?php echo $lang; ?>';
  document.getElementById('div_quiz').style.display = 'none';
  document.getElementById('div_create_quiz').style.display = 'none';
  document.getElementById('div_import_question').style.display = 'none';
  document.getElementById('div_quiz_main').style.display = 'none';
  document.getElementById('div_quiz_detail').style.display = '';
  display_disable('div_create_question', 'div_quiz_question');

  $('#question_form')[0].reset();
  $('#qiz_id_question').val(qiz_id);
  $('#qiz_id_question_import').val(qiz_id);
  $('#cos_id_question').val(cos_id);
  var table_question = $('#myTable_quiz_question').DataTable();
  var info_question = table_question.page.info();
  var length_question = info_question.pages;
  var page_current_question = info_question.page;
  fetch_data_question(qiz_id, page_current_question);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/query_quiz_detail_data",
    method: "POST",
    data: {
      qiz_id_update: qiz_id
    },
    dataType: "json",
    success: function(data) {
      if (lang == "thai") {
        $('#quiz_name_txt').text(data.quiz_name_th);
      } else {
        $('#quiz_name_txt').text(data.quiz_name_en);
      }
    }
  });
});


$(document).on('click', '.survey_detail', function() {
  var sv_id = $(this).attr("id");
  var cos_id = $('#course_id_survey').val();
  var lang = '<?php echo $lang; ?>';
  document.getElementById('div_survey').style.display = 'none';
  document.getElementById('div_create_survey').style.display = 'none';
  document.getElementById('div_survey_main').style.display = 'none';
  document.getElementById('div_import_survey_detail').style.display = 'none';
  document.getElementById('div_survey_detail').style.display = '';
  display_disable('div_create_survey_detail', 'div_sv_survey_detail');

  $('#survey_detail_form')[0].reset();
  $('#sv_id_detail').val(sv_id);
  $('#sv_id_detail_import').val(sv_id);
  $('#cos_id_detail').val(cos_id);
  var table_surveydetail = $('#myTable_survey_detail').DataTable();
  var info_surveydetail = table_surveydetail.page.info();
  var length_surveydetail = info_surveydetail.pages;
  var page_current_surveydetail = info_surveydetail.page;
  fetch_data_survey_detail(sv_id, page_current_surveydetail);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_survey_detail_data",
    method: "POST",
    data: {
      sv_id_update: sv_id
    },
    dataType: "json",
    success: function(data) {
      if (lang == "thai") {
        $('#sv_name_txt').text(data.sv_title_th);
      } else {
        $('#sv_name_txt').text(data.sv_title_en);
      }
    }
  });
});
$(document).on('click', '.update_survey_detail', function() {
  var svde_id = $(this).attr("id");
  document.getElementById('div_survey_main').style.display = 'none';
  document.getElementById('div_import_survey_detail').style.display = 'none';
  //var com_id = $('com_id_survey').val();
  var cos_id = $('course_id_survey').val();
  var sv_id = $('sv_id_detail').val();
  var com_id = $('#com_id' + cos_id).val();
  $('#survey_detail_form')[0].reset();
  $('#operation_survey_detail').val("Edit");
  $('#svde_id').val(svde_id);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_survey_sv_detail_data",
    method: "POST",
    data: {
      svde_id_update: svde_id
    },
    dataType: "json",
    success: function(data) {
      $('#svde_heading_th').val(data.svde_heading_th);
      $('#svde_heading_en').val(data.svde_heading_en);
      $('#svde_detail_th').val(data.svde_detail_th);
      $('#svde_detail_en').val(data.svde_detail_en);
    }
  });
  display_style('div_create_survey_detail', 'div_sv_survey_detail');
});
$(document).on('click', '.update_survey', function() {
  var sv_id = $(this).attr("id");
  document.getElementById('div_survey_detail').style.display = 'none';
  //var com_id = $('com_id_survey').val();
  var cos_id = $('course_id_survey').val();
  var com_id = $('#com_id' + cos_id).val();
  //$('#survey_form')[0].reset();
  $('#operation_survey').val("Edit");
  $('#sv_id').val(sv_id);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_survey_detail_data",
    method: "POST",
    data: {
      sv_id_update: sv_id
    },
    dataType: "json",
    success: function(data) {

      $('#sv_title_th').val(data.sv_title_th);
      $('#sv_title_en').val(data.sv_title_en);
      $('#sv_explanation_th').val(data.sv_explanation_th);
      $('#sv_explanation_en').val(data.sv_explanation_en);
      $('#quiz_name_en').val(data.quiz_name_en);
      $('#quiz_name_en').val(data.quiz_name_en);
      $('#course_id_survey').val(data.cos_id);
      var com_id = $('#com_id' + data.cos_id).val();
      $('com_id_survey').val(com_id);
      $.ajax({
        url: '<?= base_url() ?>index.php/workgroup/recheckquestionnaire',
        type: 'POST',
        data: {
          com_id: com_id,
          qn_id: data.qn_id
        },
        success: function(data) {

          $('#qn_id').html(data);
        }
      });
      if (data.sv_suggestion_status == "1") {
        document.getElementById("radio_sv_suggestion_status1").checked = true;
      } else {
        document.getElementById("radio_sv_suggestion_status2").checked = true;
      }

      var date_start = data.survey_open_var.split(/[- :]/);
      var date_end = data.survey_end_var.split(/[- :]/);

      // Apply each element to the Date function

      var ddate_start = mysqlTimeStampToDate(data.survey_open_var);
      var date_end = mysqlTimeStampToDate(data.survey_end_var);
      if (data.survey_open_var == "" && data.survey_end_var == "") {
        ddate_start = new Date();
        date_end = new Date();
      }
      $('#daterange_survey').daterangepicker({
          timePicker: true,
          timePicker24Hour: true,
          timePickerSeconds: false,
          startDate: ddate_start,
          endDate: date_end,
          separator: ' to ',
          locale: {
            format: 'DD/MMMM/YYYY HH:mm:00',
            applyLabel: '<?php echo label("m_ok"); ?>',
            cancelLabel: '<?php echo label("cancel"); ?>',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            <?php if ($lang == "thai") { ?>
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม',
              'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ],
            <?php } else { ?>
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
              'September', 'October', 'November', 'December'
            ],
            <?php } ?>
            firstDay: 1
          }
        },
        function(start, end) {
          $('#survey_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
          $('#survey_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
          //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        });
      /*$('#survey_open_var').val(data.survey_open_var);
      $('#survey_end_var').val(data.survey_end_var);
      $("#survey_open").datepicker("update", data.survey_open);
      $("#survey_end").datepicker("update", data.survey_end);*/
    }
  });
  display_style('div_create_survey', 'div_survey');
});

function mysqlTimeStampToDate(timestamp) {
  //function parses mysql datetime string and returns javascript Date object
  //input has to be in this format: 2007-06-05 15:26:02
  if (!timestamp || timestamp == "0000-00-00 00:00:00") {
    return new Date();
  }
  var regex = /^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
  var parts = timestamp.replace(regex, "$1 $2 $3 $4 $5 $6").split(' ');
  return new Date(parts[0], parts[1] - 1, parts[2], parts[3], parts[4], parts[5]);
}

function displayCourseDate(value) {
  if (!value || value == "0000-00-00 00:00:00") {
    return "";
  }
  var date = value.split(" ")[0].split("-");
  if (date.length != 3) {
    return "";
  }
  return date[2] + "/" + date[1] + "/" + date[0];
}

function setCoursePeriodInputValues(data) {
  var startVar = data.date_start_var || "";
  var endVar = data.date_end_var || "";
  var startDisplay = data.date_start || displayCourseDate(startVar);
  var endDisplay = data.date_end || displayCourseDate(endVar);
  $('[id="date_start_var"], [name="date_start_var"]').val(startVar);
  $('[id="date_end_var"], [name="date_end_var"]').val(endVar);
  $('[id="date_start"], [name="date_start"]').each(function() {
    var input = $(this);
    if (startDisplay != "" && input.data('datepicker')) {
      input.datepicker("update", startDisplay);
    } else {
      input.val(startDisplay);
    }
  });
  $('[id="date_end"], [name="date_end"]').each(function() {
    var input = $(this);
    if (endDisplay != "" && input.data('datepicker')) {
      input.datepicker("update", endDisplay);
    } else {
      input.val(endDisplay);
    }
  });
}
$(document).on('click', '.update_quiz', function() {
  var qiz_id = $(this).attr("id");
  document.getElementById('div_quiz_detail').style.display = 'none';
  document.getElementById('div_template_qize').style.display = 'none';

  $('#operation_quiz').val("Edit");
  $('#qiz_id').val(qiz_id);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/query_quiz_detail_data",
    method: "POST",
    data: {
      qiz_id_update: qiz_id
    },
    dataType: "json",
    success: function(data) {
      if (parseInt(data.result_ques) > 0) {
        document.getElementById("quiz_numofshown").readOnly = false;
        $('#quiz_numofshown').val(data.quiz_numofshown);
        $('#totalquiz').val(data.result_ques);
        $('#txt_totalquiz').text(" / " + data.result_ques);
        document.getElementById("quiz_numofshown").max = data.result_ques;
      } else {
        document.getElementById("quiz_numofshown").max = "";
        document.getElementById("quiz_numofshown").readOnly = true;
      }
      $('#quiz_name_th').val(data.quiz_name_th);
      $('#quiz_name_en').val(data.quiz_name_en);
      $(tinymce.get('quiz_info_th').getBody()).html(data.quiz_info_th);
      $(tinymce.get('quiz_info_en').getBody()).html(data.quiz_info_en);

      if (data.quiz_random == "1") {
        document.getElementById("radio_random1").checked = true;
      } else {
        document.getElementById("radio_random2").checked = true;
      }
      if (data.quiz_show == "1") {
        document.getElementById("radio_show1").checked = true;
      } else {
        document.getElementById("radio_show2").checked = true;
      }
      if (data.quiz_grade == "1") {
        document.getElementById("radio_grade1").checked = true;
      } else {
        document.getElementById("radio_grade2").checked = true;
      }
      if (data.quiz_type == "1") {
        display('1', 'div_answer');
        document.getElementById("radio_type1").checked = true;
      } else {
        display('2', 'div_answer');
        document.getElementById("radio_type2").checked = true;
        if (data.quiz_answer == "1") {
          document.getElementById("radio_answer1").checked = true;
        } else {
          document.getElementById("radio_answer2").checked = true;
        }
      }
      Select_quiz_type(data.quiz_type, data.quiz_limitval, data.quiz_limit);
      /*if(data.quiz_limit=="1"){
          //$('#quiz_limitval').val(data.quiz_limitval);
          readonly('1','quiz_limitval');
          document.getElementById("radio_limit1").checked = true;
      }else{
          //$('#quiz_limitval').val('');
          readonly('0','quiz_limitval');
          document.getElementById("radio_limit2").checked = true;
      }*/

      $('#quiz_maxscore').val(data.quiz_maxscore);
      $('#period_open_var').val(data.period_open_var);
      $('#period_end_var').val(data.period_end_var);

      var ddate_start = mysqlTimeStampToDate(data.period_open_var);
      var date_end = mysqlTimeStampToDate(data.period_end_var);
      if (data.period_open_var == "" && data.period_end_var == "") {
        ddate_start = new Date();
        date_end = new Date();
      }
      $('#daterange_quiz').daterangepicker({
          timePicker: true,
          timePicker24Hour: true,
          timePickerSeconds: false,
          startDate: ddate_start,
          endDate: date_end,
          separator: ' to ',
          locale: {
            format: 'DD/MMMM/YYYY HH:mm:00',
            applyLabel: '<?php echo label("m_ok"); ?>',
            cancelLabel: '<?php echo label("cancel"); ?>',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            <?php if ($lang == "thai") { ?>
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม',
              'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ],
            <?php } else { ?>
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
              'September', 'October', 'November', 'December'
            ],
            <?php } ?>
            firstDay: 1
          }
        },
        function(start, end) {
          $('#period_open_var').val(start.format('YYYY-MM-DD HH:mm:00'));
          $('#period_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
          //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        });
      /*$("#period_open").datepicker("update", data.period_open);
      $("#period_end").datepicker("update", data.period_end);*/
    }
  });
  display_style('div_create_quiz', 'div_quiz');
});
$(document).on('click', '.update_period', function() {
  var cosde_id = $(this).attr("id");

  $('#operation_pp').val("Edit");
  $('#cosde_id').val(cosde_id);
  var cos_id = $('#course_id_pp').val();
  var com_id = $('#com_id_detail').val();
  $.ajax({
    url: '<?= base_url() ?>index.php/course/permission_course',
    type: 'POST',
    data: {
      com_id: com_id,
      course_id: cos_id,
      cosde_id: cosde_id
    },
    success: function(data) {

      $('#permission_div').html(data);
    }
  });
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_course_detail_data",
    method: "POST",
    data: {
      cosde_id_update: cosde_id
    },
    dataType: "json",
    success: function(data) {
      setCoursePeriodInputValues(data);
      $('#time_start').val(data.time_start || '00:00');
      $('#time_end').val(data.time_end || '23:59');
      $('#get_point').val(data.get_point);
      $('#point_redeem').val(data.point_redeem);
      //$('#daterange_period').val(data.date_start+' - '+data.date_end);
      //$('.timeseconds').data('dateRangePicker').setDateRange(data.date_start,data.date_end);
      //$("#date_start").datepicker("update", data.date_start);
      //$("#date_end").datepicker("update", data.date_end);
      /*$('#timestart_pp').val(data.timestart);
      $('#timeend_pp').val(data.timeend);*/
      var ddate_start = data.date_start_var ? mysqlTimeStampToDate(data.date_start_var) : new Date();
      var date_end = data.date_end_var ? mysqlTimeStampToDate(data.date_end_var) : ddate_start;
      $('#daterange_period').daterangepicker({
          timePicker: true,
          timePicker24Hour: true,
          timePickerSeconds: false,
          startDate: ddate_start,
          endDate: date_end,
          separator: ' to ',
          locale: {
            format: 'DD/MMMM/YYYY HH:mm:00',
            applyLabel: '<?php echo label("m_ok"); ?>',
            cancelLabel: '<?php echo label("cancel"); ?>',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            <?php if ($lang == "thai") { ?>
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม',
              'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ],
            <?php } else { ?>
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
              'September', 'October', 'November', 'December'
            ],
            <?php } ?>
            firstDay: 1
          }
        },
        function(start, end) {
          $('#date_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
          $('#date_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
          //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        });
      setCoursePeriodInputValues(data);
      setTimeout(function() {
        setCoursePeriodInputValues(data);
      }, 100);
    }
  });
  display_style('div_create_pp', 'div_pp');
});
$(document).on('click', '.check_ques', function() {
  var ques_id = $(this).attr("id");
  document.getElementById('div_question_check').style.display = '';
  document.getElementById('div_quiz_detail').style.display = 'none';

  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_question_detail_data",
    method: "POST",
    data: {
      ques_id_update: ques_id
    },
    dataType: "json",
    success: function(data) {
      <?php if ($lang == "thai") { ?>
      $('#quiz_name_txt_question').html('<?php echo label("chk_answer_txt"); ?>' + data.ques_name_th);
      <?php } else { ?>
      $('#quiz_name_txt_question').html('<?php echo label("chk_answer_txt"); ?>' + data.ques_name_en);
      <?php } ?>
    }
  });
  fetch_data_quiz_question_check(ques_id);
});

function changeScore_tc(tc_id) {
  var tc_score = $('#score_' + tc_id).val();
  var ques_score = $('#ques_score_' + tc_id).val();
  var ori_score = $('#ori_score_' + tc_id).val();
  if (parseFloat(tc_score) > parseFloat(ques_score)) {
    swal({
      title: '<?php echo label("score_over"); ?>',
      text: "",
      type: 'warning',
      showCancelButton: false,
      confirmButtonClass: 'btn btn-primary',
      confirmButtonText: '<?php echo label("m_ok"); ?>'
    }).then(function() {
      $('#score_' + tc_id).val(ori_score);
    })
  } else {
    $.ajax({
      url: '<?= base_url() ?>index.php/course/update_score_tc',
      type: 'POST',
      data: {
        tc_id: tc_id,
        tc_score: tc_score
      },
      success: function(answer) {}
    });
  }
}

function changeNote_tc(tc_id) {
  var tc_note = $('#tc_note_' + tc_id).val();
  $.ajax({
    url: '<?= base_url() ?>index.php/course/update_note_tc',
    type: 'POST',
    data: {
      tc_id: tc_id,
      tc_note: tc_note
    },
    success: function(note) {}
  });
}

function fetch_data_quiz_question_check(ques_id) {
  $('#myTable_quiz_question_check').DataTable().destroy();
  $('#myTable_quiz_question_check').on('error.dt', function(e, settings, techNote, message) {
    notificationForDatatableError("myTable_quiz_question_check", message);
  }).DataTable({
    "ajax": {
      url: '<?= base_url() ?>index.php/course/fetch_quiz_question_check/',
      data: {
        ques_id: ques_id,
        lang: "<?php echo $lang; ?>"
      },
      type: 'GET'
    },
    "columnDefs": [{
        width: "20%",
        targets: 0
      },
      {
        width: "10%",
        targets: 1
      },
      {
        width: "15%",
        targets: 2
      },
      {
        width: "25%",
        targets: 3
      },
      {
        width: "30%",
        targets: 4
      }
    ],
  });
}
$(document).on('click', '.update_ques', function() {
  var ques_id = $(this).attr("id");

  $('#operation_question').val("Edit");
  $('#ques_id').val(ques_id);

  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckmul_answer',
    type: 'POST',
    data: {
      ques_id: ''
    },
    success: function(answer) {
      $('#mul_answer').html(answer);
    }
  });
  var qiz_id = $('#qiz_id_question').val();
  var cos_id = $('#course_id_quiz').val();
  $('#cos_id_question').val(cos_id);
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_question_detail_data",
    method: "POST",
    data: {
      ques_id_update: ques_id
    },
    dataType: "json",
    success: function(data) {
      $('#ques_type').val(data.ques_type);
      $('#ques_score').val(data.ques_score);
      $(tinymce.get('ques_name_th').getBody()).html(data.ques_name_th);
      $(tinymce.get('ques_name_en').getBody()).html(data.ques_name_en);
      $(tinymce.get('ques_info_th').getBody()).html(data.ques_info_th);
      $(tinymce.get('ques_info_en').getBody()).html(data.ques_info_en);
      if (data.ques_show == "1") {
        document.getElementById("radio_ques_show1").checked = true;
      } else {
        document.getElementById("radio_ques_show2").checked = true;
      }
      if (data.ques_type == 'multi') {
        document.getElementById('div_question_mul').style.display = '';
        $(tinymce.get('mul_c1_th').getBody()).html(data.multi['mul_c1_th']);
        $(tinymce.get('mul_c2_th').getBody()).html(data.multi['mul_c2_th']);
        $(tinymce.get('mul_c3_th').getBody()).html(data.multi['mul_c3_th']);
        $(tinymce.get('mul_c4_th').getBody()).html(data.multi['mul_c4_th']);
        $(tinymce.get('mul_c5_th').getBody()).html(data.multi['mul_c5_th']);
        $(tinymce.get('mul_c1_en').getBody()).html(data.multi['mul_c1_en']);
        $(tinymce.get('mul_c2_en').getBody()).html(data.multi['mul_c2_en']);
        $(tinymce.get('mul_c3_en').getBody()).html(data.multi['mul_c3_en']);
        $(tinymce.get('mul_c4_en').getBody()).html(data.multi['mul_c4_en']);
        $(tinymce.get('mul_c5_en').getBody()).html(data.multi['mul_c5_en']);
        var myarr = data.multi['mul_answer'];
        if (myarr != "") {
          $.ajax({
            url: '<?= base_url() ?>index.php/workgroup/recheckmul_answer',
            type: 'POST',
            data: {
              ques_id: ques_id
            },
            success: function(answer) {
              $('#mul_answer').html(answer);
            }
          });
        }
      } else {
        document.getElementById('div_question_mul').style.display = 'none';
      }

    }
  });
  display_style('div_create_question', 'div_quiz_question');
});
$(document).on('click', '.add_file_lesson', function() {
  var count_file = parseInt($('#count_file').val());
  count_file++;
  $('#count_file').val(count_file);
  $('#myTable_document tr:last').after('<tr id="row_' + count_file +
    '" class="row_document"><td align="center"><button name="del_row_lessonfile" id="row_' + count_file +
    '" class="btn btn-youtube waves-effect waves-light del_row_lessonfile" onclick="return false;"><i class="mdi mdi-window-close"></i></button></td><td><input type="text" class="form-control" name="name_fileth[]" required id="name_fileth"></td><td><input type="text" class="form-control" name="name_fileen[]" required id="name_fileen"></td><td><input type="file" name="path_file[]" required id="path_file" accept=".pdf" /><input type="hidden" id="path_file_ori_' +
    count_file + '" name="path_file_ori[]" value=""><input type="hidden" id="id_fil_' + count_file +
    '" name="id_fil[]" value=""></td></tr>');
});

$(document).on('click', '.del_row_lessonfile', function() {
  var id = $(this).attr("id");

  var id_fil = $('#id_fil_' + id.substr(4)).val();
  var id_filedit = $('#id_filedit_' + id.substr(4)).val();

  if (id_filedit != "") {
    swal({
      title: '<?php echo label('wg_delete_msg'); ?>',
      text: "",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: '<?php echo label('wg_delete'); ?>',
      cancelButtonText: '<?php echo label("m_cancel"); ?>'
    }).then(function(isChk) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id_filedit,
          field: "id",
          table_name: "lms_fil"
        },
        dataType: "json",
        success: function(data) {
          $('table#myTable_document tr#' + id).remove();
        }
      });
    });
  } else {
    $('table#myTable_document tr#' + id).remove();
  }
});
$(document).on('click', '.update_lesson', function() {
  var les_id = $(this).attr("id");

  $('#operation_lesson').val("Edit");
  $('#les_id').val(les_id);

  clear_dropify('#input-file-now-custom-cosv_thumbnail');
  clear_dropify('#input-file-now-custom-cosv_video');
  clear_dropify('#input-file-now-custom-media');
  clear_dropify('#input-file-now-custom-thumbnail_med');
  //clear_dropify('#input-file-now-custom-document');
  clear_dropify('#input-file-now-custom-scorm');
  $('table#myTable_document tr.row_document').remove();
  var cos_id = $('#course_id_lesson').val();
  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_lesson_detail_data",
    method: "POST",
    data: {
      les_id_update: les_id
    },
    dataType: "json",
    success: function(data) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/query_fil_lesson",
        method: "POST",
        data: {
          les_id: les_id
        },
        success: function(data) {

          $('#tb_document_body').html(data);
        }
      });
      $('#count_file').val(data.num_fil);


      /*var date_start = data.date_start_var.split(/[- :]/);
      var date_end = data.date_end_var.split(/[- :]/);*/

      // Apply each element to the Date function

      var ddate_start = mysqlTimeStampToDate(data.time_start_var);
      var date_end = mysqlTimeStampToDate(data.time_end_var);
      if (data.time_start_var == "" && data.time_end_var == "") {
        ddate_start = new Date();
        date_end = new Date();
      }

      $('#daterange_lesson').daterangepicker({
          timePicker: true,
          timePicker24Hour: true,
          timePickerSeconds: false,
          startDate: ddate_start,
          endDate: date_end,
          separator: ' to ',
          locale: {
            format: 'DD/MMMM/YYYY HH:mm:00',
            applyLabel: '<?php echo label("m_ok"); ?>',
            cancelLabel: '<?php echo label("cancel"); ?>',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom Range',
            <?php if ($lang == "thai") { ?>
            daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
            monthNames: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม',
              'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            ],
            <?php } else { ?>
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
              'September', 'October', 'November', 'December'
            ],
            <?php } ?>
            firstDay: 1
          }
        },
        function(start, end) {
          $('#time_start_var').val(start.format('YYYY-MM-DD HH:mm:00'));
          $('#time_end_var').val(end.format('YYYY-MM-DD HH:mm:00'));
          //$('#reportrange span').html(start.format('D MMMM YYYY') + ' - ' + end.format('D MMMM YYYY'));
        });
      $('#time_start_var').val(data.time_start_var);
      $('#time_end_var').val(data.time_end_var);
      $('#les_name_th').val(data.les_name_th);
      $('#les_name_en').val(data.les_name_en);
      $(tinymce.get('les_info_th').getBody()).html(data.les_info_th);
      $(tinymce.get('les_info_en').getBody()).html(data.les_info_en);
      $('#status').val(data.status);
      $('#les_type').val(data.les_type);
      $('#scm_type').val(data.scm_type);
      //$("#time_start").datepicker("update", data.time_start);
      //$("#time_end").datepicker("update", data.time_end);
      if (data.scm_type == "0") {
        document.getElementById("radio_scm_type1").checked = true;
      } else if (data.scm_type == "1") {
        document.getElementById("radio_scm_type2").checked = true;
      } else if (data.scm_type == "2") {
        document.getElementById("radio_scm_type3").checked = true;
      }
      if (data.les_type == "1") {
        document.getElementById("radio_les_type1").checked = true;
        changeValEnableDivMedia('1');
      } else {
        document.getElementById("radio_les_type2").checked = true;
        changeValEnableDivMedia('2');
        if (data.scorm['path'] != "") {
          $('#txt_scormoriginal').text("File Scorm Original : " + data.scorm['path']);
        } else {
          $('#txt_scormoriginal').text('');
        }
      }

      if (data.url != "") {
        $('#type_media').val("1");
        $("#url_media").val(data.url);
        document.getElementById('div_multifile_url').style.display = '';
        document.getElementById('div_multifile_upload_file').style.display = 'none';
      }
      /*if(data.document.length>0){
          fetch_data_document(les_id);
          document.getElementById('tb_document').style.display = '';
      }else{
          document.getElementById('tb_document').style.display = 'none';
      }*/
      if (data.upload.length > 0) {
        $('#type_media').val("2");
        document.getElementById('div_multifile_url').style.display = 'none';
        document.getElementById('div_multifile_upload_file').style.display = '';
        var table_media = $('#myTable_media').DataTable();
        var info_media = table_media.page.info();
        var length_media = info_media.pages;
        var page_current_media = info_media.page;
        fetch_data_media(les_id, page_current_media);
        document.getElementById('tb_media').style.display = '';
      } else {
        document.getElementById('tb_media').style.display = 'none';
      }

    }
  });
  display_style('div_create_lesson', 'div_lesson');
});

function chk_posi(dep_id = "") {
  if ($('input[id="chkdep_' + dep_id + '"]').is(':checked')) {
    $('input[class="chkall_' + dep_id + '"]').prop('checked', true);
  } else {
    $('input[class="chkall_' + dep_id + '"]').prop('checked', false);
  }
}

function clear_dropify(id) {
  var drEvent = $(id).dropify({
    defaultFile: ''
  });
  drEvent = drEvent.data('dropify');
  drEvent.resetPreview();
  drEvent.clearElement();
  drEvent.settings.defaultFile = '';
  drEvent.destroy();
  drEvent.init();
}
/*  $(document).on('click', '.btn_add_file_cos', function(){
    $('input[name^="document_cos_file"]').each(function() {

        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            console.log($(this).get(0).files[i]);
        }
    });
  }); */
$(document).on('click', '.edit_document_cos', function() {
  var fil_cos_id = $(this).attr("id");
  $('.dropify').dropify();
  $('#fil_cos_id').val('');
  $('#name_fileth').val('');
  $('#name_fileen').val('');
  $('#document_cos_file_original').val('');
  document.getElementById('cos_filedocument').style.display = "none";
  $.ajax({
    url: "<?= base_url() ?>index.php/course/query_data_cosdocumentfile",
    method: "POST",
    data: {
      fil_cos_id: fil_cos_id
    },
    dataType: "json",
    success: function(data) {
      $('#fil_cos_id').val(data.fil_cos_id);
      $('#name_fileth').val(data.name_fileth);
      $('#name_fileen').val(data.name_fileen);
      $('#document_cos_file_original').val(data.path_file);
    }
  });
});

$(document).on('click', '.update', function() {
  var id = $(this).attr("id");

  $.ajax({
    url: "<?= base_url() ?>index.php/course/update_course_data",
    method: "POST",
    data: {
      id_update: id
    },
    dataType: "json",
    success: function(data) {

      $('.nav-tabs a[href="#home"]').tab('show');
      $('#modal-default').modal('show');
      $('.modal-title').text('<?php echo label("edit") . label("course"); ?>');
      $('#operation').val("Edit");
      $('#course_form')[0].reset();
      $('#ccode').val(data.ccode);
      $('#cname_th').val(data.cname_th);
      $('#cname_en').val(data.cname_en);
      $('.dropify_main').dropify();
      $('.dropify').dropify();
      document.getElementById('cos_filedocument').style.display = "";
      $('#sub_description_th').val(data.sub_description_th);
      $('#sub_description_en').val(data.sub_description_en);
      var table_doccos = $('#myTable_cos_document').DataTable();
      var info_doccos = table_doccos.page.info();
      var length_doccos = info_doccos.pages;
      var page_current_doccos = info_doccos.page;
      fetch_data_document_cos(id, page_current_doccos);
      document.getElementById('nav-item_document').style.display = '';
      //clear_dropify('#input-file-now-custom-document_cos');
      $(tinymce.get('cdesc_th').getBody()).html(data.cdesc_th);
      $(tinymce.get('cdesc_en').getBody()).html(data.cdesc_en);
      $('#goal_score').val(data.goal_score);
      $('#seat_count').val(data.seat_count);
      $('#image_ori').val(data.pic);
      $('#com_id').val(data.com_id);
      $('#status').val(data.status);
      $('#hour').val(data.hour);
      if (data.status == "1") {
        document.getElementById("radio1").checked = true;
      } else {
        document.getElementById("radio2").checked = true;
      }
      if (data.cos_public == "1") {
        document.getElementById("chk_cos_public").checked = true;
      } else {
        document.getElementById("chk_cos_public").checked = false;
      }
      $.ajax({
        url: '<?= base_url() ?>index.php/workgroup/recheckworkgroup',
        type: 'POST',
        data: {
          com_id: data.com_id,
          wg_id: data.wg_id
        },
        success: function(datawg) {
          $('#wg_id').html(datawg);
        }
      });

      $.ajax({
        url: '<?= base_url() ?>index.php/workgroup/recheckcostype',
        type: 'POST',
        data: {
          com_id: data.com_id,
          tc_id: data.tc_id
        },
        success: function(datatypecos) {
          $('#tc_id').html(datatypecos);
        }
      });

      $.ajax({
        url: '<?= base_url() ?>index.php/workgroup/recheckcondition',
        type: 'POST',
        data: {
          com_id: data.com_id,
          condition: data.condition
        },
        success: function(datacondition) {
          $('#condition').html(datacondition);
        }
      });
      $.ajax({
        url: '<?= base_url() ?>index.php/workgroup/recheckcoursegroup',
        type: 'POST',
        data: {
          wg_id: data.wg_id,
          course_id: data.id
        },
        success: function(datacg) {
          $('#cg_id').html(datacg);
        }
      });

      if (data.pic != "") {
        var nameImage = "<?php echo REAL_PATH; ?>/uploads/course/" + data.pic
        var drEvent = $('#input-file-now-custom-1').dropify({
          defaultFile: nameImage
        });
        drEvent = drEvent.data('dropify');
        drEvent.resetPreview();
        drEvent.clearElement();
        drEvent.settings.defaultFile = nameImage;
        drEvent.destroy();
        drEvent.init();

        var drEvent = $('.dropify_main').dropify({
          defaultFile: "<?php echo REAL_PATH; ?>/uploads/course/" + data.pic,
        });

        drEvent.on('dropify.beforeClear', function(event, element) {
          $('#image_ori').val("");
          return true;
        });
      } else {
        $('.dropify_main').dropify();
      }
      $('#id').val(data.id);
      id = data.id;
      $.ajax({
        url: "<?= base_url() ?>index.php/course/update_cert_data",
        method: "POST",
        data: {
          id_update: id
        },
        dataType: "json",
        success: function(data) {
          if (data != null) {
            $('#badges_name').val(data.badges_name);
            $('#badges_condition').val(data.badges_condition);
            $('#badges_img_ori').val(data.badges_img);
            $('#badges_desc').val(data.badges_desc);
            if (data.badges_img != "") {
              var nameImage = "<?php echo REAL_PATH; ?>/uploads/badges/" + data.badges_img
              var drEvent = $('#input-file-now-custom-2').dropify({
                defaultFile: nameImage
              });
              drEvent = drEvent.data('dropify');
              drEvent.resetPreview();
              drEvent.clearElement();
              drEvent.settings.defaultFile = nameImage;
              drEvent.destroy();
              drEvent.init();
              var drEvent = $('.dropify').dropify({
                defaultFile: "<?php echo REAL_PATH; ?>/uploads/badges/" + data.badges_img,
              });
              drEvent.on('dropify.beforeClear', function(event, element) {
                $('#badges_img_ori').val("");
                return true;
              });
            } else {
              $('.dropify').dropify();
            }
          }
        }
      });
      $.ajax({
        url: "<?= base_url() ?>index.php/course/update_score_data",
        method: "POST",
        data: {
          id_update: id
        },
        dataType: "json",
        success: function(data) {

          $('#mina').val(data.mina);
          $('#minb').val(data.minb);
          $('#minc').val(data.minc);
          $('#mind').val(data.mind);
        }
      });
    }
  });
});


$(document).on('click', '.delete', function() {
  var id = $(this).attr("id");
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_cos",
          field: "id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table = $('#myTable').DataTable();
              var info = table.page.info();
              var length = info.pages;
              var page_current = info.page;
              fetch_data('<?php echo $wcode; ?>', '<?php echo $cgcode; ?>', page_current);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_period', function() {
  var id = $(this).attr("id");
  var cos_id = $('#course_id_pp').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_cos_detail",
          field: "cosde_id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {

              var table_pp = $('#myTable_pp').DataTable();
              var info_pp = table_pp.page.info();
              var length_pp = info_pp.pages;
              var page_current_pp = info_pp.page;
              fetch_data_detail(cos_id, page_current_pp);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_survey', function() {
  var sv_id = $(this).attr("id");
  var course_id = $('#course_id_survey').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data_update",
        method: "POST",
        data: {
          id_delete: sv_id,
          table_name: "lms_survey",
          field: "sv_id",
          field_status: "sv_status"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_surveycos = $('#myTable_cos_id_survey').DataTable();
              var info_surveycos = table_surveycos.page.info();
              var length_surveycos = info_surveycos.pages;
              var page_current_surveycos = info_surveycos.page;
              fetch_data_survey(course_id, page_current_surveycos);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_survey_detail', function() {
  var svde_id = $(this).attr("id");
  var sv_id = $('#sv_id_detail').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: svde_id,
          table_name: "lms_survey_de",
          field: "svde_id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_surveydetail = $('#myTable_survey_detail').DataTable();
              var info_surveydetail = table_surveydetail.page.info();
              var length_surveydetail = info_surveydetail.pages;
              var page_current_surveydetail = info_surveydetail.page;
              fetch_data_survey_detail(sv_id, page_current_surveydetail);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_ques', function() {
  var ques_id = $(this).attr("id");
  var course_id = $('#cos_id_question').val();
  var qiz_id = $('#qiz_id_question').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data_update",
        method: "POST",
        data: {
          id_delete: ques_id,
          table_name: "lms_ques",
          field: "ques_id",
          field_status: "ques_status"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_question = $('#myTable_quiz_question').DataTable();
              var info_question = table_question.page.info();
              var length_question = info_question.pages;
              var page_current_question = info_question.page;
              fetch_data_question(qiz_id, page_current_question);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_quiz', function() {
  var qiz_id = $(this).attr("id");
  var cos_id = $('#course_id_quiz').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data_update",
        method: "POST",
        data: {
          id_delete: qiz_id,
          table_name: "lms_qiz",
          field: "qiz_id",
          field_status: "quiz_status"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_quiz = $('#myTable_quiz').DataTable();
              var info_quiz = table_quiz.page.info();
              var length_quiz = info_quiz.pages;
              var page_current_quiz = info_quiz.page;
              fetch_data_quiz(cos_id, page_current_quiz);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_lesson', function() {
  var id = $(this).attr("id");
  var cos_id = $('#course_id_lesson').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_les",
          field: "les_id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_les = $('#myTable_lesson').DataTable();
              var info_les = table_les.page.info();
              var length_les = info_les.pages;
              var page_current_les = info_les.page;
              fetch_data_lesson(cos_id, page_current_les);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_document_cos', function() {
  var fil_cos_id = $(this).attr("id");
  var cos_id = $('#id').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: fil_cos_id,
          table_name: "lms_cos_fil",
          field: "fil_cos_id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_doccos = $('#myTable_cos_document').DataTable();
              var info_doccos = table_doccos.page.info();
              var length_doccos = info_doccos.pages;
              var page_current_doccos = info_doccos.page;
              fetch_data_document_cos(cos_id, page_current_doccos);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});


$(document).on('click', '.delete_document', function() {
  var id = $(this).attr("id");
  var cos_id = $('#course_id_lesson').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_fil",
          field: "id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_pp = $('#myTable_pp').DataTable();
              var info_pp = table_pp.page.info();
              var length_pp = info_pp.pages;
              var page_current_pp = info_pp.page;
              fetch_data_detail(cos_id, page_current_pp);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_videocourse', function() {
  var id = $(this).attr("id");
  var cos_id = $('#course_id_cosv').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_cos_video",
          field: "cosv_id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_coursevideo = $('#myTable_videocourse').DataTable();
              var info_coursevideo = table_coursevideo.page.info();
              var length_coursevideo = info_coursevideo.pages;
              var page_current_coursevideo = info_coursevideo.page;
              fetch_data_coursevideo(cos_id, page_current_coursevideo);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_media', function() {
  var id = $(this).attr("id");
  var cos_id = $('#course_id_lesson').val();
  var les_id = $('#les_id').val();
  swal({
    title: '<?php echo label('wg_delete_msg'); ?>',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('wg_delete'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data",
        method: "POST",
        data: {
          id_delete: id,
          table_name: "lms_med",
          field: "id"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("com_msg_delete"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_media = $('#myTable_media').DataTable();
              var info_media = table_media.page.info();
              var length_media = info_media.pages;
              var page_current_media = info_media.page;
              fetch_data_media(les_id, page_current_media);
              var table_pp = $('#myTable_pp').DataTable();
              var info_pp = table_pp.page.info();
              var length_pp = info_pp.pages;
              var page_current_pp = info_pp.page;
              fetch_data_detail(cos_id, page_current_pp);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});

$(document).on('click', '.delete_enroll', function() {
  var cosen_id = $(this).attr("id");
  var cos_id = $('#course_id_lesson').val();
  $('#cos_id_enroll').val(cos_id);
  $('#cosen_id_enroll').val(cosen_id);
  display_style("div_enroll_main", "div_enroll_cancel");
});
$(document).on('click', '.manage_quiz', function() {
  var cos_id = $('#course_id_lesson').val();
  display_style("div_enroll_main", "div_enroll_qiz");
  var table_enrollqiz = $('#myTable_enroll_qiz').DataTable();
  var info_enrollqiz = table_enrollqiz.page.info();
  var length_enrollqiz = info_enrollqiz.pages;
  var page_current_enrollqiz = info_enrollqiz.page;
  fetch_data_enroll_qiz('', page_current_enrollqiz);
  $.ajax({
    url: '<?= base_url() ?>index.php/workgroup/recheckqiz_enroll',
    type: 'POST',
    data: {
      cos_id: cos_id
    },
    success: function(data) {

      $('#qiz_id_enroll').html(data);
    }
  });
});



$(document).on('click', '.Reenroll', function() {
  var cosen_id = $(this).attr("id");
  var cos_id = $('#course_id_lesson').val();
  swal({
    title: '<?php echo label('enroll_msg_reuse'); ?> ?',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '<?php echo label('enroll_reuse'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/course/delete_data_updateval",
        method: "POST",
        data: {
          id_update: cosen_id,
          field_id: "cosen_id",
          table_name: "lms_cos_enroll",
          field: "cosen_status",
          val: "1"
        },
        success: function(data) {
          if (data == "2") {
            swal(
              '<?php echo label("enroll_reuse_success"); ?>!',
              '',
              'success'
            ).then(function() {
              var table_enroll = $('#myTable_enroll').DataTable();
              var info_enroll = table_enroll.page.info();
              var length_enroll = info_enroll.pages;
              var page_current_enroll = info_enroll.page;
              fetch_data_enroll(cos_id, page_current_enroll);
            })
          } else if (data == "1") {
            swal({
              title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          } else {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            })
          }
        }
      });
    }
  })
});
$(document).on('click', '.regencert_course', function() {
  var cos_id = $('#course_id_lesson').val();
  var table_enroll = $('#myTable_enroll').DataTable();
  var info_enroll = table_enroll.page.info();
  var page_current_enroll = info_enroll.page;

  swal({
    title: 'ยืนยันสร้าง Certificate ใหม่อีกครั้ง?',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#28a745",
    confirmButtonText: '<?php echo label('m_ok'); ?>',
    cancelButtonText: '<?php echo label("m_cancel"); ?>'
  }).then(function(isChk) {
    if (isChk.value) {
      $.ajax({
        url: "<?= base_url() ?>index.php/certificate/createfilebycourseonly",
        method: "POST",
        data: {
          cos_id: cos_id
        },
        dataType: "json",
        success: function(data) {
          if (data && data.status === "0") {
            swal({
              title: '<?php echo label('com_msg_error_save'); ?>',
              text: "",
              type: 'warning',
              showCancelButton: false,
              confirmButtonClass: 'btn btn-primary',
              confirmButtonText: '<?php echo label('m_ok'); ?>'
            });
            return;
          }

          swal(
            'Certificate ถูกสร้างใหม่แล้ว ' + (data && data.success ? data.success : 0) + ' รายการ',
            '',
            'success'
          ).then(function() {
            fetch_data_enroll(cos_id, page_current_enroll);
          });
        },
        error: function() {
          swal({
            title: '<?php echo label('com_msg_error_save'); ?>',
            text: "",
            type: 'warning',
            showCancelButton: false,
            confirmButtonClass: 'btn btn-primary',
            confirmButtonText: '<?php echo label('m_ok'); ?>'
          });
        }
      });
    }
  });
});
</script>
