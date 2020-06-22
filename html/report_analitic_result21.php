<?php
include("page_top.php");
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця
							echo "<TR>";
								echo "<TD align='center'>";
									echo "<font class='Warning'>".$FormReportItem19 ."</font>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$DataModule->Connect();
									for ($i=1; $i<=$_REQUEST["RecCount"]; $i++)
									{
										if (isset($_REQUEST["ch".$i]))
										{
											$nomen = "id_tov".$i;
											$barcode = "id_sh".$i;
											$analitic = "id_an".$i;
											//echo $_REQUEST["ch".$i]."=".$_REQUEST[$nomen]." = ".$_REQUEST[$barcode]." = ".$_REQUEST["analitic"]."<BR>";
											$TableData = $DataModule->DeleteAnalitic($_REQUEST[$analitic]);
										}
									}
									$DataModule->Disconnect();
								echo "</TD>";
							echo "</TR>";
						echo "</table>";
include("page_bottom.php");
?>