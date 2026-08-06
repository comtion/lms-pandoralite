<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">
    <!--nestable CSS -->
    <link href="<?php echo REAL_PATH;?>/assets/plugins/nestable/nestable.css" rel="stylesheet" type="text/css" />
</head>

<body class="fix-header fix-sidebar card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <div class="loader__figure"></div>
            <p class="loader__label"><?php if($lang=="thai"){echo $foote[0]['da_title_th'];}else{echo $foote[0]['da_title_en'];} ?></p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <?php $this->load->view('frontend/inc/inc-header.php'); ?>
        <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row col-12 page-titles">
                    <div class="col-md-5 align-self-center">
                        <b><?php echo ucwords(strtolower($title)); ?></b>
                    </div>
                    <div class="col-md-7 align-self-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo ucwords(label('dashboard')); ?></a></li>
                            <?php if($title_main!=""){ ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title_main)); ?></li>
                            <?php } ?>
                            <li class="breadcrumb-item active"><?php echo ucwords(strtolower($title)); ?></li>
                        </ol>
                    </div>
                </div>  

                <div class="row col-12 page-titles">
                  <div class="col-md-12 card">
                    <div class="card-body">
                      <div class="col-md-12" align="right">
                        <button name="edit_order_menu" id="edit_order_menu" class="btn btn-outline-primary edit_order_menu"><i class="mdi mdi-lead-pencil"></i> <?php echo label('edit_menu_sequences'); ?></button>
                        <?php if($btn_add=="1"){ ?>
                          <button name="add_button" id="add_button" class="btn btn-outline-info add_button" data-toggle="modal" data-target="#modal-default"><i class="mdi mdi-plus-box-outline"></i> <?php echo label('menu_add'); ?></button>
                        <?php } ?>
                      </div>
                      <div class="table-responsive">
                          <table id="myTable" width="100%" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th style="min-width: 80px;"><center><?php echo label('manage'); ?></center></th>
                                <th width="5%"></th>
                                <th width="10%" align="center"><center><?php echo label('menu_icon'); ?></center></th>
                                <th width="30%"><center><?php echo label('m_menu'); ?></center></th>
                                <th width="30%"><center><?php echo label('menu_path'); ?></center></th>
                                <th width="10%"><center><?php echo label('menu_customer'); ?></center></th>
                              </tr>
                            </thead>
                          </table>
                      </div>
                      <p><?php echo label('preNote'); ?>: <?php if($btn_update=="1"){ ?><button type="button" class="btn btn-warning btn-xs"><i class="mdi mdi-lead-pencil"></i></button> = <b><?php echo label('m_edit'); ?></b><?php } ?><?php if($btn_delete=="1"){ ?> , <button type="button" class="btn btn-danger btn-xs"><i class="mdi mdi-window-close"></i></button> = <b><?php echo label('delete'); ?></b><?php } ?></p>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>

    <div class="modal fade bs-example-modal-lg app-focus-modal" id="modal-default" role="dialog" aria-labelledby="myLargeModalLabel" aria-describedby="menu-modal-description" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-xl modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <div class="modal-heading">
                    <span class="modal-heading-accent" aria-hidden="true"></span>
                    <div>
                      <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
                      <p class="modal-description" id="menu-modal-description"><?php echo label('m_menu'); ?> — TH / EN / JP</p>
                    </div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-close" aria-hidden="true"></i></button>
              </div>
              <form method="post" id="menu_form" autocomplete="off" name="menu_form" enctype="multipart/form-data"  class="form-horizontal" role="form">
              <div class="modal-body">
                <div class="row menu-form-grid">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mu_name_th"><b style="color: #FF2D00">*</b><?php echo label('m_menu')." TH"; ?>:</label>
                    <input type="text" id="mu_name_th" name="mu_name_th" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mu_name_en"><b style="color: #FF2D00">*</b><?php echo label('m_menu')." EN"; ?>:</label>
                    <input type="text" id="mu_name_en" name="mu_name_en" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mu_name_jp"><b style="color: #FF2D00">*</b><?php echo label('m_menu')." JP"; ?>:</label>
                    <input type="text" id="mu_name_jp" name="mu_name_jp" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mu_name_th"><b style="color: #FF2D00">*</b><?php echo label('menu_icon'); ?>:</label>
                    <select id="mu_icon" name="mu_icon" required class="icons_select2">
                      <?php $icon = icon_material();$icon_code = icon_material_code();
                            for ($i=0; $i < countArray($icon) ; $i++) { ?>
                              <option value="<?php echo $icon[$i]; ?>" data-icon="<?php echo $icon[$i]; ?>"><?php echo $icon[$i]; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mu_path"><b style="color: #FF2D00">*</b><?php echo label('menu_path'); ?>:</label>
                    <input type="text" id="mu_path" name="mu_path" required class="form-control"> 
                  </div>
                </div>
                <div class="col-md-4 menu-customer-column">
                  <div class="form-group menu-customer-group">
                    <div class="checkbox checkbox-success">
                      <input type="checkbox" id="mu_customer" name="mu_customer" value="1">
                      <label for="mu_customer"><?php echo label('menu_customer'); ?></label>
                    </div>
                  </div>
                </div>
                </div>
              </div>
              <input type="hidden" id="operation" name="operation" value="Add">
              <input type="hidden" id="mu_id" name="mu_id">
              <div class="modal-footer">
                  <button type="button" class="btn btn-modal-secondary btn-flat" data-dismiss="modal"><i class="mdi mdi-close"></i> <?php echo label('m_cancel'); ?></button>
                  <button type="submit" class="btn btn-modal-primary btn-flat" name="action" id="action"><i class="mdi mdi-content-save"></i> <?php echo label('saveR'); ?></button>
              </div>
              </form>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-lg app-focus-modal" id="modal-order_menu" role="dialog" aria-labelledby="menuOrderModalLabel" aria-hidden="true" style="display: none;">
      <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <div class="modal-heading">
                    <span class="modal-heading-accent" aria-hidden="true"></span>
                    <div><h4 class="modal-title" id="menuOrderModalLabel"><i class="mdi mdi-lead-pencil"></i> <?php echo label('edit_menu_sequences'); ?></h4></div>
                  </div>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="mdi mdi-close" aria-hidden="true"></i></button>
              </div>
              <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body">
                                                            <div class="myadmin-dd dd" id="nestable">
                                                                <ol class="dd-list" id="load_li_menu" style="width: 100%;font-size: 20px">
                                                                </ol>
                                                            </div>
                                                    </div>
                                                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-modal-secondary btn-flat" data-dismiss="modal"><i class="mdi mdi-close"></i> <?php echo label('close'); ?></button>
              </div>
          </div>
          <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <script type="text/javascript">var base_url = "<?php echo REAL_PATH; ?>";</script>
    <!-- wysuhtml5 Plugin JavaScript -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/tinymce/tinymce.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!--Nestable js -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/nestable/jquery.nestable.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/js/instascan.min.js"></script>
    <script type="text/javascript">
        (function ($) {
            var csrfName = <?php echo json_encode($this->security->get_csrf_token_name()); ?>;
            var csrfToken = <?php echo json_encode($this->security->get_csrf_hash()); ?>;

            $.ajaxPrefilter(function (settings) {
                if ((settings.type || 'GET').toUpperCase() === 'GET') return;

                if (window.FormData && settings.data instanceof window.FormData) {
                    if (!settings.data.has(csrfName)) settings.data.append(csrfName, csrfToken);
                } else if (typeof settings.data === 'string' &&
                    settings.data.indexOf(encodeURIComponent(csrfName) + '=') < 0) {
                    settings.data += (settings.data ? '&' : '') +
                        encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfToken);
                } else if (!settings.data) {
                    settings.data = encodeURIComponent(csrfName) + '=' + encodeURIComponent(csrfToken);
                }
            });
        })(jQuery);

        $.fn.dataTable.ext.errMode = "none";
        $(document).ready(function() {
            function iformat(icon) {
                var originalOption = icon.element;
                return $('<span><i class="' + $(originalOption).data('icon') + '"></i> ' + icon.text + '</span>');
            }
            $('.icons_select2').select2({
              dropdownParent: $('#modal-default'),
              bindEvents: false,
                width: "100%",
                templateSelection: iformat,
                templateResult: iformat,
                allowHtml: true
            });
            //$(".bt-switch input[type='checkbox'], .bt-switch input[type='radio']").bootstrapSwitch();
        });

      fetch_data_menu(0);
      function fetch_data_menu(page_num)
         {
            $('#myTable').DataTable().destroy();
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
                    url: '<?=base_url()?>index.php/setting/fetch_detail_menu/',
                    type : 'GET',
                    data : {lang: "<?php echo $lang; ?>"}
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
          $(document).ready(function () {
              var updateOutput = function (e) {
                  var list = e.length ? e : $(e.target), output = list.data('output');
                  if (window.JSON) {output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                  } else {
                      output.val('JSON browser support required for this demo.');
                  }
                  var myObj = JSON.parse(window.JSON.stringify(list.nestable('serialize')));
                  $.ajax({
                      url:"<?=base_url()?>index.php/setting/edit_li_menu",
                      method:'POST',
                      data:{arr_obj:myObj},
                      success:function(data)
                      {
                      }
                  });
              };
              $('#nestable').nestable({
                  group: 1,
                  maxDepth: 7,
              }).on('change', updateOutput);
              var arr_out =  updateOutput($('#nestable').data('output', $('#nestable-output')));
              
          });

    $('.slimtest1').perfectScrollbar();

           $('#add_button').click(function(){
                $('.modal-title').text('<?php echo label("menu_add"); ?>');
                $("#modal-default").modal({backdrop: false});
                $('#menu_form')[0].reset();
                $('#operation').val("Add");

            });

    $(document).ready(function() {
        $(document).on('submit', '#menu_form', function(event){
              event.preventDefault(); 
                $.ajax({
                  url:"<?=base_url()?>index.php/setting/insert_menu",
                  method:'POST',
                  data:new FormData(this),
                  contentType:false,
                  processData:false,
                  success:function(data)
                  {
                    if(data=="2"){
                        $('#menu_form')[0].reset();
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
                                      fetch_data_menu(page_current);
                        })
                    }else if(data=="1"){
                        swal({
                            title: '<?php echo label("com_msg_duplicate"); ?>',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label("m_ok"); ?>'
                        }).then(function () {
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
                   
                  }
                });
            });

         $(document).on('click', '.delete', function(){
            var mu_id = $(this).attr("id");
            swal({
                title: '<?php echo label('wg_delete_msg'); ?>',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: '<?php echo label('wg_delete'); ?>',
                cancelButtonText: '<?php echo label('cancel'); ?>'
            }).then(function (isChk) {
              if(isChk.value){
                $.ajax({
                    url:"<?=base_url()?>index.php/setting/delete_menu_data",
                    method:"POST",
                    data:{id_delete:mu_id,table_name:"lms_menu"},
                    success:function(data)
                    {
                      if(data == "2"){
                        swal(
                            '<?php echo label("com_msg_delete"); ?>!',
                            '',
                            'success'
                        ).then(function () {
                                      var table = $('#myTable').DataTable();
                                      var info = table.page.info();
                                      var length = info.pages;
                                      var page_current = info.page;
                                      fetch_data_menu(page_current);
                        })
                      }else if(data == "1"){
                         swal({
                            title: 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากข้อมูลถูกใช้งาน',
                            text: "",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: '<?php echo label('m_ok'); ?>'
                        })
                      }else{
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

          $(document).on('click', '.edit_order_menu', function(){
                $("#modal-order_menu").modal({backdrop: false});
                $.ajax({
                      url: '<?=base_url()?>index.php/setting/li_menu',
                      type: 'POST',
                      success: function(data){
                        $('#load_li_menu').html(data);
                      }
                });
          });
          $(document).on('click', '.update', function(){
            var mu_id = $(this).attr("id");
            $.ajax({
              url:"<?=base_url()?>index.php/setting/update_menu",
              method:"POST",
              data:{mu_id:mu_id},
              dataType:"json",
              success:function(data)
              {
                $("#modal-default").modal({backdrop: false});
                $('.modal-title').text('<?php echo label("menu_edit"); ?>');
                $('#operation').val("Edit");
                $('#menu_form')[0].reset();
                $('#mu_name_th').val(data.mu_name_th);
                $('#mu_name_en').val(data.mu_name_en);
                $('#mu_name_jp').val(data.mu_name_jp);
                $('#mu_path').val(data.mu_path);
                if(data.mu_customer=="1"){
                  document.getElementById("mu_customer").checked = true;
                }else{
                  document.getElementById("mu_customer").checked = false;
                }
                $('#mu_icon').val(data.mu_icon);$('#mu_icon').trigger('change'); 
                $('#mu_id').val(data.mu_id);
              }
            });
          });
    });
    </script>
</body>

</html>
