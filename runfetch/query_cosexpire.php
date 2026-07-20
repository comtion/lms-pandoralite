<?php 
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');
date_default_timezone_set("Asia/Bangkok");
$thaimonth = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

$obj_sql = "select * from lms_setting_mail where sm_id='1'";
$query_obj = mysqli_query($conndb,$obj_sql);
$object_connect = mysqli_fetch_array($query_obj);

$where = 'cos_approve="1" and cos_public="1" and cos_expire_noti!="" and cos_status="1" and cos_isDelete="0" and lms_cos_detail.date_end!="0000-00-00 00:00:00" and lms_cos_detail.date_end >= "'.date('Y-m-d').'"';// and lms_cos.cname_eng = "How you like that_EN"
$db->where($where);
$db->join('lms_cos_detail','lms_cos_detail.cos_id = lms_cos.cos_id');
$fetch_courseexp = $db->get('lms_cos', null, 'cname_eng,cos_expire_noti,date_start,date_end');
$lang = "english";
if(count($fetch_courseexp)>0){
    foreach ($fetch_courseexp as $key_courseexp => $value_courseexp) {
        if($value_courseexp['cos_expire_noti']!=""){
            $cos_expire_noti = explode(",",$value_courseexp['cos_expire_noti']);
            $numrechk = 0;
            $numtotal = 0;
            $num_chk = 0;
            $arrDateNoti = array();
            for ($i=0; $i < count($cos_expire_noti); $i++) { 
                $numtotal++;
                if(isset($cos_expire_noti[$i])&&$cos_expire_noti[$i]!=""){
                    $numrechk++;
                    if(date('Y-m-d')<=date('Y-m-d',strtotime($value_courseexp['date_end']))){
                        if($cos_expire_noti[$i]=="0"){
                            $date_selectend = date('Y-m-d',strtotime($value_courseexp['date_end']));
                        }else{
                            $date_selectend = date('Y-m-d',strtotime($value_courseexp['date_end'].' -'.$cos_expire_noti[$i].'day'));
                        }
                        array_push($arrDateNoti, $date_selectend);
                        $date_now = date('Y-m-d');
                        //echo $date_now.":::".$date_selectend."<br>";
                        if($date_now!=$date_selectend){
                            $num_chk++;
                            //unset($fetch_courseexp[$key_courseexp]);
                        }
                    }
                    // else{
                    //     unset($fetch_courseexp[$key_courseexp]);
                    // }
                }
            }
            $fetch_courseexp[$key_courseexp]["arrDateNoti"] = $arrDateNoti;
            // if($num_chk>=count($cos_expire_noti)){
            //     unset($fetch_courseexp[$key_courseexp]);
            // }
            // if($numrechk==0){
            //     unset($fetch_courseexp[$key_courseexp]);
            // }
        }else{
            unset($fetch_courseexp[$key_courseexp]);
        }
    }
}
print_r($fetch_courseexp);