<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('frontend/inc/inc-meta.php'); ?>
<?php 
  
  function data_rechk($objConnect,$sq_id='',$num=''){
    $sql_detail = "select * from lms_sva_tc where lms_sva_tc.sq_id = '".$sq_id."' and ans='".$num."'";
    $query_detail = mysqli_query($objConnect,$sql_detail);
    $num_detail = mysqli_num_rows($query_detail);
    return $num_detail;
  }
?>
  <!-- Morris charts -->
  <link rel="stylesheet" href="<?php echo REAL_PATH;?>/assets/morris.js/morris.css">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body>
  	<div id="superwrapper">
	    <!--Nav-->
      <?php $this->load->view('frontend/inc/inc-header.php'); ?>
		<!--content-->
		<div class="container dashboard main">
		 <a href="#menu-toggle" class="btn btn-default" id="menu-toggle"><i class="fa fa-custom-arrow" aria-hidden="true"></i></a>
      <div class="row">
       <?php $this->load->view('frontend/inc/inc-sidemenu.php'); ?>
       <div class="content dashWrap"><form id="SBack" method="post"></form>
        <div class="saveWrap"><button form="SBack" name="SBack" value="normal" type"submit" class="btn btn-default display" href="<?php echo base_url().'survey/detail/'.$survey['scode']; ?>"><?php echo label('sback'); ?></button></div><br>
         <div class="dashElement page">
           <div class="row">
             <div class="col-sm-12">
               <div class="dashHeader">
                 <h2><?php echo label('sforr'); ?><?php echo $survey['sname'] ?></h2>
                 <h2><?php echo label('sforcou'); ?><?php echo $course['cname']; ?></h2>
                 <br> <?php $a = 0; $scode = $survey['scode'];
                 $value1 = 0;$value2 = 0;$value3 = 0;$value4 = 0;$value5 = 0;
                 $per1 = 0;$per2 = 0;$per3 = 0;$per4 = 0;$per5 = 0;
                 $total = 0;
                 $out_arr = array();
                 ?>
                 <?php foreach($surveys as $rowsu) {
                   $ans1[$a]=0;$ans2[$a]=0;$ans3[$a]=0;$ans4[$a]=0;$ans5[$a]=0;
                   if( 1 == $rowsu['type']){

                     foreach($data1 as $rowda) {if(($rowsu['id'] == $rowda['sq_id'])&&($rowda['ans'] == '1')):$ans1[$a]++;endif;}
                     foreach($data2 as $rowda) {if(($rowsu['id'] == $rowda['sq_id'])&&($rowda['ans'] == '2')):$ans2[$a]++;endif;}
                     foreach($data3 as $rowda) {if(($rowsu['id'] == $rowda['sq_id'])&&($rowda['ans'] == '3')):$ans3[$a]++;endif;}
                     foreach($data4 as $rowda) {if(($rowsu['id'] == $rowda['sq_id'])&&($rowda['ans'] == '4')):$ans4[$a]++;endif;}
                     foreach($data5 as $rowda) {if(($rowsu['id'] == $rowda['sq_id'])&&($rowda['ans'] == '5')):$ans5[$a]++;endif;}


                     $val1 = intval($ans1[$a])*1;
                     $val2 = intval($ans2[$a])*2;
                     $val3 = intval($ans3[$a])*3;
                     $val4 = intval($ans4[$a])*4;
                     $val5 = intval($ans5[$a])*5;
                     $total_val = $val1 + $val2 + $val3 + $val4 + $val5;
                     $total = $ans1[$a] + $ans2[$a] + $ans3[$a] + $ans4[$a] + $ans5[$a];
                     if($total!=0&&$total_val!=0){
                       $output = array();
                       $output['mean'] = $total_val/$total;
                       $output['percent'] = (($total_val/$total)*100)/5;
                       $output['percent_1'] = (((($total_val/$total)*100)/5)*$val1)/$total_val;
                       $output['percent_2'] = (((($total_val/$total)*100)/5)*$val2)/$total_val;
                       $output['percent_3'] = (((($total_val/$total)*100)/5)*$val3)/$total_val;
                       $output['percent_4'] = (((($total_val/$total)*100)/5)*$val4)/$total_val;
                       $output['percent_5'] = (((($total_val/$total)*100)/5)*$val5)/$total_val;
                       //print_r($data1);
                       array_push($out_arr, $output);
                     }
                   }
               $a++;}
               if($value1>0&&$total>0){
                $per1 = ($value1*100)/$total;
               }
               if($value2>0&&$total>0){
                $per2 = ($value2*100)/$total;
               }
               if($value3>0&&$total>0){
                $per3 = ($value3*100)/$total;
               }
               if($value4>0&&$total>0){
                $per4 = ($value4*100)/$total;
               }
               if($value5>0&&$total>0){
                $per5 = ($value5*100)/$total;
               }

               ?>
               <div class="dashpageWrap">
                  <button type="button" id="export_button" onclick="export_excel('<?php echo $survey['scode']; ?>')" class="btn btn-success margin pull-right export_button" ><i class="fa fa-file-excel-o"></i><span> Export Excel</span></button>
                   <p class="textDescription">
                     <?php echo $survey['sdesc'];?>
                   </p>
                   <p class="textDescription">
                     <?php echo label('speopled').' '.$empdid.' '.label('speople'); ?>
                   </p>

                  <div class="box-body">
                    <div class="chart" id="pieChart" style="height: 300px; position: relative;"></div>
                  </div>
                        <table class="display" align="right" >
                          <tr>
                            <th></th>
                            <th colspan="2"><?php echo label('smax'); ?></th>
                            <th></th>
                            <th colspan="2"><?php echo label('smin'); ?></th>
                            <th></th>
                          </tr>
                          <tr>
                            <th width="500"></th>
                            <th width="50">5</th>
                            <th width="50">4</th>
                            <th width="50">3</th>
                            <th width="50">2</th>
                            <th width="50">1</th>
                            <th width="150" align="center"><?php echo label('Suggestion'); ?></th>
                          </tr>
                        <?php   
                        $title_arr = array();
                        foreach ($surveys_title as $key => $value) {
                          array_push($title_arr, $value['title_svq']);
                        }
                        //print_r($title_arr);
                        $count_arr = 0;$count_row = 1;
                        $row = 0;$count = 1; foreach($surveys as $survey) { ?>
                          <?php $quest[$row] = $survey['id'];

                          if($title_arr[$count_arr]!=$survey['title_svq']){
                             $count_arr++;$count++;
                             $count=1;
                          }
                          if($count==1){ ?>
                            <tr>
                              <td colspan="7" align="left"><br><?php echo $title_arr[$count_arr]; ?></td>
                            </tr>
                          <?php $count=0;
                          } 
                          ?>

                          <tr>
                            <td width="500">
                              <div class="questions"><?php echo " - " .$survey['question']." (".$count_row.")"; ?>
                              </div>
                            </td>
                            <td width="50"><?php echo $ans5[$row]?></td>
                            <td width="50"><?php echo $ans4[$row]?></td>
                            <td width="50"><?php echo $ans3[$row]?></td>
                            <td width="50"><?php echo $ans2[$row]?></td>
                            <td width="50"><?php echo $ans1[$row]?></td>
                            <td width="150" align="center"><button type="button" id="<?php echo $survey['id'] ?>" class="btn btn-success btn-sm margin view_suggestion" name="view_suggestion" data-toggle="modal" data-target="#modal-Suggestion"><i class="fa fa-search"></i><span><?php echo label('slink'); ?></span></button></td>
                          </tr>
                          <?php $row++;$count_row++;?>
                        <?php } ?>
                        <tr>
                          <td colspan="7" align="center"><br><button type="button" id="<?php echo $scode ?>" class="btn btn-info btn-sm margin view_suggestionhead" name="view_suggestionhead" data-toggle="modal" data-target="#modal-Suggestionhead"><i class="fa fa-search"></i><span><?php echo label('slinkhead'); ?></span></button><br><br></td>
                        </tr>
                  </table>
                  <br><br><br><br><br>


                    </div>
                  </div>
								</div>
							</div>
						</div>
					</div>
          <br><br><br>
				</div>
			 </div>


      <?php $this->load->view('frontend/inc/inc-footer.php'); ?>
      <?php $this->load->view('frontend/inc/inc-footer-script.php'); ?>
    </div>


      <div class="modal fade bs-example-modal-lg" id="modal-Suggestion">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i><span> <?php echo label('slink'); ?></span></h4>
              </div>

              <div class="modal-body">
                <div class="box-body">
                  <div id="taa_table" class="table-responsive" >
                    <table id="tbtable" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 20%"></th>
                        <th class="text-center" style="width: 80%"><?php echo label('Suggestion'); ?></th>
                      </tr>
                      </thead>
                      <tbody id="tbtable_detail"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
            </div>
          </div>
        </div> 


      <div class="modal fade bs-example-modal-lg" id="modal-Suggestionhead">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-search"></i><span> <?php echo label('slinkhead'); ?></span></h4>
              </div>

              <div class="modal-body">
                <div class="box-body">
                  <div id="taa_table" class="table-responsive" >
                    <table id="tbtable_head" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th style="width: 20%"></th>
                        <th class="text-center" style="width: 80%"><?php echo label('Suggestion'); ?></th>
                      </tr>
                      </thead>
                      <tbody id="tbtable_detail"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal"><?php echo label('close'); ?></button>
              </div>
            </div>
          </div>
        </div> 
