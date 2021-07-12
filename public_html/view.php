<?
function ob_html_compress($buf){
	return preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'',$buf));
}
ob_start("ob_html_compress");
?>

<html>
<head>
	<!--TODO glyphicons printable & Centered-->
	<meta content="text/html" http-equiv="Content-Type">
	<link media="screen" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.css" />
	<link href="./css/view_style.css" rel="stylesheet" media="screen">
	<link href="./css/view_style.css" rel="stylesheet" media="print">
	<title>Loading...</title>
</head>

<body>
	<div id="wrapper">
		<div class="no-print">
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="container-fluid">
					<div class="navbar-header">
						<a class="navbar-brand" href="home.php">Abrechnungsgenerator</a>
					</div>
					<!-- /.navbar-top-links -->
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
										<a id="exceldownload" href="#" target="_blank">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
                  </a>
									</li>
									<li>
										<a id="pdfdownload" href="#" target="_blank">
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

					<div id="noProject" class="alert alert-danger" style="display:none">
						<strong>FEHLER!</strong> Die eingegebene Projekt-ID ist ung&uuml;ltig oder es existiert ein Fehler auf der Seite!
					</div>

					<div id="ES6" class="alert alert-danger" style="display:none">
						<strong>FEHLER!</strong> Der verwendete Browser unterst&uuml;tzt moderne Webtechnolgien nicht welche zur Anzeige verwendet werden. F&uuml;r die korrekte Anzeige der Seite bitte einen neuen Webbrowser verwenden.
					</div>
