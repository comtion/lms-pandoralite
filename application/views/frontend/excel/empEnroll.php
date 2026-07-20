<?php
$filename = $mem_type.'_report';
  header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
  header("Content-Disposition: attachment; filename=" . 'รายชื่อผู้ลงทะเบียนหลักสูตร_'.$course['cname'] . ".xls");
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
      <p><span> รายชื่อผู้ลงทะเบียนหลักสูตร </span> : <?php echo "[ ".$course['ucode']." ] : ".$course['cname'];?></p>
      <p><span> จำนวน </span> : <?php echo sizeof( $real_l ); ?></p>
    </div>
    <table border="1">
      <thead>
        <tr>
          <th class="header">รหัสพนักงาน</th>
          <th class="header">ชื่อ</th>
          <th class="header">นามสกุล</th>
          <th class="header">อีเมล</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 1; ?>
        <?php foreach( $real_l as $list){ ?>
        <tr class="list-check" data-id="<?php echo $list['id']; ?>">

          <td><?php echo $list['emp_c']; ?></td>
          <td><?php echo $list['fname']; ?></td>
          <td><?php echo $list['lname']; ?></td>
          <td> <?php echo $list['email']; ?> </td>
        </tr>
      <?php $i++;} ?>
      </tbody>
    </table>
</body>
</html>
