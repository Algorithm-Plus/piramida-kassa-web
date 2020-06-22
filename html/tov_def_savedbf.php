<?php
include ("page_top.php");

function file_force_download($file)
  {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    if (readfile($file))
    {
      unlink($file);
    }
    exit;
   }
  }

if (strpos($_SERVER["HTTP_REFERER"], "tov_zero.php")>0)
{
  $DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
  $def = array(
  array("SCODE","N",10,0),
  array("SNAME","C",100),
  array("SBARCODE","C",25),
  array("SSERIACODE","C",25),
  array("SPARTNUMB","C",25),
  array("NPRICE","N",18, 3),
  array("NQUANT","N",18, 3),
  );
  $DBFFileName=sys_get_temp_dir().DIRECTORY_SEPARATOR.'def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
  unlink($DBFFileName);
  dbase_create($DBFFileName, $def);
  $dbf = dbase_open($DBFFileName,2);
  //$TableData = $DataModule->GetPrice();
  $TableData = $_SESSION['price'];
  for ($i=1; $i<=count($TableData); $i++)
	{
    //echo "<b>".$_REQUEST["ch".$i]."</b><BR>";
    if (isset($_REQUEST["ch".$i]))
    {
      //if ($_REQUEST["ch".$i])
      {
        dbase_add_record($dbf, array(
						$TableData[$i][10], 
						$TableData[$i][2],
						$TableData[$i][1],
            $TableData[$i][3],
            $TableData[$i][7],
            $TableData[$i][5],
            $TableData[$i][4]
						));
      }
    }
  }
  dbase_close($dbf);
}

function PrintRows($document_node, $TableData, $j)
{
  //$i=$j;
  //echo $i."=".$j." - ".count($TableData)."<BR><BR>";
  //for ($i=$j; $i<=count($TableData); $i++)
  for ($i=1; $i<=count($TableData); $i++)
	{
    $counter = $j+$i-2;
    //echo $i."<BR>";
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'RECNO');
    $row_node->addChild("value",$counter);
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A12');
    //$row_node->addChild("value",date("t",mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("m", mktime(0, 0, 0, date("m")-1  , date("d"), date("Y"))).".".date("Y"));
    $row_node->addChild("value",  date("d.m.Y", strtotime("+1 day", strtotime($_REQUEST["Date2"]))));
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A13');
    $row_node->addChild("value",iconv("cp1251", "utf-8",$TableData[$i][1]));
  
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A14');
    $row_node->addChild("value",iconv("cp1251", "utf-8","шт"));
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A141');
    $row_node->addChild("value",iconv("cp1251", "utf-8","2009"));
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A15');
    $row_node->addChild("value",$TableData[$i][4]);
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A16');
    $row_node->addChild("value",number_format($TableData[$i][3],2,".","")); //Ціна без ПДВ
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A1');
    $row_node->addChild("value","I");
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_A171');
    $row_node->addChild("value",number_format($TableData[$i][5],2,".",""));
    
    $row_node = $document_node->addChild("ROW");
    $row_node->addAttribute('LINE', $counter);
    $row_node->addAttribute('TAB', '1');
    $row_node->addAttribute('NAME', 'TAB1_RATE');
    $row_node->addChild("value",$TableData[$i][2]."%");
    
  }
}
if (strpos($_SERVER["HTTP_REFERER"], "report_strah1_result.php")>0)
{
  $DBFFileName = sys_get_temp_dir().DIRECTORY_SEPARATOR.'F1201007'.'.XML'; //tempnam(
  copy('F1201007.XML', $DBFFileName);
  $xml=simplexml_load_file($DBFFileName) or die("Error: Cannot open XML");
  $document_node = $xml->xpath('ORG/CARD/DOCUMENT');//[0];
  //$document_node = $xml->xpath('ORG/CARD/DOCUMENT')[0];
  $document_node = $document_node[0]; //for alocum

if ($_REQUEST["a"]=="all")
{
  $k=1;
  $DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);      
  $InsurerName = $DataModule->GetInsurerByID($_REQUEST["Insurer"]);
  $InsurerName = $InsurerName[1][3];
  for ($j=1; $j<=count($_SESSION["AptekaList"]); $j++)
	{
		if ($_SESSION["AptekaList"][$j]->Active==1)
		{
      $DataModule = new DataModule($_SESSION["AptekaList"][$j]);      
			//echo $InsurerName[1][3]."<BR><BR>";
      $TableData = $DataModule->ReportStrah3($_REQUEST["Date1"],$_REQUEST["Date2"],$InsurerName);
      //print_r($TableData); echo "<BR><BR><BR>";
      PrintRows($document_node, $TableData, $k);
      $k = count($TableData)+1;
      //echo "<BR><BR>".$k." - ".$j."<BR><BR>";
    }
  }
}
else
{
  $DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
  $InsurerName = $DataModule->GetInsurerByID($_REQUEST["Insurer"]);
  $TableData = $DataModule->ReportStrah3($_REQUEST["Date1"],$_REQUEST["Date2"],$InsurerName[1][3]);
  PrintRows($document_node, $TableData, 1);
}
  //print_r($xml);
  $xml->SaveXML($DBFFileName);
   
  /*
  $def = array(
  array("SCODE","N",10,0),
  array("SNAME","C",100),
  array("SBARCODE","C",25),
  array("SSERIACODE","C",25),
  array("SPARTNUMB","C",25),
  array("NPRICE","N",18, 3),
  array("NQUANT","N",18, 3),
  );
  $DBFFileName='def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
  unlink($DBFFileName);
  dbase_create($DBFFileName, $def);
  $dbf = dbase_open($DBFFileName,2);
  //$TableData = $DataModule->GetPrice();
  $TableData = $_SESSION['price'];
  for ($i=1; $i<=count($TableData); $i++)
	{
    if (isset($_REQUEST["ch".$i]))
    {
      if ($_REQUEST["ch".$i])
      {
        dbase_add_record($dbf, array(
						$TableData[$i][10], 
						$TableData[$i][2],
						$TableData[$i][1],
            $TableData[$i][3],
            $TableData[$i][7],
            $TableData[$i][5],
            $TableData[$i][4]
						));
      }
    }
  }
  dbase_close($dbf);
  */
}

