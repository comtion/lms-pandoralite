<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta-dashboard.php');

?>
<link rel="stylesheet" type="text/css" href="<?php echo REAL_PATH; ?>/assets/plugins/datatables/media/css/dataTables.bootstrap4.css">

<link href="<?php echo REAL_PATH; ?>/assets/css/custom_imat.css" rel="stylesheet">

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
        <div class="row page-titles">
          <div class="col-5 align-self-center">
              <b><?php echo label('report_survey'); ?></b>
          </div>
          <div class="col-7 align-self-right ">
            <ol class="breadcrumb float-right">
              <li class="breadcrumb-item"><a href="<?php echo REAL_PATH;?>/dashboard"><?php echo label('dashboard'); ?></a></li>
              <li class="breadcrumb-item active"><?php echo label('report_survey'); ?></li>
            </ol>
          </div>
        </div>
  
        <div class="">
          <div class="">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-sm-12 card card-body">
                    <h4 class="mt-3">The Art of Communication</h4>
                    <p>This questionnaire was created to explore opinions about the "The Art of Communication" course.</p>
                    <div class="table-responsive">
                      <table id="survey_detail_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr>
                                  <th><?php echo label('name'); ?></th>
                                  <th><?php echo label('com_name'); ?></th>
                                  <th><?php echo label('dep_name'); ?></th>
                                  <th><?php echo label('p_postname'); ?></th>
                                  <th><?php echo label('faqq').' '.'1' ?></th>
                                  <th><?php echo label('faqa').' '.'1' ?></th>
                                  <th><?php echo label('faqq').' '.'2' ?></th>
                                  <th><?php echo label('faqa').' '.'2' ?></th>
                                  <th><?php echo label('faqq').' '.'3' ?></th>
                                  <th><?php echo label('faqa').' '.'3' ?></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>Tiger Nixon</td>
                                  <td title="Isuzu Motors Asia (Thailand) Co., Ltd.">IMAT</td>
                                  <td>Programmer</td>
                                  <td>Manager</td>
                                  <td>Sed et sem scelerisque, fermentum mi ut, accumsan massa ?</td>
                                  <td>Vestibulum eu nunc sed neque aliquet ornare.</td>
                                  <td>Duis nec purus eget nulla egestas sagittis ?</td>
                                  <td>Mauris vel leo nec augue dignissim hendrerit sed a nisi.</td>
                                  <td>Etiam rutrum leo at dictum sodales?</td>
                                  <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sapien ipsum, rhoncus ut dolor sed, rhoncus aliquam felis. Curabitur a enim vel nisi mollis faucibus. Duis sit amet mi in ligula efficitur eleifend ut sed libero.</td>
                              </tr>
                              <tr>
                                  <td>Garrett Winters</td>
                                  <td title="Isuzu Motors Asia (Thailand) Co., Ltd.">IMAT</td>
                                  <td>Programmer</td>
                                  <td>Research & Development</td>
                                  <td>Sed et sem scelerisque, fermentum mi ut, accumsan massa ?</td>
                                  <td>In gravida elit et euismod faucibus.</td>
                                  <td>Duis nec purus eget nulla egestas sagittis ?</td>
                                  <td>Mauris vel leo nec augue dignissim hendrerit sed a nisi.</td>
                                  <td>Etiam rutrum leo at dictum sodales?</td>
                                  <td>Aenean urna nulla, lacinia vel leo eget, aliquam maximus nibh. Nunc nec ex ligula. Donec venenatis velit orci. Fusce volutpat auctor velit, eget efficitur purus lobortis vestibulum. Curabitur luctus mattis volutpat.</td>
                              </tr>
                              <tr>
                                  <td>Ashton Cox</td>
                                  <td title="Shonan Unitec (Thailand) Co., Ltd.">SUTT</td>
                                  <td>Human Resources</td>
                                  <td>Manager</td>
                                  <td>Sed et sem scelerisque, fermentum mi ut, accumsan massa ?</td>
                                  <td>In gravida elit et euismod faucibus.</td>
                                  <td>Duis nec purus eget nulla egestas sagittis ?</td>
                                  <td>Etiam non ligula nec neque mollis consectetur.</td>
                                  <td>Etiam rutrum leo at dictum sodales?</td>
                                  <td> quis dapibus eros, vitae elementum elit. Praesent gravida vel orci vel posuere. Curabitur et congue metus, id accumsan diam. Phasellus lobortis nibh convallis, dignissim est sit amet, ullamcorper leo. Nullam elementum finibus bibendum.</td>
                              </tr>
                              <tr>
                                  <td>Cedric Kelly</td>
                                  <td title="Shonan Unitec (Thailand) Co., Ltd.">SUTT</td>
                                  <td>Human Resources</td>
                                  <td>Research & Development</td>
                                  <td>Sed et sem scelerisque, fermentum mi ut, accumsan massa ?</td>
                                  <td>In gravida elit et euismod faucibus.</td>
                                  <td>Duis nec purus eget nulla egestas sagittis ?</td>
                                  <td>Etiam non ligula nec neque mollis consectetur.</td>
                                  <td>Etiam rutrum leo at dictum sodales?</td>
                                  <td>Vivamus pharetra posuere mauris, at placerat metus sagittis in. Donec pharetra lacus id condimentum fringilla. Curabitur in eleifend lectus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</td>
                              </tr>
                              <tr>
                                  <td>Airi Satou</td>
                                  <td title="Hitachi Chemical Automotive Products (Thailand) Co.,Ltd.">HCAT</td>
                                  <td>Human Resources</td>
                                  <td>Manager</td>
                                  <td>Sed et sem scelerisque, fermentum mi ut, accumsan massa ?</td>
                                  <td>Vestibulum eu nunc sed neque aliquet ornare.</td>
                                  <td>Duis nec purus eget nulla egestas sagittis ?</td>
                                  <td>Etiam non ligula nec neque mollis consectetur.</td>
                                  <td>Etiam rutrum leo at dictum sodales?</td>
                                  <td>Duis molestie, velit et sodales molestie, diam ligula consectetur velit, vitae scelerisque nunc nunc at felis. Nunc sagittis, tellus sit amet eleifend facilisis, neque augue suscipit ligula, id rhoncus ligula nunc eu turpis.</td>
                              </tr>
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