<!-- Morris.js charts -->
<script src="<?php echo REAL_PATH;?>/assets/raphael/raphael.min.js"></script>
<script src="<?php echo REAL_PATH;?>/assets/morris.js/morris.min.js"></script>
    <script type="text/javascript">
      $.fn.dataTable.ext.errMode = "none";
      !function($) {
        "use strict";

        var MorrisCharts = function() {};
        MorrisCharts.prototype.createStackedChart  = function(element, data, xkey, ykeys, labels, lineColors) {
            Morris.Bar({
                element: element,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                stacked: true,
                labels: labels,
                hideHover: 'auto',
                resize: true, //defaulted to true
                gridLineColor: '#2c3e50',
                gridTextColor: '#2c3e50',
                barColors: lineColors
            });
        },
        MorrisCharts.prototype.init = function() {
          var $stckedData  = [
            <?php 
                  $count_arr = 0;
                  $row = 0;$count = 1;
                  foreach($surveys as $survey) { ?>
                    { y: '<?php echo "(".$count." : ".$out_arr[$row]['percent']."% : ".$out_arr[$row]['mean']." ) "; ?>', a: parseInt('<?php echo $out_arr[$row]['percent_1']; ?>'), b: parseInt('<?php echo $out_arr[$row]['percent_2']; ?>'), c: parseInt('<?php echo $out_arr[$row]['percent_3']; ?>'), d: parseInt('<?php echo $out_arr[$row]['percent_4']; ?>'), e: parseInt('<?php echo $out_arr[$row]['percent_5']; ?>')}
            <?php 
                  if($row<(countArray($surveys)+1)){
                    echo ",";
                  }
                  $row++;$count++;
                  } ?>
          ];
          this.createStackedChart('pieChart', $stckedData, 'y', ['a', 'b', 'c', 'd', 'e'], ['1', '2', '3', '4', '5'], ['#1abc9c','#2ecc71','#3498db','#e74c3c','#9b59b6']);
        },
    //init
    $.MorrisCharts = new MorrisCharts, $.MorrisCharts.Constructor = MorrisCharts
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.MorrisCharts.init();
}(window.jQuery);
/*
      (function () {
          var $, MyMorris;

          MyMorris = window.MyMorris = {};
          $ = jQuery;

          MyMorris = Object.create(Morris);

          MyMorris.Donut.prototype.select = function (idx) {
              var row, s, segment, _i, _len, _ref, _fill_color; // ADDED _fill_color
              _ref = this.segments;
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                  s = _ref[_i];
                  s.deselect();
              }
              segment = this.segments[idx];
              segment.select();
              row = this.data[idx];
              _fill_color = row.labelColor || this.options.labelColor || '#000000'; // ADDED
              return this.setLabels(row.label, this.options.formatter(row.value, row), _fill_color); // ADDED parameter _fill_color
          };


          MyMorris.Donut.prototype.setLabels = function (label1, label2, fill_color) {
              var inner, maxHeightBottom, maxHeightTop, maxWidth, text1bbox, text1scale, text2bbox, text2scale;
              _default_fill = fill_color || '#000000'; // ADDED
              inner = (Math.min(this.el.width() / 2, this.el.height() / 2) - 10) * 2 / 3;
              maxWidth = 1.8 * inner;
              maxHeightTop = inner / 2;
              maxHeightBottom = inner / 3;
              this.text1.attr({
                  text: label1,
                  transform: '',
                  fill: fill_color // ADDED
              });
              text1bbox = this.text1.getBBox();
              text1scale = Math.min(maxWidth / text1bbox.width, maxHeightTop / text1bbox.height);
              this.text1.attr({
                  transform: "S" + text1scale + "," + text1scale + "," + (text1bbox.x + text1bbox.width / 2) + "," + (text1bbox.y + text1bbox.height)
              });
              this.text2.attr({
                  text: label2,
                  transform: '',
                  fill: fill_color // ADDED
              });
              text2bbox = this.text2.getBBox();
              text2scale = Math.min(maxWidth / text2bbox.width, maxHeightBottom / text2bbox.height);
              return this.text2.attr({
                  transform: "S" + text2scale + "," + text2scale + "," + (text2bbox.x + text2bbox.width / 2) + "," + text2bbox.y
              });
          };
      }).call(this);

      getMorris('donut', 'pieChart');

      function getMorris(type, element) {
          if (type === 'donut') {
              var morris = Morris.Donut({
                  element: element,
                  data: [
              {label: 'คะแนน 1 : <?php echo number_format($per1); ?>% จำนวนครั้งที่ตอบ', value: parseInt('<?php echo $value1; ?>'),labelColor:'black'},
              {label: 'คะแนน 2 : <?php echo number_format($per2); ?>% จำนวนครั้งที่ตอบ', value: parseInt('<?php echo $value2; ?>'),labelColor:'black'},
              {label: 'คะแนน 3 : <?php echo number_format($per3); ?>% จำนวนครั้งที่ตอบ', value: parseInt('<?php echo $value3; ?>'),labelColor:'black'},
              {label: 'คะแนน 4 : <?php echo number_format($per4); ?>% จำนวนครั้งที่ตอบ', value: parseInt('<?php echo $value4; ?>'),labelColor:'black'},
              {label: 'คะแนน 5 : <?php echo number_format($per5); ?>% จำนวนครั้งที่ตอบ', value: parseInt('<?php echo $value5; ?>'),labelColor:'black'}
                  ],
                  labelColor: "#9CC4E4",
                  colors: ['#1abc9c', '#3498db', '#9b59b6', '#e67e22', '#e74c3c']
              });
          }
      }*/

        function export_excel(survey_id=''){
          if(survey_id!=""){
            window.open('<?=base_url()?>excel_export/export_report_survey.php?survey_id='+survey_id);
          }
        }
      $(document).ready(function() {
         
          $(document).on('click', '.view_suggestion', function(){
            var survey_id = $(this).attr("id");
            console.log("survey:"+survey_id);
            $('#modal-Suggestion').modal('show');

            $('#tbtable').DataTable().destroy();
            fetch_data(survey_id);
          });
         
          $(document).on('click', '.view_suggestionhead', function(){
            var scode = $(this).attr("id");
            console.log("scode:"+scode);
            $('#modal-Suggestionhead').modal('show');

            $('#tbtable_head').DataTable().destroy();
            fetch_data_head(scode);
          });

         function fetch_data(survey_id='')
         {
            $('#tbtable').DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/Survey/fetch_detail/'+survey_id,
                    type : 'GET'
                },
            });
         }
         function fetch_data_head(scode='')
         {
            $('#tbtable_head').DataTable({
                "ajax": {
                    url : '<?=base_url()?>index.php/Survey/fetch_detail_head/'+scode,
                    type : 'GET'
                },
            });
         }
       });
    </script>
  </body>
</html>
