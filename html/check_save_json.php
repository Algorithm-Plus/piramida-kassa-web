<?php
include ("config.php");
$DataModule = new DataModule($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]);
$check_list = $DataModule->GetCheckList($_POST['Date1'], $_POST['Date2']);
//echo '<pre>';
foreach ($check_list as $check){
    //print_r($check);
    $list = $DataModule->GetCheckById($check[2]);
    //print_r($list);
    foreach ($list as $key => $line){
        //Перша шапка
        if ($key==1){
            $data = [
                'STORE_ID'  => $_SESSION["AptekaList"][$_SESSION["CurrentApteka"]]->StoreID,
                'REC_DATE'  => date('d.m.Y H:i:s',strtotime($check[1])),
                'NUMB'      => $check[2],
                'FORE_NUMB' => 0, //TODO: Привязатися до номеру чека з апарата
                'SELLER'    => 1, //TODO: Зробити табличку продавців
                'KASSA'     => 1, //TODO: Зробити табличку кас
                'PAY_DESK'  => 1,
                'DISCOUNT'  => $check[8],
                'DISCOUNT_ID'=> 0,
                'DISCOUNT_SUM'=>$check[8],
                'PARTNER_ID' => 0,
                'TAX_TOTAL' => $check[6],
                'TAX_TOTAL_FULL' => $check[6],
                'PAY_CASH'  => $check[7],
                'PAY_CARD'  => $check[9],
                'PAY_CREDIT'=> $check[10],
                'COMMENT'   =>'',
                'TOVAR_LIST'=> []
            ];
        } else {
            //print_r($line);
            $data['TOVAR_LIST'][$key-2] = [
                'ID' => $line[1],
                'SCODE' => $line[11],
                'SNOMEN' =>  mb_convert_encoding($line[2], "utf-8", "windows-1251"),
                'SBARCODE' => $line[5],
                'SPART' => $line[8],
                'SSERIES' => mb_convert_encoding($line[4], "utf-8", "windows-1251"),
                'PRICE' => $line[6],
                'PRICE_TAX' => $line[7],
                'CHECK_PRICE' => $line[6],
                'QUANT' => $line[9],
                'FULL_PRICE' => $line[6],
                'SALE_PRICE' => $line[10],
                'SALE_TAXSUM' => $line[7]*$line[9],
                'CASHPAYSUM' => ($check[7]>0)?$line[6]*$line[9]:0,
                'CARDPAYSUM' => ($check[9]>0)?$line[6]*$line[9]:0,
                'DISCONT_SUM' => $line[12],
                'CREDIT_SUM' => ($check[10]>0)?$line[6]*$line[9]:0,
                'ID_RETURN_REC' => ($line[13]>0)?$line[13]:0,
                'SDISCONT' => 0
            ];
        }



    }
}
echo json_encode([$data]);
header("Content-Type: application/json");
$filename = ($_SESSION["AptekaList"][$_SESSION["CurrentApteka"]])->MnemoCod.'_'.$_POST['Date1'].'-'.$_POST['Date2'].'.json';
//echo sys_get_temp_dir().'/'.$filename;
$fp = fopen(sys_get_temp_dir().'/'.$filename, 'w');
fwrite($fp, json_encode([$data]));
fclose($fp);
header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
header("Cache-Control: public"); // needed for internet explorer
header("Content-Transfer-Encoding: Binary");
header("Content-Length:".filesize(sys_get_temp_dir().'/'.$filename));
header("Content-Disposition: attachment; filename=".$filename);
//readfile(sys_get_temp_dir().'/'.$filename);

?>

