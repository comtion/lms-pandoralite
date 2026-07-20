<?php
include('config_db.php');
//include('../application/controllers/class/class.simple_mail.php');
include('../application/controllers/class/phpmailer/PHPMailerAutoload.php');

$arrRemoveCourse = array(
    'PRD Notification Test',
    'IT Security Awareness (Test)',
    'Test'
);
$arrRemoveVideo = array(
    '[ICO 2021] Basic Compliance: ความรู้ทั่วไปด้านการกำกับดูแลฯ',
    '[ICO 2021] PDPA: เตรียมความพร้อมเพื่อรับมือกับกฎหมายคุ้มครองข้อมูลส่วนบุคคล',
    '[ICO 2021] Japanese Business Manners: มารยาททางธุรกิจแบบญี่ปุ่น',
    '[ICO 2021] Isuzu Midterm Business Plan: แผนธุรกิจระยะกลางปี 2567',
    '[ICO 2021] Isuzu Beyond the Best: ค่านิยมพนักงานอีซูซุ (TH)',
    '[ICO 2021] Isuzu Corporate Information : ข้อมูลองค์กรอีซูซุ',
    'การล่วงละเมิดหรือคุกคามในที่ทำงาน (IMIT 7 ธันวาคม 2564  - 31 มกราคม 2565)',
    'Thai Manager School#4 (Postest)',
    'Team Spirit @ Work (Group 2)',
    'Proactive Working Mindset (Group 2)',
    'Energized Presentation Skill (Group 2)',
    '適切な情報管理（2021年10月15日~11月15日ITT向け）(การควบคุมดูแลข้อมูลอย่างเหมาะสม)',
    '職場におけるハラスメント（2021年度タイいすゞグループ）(การล่วงละเมิดหรือคุกคามในที่ทำงาน(JP))',
    'ความรู้ทั่วไปด้านการกำกับดูแลฯ (LNXI 12 - 15 ตุลาคม 2564)',
    'การล่วงละเมิดหรือคุกคามในที่ทำงาน (IGCE 1 - 31 ตุลาคม 2564)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (IGCE 1 - 31 ตุลาคม 2564)',
    'ความรู้ทั่วไปด้านการกำกับดูแลฯ (IGCE 1 - 31 ตุลาคม 2564)',
    '適切な情報管理（2021年9月13日~30日IMIT向け）(การควบคุมดูแลข้อมูลอย่างเหมาะสม)',
    '適切な情報管理（2021年9月15日~10月15日IGCE向け）(การควบคุมดูแลข้อมูลอย่างเหมาะสม)',
    'Thai Manager School#4 (Pretest)',
    'Team Spirit @ Work',
    'Proactive Working Mindset',
    'Energized Presentation Skill',
    '適切な情報管理（2021年度 第2回IMAT向け）',
    '適切な情報管理（2021年度タイいすゞグループ）',
    'ความรู้ทั่วไปด้านการกำกับดูแลฯ (IJTT 1 สิงหาคม - 30 กันยายน 2564)',
    'การล่วงละเมิดหรือคุกคามในที่ทำงาน (IGCE พฤษภาคม 2564)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (IGCE พฤษภาคม 2564)',
    'ความรู้ทั่วไปด้านการกำกับดูแลฯ (IGCE พฤษภาคม 2564)',
    'ความรู้ทั่วไปด้านการกำกับดูแลฯ (IBCT พฤษภาคม - กรกฎาคม 2564)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (หลักสูตรเพิ่มเติมระหว่างวันที่ 1 - 30 เมษายน 2564)',
    '適切な情報管理（2021年度IMAT向け）',
    'การล่วงละเมิดหรือคุกคามในที่ทำงาน (หลักสูตรเพิ่มเติมระหว่างวันที่ 1 - 30 เมษายน 2564)',
    'ตอบปุ๊บ รับปั๊บ! กับการกำกับดูแลฯ',
    'ตอบปุ๊บ รับปั๊บ! กับการกำกับดูแลฯ (ทดลอง3)',
    'ตอบปุ๊บ รับปั๊บ! กับการกำกับดูแลฯ (ทดลอง)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (หลักสูตรเพิ่มเติมระหว่างวันที่ 25 มกราคม - 28 กุมภาพันธ์ 2564)',
    'การล่วงละเมิดหรือคุกคามในที่ทำงาน (สำหรับกลุ่มบริษัทอีซูซุในประเทศไทย ปี 2563)',
    'IQ',
    '漢字 KANJI ',
    'Japanese Festival for IT',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (หลักสูตรเพิ่มเติมสำหรับกลุ่มบริษัทอีซูซุในประเทศไทย ปี 2563)',
    'Isuzu Midterm Business Plan: แผนธุรกิจระยะกลางอีซูซุ (รุ่น2)',
    'Isuzu Beyond the Best: ค่านิยมพนักงานอีซูซุ (รุ่น2)',
    'Isuzu Corporate Information: ข้อมูลองค์กรอีซูซุ (รุ่น2)',
    'Japanese Business Manners: มารยาททางธุรกิจแบบญี่ปุ่น (รุ่น2)',
    'Train the Trainer (Group D)',
    'แบบทดสอบหลังอบรม (Post-test) Thai ISUZU Manager School รุ่นที่ 3',
    'Train the Trainer (สำหรับทบทวนบทเรียน)',
    'Train the Trainer (Group C)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (สำหรับบริษัท IEMT ปี 2563)',
    'Train the Trainer (Group B)',
    'Train the Trainer (Group A)',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (สำหรับกลุ่มบริษัทอีซูซุในประเทศไทย ปี 2563)',
    'Isuzu Midterm Business Plan: แผนธุรกิจระยะกลางอีซูซุ (รุ่น1)',
    'Japanese Business Manners: มารยาททางธุรกิจแบบญี่ปุ่น (รุ่น1)',
    'Isuzu Corporate Information: ข้อมูลองค์กรอีซูซุ (รุ่น1)',
    'Isuzu Beyond the Best: ค่านิยมพนักงานอีซูซุ (รุ่น1)',
    'ข้อมูลองค์กรอีซูซุ',
    'New Normal: How to work at office safely during COVID-19?',
    'Japanese Business Manners: มารยาททางธุรกิจแบบญี่ปุ่น',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม',
    'การควบคุมดูแลข้อมูลอย่างเหมาะสม (ทดลอง) ',
    'Japanese Festival',
    'Isuzu Beyond the Best'
);