if (strpos($_SERVER["HTTP_REFERER"], "tov_prodagi.php")>0)
{
  $DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
  $def = array(
  array("SCODE","N",10,0),
  array("SNAME","C",100),
  array("SBARCODE","C",25),
  array("SSERIACODE","C",25),
  array("SPARTNUMB","C",25),
  array("NPRICE","N",18, 3),
  array("NQUANT","N",18, 3),
  array("USER","C",25),
  array("DISC","N",5,0),
  );
  $DBFFileName=sys_get_temp_dir().DIRECTORY_SEPARATOR.'def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
  unlink($DBFFileName);
  dbase_create($DBFFileName, $def);
  $dbf = dbase_open($DBFFileName,2);
  //$TableData = $DataModule->GetLastPreparatsOnDate($_REQUEST["Date1"], $_REQUEST["Date2"],"");
  //print_r($_POST);  exit;
  //for ($i=1; $i<=count($TableData); $i++)
  for ($i=1; $i<=$_POST['item_count']; $i++)
	{
    if (isset($_POST['ch'.$i])){
     
      dbase_add_record($dbf, array(
        $_POST['ch'.$i],
        $_POST['sname'.$i],         
        '',
        '',
        '', //part
        '', //price
        $_POST['quant'.$i], //quant
        '', //user
        0 //discont
    ));

    }
    
    
/*    
            dbase_add_record($dbf, array(
						$TableData[$i][14], 
						$TableData[$i][4],
						$TableData[$i][1],
            $TableData[$i][13],
            $TableData[$i][18], //part
            $TableData[$i][6], //price
            $TableData[$i][5], //quant
            $TableData[$i][15], //user
            0 //discont
						));
*/						
  }
  dbase_close($dbf);
}

if (strpos($_SERVER["HTTP_REFERER"], "tov_def_analiz_online.php")>0)
{
  //$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
  $def = array(
  array("SCODE","N",10,0),
  array("SNAME","C",100),
  //array("SBARCODE","C",25),
  //array("SSERIACODE","C",25),
  //array("SPARTNUMB","C",25),
  //array("NPRICE","N",18, 3),
  array("NQUANT","N",18, 3),
  );
  $DBFFileName=sys_get_temp_dir().DIRECTORY_SEPARATOR.'def'.$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod.'.dbf';
  unlink($DBFFileName);
  dbase_create($DBFFileName, $def);
  $dbf = dbase_open($DBFFileName,2);
  //$TableData = $DataModule->GetPrice();
  //$TableData = $_SESSION['price'];
  for ($i=1; $i<=$_REQUEST["count"]; $i++)
	{
    //echo "<b>".$_REQUEST["ch".$i]."</b><BR>";
    if (isset($_REQUEST["cod".$i]))
    {
      //if ($_REQUEST["ch".$i])
      {
        dbase_add_record($dbf, array(
						$_REQUEST["cod".$i], 
						$_REQUEST["sname".$i], 
            $_REQUEST["val".$i],
						));
      }
    }
  }
  dbase_close($dbf);
}

if (isset($_REQUEST["file"]))
{
  $DBFFileName=$_REQUEST["file"];
}
unset($DataModule);
unset($_SESSION['price']);
file_force_download($DBFFileName);

//unlink($DBFFileName);
/*
printf("<script language='javascript'>");
printf("document.location.href='".$DBFFileName."';");
printf("</script>");
*/
?>