<?php
require_once('./includes/inc_encrypt.php');
require_once('../api-app/lib/Globals.php');
$db=$GLOBALS['db'];
$servername = "localhost";
$dbname = $db['database_name'];
$username = $db['username'];
$password = $db['password'];
$type=1;
if (!empty($_GET["t"])) {
    $type=$_GET["t"];
}

if (!empty($_GET["id"])) {
            $p_id=$_GET["id"];
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT user_id, p_name, p_company, p_job, p_gage, p_start, p_end, p_json, p_comment, spesen FROM `projects` WHERE project_id='$p_id';";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    $p_name = $row["p_name"];
                    $p_company = $row["p_company"];
                    $p_job = $row["p_job"];
                    $p_pay = $row["p_gage"];
                    $i_sdate = DateTime::createFromFormat('Y-m-d',$row["p_start"]);
                    $i_edate = DateTime::createFromFormat('Y-m-d',$row["p_end"]);
                    $p_json = $row["p_json"];
                    $user = $row["user_id"];
                    $comment = $row["p_comment"];
                    $spesen=$row["spesen"];
                }
            }

			$sdate = $i_sdate->format('d/m/Y');
			$edate = $i_edate->format('d/m/Y');
      $c_id=$p_company;

			if($p_json=='' || $p_json=='[]'){
				die('<font color="red"><b>Das Projekt ist noch leer, bitte Stunden hinzuf&uuml;gen</b></font>');
			}
			$dat = json_decode($p_json, true);
            $title= $i_sdate->format('ymd').'_'.$p_name;
            $title= str_replace(" ", "_", $title);

        }else{
			die ('ERROR, PLEASE SPECIFY PROJECT');
		}
 ?>

<html>
<head>
    <!--TODO glyphicons printable & Centered-->
	<meta content="text/html" http-equiv="Content-Type">
    <link media="screen" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="./css/view_style.css" rel="stylesheet" media="screen">
    <link href="./css/view_style.css" rel="stylesheet" media="print">
	<title><?= $title; ?></title>
</head>
<body>
<div id="wrapper">
<div class="no-print">
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="home.php">Abrechnungsgenerator</a>
      </div>            <!-- /.navbar-top-links -->
      <ul class="nav navbar-nav">
        <li class="active" id="navRapa"><a href="#" id="navRap">Rapport</a></li>
        <li id="navAbra"><a href="#" id="navAbr">Abrechnung</a></li>
        <li id="navExpa"><a href="#" id="navExp">Spesen</a></li>

      </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="fa fa-comments fa-fw"></i> <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-comments">
                <div id="chats"></div>

                    <textarea style="border: none" class="col-lg-2 form-control send-message" rows="1" placeholder="Antworten..." id="commentText"></textarea>
                    <a href="" class=" btn send-message-btn pull-right" role="button" id="submitComment"><i class="fa fa-plus"></i> Antworten</a>

              </ul>
              <!-- /.dropdown-messages -->
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-cog" aria-hidden="true"></i> <i class="fa fa-caret-down" aria-hidden="true"></i></a>
              <ul class="dropdown-menu dropdown-user">
                <li>
                  <a href="javascript:window.print();">
                    <i class="fa fa-print" aria-hidden="true"></i> Drucken
                  </a>
                </li>
                <li class="divider"></li>
                  <h6 class="dropdown-header">Download:</h6>
                <li>
                  <a href="https://filmstunden.ch/api/v01/view/download/<?= $p_id ?>?format=xlsx" target="_blank">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
                  </a>
                </li>
                <li>
                  <a href="https://filmstunden.ch/api/v01/view/download/<?= $p_id ?>?format=pdf" target="_blank">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                  </a>
                </li>
              </ul>
            </li>
        </ul>
      </div>
    </nav>
</div>
<div id="page-wrapper">
    <p></p>
    <div class="row">
        <div class="col-sm-1">
        </div>
    <div class="col-sm-10">
	<div id="loading">
    <i class="fa fa-spinner fa-spin" style="position:absolute;right:50%;margin-top:40px;"></i>
  </div>