$output = array(
    "removeCourse" => array(),
    "removeContent" => array()
);
if (!empty($arrRemoveCourse)) {
    foreach ($arrRemoveCourse as $keyCourse) {
        $arrMedia = array();
        $sqlCourse = "SELECT * FROM `lms_cos` WHERE (`cname_th` in ( '" . $keyCourse . "' ) or `cname_eng` in ( '" . $keyCourse . "' )) and cos_isDelete = 0);";
        $queryCourse = $conndb->query($sqlCourse);
        $fetchCourse = mysqli_fetch_array($queryCourse);
        if (count($fetchCourse) > 0) {

            $sqlMedia = "SELECT * FROM `lms_med` where lessons_id in (select lms_les.les_id from lms_les where lms_les.cos_id = " . $fetchCourse["cos_id"] . " and lms_les.les_isDelete = 0";
            $queryMedia = $conndb->query($sqlMedia);
            $fetchMedia = mysqli_fetch_array($queryMedia);
            $numMedia = count($fetchMedia);
            if ($numMedia > 0) {
                while ($fetchMedia) {
                    // if ($fetchMedia["thumbnail_med"] != "" && is_file('../uploads/thumbnail/' . $fetchMedia["thumbnail_med"])) {
                    //     unlink('../uploads/thumbnail/' . $fetchMedia["thumbnail_med"]);
                    // }
                    if ($fetchMedia["video"] != "" && is_file('../uploads/media/' . $fetchMedia["video"])) {
                        unlink('../uploads/media/' . $fetchMedia["video"]);
                    }
                    array_push($arrMedia, array(
                        "med_name_th"   => $fetchMedia["med_name_th"],
                        "med_name_eng"  => $fetchMedia["med_name_eng"],
                        "med_name_jp"   => $fetchMedia["med_name_jp"],
                        "thumbnail"     => $fetchMedia["thumbnail_med"],
                        "video"         => $fetchMedia["video"],
                    ));
                    mysqli_query($conndb, "DELETE FROM lms_med where id = " . $fetchMedia["id"]);
                }
            }

            mysqli_query($conndb, "update lms_cos set cos_isDelete = 1 where cos_id = " . $fetchCourse["cos_id"]);
            array_push($output["removeCourse"], array(
                "courseName" => $keyCourse,
                "arrayMedia" => $arrMedia
            ));
        }
    }
}


