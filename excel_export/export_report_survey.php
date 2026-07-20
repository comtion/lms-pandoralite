<?php

header('Content-type: application/vnd.ms-excel');

//// It will be called file.xls

header('Content-Disposition: attachment; filename="report_survey.xls"');

$survey_id = isset($_REQUEST['survey_id']) ? $_REQUEST['survey_id'] : '';
$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : '';
// Create new PHPExcel object

$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');

include("conn.php");
mysqli_query($conndb,"SET NAMES 'utf8'");

?>

<?php 
  function data_rechk($conndb, $sv_id='', $num=''){
	$sql_detail = "select lms_qn_user_de.svde_id,lms_qn_user_de.qnude_var from lms_survey_de inner join lms_qn_user_de on lms_survey_de.svde_id = lms_qn_user_de.svde_id 
					where lms_survey_de.sv_id = '".$sv_id."' and lms_qn_user_de.qnude_var='".$num."'";
	$query_detail = mysqli_query($conndb, $sql_detail);
	$num_detail = mysqli_num_rows($query_detail);
    return $query_detail;
  }

  echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">';
?>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">


<body>
 
<table width="100%"  id="tbl1" class="table2excel" border="1" style="border-collapse: collapse;border: 1px solid black;">
	<thead>
		<?php 
			$strSQL = "SELECT * FROM lms_survey_de where lms_survey_de.sv_id = '".$survey_id."'";
			$objQuery = mysqli_query($conndb,$strSQL);
			$num_row = mysqli_num_rows($objQuery);

			$sql_header = "select * from lms_survey inner join lms_cos on lms_survey.cos_id = lms_cos.cos_id where lms_survey.sv_id = '".$survey_id."'";
			$query_header = mysqli_query($conndb,$sql_header);
			$fetch_header = mysqli_fetch_array($query_header);
			if($lang=="thai"){ 
				$cname = $fetch_header['cname_th']!=""?$fetch_header['cname_th']:$fetch_header['cname_eng'];
				$cname = $cname!=""?$cname:$fetch_header['cname_jp'];
			}else if($lang=="english"){ 
				$cname = $fetch_header['cname_eng']!=""?$fetch_header['cname_eng']:$fetch_header['cname_th'];
				$cname = $cname!=""?$cname:$fetch_header['cname_jp'];
			}else{
				$cname = $fetch_header['cname_jp']!=""?$fetch_header['cname_jp']:$fetch_header['cname_eng'];
				$cname = $cname!=""?$cname:$fetch_header['cname_th'];
			}
		?>
		<tr>
			<th colspan="<?php echo (2*$num_row)+1; ?>"><?php echo $lang=="thai"?"แบบสอบถามสำหรับรายวิชา ":"Survey for courses "; ?><?php echo $cname; ?></th>
		</tr>
		<tr>
			<th rowspan="2"><?php echo $lang=="thai"?"ข้อเสนอแนะท้ายบท":"Suggestion at the end of survey"; ?></th>
			<?php 
				$num = 0;
				while($fetch_chk = mysqli_fetch_array($objQuery)){ 
                     $num++;

                    if($lang=="thai"){ 
                      $svde_detail = $fetch_chk['svde_detail_th']!=""?$fetch_chk['svde_detail_th']:$fetch_chk['svde_detail_eng'];
                      $svde_detail = $svde_detail!=""?$svde_detail:$fetch_chk['svde_detail_jp'];
                    }else if($lang=="english"){ 
                      $svde_detail = $fetch_chk['svde_detail_eng']!=""?$fetch_chk['svde_detail_eng']:$fetch_chk['svde_detail_th'];
                      $svde_detail = $svde_detail!=""?$svde_detail:$fetch_chk['svde_detail_jp'];
                    }else{
                      $svde_detail = $fetch_chk['svde_detail_jp']!=""?$fetch_chk['svde_detail_jp']:$fetch_chk['svde_detail_eng'];
                      $svde_detail = $svde_detail!=""?$svde_detail:$fetch_chk['svde_detail_th'];
                    }
			?>
					<th colspan="2"><?php echo $svde_detail; ?></th>
			<?php } ?>
		</tr>
		<tr>
			<?php for($a=0;$a<$num;$a++){ ?>
				<th><?php echo $lang=="thai"?"คะแนน":"Score"; ?></th>
				<th><?php echo $lang=="thai"?"ข้อเสนอแนะ":"Suggestion"; ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php 
		$out_arr = array();
		$sql_head = "select * from lms_qn_user where sv_id = '".$survey_id."'";
		$query_head = mysqli_query($conndb,$sql_head);
		$num_row = mysqli_num_rows($query_head);
		if($num_row>0){
			while($fetch_head = mysqli_fetch_array($query_head)){
	?>
		<tr>
			<td ><?php echo $fetch_head['qnu_suggestion']; ?></td>
		<?php	
					$sql_detail = "select * from lms_qn_user_de where lms_qn_user_de.qnu_id = '".$fetch_head['qnu_id']."'";
					$query_detail = mysqli_query($conndb,$sql_detail);
					while($fetch_detail = mysqli_fetch_array($query_detail)){ 
						?>
						<td align="center"><?php echo $fetch_detail['qnude_var']; ?></td>
						<td><?php echo $fetch_detail['qnude_suggestion']; ?></td>
			<?php 	} ?>
				</tr>
		<?php 	

			} 
		}
		?>
	</tbody>
	<tfoot>
		<th></th>
		<?php 
			$a = 0;
			$out_arr = array();
			$data1 = data_rechk($conndb,$survey_id,1);
			$data2 = data_rechk($conndb,$survey_id,2);
			$data3 = data_rechk($conndb,$survey_id,3);
			$data4 = data_rechk($conndb,$survey_id,4);
			$data5 = data_rechk($conndb,$survey_id,5);
			$strSQL = "SELECT * FROM lms_survey_de where lms_survey_de.sv_id = '".$survey_id."'";
			$objQuery = mysqli_query($conndb,$strSQL);
			$num_row = mysqli_num_rows($objQuery);
			while($fetch_chk = mysqli_fetch_array($objQuery)){
				$total = 0;
				$ans1[$a]=0;$ans2[$a]=0;$ans3[$a]=0;$ans4[$a]=0;$ans5[$a]=0;



				foreach($data1  as $key1 => $rowda) {if(($fetch_chk['svde_id'] == $rowda['svde_id'])&&($rowda['qnude_var'] == '1')):$ans1[$a]++;endif;}
				foreach($data2  as $key2 => $rowda) {if(($fetch_chk['svde_id'] == $rowda['svde_id'])&&($rowda['qnude_var'] == '2')):$ans2[$a]++;endif;}
				foreach($data3  as $key3 => $rowda) {if(($fetch_chk['svde_id'] == $rowda['svde_id'])&&($rowda['qnude_var'] == '3')):$ans3[$a]++;endif;}
				foreach($data4  as $key4 => $rowda) {if(($fetch_chk['svde_id'] == $rowda['svde_id'])&&($rowda['qnude_var'] == '4')):$ans4[$a]++;endif;}
				foreach($data5  as $key5 => $rowda) {if(($fetch_chk['svde_id'] == $rowda['svde_id'])&&($rowda['qnude_var'] == '5')):$ans5[$a]++;endif;}

				$val1 = intval($ans1[$a])*1;
				$val2 = intval($ans2[$a])*2;
				$val3 = intval($ans3[$a])*3;
				$val4 = intval($ans4[$a])*4;
				$val5 = intval($ans5[$a])*5;
				$total_val = $val1 + $val2 + $val3 + $val4 + $val5;

				$total = $ans1[$a] + $ans2[$a] + $ans3[$a] + $ans4[$a] + $ans5[$a];
				$output = array();
				if($total_val>0&&$total>0){
					$calval = $total_val/$total;
				}else{
					$calval = 0;
				}
				$output['mean'] = $calval;
				$output['percent'] = (($calval)*100)/5;
				$output['total_val'] = $total;
				//print_r($data1);
				array_push($out_arr, $output);
				$a++;
			}

			$num = 0;
			//print_r($out_arr);
			$strSQL = "SELECT * FROM lms_survey_de where lms_survey_de.sv_id = '".$survey_id."'";
			$objQuery = mysqli_query($conndb,$strSQL);
			$num_row = mysqli_num_rows($objQuery);
			while($fetch_chk = mysqli_fetch_array($objQuery)){ ?>
				<th colspan="2"><?php echo $out_arr[$num]['mean']." : ".$out_arr[$num]['percent']." %" ?></th>
	<?php 	$num++;
			} ?>
	</tfoot>
</table>
 
</body>
</html>