<!--------------------------------------------------------------------------------------------------------------->
					<div id="rapport" style="display:none">
						<table border="0" cellpadding="0" cellspacing="0" class="f10">
							<col class="f10">
							<col class="f10">
							<col class="f10" width="100">
							<col class="f10">
							<col class="f10" span="4">
							<col class="f10" width="30">
							<col class="f10">
							<col class="f10" width="10">
							<col class="f10" span="4">
							<col class="f10">
							<col class="f10">
							<col class="f10">
							<col class="f10">
							<col class="f10">
							<col class="f10">
							<col class="f10" width="10">
							<col class="f10" span="2">

							<tr>
								<td class="f14" colspan="4" height="26" width="194">ARBEITSRAPPORT</td>
								<td class="f8" colspan="2">Grundlohn:</td>
								<td class="xl70 bold pay gage"></td>
								<td class="f8 center">CHF</td>
								<td class="xl7 hoursperday" colspan="2"></td>
								<td></td>
								<td class="f8" colspan="2">Produktion:</td>
								<td></td>
								<td></td>
								<td class="blue center bold projectname" colspan="9"></td>
							</tr>

							<tr>
								<td class="blue username" colspan="3" height="18"></td>
								<td class="f8" colspan="3"></td>
								<td class="f8" colspan="4">Abrechnung nach <a href="https://www.ssfv.ch/?action=get_file&language=de&id=71&resource_link_id=18e">AAB SSFV 2014</a></td>
								<td></td>
								<td class="f8" colspan="3">Datum [von/bis] :</td>
								<td></td>
								<td class="blue center startdate" colspan="4" ></td>
								<td class="f8 center" colspan="2">bis</td>
								<td class="blue center enddate" colspan="3"></td>
							</tr>

							<tr>
								<td class="blue u_address1" colspan="3" height="18"></td>
								<td></td>
								<td class="f8">AHV-Nr.:</td>
								<td></td>
								<td class="blue ahv" colspan="4"></td>
								<td></td>
								<td class="f8" colspan="3">Produktionsfirma:</td>
								<td></td>
								<td class="blue company" colspan="9" ></td>
							</tr>

							<tr>
								<td class="blue u_address2" colspan="3" height="18"></td>
								<td></td>
								<td class="f8" colspan="2">Geb. Datum:</td>
								<td class="blue dob" colspan="4"></td>
								<td></td>
								<td class="f10" colspan="4"></td>
								<td class="blue c_addr1" colspan="9"></td>
							</tr>
							<tr>
								<td class="blue tel" colspan="3" height="18"></td>
								<td></td>
								<td class="f8">Konto:</td>
								<td></td>
								<td class="blue konto" colspan="4"></td>
								<td></td>
								<td class="f10" colspan="4"></td>
								<td class="blue c_addr2" colspan="9"></td>
							</tr>
							<tr>
								<td class="blue mail" colspan="3" height="18"></td>
								<td></td>
								<td class="f8">BVG:</td>
								<td></td>
								<td class="blue bvg" colspan="4"></td>
								<td></td>
								<td class="f8" colspan="2">Arbeit als:</td>
								<td></td>
								<td></td>
								<td class="blue job" colspan="9">#JOB</td>
							</tr>
							<tr>
								<td height="9"></td>
							</tr>
							<tr>
								<td class="line" colspan="24" height="9"></td>
							</tr>
							<tr class="f8">
								<td class="gray" colspan="2" height="20"> Datum</td>
								<td class="gray" colspan="2"> Was</td>
								<td class="gray" colspan="4"> Arbeitszeit</td>
								<td></td>
								<td class="gray"> Grundlohn</td>
								<td></td>
								<td class="gray" colspan="10"> Zuschl&auml;ge (Nach <a href="https://www.ssfv.ch/?action=get_file&language=de&id=71&resource_link_id=18e">AAB SSFV 2014</a>)</td>
								<td></td>
								<td class="gray" colspan="2"> Spesen
									<font class="f8"><sup>4</sup></font>
								</td>
							</tr>
							<tr>
								<td class="f8" height="19"></td>
								<td></td>
								<td></td>
								<td rowspan="4" align="centered"></td>
								<td class="bluetop"></td>
								<td class="bluetop"></td>
								<td class="bluetop"></td>
								<td class="bluetop"></td>
								<td rowspan="4" align="centered">&#9656;</td>
								<td class="xl195 tohoursperday">(x/hours/day)</td>
								<td rowspan="4" align="centered">&#9656;</td>
								<td class="xl19 fromhoursperday" colspan="8">(x+hours/day)</td>
								<td class="xl19">Nacht</td>
								<td></td>
								<td rowspan="4" align="centered">&#9656;</td>
								<td class="brightgreen"></td>
								<td class="brightgreen"></td>
							</tr>
							<tr>
								<td height="12" colspan="3"></td>

								<td class="bluetop vbottom"></td>
								<td class="bluetop"></td>
								<td class="bluetop"></td>
								<td class="bluetop"></td>
								<td class="brightyellow"></td>
								<td class="f8orange" rowspan="3" id="1Over"></td>
								<td class="f8orange" rowspan="3" id="2Over"></td>
								<td class="f8orange" rowspan="3" id="3Over"></td>
								<td class="f8orange" rowspan="3" id="4Over"></td>
								<td class="f8orange" rowspan="3" id="5Over"></td>
								<td class="f8orange" rowspan="3" id="6Over"></td>
								<td class="f8orange" colspan="2" rowspan="3" id="7Over"></td>
								<td class="f8orange" colspan="2">23:00</td>
								<td class="brightgreen"></td>
								<td class="brightgreen"></td>
							</tr>
							<tr>
								<td class="f8" colspan="2" height="12"></td>
								<td class="f8"></td>
								<td class="bluetop">Beginn</td>
								<td class="bluetop">Ende</td>
								<td class="bluetop">Pausen</td>
								<td class="bluetop">in h</td>
								<td class="brightyellow">Anz. Tage</td>
								<td class="f8orange" colspan="2">bis</td>
								<td class="td127">Essen</td>
								<td class="td127">Auto-Km</td>
							</tr>
							<tr>
								<td height="12"></td>
								<td></td>
								<td></td>
								<td class="bluetop" colspan="4"></td>
								<td class="brightyellow"></td>
								<td class="f8orange" colspan="2">05:00</td>
								<td class="td127"></td>
								<td class="td127"></td>
							</tr>
							<tr class="f10" id="fromhere">
								<td class="f8" height="2"></td>
							</tr>
							<tr class="f10">
								<td class="f8" colspan="24" height="2"></td>
							</tr>

							<tr>
								<td class="f10" height="21"></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="f8" colspan="3">Total Arbeitszeit in h:</td>
								<td id="totalWorkHours" class="blue"></td>
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
								<td class="f8" height="2"></td>
							</tr>
							<tr class="f8">
								<td class="f8" colspan="7" height="17">Bemerkungen:</td>
								<td></td>
								<td></td>
								<td id="nrOfDays" class="darkyellow" style="font-weight:Bold;"></td>
								<td></td>
								<td id="overtime1" class="darkorange" colspan="2"></td>
								<td id="overtime2" class="darkorange" colspan="2"></td>
								<td id="overtime3" class="darkorange" colspan="2"></td>
								<td id="overtime4" class="darkorange" colspan="2"></td>
								<td id="nighttime" class="darkorange" colspan="2"></td>
								<td></td>
								<td class="darkgreen alllunches"></td>
								<td class="darkgreen allcar"></td>
							</tr>
							<tr>
								<td height="2"></td>
							</tr>
							<tr>
								<td class="f7 vbottom" colspan="7" height="17">Berechung nach <a href="https://www.ssfv.ch/?action=get_file&language=de&id=71&resource_link_id=18e">AAB SSFV 2014</a></td>
								<td></td>
								<td class="f8"><sub>&agrave; CHF</sub></td>
								<td class="	gage brightyellow"></td>
								<td></td>
								<td id="rate125" class="td202" colspan="2"></td>
								<td id="rate150" class="td202" colspan="2"></td>
								<td id="rate200" class="td202" colspan="2"></td>
								<td id="rate250" class="td202" colspan="2"></td>
								<td id="rate25" class="td202" colspan="2"></td>
								<td></td>
								<td class="brightgreen foodrate"></td>
								<td class="brightgreen kilrate"></td>
							</tr>
							<tr>
								<td class="f7 vbottom" id="comments" colspan="7" rowspan="4" height="17"></td>
								<td></td>
								<td></td>
								<td id="payBase" class="brightyellow"></td>
								<td class="td7"></td>
								<td id="pay125" class="td202" colspan="2"></td>
								<td id="pay150" class="td202" colspan="2"></td>
								<td id="pay200" class="td202" colspan="2"></td>
								<td id="pay250" class="td202" colspan="2"></td>
								<td id="pay25" class="td202" colspan="2"></td>
								<td></td>
								<td class="brightgreen"></td>
								<td class="brightgreen"></td>
							</tr>
							<tr>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td class="totals">Grundlohn</td>
								<td></td>
								<td class="totals" colspan="10">Zuschl&auml;ge</td>
								<td></td>
								<td class="totals" colspan="2">Spesen</td>
							</tr>
							<tr>
								<td></td>
								<td class="f8">Total</td>
								<td align="center">&#9662;</td>
								<td ></td>
								<td align="center" colspan="10"> &#9662;</td>
								<td></td>
								<td colspan="2" align	="center"> &#9662;</td>
							</tr>
							<tr>
								<td class="f7 vbottom" colspan="7" height="17"></td>
								<td></td>
								<td class="f8">CHF</td>
								<td class="base pay" id="totalBase"></td>
								<td></td>
								<td class="overtime pay" id="totalOvertime" colspan="10"></td>
								<td></td>
								<td class="additional pay" id="totalAdditional" colspan="2"></td>
							</tr>
							<tr>
								<td height="2"></td>
							</tr>
							<tr>
								<td colspan="11"></td>
								<td colspan="10" class="f8" align="right">Zusätzliche Spesen (siehe Spesenblatt):</td>
								<td></td>
								<td class="additional pay additionalExpense" colspan="2"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td class="f6" colspan="8">1 F&uuml;r Laden, Ausladen oder Vorbereitung bis 5 h pro Tag: Pauschal 0.6 Tag</td>
								<td></td>
								<td class="xl16221306" colspan="5">p
									<font class="f6 helv">Grundlohn exkl. 8.33% Ferienzulage</font>
								</td>
							</tr>
							<tr>
								<td class="f6" colspan="6" id="otText">2 &Uuml;berstunden: Bei mehr als 9 h pro Tag auf der Basis von 1/9 Tag.</td>
								<td></td>
							</tr>
							<tr>
								<td class="f6" colspan="6">3 Nachtstunden: Arbeitszeit zwischen 23 und 5 Uhr abz&uuml;glich Pausen.</td>
								<td></td>
								<td></td>
								<td></td>
								<td class="f9 vbottom" colspan="15">Bitte auf der Lohnabrechnung die entsprechenden Zulagen, Abz&uuml;ge und Spesen kalkulieren.</td>
							</tr>
							<tr>
								<td class="f6" colspan="6">4 Spesenregelung gem&auml;ss <a href="https://www.ssfv.ch/?action=get_file&language=de&id=71&resource_link_id=18e">AAB SSFV 2014</a></td>
								<td class="f10" colspan="3"></td>
								<td class="f9 vbottom" colspan="15">Zahlbar innert 30 Tagen nach Erhalt. Betrag auf obenstehendes Konto &uuml;berweisen. Danke.</td>
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
									<td class="f8">&nbsp;</td>
									<td class="f8" colspan="2">Datum:</td>
									<td class="f8 enddate" colspan="3"></td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
									<td class="f8" colspan="2">Rapport Nr.:</td>
									<td class="f8" colspan="3" id="ab_rappnr">##RAPPNR##</td>
								</tr>
								<tr>
									<td class="f8">Vorname/ Name:</td>
									<td class="f8 username"></td>
									<td class="spacer" colspan="6">&nbsp;</td>
								</tr>
								<tr>
									<td class="f8">Adresse:</td>
									<td class="f8 u_address1"></td>
									<td class="spacer"></td>
									<td class="f8" colspan="2">Produktion:</td>
									<td class="BetwTitlesmall fs7 projectname" colspan="3"></td>
								</tr>
								<tr>
									<td class="f8">&nbsp;</td>
									<td class="f8 u_address2"></td>
									<td class="spacer">&nbsp;</td>
									<td class="f8" colspan="2">Dreh [von/bis]:</td>
									<td class="BetwTitlesmall fs7 startdate"></td>
									<td class="BetwTitlesmall fs7 enddate" colspan="2" ></td>
								</tr>
								<tr>
									<td class="f8">E-Mail:</td>
									<td class="f8 mail"></td>
									<td class="spacer">&nbsp;</td>
									<td class="f8" colspan="2">Arbeit als:</td>
									<td class="BetwTitlesmall fs7 job" colspan="3"></td>
								</tr>
								<tr>
									<td class="f8">Telefon:</td>
									<td class="f8 tel"></td>
									<td class="spacer">&nbsp;</td>
									<td class="f8" colspan="2">Grundlohn:</td>
									<td class="bryellow f8 pay gage"></td>
									<td class="darkyellow f8 bold hoursperday" colspan="2"></td>
								</tr>

								<tr>
									<td class="f8">AHV-Nr.:</td>
									<td class="f8 ahv"></td>
									<td class="spacer" colspan="3">&nbsp;</td>
									<td class="fs7" colspan="3">Abrechnung nach <a href="https://www.ssfv.ch/?action=get_file&language=de&id=71&resource_link_id=18e">AAB SSFV 2014</a></td>
								</tr>
								<tr>
									<td class="f8">Geb. Datum:</td>
									<td class="f8 dob" style="width: 121px;" ></td>
									<td class="f8">&nbsp;</td>
									<td class="f8" style="width: 90px;" colspan="2">Produktionsfirma:</td>
									<td class="BetwTitlesmall fs7 company" style="width: 184px;" colspan="3"></td>
								</tr>
								<tr>
									<td class="f8">Konto:</td>
									<td class="f8 konto"></td>
									<td class="spacer">&nbsp;</td>
									<td class="f8" colspan="2" rowspan="2">Adresse:</td>
									<td class="BetwTitlesmall fs7 c_addr1" colspan="3"></td>
								</tr>
								<tr>
									<td class="f8">BVG:&nbsp;</td>
									<td class="f8 bvg"></td>
									<td class="spacer">&nbsp;</td>
									<td class="BetwTitlesmall fs7 c_addr2" colspan="3"></td>
								</tr>
								<tr>
									<td class="f8" colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<td class="titlebar fs7" colspan="2">Beschreibung&nbsp;</td>
									<td class="titlebar fs7" colspan="2">Anzahl</td>
									<td class="titlebar fs7">Einheit</td>
									<td class="titlebar fs7">CHF/Stk.</td>
									<td class="titlebar fs7" colspan="2">CHF</td>
								</tr>
								<tr id="abr_baselist">
									<td class="yelltitle fs10" colspan="2">Tagesgage / Grundlohn</td>
									<td class="fs7" colspan="4"><span class="wingdings">q</span>&Uuml;bertrag aus Arbeitsrapport</td>
									<td class="f8" colspan="2">&nbsp;</td>
								</tr>
								<tr>
									<td class="bryellow f8" colspan="2">Anzahl Tage x Grundlohn</td>
									<td id="totalDays" class="ab_darkyellow f8"></td>
									<td class="f8">&nbsp;</td>
									<td class="bryellow f8">Tag</td>
									<td class="bryellow f8 gage"></td>
									<td class="bryellow f8 totalBase" colspan="2"></td>
								</tr>
								</tr>
								<tr>
									<td class="f8" colspan="8">&nbsp;</td>
								</tr>

								<tr>
									<td class="bryellow f8" colspan="2">Ferienzulage</td>
									<td class="bryellow f8" align="right">8.33%</td>
									<td class="f8">&nbsp;</td>
									<td class="bryellow f8 totalBase"></td>
									<td class="f8">&nbsp;</td>
									<td id="ferienzulage"class="bryellow f8" colspan="2"></td>
								</tr>
								<tr>
									<td class="overline fs7" colspan="6">Subtotal Grundlohn inkl. Ferienzulage</td>
									<td id="lohnundfz" class="overline fs7" colspan="2"></td>
								</tr>
								<tr>
									<td class="f8" colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<td class="orantitle fs10" colspan="2">&Uuml;berstunden &amp; Zuschl&auml;ge</td>
									<td class="fs7" colspan="6"><span class="wingdings">q</span> &Uuml;bertrag aus Arbeitsrapport</td>
								</tr>
								<tr>
									<td class="brorange f8">10. und 11. Stunde</td>
									<td class="brorange f8">125%</td>
									<td id="abr_ot0" class="darorange f8" align="right"></td>
									<td class="f8">&nbsp;</td>
									<td class="brorange f8">&agrave; CHF</td>
									<td id="rate0" class="brorange f8"></td>
									<td id="ot0" class="brorange f8" colspan="2"></td>
								</tr>
								<tr>
									<td class="brorange f8">12. und 13. Stunde</td>
									<td class="brorange f8">150%</td>
									<td id="abr_ot1" class="darorange f8" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brorange f8">&agrave; CHF</td>
									<td id="rate1" class="brorange f8"></td>
									<td id="ot1" class="brorange f8" colspan="2">##TOT1213##</td>
								</tr>
								<tr>
									<td class="brorange f8">14. und 15. Stunde</td>
									<td class="brorange f8">200%</td>
									<td id="abr_ot2" class="darorange f8" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brorange f8">&agrave; CHF</td>
									<td id="rate2" class="brorange f8"></td>
									<td id="ot2" class="brorange f8" colspan="2">-</td>
								</tr>
								<tr>
									<td class="brorange f8">ab 16. Stunde</td>
									<td class="brorange f8">250%</td>
									<td id="abr_ot3" class="darorange f8" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brorange f8">&agrave; CHF</td>
									<td id="rate3" class="brorange f8"></td>
									<td id="ot3" class="brorange f8" colspan="2">-</td>
								</tr>
								<tr>
									<td class="brorange f8" colspan="2">Nachtstundenzuschlag &agrave; 25% (Nachtstunden 23:00-5:00)</td>
									<td id="abr_nt"class="darorange f8" align="right">1</td>
									<td class="f8">&nbsp;</td>
									<td class="brorange f8">&agrave; CHF</td>
									<td id="rate4" class="brorange f8"></td>
									<td id="ot4" class="brorange f8" colspan="2">10.25</td>
								</tr>
								<tr>
									<td class="overline fs7" colspan="6">&nbsp;Subtotal &Uuml;berstunden &amp; Zuschl&auml;ge</td>
									<td id="totalUeberstunden"class="overline fs7" colspan="2"></td>
								</tr>
								<tr>
									<td class="f8" colspan="6">&nbsp;</td>
									<td class="f8" colspan="2">&nbsp;</td>
								</tr>

								<tr>
									<td class="BetwTitle" colspan="6">Total Bruttolohn</td>
									<td class="BetwTitle totalBrutto" colspan="2">##TOTBRUT##</td>
								</tr>
								<tr>
									<td class="f8" colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<td class="bluetitle fs10" colspan="2" height="16">Abz&uuml;ge Sozialleistungen</td>
									<td class="fs7" colspan="6">* Einige Abz&uuml;ge sind je nach Arbeitgeber unterschiedlich !</td>
								</tr>
								<tr>
									<td class="brblue f8">AHV / IV / EO</td>
									<td class="brblue f8">&nbsp;</td>
									<td class="brblue f8" align="right"><input type="number" id="percAHV" value="6.05" step="0.01" disabled>%</td>
									<td class="f8"></td>
									<td class="brblue f8 totalBrutto"></td>
									<td class="f8"></td>
									<td id="abzAhv" class="brblue f8" colspan="2"></td>
								</tr>
								<tr>
									<td class="brblue f8">ALV</td>
									<td class="brblue f8">( je nach Arbeitgeber * )</td>
									<td class="brblue f8" align="right"><input type="number" id="percALV" value="1.10" step="0.01">%</td><!--1.1-->
									<td class="f8">&nbsp;</td>
									<td class="brblue f8 totalBrutto"></td>
									<td class="f8">&nbsp;</td>
									<td id="abzAlv" class="brblue f8" colspan="2"></td>

								</tr>
								<tr>
									<td class="brblue f8">BVG-Pr&auml;mie</td>
									<td class="brblue f8">VFA&nbsp;</td>
									<td class="brblue f8" align="right"><input type="number" id="percBVG" value="6.00" step="0.01" disabled>%</td>
									<td class="f8"></td>
									<td class="brblue f8 totalBrutto"></td>
									<td class="f8"></td>
									<td id="abzBvg" class="brblue f8" colspan="2"></td>
								</tr>
								<tr>
									<td class="brblue f8">UVG / NBU</td>
									<td class="brblue f8">( je nach Arbeitgeber * )</td>
									<td class="brblue f8" align="right"><input type="number" id="percNBU" value="1.62" step="0.01">%</td>
									<td class="f8">&nbsp;</td>
									<td class="brblue f8 totalBrutto"></td>
									<td class="f8">&nbsp;</td>
									<td id="abzUvg" class="brblue f8" colspan="2">-</td>
								</tr>
								<tr>
									<td class="f8" colspan="8"></td>
								</tr>
								<tr>
									<td class="overline fs7" colspan="6">Subtotal Abz&uuml;ge</td>
									<td id="totalAbz" class="overline fs7" colspan="2"></td>
								</tr>
								<tr>
									<td class="f8" colspan="8"></td>
								</tr>
								<tr>
									<td class="BetwTitle" colspan="6">Total Nettolohn</td>
									<td id="totalNetto" class="BetwTitle" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="8"></td>
								</tr>
								<tr>
									<td class="f8" colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<td class="darblue fs10" colspan="2">Familienzulage</td>
									<td class="brblue f8" align="right">0.00%</td>
									<td class="f8">&nbsp;</td>
									<td class="brblue f8">-</td>
									<td class="f8">&nbsp;</td>
									<td class="brblue f8" colspan="2">-</td>
								</tr>
								<tr>
									<td class="f8" colspan="8"></td>
								</tr>
								<tr>
									<td class="greentitle" colspan="2" height="17">Spesen / Transportkosten</td>
									<td class="fs7" colspan="6"><span class="wingdings">q</span>&Uuml;bertrag aus Arbeitsrapport</td>
								</tr>
								<tr>
									<td class="spacer" colspan="8" height="3"></td>
								</tr>
								<tr>
									<td class="brgreen f8" colspan="2">Verpflegung</td>
									<td class="dargreen f8" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brgreen f8">&agrave; CHF</td>
									<td class="brgreen f8 foodrate"></td>
									<td class="brgreen f8 alllunches" colspan="2"></td>
								</tr>
								<tr>
									<td class="brgreen f8" colspan="2">Autokilometer</td>
									<td class="dargreen f8" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brgreen f8">&agrave; CHF</td>
									<td class="brgreen f8 kilrate"></td>
									<td class="brgreen f8 allcar" colspan="2"></td>
								</tr>
								<tr>
									<td class="brgreen f8" colspan="2">Weitere Spesen</td>
									<td class="dargreen f8 additionalExpense" align="right">0</td>
									<td class="f8">&nbsp;</td>
									<td class="brgreen f8"></td>
									<td class="brgreen f8"></td>
									<td id="additionalExpense" class="brgreen f8 additionalExpense" colspan="2">-</td>
								</tr>
								<tr class="spacer">
									<td class="f8" colspan="8"></td>
								</tr>
								<tr>
									<td class="overline fs7" colspan="6">Subtotal Spesen</td>
									<td id="totalSpesen" class="overline fs7" colspan="2"></td>
								</tr>
								<tr>
									<td class="f8" colspan="8"></td>
								</tr>
								<tr>
									<td class="BetwTitle" colspan="6">Total Betrag</td>
									<td id="total" class="BetwTitle" colspan="2"></td>
								</tr>
								<tr>
									<td colspan="8"></td>
								</tr>
								<tr>
									<td class="f8" colspan="3">Bitte den entsprechenden Betrag innert 30 Tagen auf folgendes Konto &uuml;berweisen:</td>
									<td class="spacer"></td>
									<td class="f8 konto" colspan="4"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!--abrechnung-->
					<div id="spesen" style="display:none">
						<table border="0" cellpadding="0" cellspacing="0" class="f10" width="100%">
							<tr>
								<td class="f14" colspan="6" height="26" width="194">SPESENBLATT</td>
								<td class="f8">Produktion:</td>
								<td class="blue bold projectname" colspan="2"></td>
							</tr>

							<tr>
								<td height="2"></td>
							</tr>

							<tr>
								<td class="f8" colspan="6"></td>
								<td class="f8">Datum [von/bis] :</td>
								<td class="blue" colspan="2" id="dateFromTo"> bis </td>
							</tr>

							<tr>
								<td height="15"></td>
							</tr>

							<tr class="f8">
								<td class="gray" height="20" width="150px"> Datum</td>
								<td class="gray"> Name</td>
								<td class="gray" colspan="6"> Beschreibung</td>
								<td class="gray" width="150px"> Betrag</td>
							</tr>

							<tr class="f8" id="expenseList">
							</tr>

						</table>
					</div>
					<!--spesen-->
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.7.13/featherlight.min.js"></script>
	<script>
	/* FIXME: UNSAFE INLINE CSP!
	var supportsES6 = function() {
	  try {
	    new Function("(a = 0) => a");
	    return true;
	  }
	  catch (err) {
	    return false;
	  }
	}();
	if(!supportsES6){
	  $( "#ES6" ).show();
		$('#loading').hide()
	}
	*/
	</script>
	<script type="module" src="./js/view.min.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