<!-- sample modal content -->
<div id="survey_slink_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"><?php echo label('slink'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table id="slink_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><?php echo label('sqi'); ?></th>
                      <th><?php echo label('Suggestion'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="width: 50px;">1</td>
                      <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Nullam eu nulla eu dolor bibendum interdum quis vel mi.</td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>Duis non dui luctus, egestas nisl ac, dapibus quam.</td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>Maecenas laoreet ligula non nunc commodo, sit amet finibus sapien iaculis.</td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td>Ut in risus posuere, facilisis enim eget, blandit sapien.</td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td>Nulla lacinia ex vitae ipsum maximus ultrices.</td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td>Nunc a est eget mauris aliquet fermentum in in velit.</td>
                    </tr>
                    <tr>
                      <td>8</td>
                      <td>Aenean imperdiet sapien sed velit maximus eleifend.</td>
                    </tr>
                    <tr>
                      <td>9</td>
                      <td>Pellentesque ac leo sollicitudin, gravida ex ut, consectetur diam.</td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td>Quisque et nunc sed felis varius pretium.</td>
                    </tr>
                    <tr>
                      <td>11</td>
                      <td>Nulla a enim molestie, feugiat erat vel, suscipit nisl.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- sample modal content -->
<div id="survey_slinkhead_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel"><?php echo label('slinkhead'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                <table id="slinkhead_table" class="display table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <th><?php echo label('sqi'); ?></th>
                      <th><?php echo label('Suggestion'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="width: 50px;">1</td>
                      <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td>Nullam eu nulla eu dolor bibendum interdum quis vel mi.</td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td>Duis non dui luctus, egestas nisl ac, dapibus quam.</td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td>Maecenas laoreet ligula non nunc commodo, sit amet finibus sapien iaculis.</td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td>Ut in risus posuere, facilisis enim eget, blandit sapien.</td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td>Nulla lacinia ex vitae ipsum maximus ultrices.</td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td>Nunc a est eget mauris aliquet fermentum in in velit.</td>
                    </tr>
                    <tr>
                      <td>8</td>
                      <td>Aenean imperdiet sapien sed velit maximus eleifend.</td>
                    </tr>
                    <tr>
                      <td>9</td>
                      <td>Pellentesque ac leo sollicitudin, gravida ex ut, consectetur diam.</td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td>Quisque et nunc sed felis varius pretium.</td>
                    </tr>
                    <tr>
                      <td>11</td>
                      <td>Nulla a enim molestie, feugiat erat vel, suscipit nisl.</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

    <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
    <?php $this->load->view('frontend/modal/modal_course.php'); ?>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- This is data table -->
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/datatables/datatables.min.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/cdn/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/Chart.js/Chart.min.js"></script>    
    <!-- end - This is for export functionality only -->
    <script>
       new Chart(document.getElementById("chart2"),
        {
            "type":"bar",
            "data":{"labels":["(Instruction media (1) : 100% : 5 )","(Instruction media (2) : 60% : 3 )","(Instruction media (3) : 70% : 3.5 )","(Overview (4) : 80% : 4 )"],
            "datasets":[{
                            "label":"My First Dataset",
                            "data":[65,59,80,81,56,55,40],
                            "fill":false,
                            "backgroundColor":["rgba(255, 99, 132, 0.2)","rgba(255, 159, 64, 0.2)","rgba(255, 205, 86, 0.2)","rgba(75, 192, 192, 0.2)","rgba(54, 162, 235, 0.2)","rgba(153, 102, 255, 0.2)","rgba(201, 203, 207, 0.2)"],
                            "borderColor":["rgb(239, 83, 80)","rgb(255, 159, 64)","rgb(255, 178, 43)","rgb(86, 192, 216)","rgb(57, 139, 247)","rgb(153, 102, 255)","rgb(201, 203, 207)"],
                            "borderWidth":1}
                        ]},
            "options":{
              "scales":{"yAxes":[{"ticks":{"beginAtZero":true}}]},
              legend: {
                display: false
              },
              tooltips: {
                callbacks: {
                  label: function(tooltipItem) {
                    return tooltipItem.yLabel;
                  }
                }
              }
            }
        });
    </script>
    <script>
      $('#survey_detail_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf'
        ]
    });
    </script>
    <script src="<?php echo REAL_PATH; ?>/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
