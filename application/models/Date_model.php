<?php
class Date_model extends CI_Model {
  public function __construct()
  {
    // Call the CI_Model constructor
    parent::__construct();
  }

  public function convertDate($oriDate)
	{
		if ($oriDate == '' || $oriDate == ' ' || strpos($oriDate,'0000-00-00')){
      return '0000-00-00';
		}
    $date = DateTime::createFromFormat('d/m/Y', $oriDate);
    return $date->format('Y-m-d');
	}

  public function convertSqlDate($date)
	{
    $datetime = DateTime::createFromFormat('Y-m-d H:i', $date);
    if ($date == '0000-00-00 00:00:00'){
      return '';
    }
		return $datetime->format('d/m/Y H:i');
	}

  public function convertToView($date)
  {
    $datetime = DateTime::createFromFormat('Y-m-d H:i', $date);
    $thaimonth = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		return $datetime->format('j ').$thaimonth[intval($datetime->format('m'))-1].' '.($datetime->format('Y')+543).' '.$datetime->format('H:i');
  }

  public function getBadYears($data)
  {
    $years = array();
    foreach ($data as $each) {
        /*$date = DateTime::createFromFormat('Y-m-d H:i', $each['time']);
        $each['time'] = $date->format('j M Y H:i');
        if (!isset($years[$date->format('Y')])){
          $years[$date->format('Y')] = array();
        }
        array_push($years[$date->format('Y')], $each);*/
        $year = date('Y', strtotime($each['finish_time']));
        $years[$year][] = $each;
    }
    return $years;
  }

  public function getLogYears($data)
  {
    $years = array();
    $thaimonth = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
    foreach ($data as $each) {
        $date = DateTime::createFromFormat('Y-m-d H:i', $each['log_time']);
        $each['log_time'] = $date->format('j ').$thaimonth[intval($date->format('m'))-1].$date->format(' Y H:i');
        if (!in_array($date->format('Y'), $years)){
          array_push($years, $date->format('Y'));
        }
    }
    return $years;
  }

}
?>
