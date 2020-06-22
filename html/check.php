<?php
include ("config.php");
$_SESSION["CurrentSection"]=4;
echo "<script language='JavaScript'>";
	echo "parent.frames['frametop'].location.reload();";
echo "</script>";

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
						echo "<table>";

							echo "<TR valign='top'>";
							//Чекі за сьогодні
								echo "<TD>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetCheckList(date("d.m.Y"), date("d.m.Y"));

									$Table = new Table();
									$Row = array($FormCheckHeader1, $FormCheckHeader2, $FormCheckHeader3, $FormCheckHeader4, $FormCheckHeader5, $FormCheckHeader6, $FormCheckHeader7, $FormCheckHeader8, $FormCheckHeader9, $FormCheckHeader10,'');
									$Table->AddRow($Row);
									$Table->LastRowIsTableHeader();//Останній доданий рядок був заголовком в таблиці
									for ($i=1; $i<=count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										$Link = "<a class='SimpleColor' target='_blank' href='get_check.php?id=". $TableRow[2]."'>". $TableRow[1]."</a>";
										if ($TableRow[10]>0)
										{
											$ChangePayLink ="";
										}
										else
										{
											//$ChangePayLink = "<a class='ButtonPasive' href='check_chpytype.php?id=".$TableRow[2]."' target=_blank>".$FormKassaItem5." </a>";
											$ChangePayLink = "";
										}
										$Row = array($Link, $TableRow[2], $TableRow[3], $TableRow[5], $TableRow[6], $TableRow[7], $TableRow[8], $TableRow[9], number_format($TableRow[10],2,",",""), $TableRow[11],$ChangePayLink);
										$Table->AddRow($Row);
										if ($TableRow[11]<>"+") //Не надрукований чек
										{
											$Table->ChangeLastRowDark();
										}
									}

									$TableData = $DataModule->GetTotalReceiptsSum(date("d.m.Y")." 00:00:00", date("d.m.Y")." 23:59:59");
									$Row = array($lnTotal,"","",$TableData[3],$TableData[4],$TableData[5],$TableData[6],$TableData[7],"","");
									$Table->AddRow($Row);
									$Table->ChangeLastRowDark();

									$Table->ChangeAlign(4,'Right'); //Колонки з сумами по правому краю
									$Table->ChangeAlign(5,'Right');
									$Table->ChangeAlign(6,'Right');
									$Table->ChangeAlign(7,'Right');
									$Table->ChangeAlign(8,'Right');
								  $Table->ChangeAlign(9,'Right');
									$Table->ChangeAlign(10,'Center');

									$TableInfo = new TableInfo($FormCheckItem1); //Створюю і вивожу інформативний блок з таблицею $Table->Show()
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Table->Show());
									echo $TableInfo->Show();
									echo $Table->ShowMenu();
								echo "</TD>";
								echo "<TD valign='top'>";
									$Form = "
									<form name='check' id='form_check' method=post target='_blank' action='checkdata.php'>
										<font> ".$From." </font><input type=text class='Date' name='Date1' value='01.".date("m.Y")."'><font>
										".$To."</font><input type=text class='Date' name='Date2' value='".date("d.m.Y")."'>
										<BR>
										<input type=button class='ButtonPasive' value='".$FormKassaItem6."' onclick='ShowBayDate();'>
										<br><br>
										<input type='button' class='ButtonPasive' value='Зберегти' onclick='SaveToJson()'>										
									</form>									
									
									";


									$TableInfo = new TableInfo($FormCheckItem2);
									$TableInfo->ChangeBorder(2);
									$TableInfo->AddBody($Form);
									echo $TableInfo->Show();
								echo "</TD>";

							echo "</TR>";
						echo "</table>";
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
?>

<script>
    function SaveToJson(){
        form = document.getElementById('form_check');
        form.action = 'check_save_json.php';
        form.submit();
    }

    function ShowBayDate(){
        form = document.getElementById('form_check');
        form.action = 'checkdata.php';
        form.submit();
    }
</script>

<?php
echo "</BODY>";
?>