<!--------------------------------------------------------------------------------------------------------------->
	<div id="rapport" style="display:none">
		<table border="0" cellpadding="0" cellspacing="0" class="f10">
			<col class="f10" >
			<col class="f10" >
			<col class="f10" >
			<col class="f10" width="30">
			<col class="f10" span="4" >
			<col class="f10" width="30">
      <col class="f10">
      <col class="f10" width="30">
      <col class="f10" span="4">
			<col class="f10" >
			<col class="f10" >
			<col class="f10" >
			<col class="f10" >
			<col class="f10" >
			<col class="f10" >
			<col class="f10" width="30">
			<col class="f10" span="2">

			<tr>
				<td class="f14" colspan="4" height="26" width="194">ARBEITSRAPPORT</td>
				<td class="xl1" colspan="2">Grundlohn:</td>
				<td class="xl70 bold" id="p_pay"><?= $p_pay;?></td>
				<td class="xl71">CHF</td>
				<td class="xl7" colspan="2">(9h / Tag)</td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Produktion:</td>
				<td class="f10"></td>
				<td class="f10"></td>
				<td class="td17 bold" colspan="9" id="p_name"><?= $p_name;?></td>
			</tr>

			<tr>
				<td class="blue" colspan="3" height="18" id="u_name"></td>
				<td class="xl1" colspan="3"></td>
				<td class="xl1" colspan="4">Abrechnung nach AAB SSFV 2007</td>
				<td class="xl1"></td>
				<td class="xl1" colspan="3">Datum [von/bis] :</td>
				<td class="f10"></td>
				<td class="td17" colspan="4" id="sdate"><?= $sdate?></td>
				<td class="xl71" colspan="2">bis</td>
				<td class="td17" colspan="3" id="edate"><?= $edate?></td>
			</tr>

			<tr>
				<td class="blue" colspan="3" height="18" id="u_address1"></td>
				<td class="xl1"></td>
				<td class="xl1">AHV-Nr.:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4" id="u_ahv"></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="3">Produktionsfirma:</td>
				<td class="f10"></td>
				<td class="blue" colspan="9" id="c_name"></td>
			</tr>

			<tr>
				<td class="blue" colspan="3" height="18" id="u_address2"></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Geb. Datum:</td>
				<td class="blue" colspan="4" id="u_dateob"></td>
				<td class="xl1"></td>
				<td class="f10" colspan="4"></td>
				<td class="blue" colspan="9" id="c_address1"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18" id="u_tel"></td>
				<td class="xl1"></td>
				<td class="xl1">Konto:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4" id="u_konto"></td>
				<td class="xl1"></td>
				<td class="f10" colspan="4"></td>
				<td class="blue" colspan="9" id="c_address2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18" id="u_mail"></td>
				<td class="xl1"></td>
				<td class="xl1">BV:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4" id="u_bvg"></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Arbeit als:</td>
				<td class="f10"></td>
				<td class="f10"></td>
				<td class="blue" colspan="9" id="p_job"><?= $p_job ?></td>
			</tr>
			<tr>
				<td height="9"></td>
			</tr>
			<tr>
				<td class="line" colspan="24" height="9"></td>
			</tr>
			<tr class="xl1">
				<td class="gray" colspan="2" height="20"> Datum</td>
				<td class="gray"> Was</td>
				<td></td>
				<td class="gray" colspan="4"> Arbeitszeit</td>
				<td></td>
				<td class="gray"> Grundlohn</td>
				<td></td>
				<td class="gray" colspan="10"> Zuschl&auml;ge (Nach AAB SSFV 2007)</td>
				<td></td>
				<td class="gray" colspan="2"> Spesen<font class="xl1"><sup>4</sup></font></td>
			</tr>
			<tr>
				<td class="xl1" height="19"></td>
				<td class="xl1"></td>
				<td class="xl1"></td>
				<td rowspan="4" align="centered">&#9656;</td>
				<td class="bluetop"></td>
				<td class="bluetop"></td>
				<td class="bluetop"></td>
				<td class="bluetop"></td>
				<td rowspan="4" align="centered">&#9656;</td>
				<td class="xl195">(bis 9h/Tag)<font class="f9"><sup>1</sup></font></td>
				<td  rowspan="4" align="centered">&#9656;</td>
				<td class="td972" colspan="8">&Uuml;berstunden<font class="f9"><sup>2</sup></font> <font class="f6h">(9h +)</font></td>
				<td class="td972">Nacht</td>
				<td class="td972"></td>
				<td rowspan="4" align="centered">&#9656;</td>
				<td class="xl10"></td>
				<td class="xl10"></td>
			</tr>
			<tr>
				<td height="12" colspan="3"></td>
				<td class="bluetop vbottom"></td>
				<td class="bluetop"></td>
				<td class="bluetop"></td>
				<td class="bluetop"></td>
				<td class="brightyellow"></td>
				<td class="f8orange" rowspan="3">10.te</td>
				<td class="f8orange" rowspan="3">11.te</td>
				<td class="f8orange" rowspan="3">12.te</td>
				<td class="f8orange" rowspan="3">13.te</td>
				<td class="f8orange" rowspan="3">14.te</td>
				<td class="f8orange" rowspan="3">15.te</td>
				<td class="f8orange" colspan="2" rowspan="3">ab<br>
				16.te</td>
				<td class="td128" colspan="2">23:00</td>
				<td class="td127"></td>
				<td class="td127"></td>
			</tr>
			<tr>
				<td class="xl1" colspan="2" height="12" ></td>
				<td class="xl1"></td>
				<td class="bluetop">Beginn</td>
				<td class="bluetop">Ende</td>
				<td class="bluetop">Pausen</td>
				<td class="bluetop">in h</td>
				<td class="brightyellow">Anz. Tage</td>
				<td class="td128" colspan="2">bis</td>
				<td class="td127">Essen</td>
				<td class="td127">Auto-Km</td>
			</tr>
			<tr>
				<td height="12" ></td>
				<td></td>
				<td class="td102"></td>
				<td class="bluetop" colspan="4"></td>
				<td class="brightyellow"></td>
				<td class="td128" colspan="2">05:00</td>
				<td class="td127"></td>
				<td class="td127"></td>
			</tr>
			<tr class="f10">
				<td class="xl1" height="2"></td>
			</tr>

