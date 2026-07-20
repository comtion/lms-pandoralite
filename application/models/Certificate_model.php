<?php
class Certificate_model extends CI_Model {

        public function __construct()
        {
          // Call the CI_Model constructor
          parent::__construct();
        }
        
        public function loadDB()
        {
          $this->load->database();
        }

        public function closeDB()
        {
          $this->db->close();
        }

        private function getMpdfTempDir()
        {
          $tempDir = ROOT_DIR.'uploads/mpdf/tmp';
          if(!is_dir($tempDir)){
            @mkdir($tempDir, 0777, true);
          }
          return $tempDir;
        }
        
        public function createfile($arr_user,$cos_id,$dateissue=''){
          date_default_timezone_set("Asia/Bangkok");
          $thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
          $this->db->from('lms_certificate');
          $this->db->where('cos_id',$cos_id);
          $this->db->where('emp_id',$arr_user['emp_id']);
          $query_chk = $this->db->get();
          $fetch_chk = $query_chk->row_array();
          if(countArray($fetch_chk)>0){
            return $fetch_chk['cert_file'];
          }else{
            $this->db->from('lms_bad');
            $this->db->where('courses_id',$cos_id);
            $query_bad = $this->db->get();
            $fetch_bad = $query_bad->row_array();

            $this->db->from('lms_cos');
            $this->db->join('lms_typecos','lms_cos.tc_id = lms_typecos.tc_id');
            $this->db->where('cos_id',$cos_id);
            $query_cos = $this->db->get();
            $fetch_cos = $query_cos->row_array();

            $this->db->from('lms_cos_enroll');
            $this->db->where('cos_id',$cos_id);
            $this->db->where('emp_id',$arr_user['emp_id']);
            $query_enroll = $this->db->get();
            $fetch_enroll = $query_enroll->row_array();
                
                header('Cache-Control: no-cache');
                header('Pragma: no-cache');
                header('Expires: 0');

                // REAL PATH

              require_once  './vendor/autoload.php';

							$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
							$fontData = $defaultFontConfig['fontdata'];
              $fontDirs = (new Mpdf\Config\ConfigVariables())->getDefaults()['fontDir'];
              $pdf = new \Mpdf\Mpdf([
                'margin_left' => 40,
                'margin_right' => 40,
                'orientation' => 'L',
                'tempDir' => $this->getMpdfTempDir(),
                'fontDir' => array_merge($fontDirs, [
                  ROOT_DIR.'assets/tfpdf/font',
                ]),
								'fontdata' => $fontData + [ // lowercase letters only in font key
									'cordia' => [
										'R' => 'cordia.ttf',
                  ],
                  'freesans' => [
										'R' => 'FreeSans.ttf',
                    'B' => 'FreeSansBold.ttf',
                  ],
                  'notojpbold' => [
										'R' => 'NotoSansJP-Bold.ttf',
                    'B' => 'NotoSansJP-Bold.ttf',
                    'useOTL' => 0xFF,
                  ]
								],
								'default_font' => 'cordia'
							]);
              
              if($fetch_enroll['cosen_lang']=="thai"){
                  $path_img = ROOT_DIR.'uploads/certificate/certificate_original_th.jpg';
              }else{
                  $path_img = ROOT_DIR.'uploads/certificate/certificate_original.jpg';
              }
              $pdf->SetTopMargin(80);
              $pdf->SetLeftMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              $pdf->SetRightMargin(20); // ตั้งระยะขอบซ้ายเป็น 20 มม.
              
              $pdf->AddPage('L','mm','A4');
              $pdf->Image($path_img, 0, 0, 297, 210, 'jpg', '', true, false,true); //mm 
              $pdf->SetDisplayMode('fullpage');

              if($fetch_enroll['cosen_lang']=="thai"){ 
                  $cname = $fetch_cos['cname_th']!=""?$fetch_cos['cname_th']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else if($fetch_enroll['cosen_lang']=="english"){ 
                  $cname = $fetch_cos['cname_eng']!=""?$fetch_cos['cname_eng']:$fetch_cos['cname_th'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_jp'];
              }else{
                  $cname = $fetch_cos['cname_jp']!=""?$fetch_cos['cname_jp']:$fetch_cos['cname_eng'];
                  $cname = $cname!=""?$cname:$fetch_cos['cname_th'];
              }
              if(in_array($fetch_enroll['cosen_lang'], array('japan','english'))){
                if($fetch_enroll['cosen_lang']=="japan"){
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }else{
                    $label_a = 'Complete the course';
                    $label_b = 'Type';
                    $label_c = 'With score';
                    $label_d = 'Issued on';
                }
                $tc_name = $fetch_cos['tc_name_en'];
                if($dateissue!=''){
                  $dateval = date('d F Y',strtotime($dateissue));
                }else{

                $dateval = date('d F Y');
                }
                
                $fullname = $arr_user['fullname_en'];
                $com_name = $arr_user['com_name_eng'];
                
                $currentFont = $fetch_enroll['cosen_lang'] == "english" ? 'freesans' : 'notojpbold';
                $layout = certificate_text_layout($fetch_enroll['cosen_lang'] == "english" ? 'EN' : 'JP');
                $pdf->SetTextColor(0,0,0);
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont($currentFont, 'B', $layout['name_size']);
                $pdf->WriteCell(0, $layout['name_height'], $fullname, 0, 1, 'C');

                $pdf->SetY($layout['company_y']);
                $pdf->SetFont($currentFont, 'B', $layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'], $com_name, 0, 1, 'C');

                $pdf->SetY($layout['course_y']);
                $safeCourse = htmlspecialchars($cname, ENT_QUOTES, 'UTF-8');
                $pdf->WriteHTML("<div style='font-family:$currentFont; font-weight:bold; font-size:{$layout['course_size']}pt; text-align:center; width:100%; line-height:1.1;'>$safeCourse</div>");

                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont($currentFont, 'B', $layout['issued_size']);
                $pdf->WriteCell(0, $layout['issued_height'], $label_d.' '.$dateval, 0, 1, 'C');
              }else{
                $label_a = 'สำเร็จหลักสูตร';
                $label_b = 'ประเภท';
                $label_c = 'ด้วยคะแนน';
                $label_d = 'ให้ไว้ ณ วันที่';
                $tc_name = $fetch_cos['tc_name_th'];
                if($dateissue!=''){
                  $dateval = date('d F Y',strtotime($dateissue));
                  
                $dateval = date('d',strtotime($dateissue))." ".$thaimonth[intval(date('m',strtotime($dateissue)))]." ".(date('Y',strtotime($dateissue))+543);
                }else{
                  
                $dateval = date('d')." ".$thaimonth[intval(date('m'))]." ".(date('Y')+543);
    
                }

                $fullname = $arr_user['fullname_th'];
                $com_name = $arr_user['com_name_th'];
                
                $layout = certificate_text_layout('TH');
                $pdf->SetY($layout['name_y']);
                $pdf->SetFont('cordia','',$layout['name_size']);
                $pdf->SetTextColor(0,0,0); 
                $pdf->WriteCell(0, $layout['name_height'],$fullname, 0, 1, 'C');
                $pdf->SetY($layout['company_y']);
                $pdf->SetFont('cordia','',$layout['company_size']);
                $pdf->WriteCell(0, $layout['company_height'],$com_name, 0, 1, 'C');
                $pdf->SetFont('cordia','',25);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,10,'',0,1,'C'); //space
                // $pdf->WriteCell(0,13,$cname,0,1,'C');

                $pdf->SetY($layout['course_y']);
                $pdf->WriteHTML("<p  style='white-space: nowrap; font-size:{$layout['course_size']}pt;text-align: center;'>".$cname."</p>");
                // $pdf->WriteCell(0, 0, $cname, 0, 1, 'C', 0, '', 0, true, false, true, 0, true);
                $pdf->SetY($layout['issued_y']);
                $pdf->SetFont('cordia','',$layout['issued_size']);
                $pdf->SetTextColor(0,0,0);   
                $pdf->WriteCell(0,$layout['issued_height'],$label_d.' '.$dateval,0,1,'C');
              }
              $name_cert = "Certificate_".$cos_id."_".$arr_user['emp_id']."_".date('YmdHis').".pdf";
              $filename = ROOT_DIR."uploads/certificate/".$name_cert;
              $pdf->Output($filename,'F');

                $data = array(
                  'cos_id' => $cos_id,
                  'emp_id' => $arr_user['emp_id'],
                  'cert_file' => $name_cert,
                  'cert_date' => date('Y-m-d'),
                  'cert_createtime' => date('Y-m-d H:i')
                );
                $this->db->insert('lms_certificate', $data);
            return $name_cert;
          }
        }
}
