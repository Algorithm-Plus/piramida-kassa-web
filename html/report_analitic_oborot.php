<?php
include ("config.php");

//============== SETINGS =============

$AnaliticCod = $_REQUEST["Analitic"];
$DayCount = 7;
$Date1 = $_REQUEST["Date1"];//date("d.m.Y", strtotime("-".$DayCount." day"));
$Date2 = $_REQUEST["Date2"];//date("d.m.Y", strtotime("-1 day"));
$message = $Date1." - ".$Date2."<BR>";
//====================================

$Attachment = array();
$ArrayFactory = new ArrayFactory();
$FieldList = array();
$FieldList[1] = "VARCHAR(50)"; //�������� � ����� ���������
$FieldList[2] = "VARCHAR(50)"; //��� ������
$FieldList[3] = "VARCHAR(200)"; //�����
$FieldList[4] = "VARCHAR(50)"; //�����-���
$FieldList[5] = "VARCHAR(100)"; //��������

$FieldList[6]  = "DECIMAL(10,2)"; //������ �-��
$FieldList[7]  = "DECIMAL(10,2)"; //������ ����

$FieldList[8]  = "DECIMAL(10,2)";  //������ �-��
$FieldList[9]  = "DECIMAL(10,2)"; //������ ����

$FieldList[10] = "DECIMAL(10,2)"; //���������� �-��
$FieldList[11] = "DECIMAL(10,2)"; //���������� ����

$FieldList[12] = "DECIMAL(10,2)"; //������� �-��
$FieldList[13] = "DECIMAL(10,2)"; //������� ����

$FieldList[14] = "DECIMAL(10,2)"; //�������� �-��
$FieldList[15] = "DECIMAL(10,2)"; //�������� ����


//�������
$GroupField = array();
$GroupField[1] = 1;
$GroupField[2] = 2;
$GroupField[3] = 3;
$GroupField[4] = 4;
$GroupField[5] = 5;

//������ �-��
$SumField = array();
$SumField[1] = 6;
$SumField[2] = 7;
$SumField[3] = 8;
$SumField[4] = 9;
$SumField[5] = 10;
$SumField[6] = 11;
$SumField[7] = 12;
$SumField[8] = 13;
$SumField[9] = 14;
$SumField[10] = 15;

//���������� �� ���� � 2 (�����)
$OrderField = 3;
//��������� ��������� �������

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<table class='NoMargin' border=0 width=100%>";
				echo "<TR>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_lt.jpg'>";	
					echo "</TD>";
					echo "<TD style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-x;' background='template/".$_SESSION["Template"]."/img/l_t.jpg'>";
					echo "</TD>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_rt.jpg'>";	
					echo "</TD>";
				echo "</TR>";
				echo "<TR>";
					echo "<TD width='6px' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/l_l.jpg'>";
					echo "</TD>";
					echo "<TD width=".$WidthPage."px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-yx;' background='template/".$_SESSION["Template"]."/img/bkground1.jpg'>";
//---------------------------��� �� �������

$TableHeader = array(
          0=>'',
          1=>'��������',
          2=>'������',
          3=>$FormTovarProdagiHeader3,
          4=>$FormPreparatItem4,
          5=>$FormTovarItem16,
          
          6=>$FormTovarDefecturaItem7,
          7=>$FormTovarOborotItem8,
          
          8=>$FormTovarDefecturaItem8,
          9=>$FormTovarOborotItem8,
          
          10=>$FormTovarDefecturaItem9,
          11=>$FormTovarOborotItem8,
          
          12=>$FormTovarDefecturaItem4,
          13=>$FormTovarOborotItem8,
          
          14=>'������',
          15=>$FormTovarOborotItem8
          );
    
  

$TableData = array();

$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
$TovarList = $DataModule->ViewAnalitic($AnaliticCod);

$ArrayFactory->TempTableCreate($FieldList);