<?
$allbase=$all125=$all150=$all200=$all250=$all25=$allfood=$allcar=$version=0;
$allhours1 = $allhours2 = new DateTime('2000-01-01 00:00:00');
			foreach($dat as $arr){
				if($arr['workhours']==0){
					break;
				}

        if(isset($arr['overtime'])){ //TODO: Versioning system
          $arr['tent']=$arr['overtime'][0];
          $arr['elev']=$arr['overtime'][1];
          $arr['twel']=$arr['overtime'][2];
          $arr['thir']=$arr['overtime'][3];
          $arr['four']=$arr['overtime'][4];
          $arr['fift']=$arr['overtime'][5];
          $arr['sixt']=$arr['overtime'][6];
        }

				list($hours, $minutes) = explode(':', $arr['workhours']);
				$allhours2->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'));
				$allbase+=$arr['base'];
				$all125= $all125 + $arr['tent'] + $arr['elev'];
				$all150= $all150 + $arr['twel'] + $arr['thir'];
				$all200= $all200 + $arr['four'] + $arr['fift'];
				$all250+=$arr['sixt'];
				$all25+=$arr['night'];
				$allfood+=$arr['lunch'];
				$allcar+=$arr['car'];
//---------------------------------------------------------------------------------
	?>
			<tr>
				<td class="td186 td187" colspan="2" height="30"><?
				$date = DateTime::createFromFormat('Y-m-d', $arr['date']);
				$date = $date->format('d/m/Y');
				echo $date;
				?></td>
				<td class="td186 td187"><?= $arr['work'];?></td>
                <td></td>
				<td class="td186"  ><?= $arr['start'];?></td>
				<td class="td186" ><?= $arr['end'];?></td>
				<td class="td186"  ><?= $arr['break'];?></td>
				<td class="td186" ><?= $arr['workhours'];?></td>
				<td></td>
				<td class="darkyellow bold"><?= $arr['base'];?></td>
				<td></td>
				<td class="brightorange" <? if ($arr['tent']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['tent']>0){echo $arr['tent'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" <? if ($arr['elev']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['elev']>0){echo $arr['elev'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" <? if ($arr['twel']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['twel']>0){echo $arr['twel'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" <? if ($arr['thir']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['thir']>0){echo $arr['thir'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" <? if ($arr['four']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['four']>0){echo $arr['four'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" <? if ($arr['fift']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['fift']>0){echo $arr['fift'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" colspan="2" <? if ($arr['sixt']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['sixt']>0){echo $arr['sixt'];}else{echo '&nbsp;';}?></td>
				<td class="brightorange" colspan="2 "<? if ($arr['night']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['night']>0){echo $arr['night'];}else{echo '&nbsp;';}?></td>
				<td></td>
				<?
				if ( $arr['lunch']>0) {
          echo '<td class="darkgreen">'.$arr["lunch"].'</td>';
        }else{
          echo '<td class="brightgreen">&nbsp;</td>';}

				if ( $arr['car']>0) {
          echo '<td class="darkgreen">'.$arr["car"].'</td>';
				}else{
          echo '<td class="brightgreen"></td>';}
				?>
			</tr>
			<tr class="f10">
				<td class="xl1" colspan="24" height="2"></td>
			</tr>
<?
//---------------------------------------------------------------------------------
}
?>
			<tr>
				<td class="f10" height="21"></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="xl1" colspan="3">Total Arbeitszeit in h:</td>
				<td class="xl124">
        <?
				$interval = $allhours1->diff($allhours2);
				$d=$interval->d;
				$h=$interval->h;
				$m= $interval->i;
				echo $d*24+$h.':'.$m;
        ?>
        </td>
				<td></td>
				<td class="brightyellow">Anz. Tage</td>
				<td></td>
				<td class="xl19" colspan="2">125%</td>
				<td class="xl19" colspan="2">150%</td>
				<td class="xl19" colspan="2">200%</td>
				<td class="xl19" colspan="2">250%</td>
				<td class="xl19" colspan="2">25%</td>
				<td></td>
				<td class="f10 vbottom"></td>
			</tr>
			<tr class="f10">
				<td class="xl1" height="2"></td>
			</tr>
			<tr class="xl1" >
				<td class="xl1" colspan="7" height="17" >Bemerkungen:</td>
                <td></td>
                <td></td>
				<td class="darkyellow"><?echo $allbase;?></td>
				<td class="xl1"></td>
				<td class="darkorange" colspan="2"><?= $all125 ?></td>
				<td class="darkorange" colspan="2"><?= $all150 ?></td>
				<td class="darkorange" colspan="2"><?= $all200 ?></td>
				<td class="darkorange" colspan="2"><?= $all250 ?></td>
				<td class="darkorange" colspan="2"><?= $all25 ?></td>
				<td class="xl1"></td>
				<td class="darkgreen"><?= $allfood ?></td>
				<td class="darkgreen"><?= $allcar ?></td>
			</tr>
			<tr class="f10">
				<td class="xl1" height="2"></td>
			</tr>
			<tr>
				<td class="f7 vbottom" colspan="7" height="17" >Berechung nach SSFV</td>
				<td></td>
				<td class="xl1"><sub>&agrave; CHF</sub></td>
				<td class="brightyellow"><?= $p_pay;?></td>
				<td class="f7"></td>
				<td class="td202" colspan="2"> <?=  round($p_pay/9*1.25, 2);?></td>
				<td class="td202" colspan="2"> <?=  round($p_pay/9*1.5, 2);?></td>
				<td class="td202" colspan="2"> <?=  round($p_pay/9*2.0, 2);?></td>
				<td class="td202" colspan="2"> <?=  round($p_pay/9*2.5, 2);?></td>
				<td class="td202" colspan="2"> <?=  round($p_pay/9*0.25, 2);?></td>
				<td></td>
				<td class="brightgreen">32.00</td>
				<td class="brightgreen">0.70</td>
			</tr>
			<tr>
				<td class="f7" colspan="7" rowspan="4" height="17" ><?= $comment; ?></td>
				<td></td>
				<td class="xl1"><sub>CHF</sub></td>
				<td class="brightyellow"><?=  round($allbase*$p_pay);?></td>
				<td class="td7"></td>
				<?
				$p125=round($all125*$p_pay/9*1.25, 2);
				$p150=round($all150*$p_pay/9*1.5, 2);
				$p200=round($all200*$p_pay/9*2.0, 2);
				$p250=round($all250*$p_pay/9*2.5, 2);
				$p25=round($all25*$p_pay/9*0.25, 2);
				?>
				<td class="td202" colspan="2"><?= $p125;?></td>
				<td class="td202" colspan="2"><?= $p150;?></td>
				<td class="td202" colspan="2"><?= $p200;?></td>
				<td class="td202" colspan="2"><?= $p250;?></td>
				<td class="td202" colspan="2"><?= $p25;?></td>
				<td></td>
				<td class="brightgreen"><?=  round($allfood*32, 2);?></td>
				<td class="brightgreen"><?=  round($allcar*0.7, 2);?></td>
			</tr>
			<tr>
			</tr>
			<tr>
				<td></td>
				<td class="xl1">Total</td>
				<td class="totals">Grundlohn</td>
				<td></td>
				<td class="totals" colspan="10">Zuschl&auml;ge</td>
				<td></td>
				<td class="totals" colspan="2">Spesen</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td align= "center">&#9662;</td>
				<td></td>
				<td align= "center" colspan="10">	&#9662;</td>
				<td></td>
				<td colspan="2" align= "center">	&#9662;</td>
			</tr>
			<tr>
				<td class="f7 vbottom" colspan="7" height="17" ></td>
				<td></td>
				<td class="xl14221306"><sub>CHF</sub></td>
				<td class="pay base"><?=  round($allbase*$p_pay);?></td>
				<td class="xl15"></td>
				<td class="pay overtime" colspan="10"><?=  round($p125+$p150+$p200+$p250+$p25,2);?></td>
				<td class="xl15"></td>
				<td class="pay additional" colspan="2"><?=  round(round($allfood*32, 2)+round($allcar*0.7, 2),2);?></td>
			</tr>
      <tr>
        <td class="f10" height="2" ></td>
      </tr>
      <tr>
        <td colspan="11"></td>
        <td colspan="10" class="xl1" align="right">Zusätzliche Spesen (siehe Spesenblatt):</td>
        <td></td>
        <td class="pay additional" colspan="2" id="addExp"><?= $spesen; ?></td>
      </tr>
			<tr>
				<td class="f10" height="10" ></td>
			</tr>
			<tr>
				<td class="f6" colspan="6">1 F&uuml;r Laden, Ausladen oder Vorbereitung bis 5 h pro Tag: Pauschal 0.6 Tag</td>
				<td class="xl11"></td>
				<td class="xl1"></td>
				<td class="f10"></td>
				<td class="xl16221306" colspan="5">p <font class="f6h">Grundlohn exkl. 8.33% Ferienzulage</font></td>
			</tr>
			<tr>
				<td class="f6" colspan="6">2 &Uuml;berstunden: Bei mehr als 9 h pro Tag auf der Basis von 1/9 Tag.</td>
				<td class="f6"></td>
			</tr>
			<tr>
				<td class="f6" colspan="6">3 Nachtstunden: Arbeitszeit zwischen 23 und 5 Uhr abz&uuml;glich Pausen.</td>
				<td class="xl11"></td>
				<td class="f10"></td>
				<td class="f10"></td>
				<td class="xl11" colspan="14">Bitte auf der Lohnabrechnung die entsprechenden Zulagen, Abz&uuml;ge und Spesen kalkulieren.</td>
				<td class="f10"></td>
			</tr>
			<tr>
				<td class="f6" colspan="6">4 Spesenregelung gem&auml;ss AAB SSFV 2007</td>
				<td class="f10" colspan="3"></td>
				<td class="xl11" colspan="13">Zahlbar innert 30 Tagen nach Erhalt. Betrag auf obenstehendes Konto &uuml;berweisen. Danke.</td>
				<td class="f10" colspan="2"></td>
			</tr>
		</table>
	</div>
  <!--------------------------------------------------------------------------------------------------------------->
  <div id="abrechnung" style="display:none">
    <table border="0" cellpadding="1px">
      <tbody>
        <tr>
          <td class="maintitle" colspan="2" rowspan="2" height="20">LOHNABRECHNUNG</td>
          <td class="spacer" style="width: 73px;">&nbsp;</td>
          <td class="spacer" style="width: 08px;">&nbsp;</td>
          <td class="spacer" style="width: 82px;">&nbsp;</td>
          <td class="spacer" style="width: 88px;">&nbsp;</td>
          <td class="spacer" style="width: 82px;">&nbsp;</td>
          <td class="spacer" style="width: 14px;">&nbsp;</td>
        </tr>
        <tr>
          <td class="fs8">&nbsp;</td>
          <td class="fs8" colspan="2">Datum:</td>
          <td class="fs8" colspan="3" id="ab_date">##DATE##</td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
          <td class="fs8" colspan="2">Rapport Nr.:</td>
          <td class="fs8" colspan="3" id="ab_rappnr">##RAPPNR##</td>
        </tr>
        <tr>
          <td class="fs8">Vorname/ Name:</td>
          <td class="fs8" id="ab_name">##NAME##</td>
          <td class="spacer" colspan="6">&nbsp;</td>
        </tr>
        <tr>
          <td class="fs8" >Adresse:</td>
          <td class="fs8" id="ab_addr1">#ADD1##4</td>
          <td class="spacer" ></td>
          <td class="fs8" colspan="2">Produktion:</td>
          <td class="BetwTitlesmall fs7" colspan="3" id="ab_proj">##PROJ##</td>
        </tr>
        <tr>
          <td class="fs8">&nbsp;</td>
          <td class="fs8" id="ab_addr2">##ADD2##</td>
          <td class="spacer" >&nbsp;</td>
          <td class="fs8" colspan="2">Dreh [von/bis]:</td>
          <td class="BetwTitlesmall fs7" id="ab_fromdate">##VON##</td>
          <td class="BetwTitlesmall fs7"  colspan="2" id="ab_todate">##BIS##</td>
        </tr>
        <tr>
          <td class="fs8" >E-Mail:</td>
          <td class="fs8" id="ab_mail">##EMAIL##</td>
          <td class="spacer" >&nbsp;</td>
          <td class="fs8" colspan="2">Arbeit als:</td>
          <td class="BetwTitlesmall fs7" colspan="3" id="ab_job">##JOB##</td>
        </tr>
        <tr>
          <td class="fs8">Telefon:</td>
          <td class="fs8" id="ab_tel">##TELNR##</td>
          <td class="spacer">&nbsp;</td>
          <td class="fs8" colspan="2">Grundlohn:</td>
          <td class="bryellow fs8" id="ab_base">##GRUNDLOHN##</td>
          <td class="darkyellow fs8 bold"  colspan="2">(9h / Tag)</td>
        </tr>

        <tr>
          <td class="fs8" >AHV-Nr.:</td>
          <td class="fs8" id="ab_ahv">##AHV##</td>
          <td class="spacer" colspan="3">&nbsp;</td>
          <td class="fs7" colspan="3">Abrechnung nach AAB SSFV 2007</td>
        </tr>
        <tr>
          <td class="fs8" >Geb. Datum:</td>
          <td class="fs8" style="width: 121px;" id="ab_dob">##DOB##</td>
          <td class="fs8" >&nbsp;</td>
          <td class="fs8" style="width: 90px;" colspan="2">Produktionsfirma:</td>
          <td class="BetwTitlesmall fs7" style="width: 184px;" colspan="3" id="ab_company">##PRODF##</td>
        </tr>
        <tr>
          <td class="fs8">Konto:</td>
          <td class="fs8" id="ab_konto">##KONTO##</td>
          <td class="spacer">&nbsp;</td>
          <td class="fs8" colspan="2" rowspan="2">Adresse:</td>
          <td class="BetwTitlesmall fs7" colspan="3" id="ab_caddr1">##ADD1##</td>
        </tr>
        <tr>
          <td class="fs8" >BVG:&nbsp;</td>
          <td class="fs8" id="ab_bvg">##BVG##</td>
          <td class="spacer" >&nbsp;</td>
          <td class="BetwTitlesmall fs7" colspan="3" id="ab_caddr2">##ADD2##</td>
        </tr>
        <tr>
          <td class="fs8" colspan="8">&nbsp;</td>
        </tr>
        <tr>
          <td class="titlebar fs7"  colspan="2">Beschreibung&nbsp;</td>
          <td class="titlebar fs7"  colspan="2">Anz.</td>
          <td class="titlebar fs7" >Einheit</td>
          <td class="titlebar fs7" >CHF/Stk.</td>
          <td class="titlebar fs7"  colspan="2">CHF</td>
        </tr>
        <tr>
          <td class="yelltitle fs10" colspan="2">Tagesgage / Grundlohn</td>
          <td class="fs7"  colspan="4"><span class="wingdings">q</span>&Uuml;bertrag aus Arbeitsrapport</td>
          <td class="fs8" colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td class="bryellow fs8">##DATE1##</td>
          <td class="bryellow fs8">Dreh</td>
          <td class="ab_darkyellow fs8">##ANZTAG##</td>
          <td class="fs8">&nbsp;</td>
          <td class="bryellow fs8">Tag</td>
          <td class="bryellow fs8">##GRUNDLOHNTAG##</td>
          <td class="bryellow fs8" colspan="2">#GRUNDLOHNTAG##</td>
        </tr>
        <tr>
          <td class="bryellow fs8" >##DTAE2</td>
          <td class="bryellow fs8" >Dreh</td>
          <td class="ab_darkyellow fs8" >##ANZTAG2##</td>
          <td class="fs8" >&nbsp;</td>
          <td class="bryellow fs8" >Tag</td>
          <td class="bryellow fs8" >##GRUNDLOHNTAG##</td>
          <td class="bryellow fs8" colspan="2">##GRUNDLOHNTAG##</td>
        </tr>
        <tr>
          <td class="bryellow fs8" colspan="2">Anzahl Tage x Grundlohn</td>
          <td class="darkyellow fs8">##ANZTAG##</td>
          <td class="fs8">&nbsp;</td>
          <td class="bryellow fs8">Tag</td>
          <td class="bryellow fs8">##GRUNDLOHNTAG##</td>
          <td class="bryellow fs8" colspan="2">##GRUNDLOHN##</td>
        </tr>
      </tr>
      <tr>
        <td class="fs8" colspan="8">&nbsp;</td>
      </tr>

      <tr>
        <td class="bryellow fs8" colspan="2">Ferienzulage</td>
        <td class="bryellow fs8" align="right">8.33%</td>
        <td class="fs8" >&nbsp;</td>
        <td class="bryellow fs8" >##GRUNDLOHN##</td>
        <td class="fs8" >&nbsp;</td>
        <td class="bryellow fs8"  colspan="2">##FERIENZULA##</td>
      </tr>
      <tr>
        <td class="overline fs7" colspan="6">Subtotal Grundlohn inkl. Ferienzulage</td>
        <td class="overline fs7" colspan="2">##GRUNDLOHNUNDFZ##</td>
      </tr>
      <tr>
        <td class="fs8" colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td class="orantitle fs10"  colspan="2">&Uuml;berstunden &amp; Zuschl&auml;ge</td>
        <td class="fs7"  colspan="6"><span class="wingdings">q</span> &Uuml;bertrag aus Arbeitsrapport</td>
      </tr>
      <tr>
        <td class="brorange fs8">10. und 11. Stunde</td>
        <td class="brorange fs8">100%</td>
        <td class="darorange fs8" align="right">##ANZ1011##</td>
        <td class="fs8">&nbsp;</td>
        <td class="brorange fs8">&agrave; CHF</td>
        <td class="brorange fs8">##RATE1011##</td>
        <td class="brorange fs8" colspan="2">##TOT1011##</td>
      </tr>
      <tr>
        <td class="brorange fs8">12. und 13. Stunde</td>
        <td class="brorange fs8">150%</td>
        <td class="darorange fs8" align="right">0</td>
        <td class="fs8">&nbsp;</td>
        <td class="brorange fs8">&agrave; CHF</td>
        <td class="brorange fs8">##RATE1213##</td>
        <td class="brorange fs8" colspan="2">##TOT1213##</td>
      </tr>
      <tr>
        <td class="brorange fs8">14. und 15. Stunde</td>
        <td class="brorange fs8">200%</td>
        <td class="darorange fs8" align="right">0</td>
        <td class="fs8">&nbsp;</td>
        <td class="brorange fs8">&agrave; CHF</td>
        <td class="brorange fs8">100.00</td>
        <td class="brorange fs8" colspan="2">-</td>
      </tr>
      <tr>
        <td class="brorange fs8">ab 16. Stunde</td>
        <td class="brorange fs8">250%</td>
        <td class="darorange fs8"align="right">0</td>
        <td class="fs8">&nbsp;</td>
        <td class="brorange fs8">&agrave; CHF</td>
        <td class="brorange fs8">125.00</td>
        <td class="brorange fs8" colspan="2">-</td>
      </tr>
      <tr>
        <td class="brorange fs8" colspan="2">Nachtstundenzuschlag &agrave; 25% (Nachtstunden 23:00-5:00)</td>
        <td class="darorange fs8" align="right">1</td>
        <td class="fs8">&nbsp;</td>
        <td class="brorange fs8">&agrave; CHF</td>
        <td class="brorange fs8">12.50</td>
        <td class="brorange fs8" colspan="2">10.25</td>
      </tr>
      <tr>
        <td class="overline fs7" colspan="6">&nbsp;Subtotal &Uuml;berstunden &amp; Zuschl&auml;ge</td>
        <td class="overline fs7" colspan="2">##SUBUEBER##</td>
      </tr>
      <tr>
        <td class="fs8" colspan="6">&nbsp;</td>
        <td class="fs8" colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td class="BetwTitle" colspan="6">Total Bruttolohn</td>
        <td class="BetwTitle" colspan="2">##TOTBRUT##</td>
      </tr>
      <tr>
        <td class="fs8" colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td class="bluetitle fs10" colspan="2" height="16">Abz&uuml;ge Sozialleistungen</td>
        <td class="fs7"  colspan="6">* Einige Abz&uuml;ge sind je nach Arbeitgeber unterschiedlich !</td>
      </tr>
      <tr>
        <td class="brblue fs8" >AHV / IV / EO</td>
        <td class="brblue fs8" >&nbsp;</td>
        <td class="brblue fs8"  align="right">6.05%</td>
        <td class="fs8"></td>
        <td class="brblue fs8" >1,107.75</td>
        <td class="fs8" ></td>
        <td class="brblue fs8" colspan="2">-67.00</td>
      </tr>
      <tr>
        <td class="brblue fs8" >ALV</td>
        <td class="brblue fs8" >( je nach Arbeitgeber * )</td>
        <td class="brblue fs8" align="right">1.10%</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brblue fs8" >&nbsp;</td>
        <td class="fs8">&nbsp;</td>
        <td class="brblue fs8" colspan="2">-</td>
      </tr>
      <tr>
        <td class="brblue fs8" >BVG-Pr&auml;mie</td>
        <td class="brblue fs8" >VFA&nbsp;</td>
        <td class="brblue fs8" >6.00%</td>
        <td class="fs8" ></td>
        <td class="brblue fs8" >1,107.75</td>
        <td class="fs8" ></td>
        <td class="brblue fs8" colspan="2">-66.50</td>
      </tr>
      <tr>
        <td class="brblue fs8">UVG / NBU</td>
        <td class="brblue fs8" >( je nach Arbeitgeber * )</td>
        <td class="brblue fs8"  align="right">1.62%</td>
        <td class="fs8">&nbsp;</td>
        <td class="brblue fs8" >&nbsp;</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brblue fs8"  colspan="2">-</td>
      </tr>
      <tr>
        <td class="fs8" colspan="8"></td>
      </tr>
      <tr>
        <td class="overline fs7" colspan="6">Subtotal Abz&uuml;ge</td>
        <td class="overline fs7" colspan="2">##SUBABZ##</td>
      </tr>
      <tr>
        <td class="fs8" colspan="8"></td>
      </tr>
      <tr>
        <td class="BetwTitle"  colspan="6">Total Nettolohn</td>
        <td class="BetwTitle"  colspan="2">##NET##</td>
      </tr>
      <tr>
        <td colspan="8"></td>
      </tr>
      <tr>
        <td class="fs8" colspan="8">&nbsp;</td>
      </tr>
      <tr>
        <td class="darblue fs10"  colspan="2">Familienzulage</td>
        <td class="brblue fs8"  align="right">0.00%</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brblue fs8" >-</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brblue fs8"  colspan="2">-</td>
      </tr>
      <tr>
        <td class="fs8"  colspan="8"></td>
      </tr>
      <tr>
        <td class="greentitle"  colspan="2" height="17">Spesen / Transportkosten</td>
        <td class="fs7" colspan="6"><span class="wingdings">q</span>&Uuml;bertrag aus Arbeitsrapport</td>
      </tr>
      <tr>
        <td class="spacer"  colspan="8" height="3"></td>
      </tr>
      <tr>
        <td class="brgreen fs8"  colspan="2">Verpflegung</td>
        <td class="dargreen fs8"  align="right">0</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brgreen fs8" >&agrave; CHF</td>
        <td class="brgreen fs8" >##FOODRATE##</td>
        <td class="brgreen fs8"  colspan="2">##FOODNR##</td>
      </tr>
      <tr>
        <td class="brgreen fs8"  colspan="2">Autokilometer</td>
        <td class="dargreen fs8"  align="right">0</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brgreen fs8" >&agrave; CHF</td>
        <td class="brgreen fs8" >##KILRATE##</td>
        <td class="brgreen fs8"  colspan="2">##KILNR##</td>
      </tr>
      <tr>
        <td class="brgreen fs8"  colspan="2">Weitere Spesen</td>
        <td class="dargreen fs8"  align="right">0</td>
        <td class="fs8" >&nbsp;</td>
        <td class="brgreen fs8" >&agrave; CHF</td>
        <td class="brgreen fs8" >0.70</td>
        <td class="brgreen fs8"  colspan="2">-</td>
      </tr>
      <tr class="spacer">
        <td class="fs8" colspan="8"></td>
      </tr>
      <tr>
        <td class="overline fs7" colspan="6">Subtotal Spesen</td>
        <td class="overline fs7" colspan="2">##SPES##</td>
      </tr>
      <tr>
        <td class="fs8"  colspan="8"></td>
      </tr>
      <tr>
        <td class="BetwTitle"  colspan="6">Total Betrag</td>
        <td class="BetwTitle"  colspan="2">##TOT##</td>
      </tr>
      <tr>
        <td  colspan="8"></td>
      </tr>
      <tr>
        <td class="fs8" colspan="3">Bitte den entsprechenden Betrag innert 30 Tagen auf folgendes Konto &uuml;berweisen:</td>
        <td class="spacer" ></td>
        <td class="fs8" colspan="4">##KONTO##</td>
      </tr>
    </tbody>
  </table>
</div><!--abrechnung-->
  <div id="spesen" style="display:none">
    <table border="0" cellpadding="0" cellspacing="0" class="f10" width="100%">
      <tr>
        <td class="f14" colspan="6" height="26" width="194">SPESENBLATT</td>
        <td class="xl1" >Produktion:</td>
        <td class="td17 bold" colspan="2"><?= $p_name;?></td>
      </tr>

      <tr>
        <td height="2"></td>
      </tr>

      <tr>
        <td class="xl1" colspan="6"></td>
        <td class="xl1" >Datum [von/bis] :</td>
        <td class="td17" colspan="2" ><?= $sdate?> bis <?= $edate?></td>
      </tr>

      <tr>
        <td height="15"></td>
      </tr>

      <tr class="xl1">
        <td class="gray" height="20" width="150px"> Datum</td>
        <td class="gray" > Name</td>
        <td class="gray" colspan="6"> Beschreibung</td>
        <td class="gray" width="150px" > Betrag</td>
      </tr>

      <tr class="xl1" id="expenseList">
        <td class="blue fs8">##EXPDATE##</td>
        <td class="blue fs8">##EXPNAME##</td>
        <td class="blue fs8" colspan="6">##EXPTEXT##</td>
        <td class="blue fs8 bold">##EXPVAL##</td>
      </tr>

      <tr class="xl1" id="expenseList">
        <td class="blue fs8" height="30" style="text-align: center; font-weight: 700" colspan="9">Keine zus&auml;tzlichen Spesen angegeben</td>
      </tr>

    </table>
  </div><!--spesen-->
</div>
</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="module" src="./js/view.js"></script>
</body>
</html>
