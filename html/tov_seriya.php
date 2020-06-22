<?php
include ("config.php");

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
						echo "<BR>";
						echo "<table align='center'>";

						//Таблиця з залишками
							echo "<TR>";
								echo "<TD align='center'>";
									$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
									$TableData = $DataModule->GetSeriya($_REQUEST["Date1"]);
									
									echo "<font class='Midle'>".$FormTovarSeriya1."</font>";
									$Table = new Table();
									echo $Table->TableBegin(count($TableData[1]));
									$Row = array($FormPreparatItem4, $FormPreparatItem1, $FormTovarOborotItem4, $FormTovarSeriya3, $FormTovarSeriya4, $FormPreparatItem2);
									$Table->ChangeAlign(4,'Center'); //Колонки з к-тю по правому краю
									$Table->ChangeAlign(5,'Center');
									$Table->ChangeAlign(6,'Right');

									$Table->NextRowIsHeader(true);
									echo $Table->TableRow($Row);
									$Table->NextRowIsHeader(false);
									
									for ($i=1; $i<count($TableData); $i++)
									{
										$TableRow = $TableData[$i];
										//$BarCode="<a class='SimpleColor' id='".$TableRow[1]."' href='#".$TableRow[1]."' OnClick='PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
										$BarCode="<a class='SimpleColor' href='javascript:PutBarCode(".$TableRow[1].");'>".$TableRow[1]."</a>";
										$DayToEnd = ($TableRow[4]=='-')?'<div data-target="day_id_'.$TableRow[7].'_'.$TableRow[1].'">
										    <a onclick="SetSeriya('.$TableRow[7].', \''.$TableRow[6].'\', \''.$TableRow[1].'\', '.str_replace(',','.', $TableRow[5]).')" href="#" title="Встановити">-</a>
										</div>':$TableRow[4];
										$Row = array($BarCode, $TableRow[2], '<div data-target="seriya_name_'.$TableRow[7].'_'.$TableRow[1].'">'.$TableRow[6].'</div>', '<div data-target="seriya_date_'.$TableRow[7].'_'.$TableRow[1].'">'.$TableRow[3].'</div>', $DayToEnd, $TableRow[5]);
										//$Table->AddRow($Row);
										echo $Table->TableRow($Row);
									}
									echo $Table->TableEnd();
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
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    function PutBarCode(BarCode)
    {
        window.opener.oborot.Shtrih.value=BarCode;
    }

    function SetSeriya(seriya_id, seriya_name, barcode, quant)
    {
        var date = prompt('Вкажіть дату', '<?=date('Y-m-d')?>');
        if (!isValidDate(date)){
            Play(42, 666);
            alert('Не коректна дата');
            return false;
        }

        $.ajax({
            url: '/api.php',
            type: 'POST',
            data: {
                'proc': 'save_seriya',
                'seriya_id': seriya_id,
                'seriya_name': seriya_name,
                'barcode': barcode,
                'quant': quant,
                'date': date
            },
            success: function (response) {
                //console.log(response);
                if (response.result) {
                    $('div[data-target="day_id_' + seriya_id + '_' + barcode + '"]').html('***');
                    $('div[data-target="seriya_name_' + seriya_id + '_' + barcode + '"]').html(response.series_name);
                    $('div[data-target="seriya_date_' + seriya_id + '_' + barcode + '"]').html(date);
                } else {
                    alert('Error');
                }
            },
            error(jqXHR, textStatus, errorThrown){
                alert('Error');
                console.log(jqXHR);
            },
        });
    }

    function isValidDate(d) {
        d = new Date(d);
        return !isNaN(d.getTime());
    }

    Play = (function() {

        var ctx = new(AudioContext || webkitAudioContext);

        return function(duration, freq, finishedCallback) {
            duration = +duration;
            if (typeof finishedCallback != "function") {
                finishedCallback = function() {};
            }
            var osc = ctx.createOscillator();
            osc.type = 0;
            osc.connect(ctx.destination);
            osc.frequency.value = freq;

            if (osc.start) osc.start();
            else osc.noteOn(0);

            setTimeout(
                function() {
                    if (osc.stop) osc.stop(0);
                    else osc.noteOff(0);
                    finishedCallback();
                }, duration
            );
        };
    })();
</script>

<?php
echo "</BODY>";
?>