if (!empty($arrRemoveVideo)) {
    echo "--- Remove Vdo --- <br>";
    foreach ($arrRemoveVideo as $keyVideoCourse) {
        $arrMedia = array();
        $sqlCourseMedia = "SELECT lms_cos.cos_id,lms_cos.cname_th,lms_les.les_id,lms_les.les_name_th,lms_med.lessons_id,lms_med.video
FROM lms_cos INNER JOIN lms_les ON lms_les.cos_id=lms_cos.cos_id
INNER JOIN lms_med ON lms_med.lessons_id = lms_les.les_id
WHERE cname_th ='" . $keyVideoCourse . "'";
        echo $sqlCourseMedia . ";<br>";
        $resultCourseMedia = mysqli_query($conndb, $sqlCourseMedia);
        // $rowCourseMedia = mysqli_fetch_assoc($resultCourseMedia);


        while ($rowCourseMedia = mysqli_fetch_assoc($resultCourseMedia)) {
            echo "VDO:" . $rowCourseMedia;
        }

        // $fetchCourse = mysqli_fetch_array($queryCourse);

        // if (count($fetchCourse) > 0) {
        // echo "Course ID :" . $fetchCourse["cos_id"] . "<br>";
        // echo "Course name :" . $fetchCourse["cname_th"] . "<br>";







        // if ($numMedia > 0) {

        //     // while ($row = mysqli_fetch_assoc($queryMedia)) {
        //     echo "VDO :" . $fetchMedia["video"] . "<br>";
        //     // if ($fetchMedia["thumbnail_med"] != "" && is_file('../uploads/thumbnail/' . $fetchMedia["thumbnail_med"])) {
        //     //     unlink('../uploads/thumbnail/' . $row["thumbnail_med"]);
        //     // }

        //     if ($fetchMedia["video"] != "" && is_file('../uploads/media/' . $fetchMedia["video"])) {
        //         unlink('../uploads/media/' . $row["video"]);
        //     }
        //     array_push($arrMedia, array(
        //         "med_name_th"   => $fetchMedia["med_name_th"],
        //         "med_name_eng"  => $fetchMedia["med_name_eng"],
        //         "med_name_jp"   => $fetchMedia["med_name_jp"],
        //         "thumbnail"     => $fetchMedia["thumbnail_med"],
        //         "video"         => $fetchMedia["video"],
        //     ));
        //     mysqli_query($conndb, "DELETE FROM lms_med where id = " . $fetchMedia["id"]);
        //     // }
        // }
        // }
        array_push($output["removeContent"], array(
            "courseName" => $keyVideoCourse,
            "arrayMedia" => $arrMedia
        ));
        // }
    }
}

if (!empty($arrRemoveCourse) || !empty($arrRemoveVideo)) {
    $objSQLSettingMail = "select * from lms_setting_mail where sm_id='1'";
    $querySettingMail = mysqli_query($conndb, $objSQLSettingMail);
    $objectSettingMail = mysqli_fetch_array($querySettingMail);


    // sendEmail("it.bangkok@verztec.com", implode(",", $output), "Remove File Video & Content of Courses", $objectSettingMail);
}

function sendEmail($email, $message, $subject, $object_connect)
{
    //require_once 'class/phpmailer/PHPMailerAutoload.php';
    header('Content-Type: text/html; charset=utf-8');
    $sub = "ข้อความจากเว็บไซต์";
    $mail = new PHPMailer;
    $mail->CharSet = "utf-8";

    $mail->isSMTP();
    $mail->Host = $object_connect['sm_host']; //'mail.verztec.com';
    $mail->Port = $object_connect['sm_port']; //587;
    //$mail->SMTPSecure = 'tls';
    if ($object_connect['sm_smtpauth'] == "true") {
        $mail->SMTPAuth = true;
    } else {
        $mail->SMTPAuth = false;
    }
    //true;

    $gmail_username = $object_connect['sm_username']; //"pandora@verztec.com"; // gmail ที่ใช้ส่ง
    $gmail_password = $object_connect['sm_password']; //"pppp99999"; // รหัสผ่าน gmail
    // ตั้งค่าอนุญาตการใช้งานได้ที่นี่ https://myaccount.google.com/lesssecureapps?pli=1
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $sender = $object_connect['sm_sender']; //"THAIHEALTH LMS"; // ชื่อผู้ส่ง
    $email_sender = $object_connect['sm_emailsender']; //"pandora@verztec.com"; // เมล์ผู้ส่ง 
    $email_receiver = $email; // เมล์ผู้รับ ***


    $mail->Username = $gmail_username;
    $mail->Password = $gmail_password;
    $mail->setFrom($email_sender, $sender);
    $mail->addAddress($email_receiver);
    //$mail->addBcc("yupontee.k@verztec.com");
    //$mail->addBcc("jetsada.d@verztec.com");
    $mail->Subject = $subject;
    if ($email_receiver) {
        $mail->msgHTML($message);
        if (!$mail->send()) {  // สั่งให้ส่ง email
            // กรณีส่ง email ไม่สำเร็จ
            //echo "Error_Sentmail";
            //echo $mail->ErrorInfo; // ข้อความ รายละเอียดการ error
        } else {
            // กรณีส่ง email สำเร็จ
            //echo "Send Success";
        }
    }
}
