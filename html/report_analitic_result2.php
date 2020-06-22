<?php
include("page_top.php");

echo "<script language='JavaScript'>";
echo "
	function SelectAll(ObjStatus,EllementCount)
	{
		window.status = 'start...';
		for (i=1; i<=EllementCount; i++)
		{	
			window.status = i;
			obj =  document.getElementById('ch'+i); 
			obj.checked = ObjStatus.checked;	
		}	
	}
	";
echo "</script>";

echo "<font class='Midle'>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
echo "<form name='zero' METHOD=post ACTION='report_analitic_result21.php' target=''>";
echo "<BR>";
echo "<table align='center'>";

//Таблиця з залишками
	echo "<TR>";
		echo "<TD align='center'>";
		$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
		$TableData = $DataModule->ViewAnalitic($_REQUEST["Analitic"]);
		//echo "<font class='Midle'>".$FormTovarItem4."<BR>".$FormTovarSeriya2.": ".date("d.m.Y H:i")."<BR>".$_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->FullName."</font>";
		//Шапка таблиці
		$Table = new Table();
		$Table->NextRowIsHeader(true);
		
		echo $Table->TableBegin(count($TableData[1])+2);//+1 на чекбокс для видалення, +1 для номера п/п
		$Row = array($FormPreparatItem9, $FormPreparatItem4, $FormTovarItem16, $FormPreparatItem1, $FormTovarItem17, $FormReportItem18);
		$Table->ChangeAlign(1,'Center'); //Колонки з к-тю по правому краю
		$Table->ChangeAlign(2,'Center');
		$Table->ChangeAlign(3,'Left');
		$Table->ChangeAlign(4,'Left');
		$Table->ChangeAlign(5,'Center');
		$Table->ChangeAlign(6,'Center');
		echo $Table->TableRow($Row);
		$Table->NextRowIsHeader(false);
			for ($i=1; $i<=count($TableData); $i++)
			{
				$TableRow = $TableData[$i];       //checked
				//$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].", ".$TableRow[5].");'>".$TableRow[1]."</a>";
				$CheckBox = "<input type='checkbox' name='ch".$i."' id='ch".$i."' > 
				<INPUT type=hidden id='id_tov".$i."' name='id_tov".$i."' value='".$TableRow[3]."'>
				<INPUT type=hidden id='id_sh".$i."' name='id_sh".$i."' value='".$TableRow[4]."'>
				<INPUT type=hidden id='id_an".$i."' name='id_an".$i."' value='".$TableRow[5]."'>
				";
				$Row = array($i, $TableRow[1], $TableRow[6], $TableRow[2], $TableRow[7], $CheckBox);
				echo $Table->TableRow($Row);
			}
			//$ButtonDel = "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormReportItem18."'>";
			//$Row = array("", "", "", $ButtonDel);
			//echo $Table->TableRow($Row);
			echo $Table->TableEnd();
			echo $Table->ShowMenu();
		echo "</TD>";
	echo "</TR>";
	echo "<tr><td></td><td></td><td></td><td></td><td></td><td><input type='checkbox' NAME=chAll OnClick='SelectAll(this,".count($TableData).");' LANGUAGE='Javascript'></td></tr>";
echo "</table>";
echo "<p align='center'>";
echo "<INPUT type=hidden id='Analitic' name='Analitic' value='".$_REQUEST["Analitic"]."'>";
echo "<INPUT type=hidden name='RecCount' value=".count($TableData).">"; //Кількість записів (щоб знову не шукати)
echo "<BR>";
echo "<INPUT class='ButtonPasive' TYPE='Submit'  value='".$FormReportItem18."'>";
echo "</p>";
echo "</form>";
						
include("page_bottom.php");
?>