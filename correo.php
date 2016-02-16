<?php require_once("webassist/email/mail_php.php"); ?>
<?php require_once("webassist/email/mailformatting_php.php"); ?>
<?php
if (("" == ""))     {
  //WA Universal Email object="mail"
  @session_write_close();
  @set_time_limit(0);
  $EmailRef = "waue_correo_1";
  $BurstSize = 200;
  $BurstTime = 1;
  $WaitTime = 1;
  $GoToPage = "index.php?mod=gracias";
  $RecipArray = array();
  $StartBurst = time();
  $LoopCount = 0;
  $TotalEmails = 0;
  $RecipIndex = 0;
  // build up recipients array
  $CurIndex = sizeof($RecipArray);
  $RecipArray[$CurIndex] = array();
  $RecipArray[$CurIndex ][] = "atencionaclientes@hagrupo.com";
  $TotalEmails += sizeof($RecipArray[$CurIndex]);
  $RealWait = ($WaitTime<0.25)?0.25:($WaitTime+0.1);
  $TimeTracker = Array();
  $TotalBursts = floor($TotalEmails/$BurstSize);
  $AfterBursts = $TotalEmails % $BurstSize;
  $TimeRemaining = ($TotalBursts * $BurstTime) + ($AfterBursts*$RealWait);
  if ($TimeRemaining < ($TotalEmails*$RealWait) )  {
    $TimeRemaining = $TotalEmails*$RealWait;
  }
  writeUEProgress($EmailRef,0,$TotalEmails,$TimeRemaining);
  while ($RecipIndex < sizeof($RecipArray))  {
    $EnteredValue = is_string($RecipArray[$RecipIndex][0]);
    $CurIndex = 0;
    while (($EnteredValue && $CurIndex < sizeof($RecipArray[$RecipIndex])) || (!$EnteredValue && $RecipArray[$RecipIndex][0])) {
      $starttime = microtime_float();
      if ($EnteredValue)  {
        $RecipientEmail = $RecipArray[$RecipIndex][$CurIndex];
      }  else  {
        $RecipientEmail = $RecipArray[$RecipIndex][0][$RecipArray[$RecipIndex][2]];
      }
      $EmailsRemaining = ($TotalEmails- $LoopCount);
      $BurstsRemaining = ceil(($EmailsRemaining-$AfterBursts)/$BurstSize);
      $IntoBurst = ($EmailsRemaining-$AfterBursts) % $BurstSize;
      if ($AfterBursts<$EmailsRemaining) $IntoBurst = 0;
      $TimeRemaining = ($BurstsRemaining * $BurstTime * 60) + ((($AfterBursts<$EmailsRemaining)?$AfterBursts:$EmailsRemaining)*$RealWait) - (($AfterBursts>$EmailsRemaining)?0:($IntoBurst*$RealWait));
      if ($TimeRemaining < ($EmailsRemaining*$RealWait) )  {
        $TimeRemaining = $EmailsRemaining*$RealWait;
      }
      $CurIndex ++;
      $LoopCount ++;
      writeUEProgress($EmailRef,$LoopCount,$TotalEmails,round($TimeRemaining));
      wa_sleep($WaitTime);
      include("webassist/email/waue_correo_1.php");
      $endtime = microtime_float();
      $TimeTracker[] =$endtime - $starttime;
      $RealWait = array_sum($TimeTracker)/sizeof($TimeTracker);
      if ($LoopCount % $BurstSize == 0 && $CurIndex < sizeof($RecipArray[$RecipIndex]))  {
        $TimePassed = (time() - $StartBurst);
        if ($TimePassed < ($BurstTime*60))  {
          $WaitBurst = ($BurstTime*60) -$TimePassed;
          wa_sleep($WaitBurst);
        }
        else  {
          $TimeRemaining = ($TotalEmails- $LoopCount)*$RealWait;
        }
        $StartBurst = time();
      }
      if (!$EnteredValue)  {
        $RecipArray[$RecipIndex][0] =  mysql_fetch_assoc($RecipArray[$RecipIndex][1]);
      }
    }
    $RecipIndex ++;
  }
  @session_start();
  $_SESSION[$EmailRef."_Status"] = $GLOBALS[$EmailRef."_Status"];
  $_SESSION[$EmailRef."_Index"] = $GLOBALS[$EmailRef."_Index"];
  $_SESSION[$EmailRef."_From"] = $GLOBALS[$EmailRef."_From"];
  $_SESSION[$EmailRef."_To"] = $GLOBALS[$EmailRef."_To"];
  $_SESSION[$EmailRef."_Subject"] = $GLOBALS[$EmailRef."_Subject"];
  $_SESSION[$EmailRef."_Body"] = $GLOBALS[$EmailRef."_Body"];
  $_SESSION[$EmailRef."_Header"] = $GLOBALS[$EmailRef."_Header"];
  $_SESSION[$EmailRef."_Log"] = $GLOBALS[$EmailRef."_Log"];
  if (function_exists("rel2abs")) $GoToPage = $GoToPage?rel2abs($GoToPage,dirname(__FILE__)):"";
  if ($GoToPage!="")     {
    header("Location: ".$GoToPage);
  }
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Untitled Document</title>
</head>

<body>

</body>
</html>