for ($j=1; $j<=count($TovarList); $j++)
{
  
  $DataModule->Connect();

  $Import = $DataModule->ImportByBarCodeWithSum($TovarList[$j][1], $Date1, $Date2);
  $ImportQuant = $Import[1][1];
  $ImportSum   = $Import[1][2];
  
  $Sale = $DataModule->SaleByBarCodeWithSum($TovarList[$j][1], $Date1, $Date2);
  $SaleQuant = $Sale[1][1];
  $SaleSum = $Sale[1][2];
    
  $Export = $DataModule->ExportByBarCodeWithSum($TovarList[$j][1], $Date1, $Date2);
  $ExportQuant = $Export[1][1];
  $ExportSum   = $Export[1][2];
    
  $Rest = $DataModule->RestByBarCodeWithSum($TovarList[$j][1]);
  $RestNowQuant = $Rest[1][1];
  $RestNowSum   = $Rest[1][2];
    
  $DataModule->Disconnect();
    
  $TableData[$j] = array (
        1 =>$TovarList[$j][7],
        2 =>$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->MnemoCod,
        3 =>$TovarList[$j][2],
        4 =>$TovarList[$j][1],
        5 =>$TovarList[$j][6],
        6 =>$ImportQuant,
        7 =>$ImportSum,
        8 =>$SaleQuant,
        9 =>$SaleSum,
        10=>$ExportQuant,
        11=>$ExportSum,
        12=>$RestNowQuant,
        13=>$RestNowSum,
        14=>$ImportQuant-$SaleQuant-$ExportQuant-$RestNowQuant,
        15=>$ImportSum-$SaleSum-$ExportSum-$RestNowSum
        );

		}
$ArrayFactory->ArrayAdd($FieldList, $TableData);



$TableData = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
$Table = new Table();
$Table->NextRowIsHeader(true);
echo $Table->TableBegin(count($TableData[1])+1); //+1 �
$Table->ChangeAlign(7,'Right');
$Table->ChangeAlign(8,'Right');
$Table->ChangeAlign(9,'Right');
$Table->ChangeAlign(10,'Right');
$Table->ChangeAlign(11,'Right');
$Table->ChangeAlign(12,'Right');
$Table->ChangeAlign(13,'Right');
$Table->ChangeAlign(14,'Right');
$Table->ChangeAlign(15,'Right');
$Table->ChangeAlign(16,'Right');
echo $Table->TableRow($TableHeader);
$Table->NextRowIsHeader(false);
		
for ($m=1; $m<=Count($TableData); $m++)
{
  $row = array(
      $m,
      $TableData[$m][1],
      $TableData[$m][2],
      $TableData[$m][3],
      $TableData[$m][4],
      $TableData[$m][5],
      $TableData[$m][6],
      number_format($TableData[$m][7],2,",",""),
      number_format($TableData[$m][8],2,",",""),
      number_format($TableData[$m][9],2,",",""),
      number_format($TableData[$m][10],2,",",""),
      number_format($TableData[$m][11],2,",",""),
      number_format($TableData[$m][12],2,",",""),
      number_format($TableData[$m][13],2,",",""),
      number_format($TableData[$m][14],2,",",""),
      number_format($TableData[$m][15],2,",","")
      );
  //if (($TableData[$m][14]>0) || ($TableData[$m][15]>0))
  if ($TableData[$m][14]>0)
  {
    $Table->NextRowIsHeader(true);
  }
  else
  {
    $Table->NextRowIsHeader(false);
  }
  echo $Table->TableRow($row);			
}
echo $Table->TableEnd();
    
$ArrayFactory->TempTableDrop();

unset($DataModule);
unset($ArrayFactory);

//---------------------------
					echo "</TD>";
					echo "<TD width='6px' style='margin: 0px; padding: 0px; BACKGROUND-REPEAT:  repeat-y;' background='template/".$_SESSION["Template"]."/img/l_r.jpg'>";
					echo "</TD>";
				echo "</TR>";

				echo "<TR>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_lb.jpg'>";	
					echo "</TD>";
					echo "<TD height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: repeat-x;' background='template/".$_SESSION["Template"]."/img/l_b.jpg'>";
					echo "</TD>";
					echo "<TD width=6px height=6px style='margin: 0px; padding: 0px; BACKGROUND-REPEAT: no-repeat;' background='template/".$_SESSION["Template"]."/img/c_rb.jpg'>";	
					echo "</TD>";
				echo "</TR>";

echo "</table>";
echo "</BODY>";

?>