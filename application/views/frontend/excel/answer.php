<?php
$filename = $mem_type.'_report';
  header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
  header("Content-Disposition: attachment; filename=" . 'รายชื่อผู้ตอบคำถาม_'.$quest['qst']['questions_name'] . ".xls");
  header("Pragma: no-cache");
  header("Expires: 0");
?>
<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Activity</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
              </xml>
    <![endif]-->
</head>

<body>
    <div class="stat-all">
      <p><span> หลักสูตร </span> : <?php echo "[ ".$course['ucode']." ] : ".$course['cname'];?></p>
      <p><span> แบบทดสอบ </span> : <?php echo $quiz['quiz_name'];?></p>
      <p><span> คำถาม </span> : <?php echo $quest['qst']['questions_name']; ?></p>
      <p><span> คะแนนเต็ม </span> : <?php echo $quest['qst']['score']; ?></p>
      <p><span> จำนวนผู้ตอบ </span> : <?php echo sizeof( $answers ); ?></p>
    </div>
    <table border="1">
      <thead>
        <tr>
          <th class="header">รหัสพนักงาน</th>
          <th class="header">ชื่อ - นามสกุล</th>
          <th class="header">คำตอบ</th>
          <th class="header">คะแนน</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        <?php foreach( $answers as $list){ ?>
        <tr class="list-check" data-id="<?php echo $list['emp_c']; ?>">

          <td><?php echo $list['emp_c']; ?></td>
          <td><?php echo $list['emp_name']; ?></td>
          <td><?php echo $list['ans']; ?></td>
          <td></td>
        </tr>
      <?php $i++;} ?>
      </tbody>
    </table>
</body>
</html>
