<?
use \Medoo\Medoo;

class View
{
    public function __construct($container) {
        $this->db = $container->get('database');
        $this->enc = $container->get('encrypt');
    }

    public function get($request,$response,$args){
      $p_id=$args['p_id'];
      $in = $this->db->select('projects', [
        "[>]companies(comp)" => ["p_company" => "company_id"],
        "[>]users(usr)" => ["user_id" => "u_id"]
      ], [
        "projectData" => [
            'projects.p_start',
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
            'usr.affiliation'
          ]
      ], [
        'project_id' => $p_id
      ]);
      $ahv = $this->enc->encrypt($in[0]["userData"]["ahv"], 'd');
      $kont = $this->enc->encrypt($in[0]["userData"]["konto"], 'd');
      $in[0]["userData"]["ahv"] = $ahv;
      $in[0]["userData"]["konto"] = $kont;
      return $response->withJson($in);
    }


    public function download($request,$response,$args){
      require_once('./includes/inc_encrypt.php');
      require_once('../api-app/lib/Globals.php');
      get();

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
              die('ERROR, EMPTY PROJECT');
          }
          $dat = json_decode($p_json, true);
          $title= $i_sdate->format('ymd').'_'.$p_name;
          $title= str_replace(" ", "_", $title);


      date_default_timezone_set('Europe/Berlin');
      /** PHPExcel_IOFactory */
      require_once '../includes/Classes/PHPExcel/IOFactory.php'; //TODO: Set this correctly
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

//      $objPHPExcel->getActiveSheet()
//      ->getPageSetup()
//      ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
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
      $objWorksheet->setCellValue('G2', $p_pay);
      $objWorksheet->setCellValue('A3', $u_name);
      $objWorksheet->setCellValue('A4', $u_address1);
      $objWorksheet->setCellValue('A5', $u_address2);
      $objWorksheet->setCellValue('A6', $u_tel);
      $objWorksheet->setCellValue('A7', $u_mail);
      $objWorksheet->setCellValue('G4', $u_ahv);
      $objWorksheet->setCellValue('G5', $u_dob);
      $objWorksheet->setCellValue('G6', $u_konto);
      $objWorksheet->setCellValue('G7', $u_bvg);
      $objWorksheet->setCellValue('P2', $p_name);
      //$objWorksheet->getStyle('P2')->getFont()->setBold(true);
      //$objWorksheet->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      $objWorksheet->setCellValue('P3', $sdate);
      $objWorksheet->setCellValue('V3', $edate);
      $objWorksheet->setCellValue('P4', $c_name);
      $objWorksheet->setCellValue('P5', $c_address1);
      $objWorksheet->setCellValue('P6', $c_address2);
      $objWorksheet->setCellValue('P7', $p_job);

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
      $objWorksheet->setCellValue("J".$cur,$p_pay);
      $objWorksheet->setCellValue("L".$cur,round($p_pay/9*1.25, 2));
      $objWorksheet->setCellValue("N".$cur,round($p_pay/9*1.5, 2));
      $objWorksheet->setCellValue("P".$cur,round($p_pay/9*2.0, 2));
      $objWorksheet->setCellValue("R".$cur,round($p_pay/9*2.5, 2));
      $objWorksheet->setCellValue("T".$cur,round($p_pay/9*0.25, 2));
      $objWorksheet->setCellValue("W".$cur,32);
      $objWorksheet->setCellValue("X".$cur,0.7);
      $cur=$cur+1;
      $objWorksheet->setCellValue("J".$cur,round($allbase*$p_pay,2));
      $objWorksheet->setCellValue("L".$cur,round($all125*$p_pay/9*1.25, 2));
      $objWorksheet->setCellValue("N".$cur,round($all150*$p_pay/9*1.5, 2));
      $objWorksheet->setCellValue("P".$cur,round($all200*$p_pay/9*2.0, 2));
      $objWorksheet->setCellValue("R".$cur,round($all250*$p_pay/9*2.5, 2));
      $objWorksheet->setCellValue("T".$cur,round($all25*$p_pay/9*0.25, 2));
      $objWorksheet->setCellValue("W".$cur,round($allfood*32, 2));
      $objWorksheet->setCellValue("X".$cur,round($allcar*0.7, 2));
      $cur=$cur+3;
      $p125=round($all125*$p_pay/9*1.25, 2);
      $p150=round($all150*$p_pay/9*1.5, 2);
      $p200=round($all200*$p_pay/9*2.0, 2);
      $p250=round($all250*$p_pay/9*2.5, 2);
      $p25=round($all25*$p_pay/9*0.25, 2);
      $objWorksheet->setCellValue("J".$cur,round($allbase*$p_pay,2));
      $overtime=round($p125+$p150+$p200+$p250+$p25,2);
      $objWorksheet->setCellValue("L".$cur,$overtime);
      $objWorksheet->setCellValue("W".$cur,round(round($allfood*32, 2)+round($allcar*0.7, 2),2));

      $comment = strlen($comment) > 300 ? substr($comment,0,297)."..." : $comment;
      $parts = str_split($comment, $split_length = 60);
      $cur=$rowCounter+1;
      foreach ($parts as $text) {
          $objWorksheet->setCellValue("A".$cur,$text);
          $cur++;
      }

      //echo date('H:i:s') . " Write to Excel5 format\n";
      if ($type=="xlsx") {
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
          header('Cache-Control: max-age=0');
          $objWriter->save('php://output');
          //$objWriter->save('documents/'.$p_id.'.xlsx');
      }elseif ($type=="pdf") {
          $rendererName = PHPExcel_Settings::PDF_RENDERER_DOMPDF;
          $rendererLibrary = 'dompdf.php';
          $rendererLibraryPath = dirname(__FILE__) . '/includes/Classes/dompdf/';
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
          header('Content-Type: application/pdf');
          header('Content-Disposition: attachment;filename="'.$title.'.pdf"');
          header('Cache-Control: max-age=0');
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
          $objWriter->save('php://output');
      }

      exit;
    }

}
