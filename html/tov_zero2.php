<?php
include ("page_top.php");
echo "<script language='JavaScript'>";
	echo "
	function PutBarCode(BarCode, Ident)
	{
		window.opener.document.getElementById('Shtrih').value=BarCode;
		window.opener.document.getElementById('Ident').value=Ident;
	}
	";
	//window.opener.oborot.Shtrih.value=BarCode;
	//echo "parent.frames['frametop'].location.reload();";
	//echo "alert(sh);";
	//echo "window.opener.oborot.Shtrih.value='123';";

	//Виділяю всі елементи // window.status = i; for (i=0; i<EllementCount; i++)
	echo "
	function SelectAll(ObjStatus,EllementCount)
	{
		window.status = 'start...';
		for (i=1; i<=EllementCount; i++)
		{	
			window.status = i;
			var obj =  document.getElementById('ch'+i); 
			obj.checked = ObjStatus.checked;	
		}	
	}
	";
	/*
	function SelectAll()
	{
		if (Chk.name=='chAll')
		{
			var EllementCount =  ".count($TableData).";
			for (i=0; i<EllementCount; i++)
			{
				var obj =  document.getElementsByName('ch'+i); 
				obj[0].disabled = !Chk.checked;
			}
		}
	}
*/
echo "</script>";

//---------------------------Дані на сторінці
						echo "<form name='zero' METHOD=post ACTION='tov_oborot_2.php' target='_blank'>";
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з залишками
							echo "<TR>";
								echo "<TD align='center'>";
								  $Total = 0; //Загальна сума по позиціям
									$TotalIN = 0;
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetPriceWithEDRPOU($_REQUEST["edrpou"]);	

									
									echo "<font class='Midle'>".$FormTovarItem4."<BR>".$FormTovarSeriya2.": ".date("d.m.Y H:i")."<BR>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
									//Шапка таблиці
									$Table = new Table();
									$Table->NextRowIsHeader(true);
							    echo $Table->TableBegin(count($TableData[1])+2);//+1 на чекбокс для обороткі +1 на номер
									$Row = array($FormPreparatItem9, $FormPreparatItem4, $FormPreparatItem1, $FormTovarItem20, $FormTovarOborotItem5, $FormPreparatItem2, $FormPreparatItem3, $FormPreparatItem8, $FormPreparatItem10, $FormPreparatItem8);
									$Table->ChangeAlign(4,'Center');
									$Table->ChangeAlign(5,'Center');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
									$Table->ChangeAlign(9,'Right');
									$Table->ChangeAlign(10,'Right');
									echo $Table->TableRow($Row);
									
									$Table->NextRowIsHeader(false);
									//$Table->AddRow($Row);
									//$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці	
									$nom = 1; //Номер по-порядку
									
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];

							      //$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].",\"\");'>".$TableRow[1]."</a>";
										//$CheckBox = "<input type='checkbox' name='ch".$i."'><INPUT type=hidden id='sh".$i."' name='sh".$i."' value='".$TableRow[1]."'>";
										$Row = array($nom, $TableRow[1], $TableRow[2], $TableRow[3], $TableRow[4], $TableRow[5], $TableRow[6], number_format($TableRow[7],2,",",""), $TableRow[8], number_format($TableRow[9],2,",",""));
										$Total = $Total + $TableRow[7];
										$TotalIN = $TotalIN + $TableRow[9];
										//$Table->AddRow($Row);
										echo $Table->TableRow($Row);
										$nom = $nom + 1;
									}
									
									//echo $Table->Show();
										//Чекбокс який виділяє всі товари для оборотки
									//$CheckBox = "<input type='checkbox' NAME=chAll OnClick='SelectAll(this,".count($TableData).");' LANGUAGE='Javascript'>";
									$Table->NextRowIsHeader(true);
									$Row = array("","", "", "", "", "", "", number_format($Total,2,",",""), "", number_format($TotalIN,2,",",""));
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									echo $Table->TableEnd();
									echo $Table->ShowMenu();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
						echo "<p align='center'>";


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