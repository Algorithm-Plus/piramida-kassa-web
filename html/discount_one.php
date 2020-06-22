<?php
include("page_top.php");

$_SESSION["AptekaList"] = $AptekaList;
for ($i=1; $i<=count($_SESSION["AptekaList"]); $i++)
{
	$_SESSION["AptekaList"][$i]->Connect();
}
if ($_SESSION["AptekaList"][1]->Active==3) //Якщо зміг приконектитись до 3 аптеки, роблю її активною
	{
		$_SESSION["CurrentApteka"]=3;
	}
	else
	{
		$_SESSION["CurrentApteka"]=1;
	}

echo "<p align=center>";

echo "<BR>";

      $Form = "
      <form name='oborot' id='oborot' method=post target='_blank' action='discount_one_info.php'>
      <p align='left'>
        <font> ".$FormDiscountHeader1." </font><input type=text class='Edit 200' id='Shtrih' name='Shtrih' value='' AUTOCOMPLETE=OFF><BR>        
        
        </p>
        
        <input type=Submit class='ButtonPasive' value='".$FormTovarItem3."'>
      </form>";                  
      
      $TableInfo = new TableInfo($FormDiscountTitle2); 
      $TableInfo->ChangeBorder(2);
      $TableInfo->AddBody($Form);
      echo $TableInfo->Show();
echo "</p>";                      

echo "
  <script type='text/javascript'>
    doc = document.getElementById('Shtrih');
    doc.focus();
  </script>
";             
include("page_bottom.php");
?>