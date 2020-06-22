<?php
include ("config.php");


$_SESSION["AptekaList"] = $AptekaList;
for ($i=1; $i<=count($_SESSION["AptekaList"]); $i++)
{
	$_SESSION["AptekaList"][$i]->Connect();
}
//$_SESSION["AptekaList"][2]->Active = 0;
//$_SESSION["AptekaList"][3]->Active = 0;
if ($_SESSION["AptekaList"][1]->Active==1) //Якщо зміг приконектитись до першої аптеки, роблю її активною
	{
		$_SESSION["CurrentApteka"]=1;
	}
	else
	{
		$_SESSION["CurrentApteka"]=2;
	}
$_SESSION["CurrentSection"]=3;

echo "<script language='JavaScript'>";
echo "
	
	var xmlHttp = createXmlHttpRequestObject();
	function ChangeCurrentApteka(id) 
	{
		process(id);
		parent.frames['workarea'].location.reload();

	}

	function createXmlHttpRequestObject()
	{
		var xmlHttp;
		// IE
		if(window.ActiveXObject)
		{
			try
			{
				xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch (e)
			{
				xmlHttp = false;
			}
		}
		// Mozila
		else
		{
			try
			{
				xmlHttp= new XMLHttpRequest();
			}
			catch (e)
			{
				xmlHttp = false;
			}
		}

		if (!xmlHttp)
			alert('".$ErrorXmlHttp."');
			
		else
			return xmlHttp;
	}

	function process(id)
	{
		if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
		{
			xmlHttp.open('GET', 'changecurrentapteka.php?id='+id, false);
			xmlHttp.send(null);
		}
		else
			setTimeout('process()', 1000);
	}
";
echo "</script>";
//parent.frames['workarea'].location.reload();
//parent.frames['workarea'].location = 'kassa.php';
//alert('sdf');
//echo Count($AptekaList);

echo "<BODY class='Default'>";
echo "<LINK REL='stylesheet' href='template/".$_SESSION["Template"]."/style.css' type='text/css'>";
echo "<BR><BR>";
echo "<table align='center' class='' border=0 style='width:100%;'>";
	for ($i=1; $i<=Count($_SESSION["AptekaList"]); $i++)
	{
	echo "<TR align='center'><TD style='width:90%;'>";
		if ($_SESSION["AptekaList"][$i]->Active==1)
		{
			echo "<input type=Button class='ButtonSimple' style='width:90%;' id=".$i." value='".$_SESSION["AptekaList"][$i]->ButtonName."' onClick='ChangeCurrentApteka(".$i.");'>";
		}
		else
		{
			//echo "<input DISABLED type=Button class='ButtonSimple' style='width:90%;' value='".$_SESSION["AptekaList"][$i]->ButtonName."' onClick='ChangeCurrentApteka(".$i.");'>";
		}
	echo "</TD></TR>";
	}
echo "</table>";

echo "</BODY>";
?>