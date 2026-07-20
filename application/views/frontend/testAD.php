<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
      <?php
      //$ldap_host = "ldap.forumsys.com";
      $ldap_host = "172.20.50.253";
      $ldap_port = "389";
      //$ldap_dn = "cn=read-only-admin,dc=example,dc=com";
      $ldap_dn = "uid=hrelearning@asiaplus.co.th,dc=asiaplus,dc=co,dc=th";
      //$ldap_password = "password";
      $ldap_password = "HR-Elearning08!";

      $ldap_con = ldap_connect($ldap_host, $ldap_port);
      ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

      if($ldap_con) {
          echo "Can connect<br>";
          echo $ldap_host." ".$ldap_port."<br>";
          echo $ldap_dn."<br>";
      }

      if(@ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
          /*$filter = "uid=*";
          $result = ldap_search($ldap_con, "dc=example,dc=com", $filter) or exit("Unable to search");
          $enties = ldap_get_entries($ldap_con, $result);

          echo "<pre>";
          print_r($enties);
          echo "</pre>";*/
          echo "Successful";
      }
      else {
          echo "Error!";
      }
      ?>
    </body>
</html>
