<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting( E_ALL | E_STRICT );

include './includes/inc_encrypt.php';
include './includes/inc_dbconnect.php';
include './includes/inc_variables.php';

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
            $sql = "SELECT user_id, p_name, p_company, p_job, p_gage, p_start, p_end, p_json, p_comment FROM `projects` WHERE project_id='$p_id';";
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
                }
            }

			$sql = "SELECT name, address_1, mail, address_2, ahv, dateob, konto, bvg, tel FROM `users` WHERE u_id='$user';";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$u_name = $row["name"];
					$u_address1 =  $row["address_1"];
					$u_address2 =  $row["address_2"];
					$u_ahv =  encrypt($row["ahv"],'d');
          //$u_ahv =  "Encrypted";
					$u_dob =   DateTime::createFromFormat('Y-m-d', $row["dateob"]);
					$u_konto = encrypt($row["konto"],'d');
          //$u_konto = "Encrypted";
					$u_bvg =  $row["bvg"];
					$u_mail = $row["mail"];
                    $u_tel = $row["tel"];
				}
			}

			$sdate = $i_sdate->format('d/m/Y');
			$edate = $i_edate->format('d/m/Y');
			$u_dob = $u_dob->format('d/m/Y');

			$sql = "SELECT name, address_1, address_2 FROM `companies` WHERE company_id='$p_company';";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$c_name = $row["name"];
					$c_address1 =  $row["address_1"];
					$c_address2 =  $row["address_2"];
				}
			}

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
        <li class="active"><a href="#">Rapport</a></li>
        <li><a href="#" onclick="alert('Noch nicht implementiert')">Abrechnung</a></li>
      </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="fa fa-comments fa-fw"></i> <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-comments">
                <ul id="comments"></ul>
                <li></br></li>
                <li>
                    <textarea style="border: none" class="col-lg-2 form-control send-message" rows="1" placeholder="Antworten..." id="commentText"></textarea>
                    <a href="" class=" btn send-message-btn pull-right" role="button" id="submitComment"><i class="fa fa-plus"></i> Antworten</a>
                </li>
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
                  <a href="h_download.php?t=xlsx&id=<?= $p_id ?>" target="_blank">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
                  </a>
                </li>
                <li>
                  <a href="h_download.php?t=pdf&id=<?= $p_id ?>" target="_blank">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF
                  </a>
                </li>
              </ul>
            </li>
        </ul>
      </div>
    </nav>
</div>
<div id="page-wrapper" >
    <p></p>
    <div class="row">
        <div class="col-sm-1">
        </div>
    <div class="col-sm-10">

	<div id="main">
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
				<td class="xl70 bold"><?= $p_pay;?></td>
				<td class="xl71">CHF</td>
				<td class="xl7" colspan="2">(9h / Tag)</td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Produktion:</td>
				<td class="f10"></td>
				<td class="f10"></td>
				<td class="td17 bold" colspan="9"><?= $p_name;?></td>
			</tr>
			<tr>
				<td height="2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18"><?= $u_name?></td>
				<td class="xl1" colspan="3"></td>
				<td class="xl1" colspan="4">Abrechnung nach AAB SSFV 2007</td>
				<td class="xl1"></td>
				<td class="xl1" colspan="3">Datum [von/bis] :</td>
				<td class="f10"></td>
				<td class="td17" colspan="4"><?= $sdate?></td>
				<td class="xl71" colspan="2">bis</td>
				<td class="td17" colspan="3"><?= $edate?></td>
			</tr>
			<tr>
				<td height="2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18"><?= $u_address1 ?></td>
				<td class="xl1"></td>
				<td class="xl1">AHV-Nr.:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4"><?= $u_ahv ?></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="3">Produktionsfirma:</td>
				<td class="f10"></td>
				<td class="blue" colspan="9"><?= $c_name ?></td>
			</tr>
			<tr>
				<td height="2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18"><?= $u_address2 ?></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Geb. Datum:</td>
				<td class="blue" colspan="4"><?= $u_dob ?></td>
				<td class="xl1"></td>
				<td class="f10" colspan="4"></td>
				<td class="blue" colspan="9"><?= $c_address1 ?></td>
			</tr>
			<tr>
				<td height="2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18"><?= $u_tel ?></td>
				<td class="xl1"></td>
				<td class="xl1">Konto:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4"><?= $u_konto ?></td>
				<td class="xl1"></td>
				<td class="f10" colspan="4"></td>
				<td class="blue" colspan="9"><?= $c_address2 ?></td>
			</tr>
			<tr>
				<td height="2"></td>
			</tr>
			<tr>
				<td class="blue" colspan="3" height="18"><?= $u_mail ?></td>
				<td class="xl1"></td>
				<td class="xl1">BV:</td>
				<td class="xl1"></td>
				<td class="blue" colspan="4"><?= $u_bvg ?></td>
				<td class="xl1"></td>
				<td class="xl1" colspan="2">Arbeit als:</td>
				<td class="f10"></td>
				<td class="f10"></td>
				<td class="blue" colspan="9"><?= $p_job ?></td>
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
$allbase=0;
$all125=0;
$all150=0;
$all200=0;
$all250=0;
$all25=0;
$allfood=0;
$allcar=0;
$allhours1 = new DateTime('2000-01-01 00:00:00');
$allhours2 = new DateTime('2000-01-01 00:00:00');
			foreach($dat as $arr){
				if($arr['workhours']==0){
					break;
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
// TODO Add NBSP when 0 (optically nicer)
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
				}else{?>style='background:#FFF2E5'><?} if ($arr['tent']>0){echo $arr['tent'];}?></td>
				<td class="brightorange" <? if ($arr['elev']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['elev']>0){echo $arr['elev'];}?></td>
				<td class="brightorange" <? if ($arr['twel']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['twel']>0){echo $arr['twel'];}?></td>
				<td class="brightorange" <? if ($arr['thir']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['thir']>0){echo $arr['thir'];}?></td>
				<td class="brightorange" <? if ($arr['four']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['four']>0){echo $arr['four'];}?></td>
				<td class="brightorange" <? if ($arr['fift']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['fift']>0){echo $arr['fift'];}?></td>
				<td class="brightorange" colspan="2" <? if ($arr['sixt']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['sixt']>0){echo $arr['sixt'];}?></td>
				<td class="brightorange" colspan="2 "<? if ($arr['night']>0) {echo '>';
				}else{?>style='background:#FFF2E5'><?} if ($arr['night']>0){echo $arr['night'];}?></td>
				<td></td>
				<?
				if ( $arr['lunch']>0) {
          echo '<td class="darkgreen">'.$arr["lunch"].'</td>';
        }else{
          echo '<td class="brightgreen"></td>';}

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
</div>
</div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script type="module" src="./js/view.js"></script>
<script>
const us_id = "guest"
const p_id = "<?echo $p_id;?>"
</script>

</body>
</html>
