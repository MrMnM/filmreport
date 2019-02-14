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
            'projects.p_comment'
          ],
        "companyData" => [
            'comp.name(c_name)',
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

      $sdate = DateTime::createFromFormat('Y-m-d',$in[0]["projectData"]["p_start"]);
      $edate = DateTime::createFromFormat('Y-m-d',$in[0]["projectData"]["p_end"]);
      $u_dob = DateTime::createFromFormat('Y-m-d',$in[0]["userData"]["dateob"]);

      $title= $sdate->format('ymd').'_'.$in[0]["projectData"]["p_name"].'_'.$in[0]["userData"]["name"];
      $title= str_replace(" ", "_", $title);


      $sdate = $sdate->format('d/m/Y');
      $edate = $edate->format('d/m/Y');
      $u_dob = $u_dob->format('d/m/Y');

      date_default_timezone_set('Europe/Berlin');
      /** PHPExcel_IOFactory */
      require_once '../../vendor/PHPExcel/IOFactory.php'; //TODO: Set this correctly
      //echo date('H:i:s') . " Load from Excel5 template\n";
      $objReader = PHPExcel_IOFactory::createReader('Excel2007');
      $objPHPExcel = $objReader->load("template_rapport.xlsx");
      $objPHPExcel->getDefaultStyle()
      ->getBorders()
      ->getTop()
      ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $objPHPExcel->getDefaultStyle()
      ->getBorders()
      ->getBottom()
      ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $objPHPExcel->getDefaultStyle()
      ->getBorders()
      ->getLeft()
      ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $objPHPExcel->getDefaultStyle()
      ->getBorders()
      ->getRight()
      ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

      $objPHPExcel->getActiveSheet()
      ->getPageSetup()
      ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

      $objPHPExcel->getActiveSheet()
      ->getPageMargins()->setTop(0);
      $objPHPExcel->getActiveSheet()
      ->getPageMargins()->setRight(0);
      $objPHPExcel->getActiveSheet()
      ->getPageMargins()->setLeft(0.12);
      $objPHPExcel->getActiveSheet()
      ->getPageMargins()->setBottom(0);
      $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);

      $objWorksheet = $objPHPExcel->getActiveSheet();
      $objWorksheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
      $objWorksheet->setCellValue('G2', $pay);
      $objWorksheet->setCellValue('A3', $in[0]["userData"]["name"]);
      $objWorksheet->setCellValue('A4', $in[0]["userData"]["address_1"]);
      $objWorksheet->setCellValue('A5', $in[0]["userData"]["address_2"]);
      $objWorksheet->setCellValue('A6', $in[0]["userData"]["tel"]);
      $objWorksheet->setCellValue('A7', $in[0]["userData"]["mail"]);
      $objWorksheet->setCellValue('G4', $in[0]["userData"]["ahv"]);
      $objWorksheet->setCellValue('G5', $u_dob);
      $objWorksheet->setCellValue('G6', $in[0]["userData"]["konto"]);
      $objWorksheet->setCellValue('G7', $in[0]["userData"]["bvg"]);
      $objWorksheet->setCellValue('P2', $in[0]["projectData"]["p_name"]);
      //$objWorksheet->getStyle('P2')->getFont()->setBold(true);
      //$objWorksheet->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objWorksheet->setCellValue('P3', $sdate);
      $objWorksheet->setCellValue('V3', $edate);
      $objWorksheet->setCellValue('P4', $in[0]["companyData"]["c_name"]);
      $objWorksheet->setCellValue('P5', $in[0]["companyData"]["c_address_1"]);
      $objWorksheet->setCellValue('P6', $in[0]["companyData"]["c_address_2"]);
      $objWorksheet->setCellValue('P7', $in[0]["projectData"]["p_job"]);

      $rowCounter = 16;
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

      $i = 0;

      $len = count($dat);

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
          $date = DateTime::createFromFormat('Y-m-d', $arr['date']);
          $date = $date->format('d/m/Y');
          $cur=$rowCounter-1;
          $objWorksheet->setCellValue("A".$cur, $date);
          $objWorksheet->getStyle("A".$cur)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $objWorksheet->setCellValue("C".$cur,$arr['work']);
          $objWorksheet->setCellValue("E".$cur,$arr['start']);
          $objWorksheet->setCellValue("F".$cur,$arr['end']);
          $objWorksheet->setCellValue("G".$cur,$arr['break']);
          $objWorksheet->setCellValue("H".$cur,$arr['workhours']);
          $objWorksheet->setCellValue("J".$cur,$arr['base']);
          $objWorksheet->setCellValue("L".$cur,$arr['tent']);
          $objWorksheet->setCellValue("M".$cur,$arr['elev']);
          $objWorksheet->setCellValue("N".$cur,$arr['twel']);
          $objWorksheet->setCellValue("O".$cur,$arr['thir']);
          $objWorksheet->setCellValue("P".$cur,$arr['four']);
          $objWorksheet->setCellValue("Q".$cur,$arr['fift']);
          $objWorksheet->setCellValue("R".$cur,$arr['sixt']);
          $objWorksheet->setCellValue("T".$cur,$arr['night']);
          if ($arr['lunch']==1) {
            $objWorksheet->setCellValue("W".$cur,1);
          }else{
            $objWorksheet->setCellValue("W".$cur,0);
          }

          $objWorksheet->setCellValue("X".$cur,$arr['car']);
          if ($i != $len - 1) {
              $objWorksheet->insertNewRowBefore($rowCounter, 1);
              $objWorksheet->mergeCells('A'.$rowCounter.':B'.$rowCounter);
              $objWorksheet->mergeCells('R'.$rowCounter.':S'.$rowCounter);
              $objWorksheet->mergeCells('T'.$rowCounter.':U'.$rowCounter);
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
      $objWorksheet->setCellValue("H".$cur,$taz);

      $cur=$cur+1;
      $objWorksheet->setCellValue("J".$cur,$allbase);
      $objWorksheet->setCellValue("L".$cur,$all125);
      $objWorksheet->setCellValue("N".$cur,$all150);
      $objWorksheet->setCellValue("P".$cur,$all200);
      $objWorksheet->setCellValue("R".$cur,$all250);
      $objWorksheet->setCellValue("T".$cur,$all25);
      $objWorksheet->setCellValue("W".$cur,$allfood);
      $objWorksheet->setCellValue("X".$cur,$allcar);
      $cur=$cur+1;
      $objWorksheet->setCellValue("J".$cur,$pay);
      $objWorksheet->setCellValue("L".$cur,round($pay/9*1.25, 2));
      $objWorksheet->setCellValue("N".$cur,round($pay/9*1.5, 2));
      $objWorksheet->setCellValue("P".$cur,round($pay/9*2.0, 2));
      $objWorksheet->setCellValue("R".$cur,round($pay/9*2.5, 2));
      $objWorksheet->setCellValue("T".$cur,round($pay/9*0.25, 2));
      $objWorksheet->setCellValue("W".$cur,32);
      $objWorksheet->setCellValue("X".$cur,0.7);
      $cur=$cur+1;
      $objWorksheet->setCellValue("J".$cur,round($allbase*$pay,2));
      $objWorksheet->setCellValue("L".$cur,round($all125*$pay/9*1.25, 2));
      $objWorksheet->setCellValue("N".$cur,round($all150*$pay/9*1.5, 2));
      $objWorksheet->setCellValue("P".$cur,round($all200*$pay/9*2.0, 2));
      $objWorksheet->setCellValue("R".$cur,round($all250*$pay/9*2.5, 2));
      $objWorksheet->setCellValue("T".$cur,round($all25*$pay/9*0.25, 2));
      $objWorksheet->setCellValue("W".$cur,round($allfood*32, 2));
      $objWorksheet->setCellValue("X".$cur,round($allcar*0.7, 2));
      $cur=$cur+3;
      $p125=round($all125*$pay/9*1.25, 2);
      $p150=round($all150*$pay/9*1.5, 2);
      $p200=round($all200*$pay/9*2.0, 2);
      $p250=round($all250*$pay/9*2.5, 2);
      $p25=round($all25*$pay/9*0.25, 2);
      $objWorksheet->setCellValue("J".$cur,round($allbase*$pay,2));
      $overtime=round($p125+$p150+$p200+$p250+$p25,2);
      $objWorksheet->setCellValue("L".$cur,$overtime);
      $objWorksheet->setCellValue("W".$cur,round(round($allfood*32, 2)+round($allcar*0.7, 2),2));
/*
      $comment = strlen($comment) > 300 ? substr($comment,0,297)."..." : $comment;
      $parts = str_split($comment, $split_length = 60);
      $cur=$rowCounter+1;
      foreach ($parts as $text) {
          $objWorksheet->setCellValue("A".$cur,$text);
          $cur++;
      }
*/
      //echo date('H:i:s') . " Write to Excel5 format\n";
      $filename = $title;
      if ($type=="xlsx") {
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
          return $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                      ->withHeader('Content-Disposition', 'attachment;filename="'.$filename.'.xlsx"')
                      ->write($objWriter->save('php://output'));

          //$objWriter->save('documents/'.$p_id.'.xlsx');
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
          return $response->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                      ->withHeader('Content-Disposition', 'attachment;filename="'.$filename.'.pdf"')
                      ->write($objWriter->save('php://output'));
      }
      exit;
    }

}
