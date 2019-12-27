<?
use \Medoo\Medoo;

class View
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->enc = $container->get('encrypt');
    }

    private function get($p_id){
      $in = $this->db->select('projects', [
        "[>]companies(comp)" => ["p_company" => "company_id"],
        "[>]users(usr)" => ["user_id" => "u_id"],
      ], [
        "projectData" => [
            'projects.p_start',
            'projects.p_end',
            'projects.p_name',
            'projects.p_job',
            'projects.p_gage[Int]',
            'projects.p_json[JSON]',
            'projects.p_comment',
            'projects.settings[JSON]'
          ],
        "companyData" => [
            'comp.name(c_name)',
            'comp.url(url)',
            'comp.address_1(c_address_1)',
            'comp.address_2(c_address_2)'
          ],
        "userData" => [
            'usr.name',
            'usr.tel',
            'usr.address_1',
            'usr.address_2',
            'usr.ahv',
            'usr.dateob',
            'usr.konto',
            'usr.bvg',
            'usr.type',
            'usr.affiliation',
            'usr.mail'
          ]
      ], [
        'project_id' => $p_id
      ]);

      $inJson = $in[0]["projectData"]["p_json"];
      if (isset($inJson[0]["tent"])){
        $in[0]["projectData"]["p_json"]=self::convertOldOvertime($inJson);
      }

      $ahv = $this->enc->encrypt($in[0]["userData"]["ahv"], 'd');
      $kont = $this->enc->encrypt($in[0]["userData"]["konto"], 'd');
      $in[0]["userData"]["ahv"] = $ahv;
      $in[0]["userData"]["konto"] = $kont;

      $exp = $this->db->select('expenses', [
        'date',
        'name',
        'value',
        'comment',
        'img',
      ], [
        "project" => $p_id
      ]);
      $expArray =[];
      foreach ($exp as $cur) {
        array_push($expArray , $cur);
      }

      $in[0]["projectData"]["expenses"] = $expArray;

      return $in;
    }

    private function convertOldOvertime($inJson){
      $out=[];
      foreach ($inJson as $cur) {
        $overtime=[$cur["tent"],$cur["elev"],$cur["twel"],$cur["thir"],$cur["four"],$cur["fift"],$cur["sixt"]];
        unset($cur['tent']);
        unset($cur['elev']);
        unset($cur['twel']);
        unset($cur['thir']);
        unset($cur['four']);
        unset($cur['fift']);
        unset($cur['sixt']);
        $cur['overtime']=$overtime;
        array_push($out,$cur);
      }
      return $out;
    }

    public function show($request,$response,$args){
      $p_id=$args['p_id'];
      $in = self::get($p_id);
      return $response->withJson($in);
    }

    public function download($request,$response,$args){
      $type=$request->getQueryParam('format');
      $p_id=$args['p_id'];
      $in = self::get($p_id);

      $dat=$in[0]["projectData"]["p_json"];
      $pay=$in[0]["projectData"]["p_gage"];
      $exp= $in[0]["projectData"]["expenses"];
      $exp= array_sum(array_column($exp, 'value')); // Sum up all expenses
      //die(var_dump($exp));

      $sdate = DateTime::createFromFormat('Y-m-d',$in[0]["projectData"]["p_start"]);
      $edate = DateTime::createFromFormat('Y-m-d',$in[0]["projectData"]["p_end"]);
      $u_dob = DateTime::createFromFormat('Y-m-d',$in[0]["userData"]["dateob"]);

      $title= $sdate->format('ymd').'_'.$in[0]["projectData"]["p_name"].'_'.$in[0]["userData"]["name"];
      $title= str_replace(" ", "_", $title);

      $sdate = $sdate->format('d/m/Y');
      $edate = $edate->format('d/m/Y');
      $u_dob = $u_dob->format('d/m/Y');

      $comment = $in[0]["projectData"]["p_comment"];

      date_default_timezone_set('Europe/Berlin');
      /** PHPExcel_IOFactory */
      require_once '../../vendor/PHPExcel/IOFactory.php';
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load("template_rapport.xlsx");

      $objPHPExcel->getDefaultStyle()->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getDefaultStyle()->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getDefaultStyle()->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $objPHPExcel->getDefaultStyle()->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $objPHPExcel->setActiveSheetIndexByName('Rapport');
      $oWs = $objPHPExcel->getActiveSheet();

      $oWs->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
      $oWs->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

      $oWs->getPageMargins()->setTop(0.12);
      $oWs->getPageMargins()->setRight(0.12);
      $oWs->getPageMargins()->setLeft(0.12);
      $oWs->getPageMargins()->setBottom(0.12);
      $oWs->getPageSetup()->setFitToWidth(1);

    /*COLORS-------*/

      $darkOrange =     array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'FCD5B4')));
      $brightOrange =   array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'FFF2E5')));
      $darkGreen  =     array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'E5FFE5')));
      $brightGreen  =   array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'F2FFF2')));
      $darkBlue  =      array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'CCE5FF')));
      $brightBlue  =    array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'E5F2FF')));
      $darkYellow  =    array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'FFFFB2')));
      $brightYellow  =  array('fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'color' => array('rgb' => 'FFFFE5')));

      $oWs->setCellValue('G2', $pay);
      $oWs->setCellValue('A3', $in[0]["userData"]["name"]);
      $oWs->setCellValue('A4', $in[0]["userData"]["address_1"]);
      $oWs->setCellValue('A5', $in[0]["userData"]["address_2"]);
      $oWs->setCellValue('A6', $in[0]["userData"]["tel"]);
      $oWs->setCellValue('A7', $in[0]["userData"]["mail"]);
      $oWs->setCellValue('G4', $in[0]["userData"]["ahv"]);
      $oWs->setCellValue('G5', $u_dob);
      $oWs->setCellValue('G6', $in[0]["userData"]["konto"]);
      $oWs->setCellValue('G7', $in[0]["userData"]["bvg"]);
      $oWs->setCellValue('P2', $in[0]["projectData"]["p_name"]);
      $oWs->setCellValue('P3', $sdate);
      $oWs->setCellValue('V3', $edate);
      $oWs->setCellValue('P4', $in[0]["companyData"]["c_name"]);
      $oWs->setCellValue('P5', $in[0]["companyData"]["c_address_1"]);
      $oWs->setCellValue('P6', $in[0]["companyData"]["c_address_2"]);
      $oWs->setCellValue('P7', $in[0]["projectData"]["p_job"]);

      $rowCounter = 16;
      $all["base"]= 0;
      $all["125"]=$all["150"]=$all["200"]=$all["250"]=$all["25"]=0;
      $all["food"]=0;
      $all["car"]=0;
      $allhours1 = new DateTime('2000-01-01 00:00:00');
      $allhours2 = new DateTime('2000-01-01 00:00:00');

      $i = 0;

      $len = count($dat);

      foreach($dat as $arr){
          if($arr['workhours']==0){
              break;
          }
          list($hours, $minutes) = explode(':', $arr['workhours']);
          $allhours2->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'));
          $all["base"]+=$arr['base'];
          $all["125"]= $all["125"] + $arr['overtime'][0] + $arr['overtime'][1];
          $all["150"]= $all["150"] + $arr['overtime'][2] + $arr['overtime'][3];
          $all["200"]= $all["200"] + $arr['overtime'][4] + $arr['overtime'][5];
          $all["250"]+=$arr['overtime'][6];
          $all["25"]+=$arr['night'];
          $all["food"]+=$arr['lunch'];
          $all["car"]+=$arr['car'];
          $date = DateTime::createFromFormat('Y-m-d', $arr['date']);
          $date = $date->format('d/m/Y');
          $cur=$rowCounter-1;
          $oWs->setCellValue("A".$cur, $date);
          $oWs->getStyle("A".$cur)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $oWs->setCellValue("C".$cur,$arr['work']); self::setCellColor($oWs,"C".$cur,$darkBlue);
          $oWs->setCellValue("E".$cur,$arr['start']); self::setCellColor($oWs,"E".$cur,$darkBlue);
          $oWs->setCellValue("F".$cur,$arr['end']); self::setCellColor($oWs,"F".$cur,$darkBlue);
          $oWs->setCellValue("G".$cur,$arr['break']); self::setCellColor($oWs,"G".$cur,$darkBlue);
          $oWs->setCellValue("H".$cur,$arr['workhours']); self::setCellColor($oWs,"H".$cur,$darkBlue);
          $oWs->setCellValue("J".$cur,$arr['base']); self::setCellColor($oWs,"J".$cur,$darkYellow);
          if ($arr['overtime'][0] > 0) {  $oWs->setCellValue("L".$cur, $arr['overtime'][0]); self::setCellColor($oWs,"L".$cur,$darkOrange);}else{ self::setCellColor($oWs,"L".$cur,$brightOrange);}
          if ($arr['overtime'][1] > 0) {  $oWs->setCellValue("M".$cur, $arr['overtime'][1]); self::setCellColor($oWs,"M".$cur,$darkOrange);}else{ self::setCellColor($oWs,"M".$cur,$brightOrange);}
          if ($arr['overtime'][2] > 0) {  $oWs->setCellValue("N".$cur, $arr['overtime'][2]); self::setCellColor($oWs,"N".$cur,$darkOrange);}else{ self::setCellColor($oWs,"N".$cur,$brightOrange);}
          if ($arr['overtime'][3] > 0) {  $oWs->setCellValue("O".$cur, $arr['overtime'][3]); self::setCellColor($oWs,"O".$cur,$darkOrange);}else{ self::setCellColor($oWs,"O".$cur,$brightOrange);}
          if ($arr['overtime'][4] > 0) {  $oWs->setCellValue("P".$cur, $arr['overtime'][4]); self::setCellColor($oWs,"P".$cur,$darkOrange);}else{ self::setCellColor($oWs,"P".$cur,$brightOrange);}
          if ($arr['overtime'][5] > 0) {  $oWs->setCellValue("Q".$cur, $arr['overtime'][5]); self::setCellColor($oWs,"Q".$cur,$darkOrange);}else{ self::setCellColor($oWs,"Q".$cur,$brightOrange);}
          if ($arr['overtime'][6] > 0) {  $oWs->setCellValue("R".$cur, $arr['overtime'][6]); self::setCellColor($oWs,"R".$cur,$darkOrange);}else{ self::setCellColor($oWs,"R".$cur,$brightOrange);}
          if ($arr['night'] > 0) {  $oWs->setCellValue("T".$cur, $arr['night']);self::setCellColor($oWs,"T".$cur,$darkOrange);}else{self::setCellColor($oWs,"T".$cur,$brightOrange);}
          if ($arr['lunch']==1) {$oWs->setCellValue("W".$cur,1); self::setCellColor($oWs,"W".$cur,$darkGreen);}else{$oWs->setCellValue("W".$cur,'');self::setCellColor($oWs,"W".$cur,$brightGreen);}
          if ($arr['car']>0) {$oWs->setCellValue("X".$cur,$arr['car']);self::setCellColor($oWs,"L".$cur,$darkGreen);}else{$oWs->setCellValue("X".$cur,'');self::setCellColor($oWs,"X".$cur,$brightGreen);}

          if ($i != $len - 1) {
              $oWs->insertNewRowBefore($rowCounter, 1);
              $oWs->mergeCells('A'.$rowCounter.':B'.$rowCounter);
              $oWs->mergeCells('R'.$rowCounter.':S'.$rowCounter);
              $oWs->mergeCells('T'.$rowCounter.':U'.$rowCounter);
          }
          $rowCounter++;
          $i++;
      }

      //TotalAZ
      $cur=$rowCounter-1;
      $interval = $allhours1->diff($allhours2);
      $d=$interval->d;
      $h=$interval->h;
      $m= $interval->i;
      $taz = $d*24+$h.':'.$m;
      $oWs->setCellValue("H".$cur,$taz);

      $cur=$cur+1;
      $oWs->setCellValue("J".$cur,$all["base"]);
      $oWs->setCellValue("L".$cur,$all["125"]);
      $oWs->setCellValue("N".$cur,$all["150"]);
      $oWs->setCellValue("P".$cur,$all["200"]);
      $oWs->setCellValue("R".$cur,$all["250"]);
      $oWs->setCellValue("T".$cur,$all["25"]);
      $oWs->setCellValue("W".$cur,$all["food"]);
      $oWs->setCellValue("X".$cur,$all["car"]);
      $cur=$cur+1;
      $oWs->setCellValue("J".$cur,$pay);
      $oWs->setCellValue("L".$cur,round($pay/9*1.25, 2));
      $oWs->setCellValue("N".$cur,round($pay/9*1.5, 2));
      $oWs->setCellValue("P".$cur,round($pay/9*2.0, 2));
      $oWs->setCellValue("R".$cur,round($pay/9*2.5, 2));
      $oWs->setCellValue("T".$cur,round($pay/9*0.25, 2));
      $oWs->setCellValue("W".$cur,32);
      $oWs->setCellValue("X".$cur,0.7);
      $cur=$cur+1;
      $oWs->setCellValue("J".$cur,round($all["base"]*$pay,2));
      $oWs->setCellValue("L".$cur,round($all["125"]*$pay/9*1.25, 2));
      $oWs->setCellValue("N".$cur,round($all["150"]*$pay/9*1.5, 2));
      $oWs->setCellValue("P".$cur,round($all["200"]*$pay/9*2.0, 2));
      $oWs->setCellValue("R".$cur,round($all["250"]*$pay/9*2.5, 2));
      $oWs->setCellValue("T".$cur,round($all["25"]*$pay/9*0.25, 2));
      $oWs->setCellValue("W".$cur,round($all["food"]*32, 2));
      $oWs->setCellValue("X".$cur,round($all["car"]*0.7, 2));
      $cur=$cur+3;
      $p125=round($all["125"]*$pay/9*1.25, 2);
      $p150=round($all["150"]*$pay/9*1.5, 2);
      $p200=round($all["200"]*$pay/9*2.0, 2);
      $p250=round($all["250"]*$pay/9*2.5, 2);
      $p25=round($all["25"]*$pay/9*0.25, 2);
      $oWs->setCellValue("J".$cur,round($all["base"]*$pay,2));
      $overtime=round($p125+$p150 +$p200+$p250+$p25,2);
      $oWs->setCellValue("L".$cur,$overtime);
      $oWs->setCellValue("W".$cur,round(round($all["food"]*32, 2)+round($all["car"]*0.7, 2),2));

      $cur=$cur+1;
      $oWs->setCellValue("W".$cur,$exp);
      
      $cur=$rowCounter+1;     
      $comment = strlen($comment) > 300 ? substr($comment,0,297)."..." : $comment;
      $parts = str_split($comment, $split_length = 60);
      foreach ($parts as $text) {
          $oWs->setCellValue("A".$cur,$text);
          $cur++;
      }


      $filename = $title;
      if ($type=="xlsx") {
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
          return $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                          ->withHeader('Content-Disposition', 'attachment;filename="'.$filename.'.xlsx"')
                          ->write($objWriter->save('php://output'));

      }elseif ($type=="pdf") {
          $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
          $rendererLibrary = 'dompdf.php';
          require_once '../../vendor/dompdf/lib/Cpdf.php';
          $rendererLibraryPath = '../../vendor/dompdf/';
          if (!PHPExcel_Settings::setPdfRenderer(
              $rendererName,
              $rendererLibraryPath
          )) {
              die(
                  'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
                  EOL .
                  'at the top of this script as appropriate for your directory structure'
              );
          }

          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
          //$objWriter->writeAllSheets();
          return $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                          ->withHeader('Content-Disposition', 'attachment;filename="'.$filename.'.pdf"')
                          ->write($objWriter->save('php://output'));
      }
      exit;
    }

    private function setCellColor($oWs,$cell,$color){
        $oWs->getStyle($cell)->applyFromArray($color);
    }
}
