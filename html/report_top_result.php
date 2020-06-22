<?php
include ("config.php");
include("SpreadsheetReaderFactory.php");

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<table class='NoMargin' border=0>";
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
//---------------------------Дані на сторінці
		echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
		echo "<BR>";
		echo "<p align='center'";
			echo "<font class='Midle'>";
				$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
			echo "</font>";
			$target_path = "D:\\Projects\\drugstore\\trunk\\top.ods";
			//print_r($_FILES);
			move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path);
			$spreadsheetsFilePath = $target_path; //or test.xls, test.csv, etc.
			$reader = SpreadsheetReaderFactory::reader($spreadsheetsFilePath);
			$sheets = $reader->read($spreadsheetsFilePath);
			//echo iconv('UTF-8', 'windows-1251',$sheets[0][2][4])."<BR><BR><BR>";

			$TableData = $DataModule->GetBestPreparatsWithAnalitic($_REQUEST["Count"],$_REQUEST["Date1"],$_REQUEST["Date2"],"3");
			$Table = new Table();
			echo $Table->TableBegin(5);
			//$Table->ChangeAlign(7,'Right');
			//$Table->ChangeAlign(8,'Right');
			//$Table->ChangeAlign(9,'Right');
			$Row = array($FormPreparatItem9, $FormTovarDefecturaItem1, $FormReportHeader1_6." БАДМ", $FormReportHeader1_6, $FormReportHeader3_2);
			$Table->NextRowIsHeader(true);
			echo $Table->TableRow($Row);
			$Table->NextRowIsHeader(false);

			for ($i=1; $i<$_REQUEST["Count"]+1; $i++)
			{
				$TovarName = "-";
				$TovarPos  = "-";
				for ($j=1; $j<=count($TableData); $j++)
				{
					if ($TableData[$j][3]==$sheets[0][$i+1][2])
					{
						$TovarName = $TableData[$j][2];
						$TovarPos  = $j;
					}
				}
				//Не знайшов по коду
				if ($TovarName=="-")
				{
				  $FileNamePosPoint = strpos(iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4]),',');
					$FileNamePosSpace = strpos(iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4]),' ');
					$FileNomerPos = strpos(iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4]),'№');
					if ($FileNamePosPoint<$FileNamePosSpace)
					{
				     $FileNamePos=$FileNamePosPoint;
					}
					else
					{
				    $FileNamePos=$FileNamePosSpace;
					}
					$FileName = substr(iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4])
														 ,0,
														 $FileNamePos
														 );
					$FileName=strtolower(str_replace('®', '', $FileName));
					$FileNomer=substr(iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4])
														 ,$FileNomerPos,
														 3
														 );
					//echo "<BR>======= ".iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4])." -- ".$FileNomer." -- ".$FileName." ======<BR>";
					
					
					for ($j=1; $j<=count($TableData); $j++)
					{
								//$BaseNamePosPoint = strpos($TableData[$j][2],',');
								$BaseNamePosSpace = strpos($TableData[$j][2],' ');
								$BaseNomerPos = strpos($TableData[$j][2],'№');
								/*
								if ($BaseNamePosPoint<$BaseNamePosSpace)
								{
									 $BaseNamePos=$BaseNamePosPoint;
								}
								else
								{
								*/
									$BaseNamePos=$BaseNamePosSpace;
								//}
								$BaseName = strtolower(substr($TableData[$j][2]
																	 ,0,
																	 $BaseNamePos
																	 ));
								$BaseNomer=substr($TableData[$j][2]
																	 ,$BaseNomerPos,
																	 3
																	 );
								//echo "<BR>++++++ ".$TableData[$j][2]." --! ".$BaseNomer." !-- ".$BaseName." ======<BR>";
								if ($FileName==$BaseName && $FileNomer==$BaseNomer)
								{
												$TovarName = $TableData[$j][2];
												$TovarPos  = $j;
								}
					}
				}
				//$TableRow = $TableData[$i];
				$Row = array($i, iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][2]), iconv('UTF-8', 'windows-1251',$sheets[0][$i+1][4]), $TovarName, $TovarPos);
				echo $Table->TableRow($Row);
			}
			echo $Table->TableEnd();
		echo "</p>";
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