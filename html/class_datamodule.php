<?php
class DataModule
{
	var $CurrentApteka;
	var $DB;

	function __construct($CurrentApteka)
	{
		$this->CurrentApteka = $CurrentApteka;
	}

	function Connect()
	{
		$this->DB = ibase_connect(
			$this->CurrentApteka->IP.":".$this->CurrentApteka->BaseName,
			$this->CurrentApteka->User,
			$this->CurrentApteka->Password
			);
	}

	function Disconnect()
	{
		ibase_close($this->DB);
	}
	
	function Null2Nol($Value)
	{
		if ($Value==null)
		{
			return 0;
		}
		else
		{
			return $Value;
		}
	}

	
	function GetTotalReceiptsSum($Date1, $Date2) //Загальні суми по касі (виручка, знижки, ПДВ і т.д.)
	{
		$Sum = array(); //Загальну суму, суму знижки і т.д - в масив
		$this->Connect();
		/*
		$sql = "
			select sum(TOTAL) as SUM1 , sum(TAX_TOTAL) as SUM2, sum(TOTAL_FULL) as SUM3, sum(TAX_TOTAL_FULL) as SUM4, sum(CHECK_SUM) as SUM5, sum(DISCONT_SUM) as SUM6, sum(CARDPAYSUM) as SUM7
			from V_RECEIPTS
			where (REC_DATE>='".$Date1."') and (REC_DATE<='".$Date2."')
		";
		*/
		$sql = "
		select
		sum(TOTAL) as SUM1 ,
		sum(TAX_TOTAL) as SUM2,
		sum(TOTAL_FULL) as SUM3,
		 (
			select sum(TAX_TOTAL_FULL)
			from v_receipts
				where
				(PRINT_DATE is not null) and
				(REC_DATE>='".$Date1."') and
				(REC_DATE<='".$Date2."')
		 )as SUM4,
		sum(CHECK_SUM) as SUM5,
		sum(DISCONT_SUM) as SUM6,
		sum(CARDPAYSUM) as SUM7,
		(
				select sum(CHECK_SUM)
				from v_receipts
				where
				(PRINT_DATE is not null) and
				(REC_DATE>='".$Date1."') and
				(REC_DATE<='".$Date2."')
		) as SUM8,
		(
				select sum(TAX_TOTAL_FULL)
				from v_receipts
				where
				(PRINT_DATE is not null) and
				(REC_DATE>='".$Date1."') and
				(REC_DATE<='".$Date2."')
		) as SUM11,
		sum(CREDIT_SUM) as SUM10,
		(
				select sum(CASH)
				from v_receipts
				where
				(PRINT_DATE is not null) and
				(REC_DATE>='".$Date1."') and
				(REC_DATE<='".$Date2."')
		) as SUM12
		
		from V_RECEIPTS
		where (REC_DATE>='".$Date1."') and (REC_DATE<='".$Date2."')
		";

		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		$Sum[1] = $row["SUM1"]; //Загальна сума по позиціям без ПДВ
		$Sum[2] = $row["SUM2"]; //Загальна сума ПДВ
		$Sum[3] = $row["SUM3"]; //Загальна сума по позиціям з ПДВ
		$Sum[4] = $row["SUM4"]; //Загальна сума ПДВ 2
		$Sum[5] = $row["SUM5"]; //Сума по розрахунку готівкою
		//if ($row["SUM5"]==null){$Sum[5]=0;} else {$Sum[5]=$row["SUM5"];}
		$Sum[6] = $row["SUM6"]; //Сума по знижкам
		$Sum[7] = $row["SUM7"]; //Сума по безготівковому розрахунку
		$Sum[8] = $row["SUM8"]; //Сума по апарату
		$Sum[9] = number_format($Sum[5]-$Sum[8],2,",",""); //Сума без апарату
		//Повинно спрацьовувати правило TOTAL_FULL = CHECK_SUM + DISCONT_SUM + CREDIT_SUM (SUM3 = SUM5 + SUM6 + SUM7)
		$Sum[10] = $row["SUM10"]; //Сума кредиту по страхових комп.
		$Sum[11] = $row["SUM11"]; //Сума ПДВ фіскальна
		$Sum[12] = $row["SUM12"]; //Сума фіскальна cash
		return $Sum;
		$this->Disconnect();
	}
  
  function GetTotalReceiptsSumShort($Date1, $Date2) //Загальні суми по касі (виручка, знижки, ПДВ і т.д.)
	{
		$Sum = array(); //Загальну суму, суму знижки і т.д - в масив
		$this->Connect();
		
		$sql = "    
    select sum(CASHPAYSUM) as CASHPAYSUM, sum(CARDPAYSUM) as CARDPAYSUM , sum(CREDIT_SUM) as CREDIT_SUM
    from T_RECEIPTS
    where (REC_DATE>='".$Date1." 00:00') and (REC_DATE<='".$Date2." 23:59')
		";
				
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		$Sum[1] = $row["CASHPAYSUM"]; 
		$Sum[2] = $row["CARDPAYSUM"]; 
		$Sum[3] = $row["CREDIT_SUM"]; 		
		return $Sum;
		$this->Disconnect();
	}

	function GetReceiptsCount($Date1, $Date2) //Кількість рецептів
	{
		$this->Connect();
		$sql = "
			select count(TOTAL) as CNT1
			from V_RECEIPTS
			where (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')
		";
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		$Sum = $row["CNT1"];
		return $Sum;
		$this->Disconnect();
	}

	function GetLastPreparats($Count) //Останні продані товари
	{
		$this->Connect();
		$sql = "
			select SNOMEN_NAME, NQUANT, NPRICE
			from 
				( select SNOMEN_NAME, NQUANT, NPRICE
					from V_REC_SPEC
					order by NID_RECEIPT DESC
				)
			rows ".$Count."
		";
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		//$row=ibase_fetch_assoc($result);
		return $result;
		$this->Disconnect();
	}
	
	function GetLastPreparatsOnDate($Date1, $Date2, $EDRPOU) //Останні продані товари
	{
		$this->Connect();
		/*
		$sql = "
		select SBARCODE, SNOMEN_NAME, sum(NQUANT) as NQUANT, NPRICE, NPRICEIN, sum(NSALE_PRICE) as NSALE_PRICE, sum(NSALE_TAXSUM) as NSALE_TAXSUM, CREDIT_SUM as CREDIT_SUM, NAME as NAME
  from 
                ( select
          V_REC_SPEC.SBARCODE,
                    V_REC_SPEC.SNOMEN_NAME,
                    V_REC_SPEC.NQUANT,
                    V_REC_SPEC.NPRICE,
          V_REC_SPEC.NPRICEIN,
                    V_REC_SPEC.nsale_price,
          V_REC_SPEC.nsale_taxsum,
                    v_receipts.REC_DATE,
                    v_receipts.credit_sum,
      v_RECEIPTS.NUMB,
      t_contragent.NAME

                    from V_REC_SPEC, v_receipts, t_barcode, t_producer, t_contragent
                    where (v_rec_spec.nid_receipt=v_receipts.id) and (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')
                           and t_barcode.ID = V_REC_SPEC.NID_BARCODE and t_producer.ID = t_barcode.ID_PRODUCER and t_contragent.ID = t_producer.ID_CONTRAGENT
      
                    order by V_REC_SPEC.SNOMEN_NAME
                )
  group by SBARCODE, SNOMEN_NAME, NPRICE, NPRICEIN, CREDIT_SUM, NAME
	";
		*/

/*select REC_DATE, NUMB, NAME_USERS, N.code as NOMEN_CODE, N.name as SNOMEN_NAME, BARCODE, c1.name as PRODUCER, c2.NAME as VENDOR, PARTNUMB,
            s.CODE as SERIE, QUANT, pc.PRICE, r.TOTAL_FULL, DISCONT, SALE_PRICE, TOTAL, PRICEIN, TAX_TOTAL, pdv.rate*/
/*select N.code as NOMEN_CODE, N.name as SNOMEN_NAME, BARCODE, c1.name as PRODUCER, sum(QUANT) as QUANT, max(pc.PRICE) as PRICE, sum(r.TOTAL_FULL) as TOTAL_FULL, sum(SALE_PRICE) as SALE_PRICE, sum(TOTAL) as TOTAL, PRICEIN*/

		$sql = "
		select REC_DATE, NUMB, NAME_USERS, N.code as NOMEN_CODE, N.name as SNOMEN_NAME, BARCODE, c1.name as PRODUCER, c2.NAME as VENDOR, PARTNUMB,
            s.CODE as SERIE, QUANT, pc.PRICE, r.TOTAL_FULL, DISCONT, SALE_PRICE, TOTAL, PRICEIN, TAX_TOTAL, pdv.rate

    from T_REC_SPEC t, T_RECEIPTS r, T_NOMEN N, T_USERS u, T_BARCODE b, T_PART p, T_PRICE pc, T_SERIES s,
         T_PRODUCER pr, T_VENDOR v, T_CONTRAGENT c1, T_CONTRAGENT c2, t_pdvrate pdv

    where r.id = t.id_receipt
    and cast(REC_DATE as date) between '".$Date1."' and '".$Date2."'
    and u.ID = r.SELLER
    and b.id = t.id_barcode
    and n.id = b.id_nomen
    and s.id = t.id_series
    and p.id = t.id_part
    and pc.id = t.id_price
    and pr.id = b.id_producer
    and c1.id = pr.id_contragent
    and v.id = p.id_vendor
    and c2.id = v.id_contragent
		and pc.id_taxrate=pdv.id
		";
		if ($EDRPOU<>"")
		{
			$sql=$sql." and c2.EDRPOU='".$EDRPOU."'";
		}
		//$sql = $sql." group by NOMEN_CODE, SNOMEN_NAME, BARCODE, PRODUCER, PRICEIN ";
		$sql = $sql." order by SNOMEN_NAME, BARCODE";
		//echo $sql."<BR><BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			//$Sum[1] = $row["NUMB"];
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["PRODUCER"];
			$Sum[3] = $row["VENDOR"];
			$Sum[4] = $row["SNOMEN_NAME"];
			$Sum[5] = $row["QUANT"];
			if ($row["PRICE"]>0)
			{
				$Sum[6] = $row["PRICE"];
			}
			else
			{
				//$Sum[6] = $row["FULL_PRICE"];
				$Sum[6] = 0;
			}
			$Sum[7] = $Sum[5]*$Sum[6];
			if ($row["PRICEIN"]>0)
			{
				$Sum[8] = $row["PRICEIN"];
			}
			else
			{
				$Sum[8] = 0;
			}
			$Sum[9] = $Sum[5]*$Sum[8];
			$Sum[10] = $row["RATE"];
			$Sum[11] = $row["REC_DATE"];      
			$Sum[12] = $this->RestByBarCode($row["BARCODE"]);
			$Sum[13] = $row["SERIE"];
			$Sum[14] = $row["NOMEN_CODE"];
			$Sum[15] = $row["NAME_USERS"];
			$Sum[16] = (1-$row["TOTAL"]/$row["TOTAL_FULL"])*100;
			$Sum[17] = $row["NUMB"];
      $Sum[18] = $row["PARTNUMB"];
			
			
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	

	function GetSumFromSeller($Date1, $Date2) //Розбивка сум по продавцям
	{
		$this->Connect();
		$sql = "
			select SELLER_NAME, sum(TOTAL) as SUM1, count(TOTAL) as COUNT1
			from V_RECEIPTS
			where (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')
			group by SELLER_NAME
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SELLER_NAME"];
			$Sum[2] = $row["SUM1"];
			$Sum[3] = $row["COUNT1"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetSumFromDiscount($Date1, $Date2, $Sort) //Розбивка сум по знижкам
	{
		$this->Connect();
				$sql = "
		select dc.cardcode, con.name, sum(DISCONT_SUM) as SUM1, avg(dc.discont) as DISCONT_VAL, CARDNAME, sum(TOTAL_FULL) as TOTAL_FULL, sum(CHECK_SUM) as CHECK_SUM
		from V_RECEIPTS r, t_receipts_card rc, t_discont_card dc, t_contragent con
		where	
		(r.REC_DATE>='".$Date1." 00:00') and
		(r.REC_DATE<='".$Date2." 23:59') and
		(DISCONT_VAL<>'') and
		(rc.id_receipts=r.id) and
		(rc.id_discont_card=dc.id) and
		(dc.id_company_owner=con.id)		
		group by CARDNAME, con.name, cardcode
		";
		if ($Sort == 2)	{	$sql = $sql." order by CARDCODE";	}
		if ($Sort == 3)	{	$sql = $sql." order by SUM1 desc";	}
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["CARDCODE"];
			$Sum[2] = $row["NAME"];
			$Sum[3] = number_format($row["SUM1"],2,",","");
			$Sum[4] = $row["DISCONT_VAL"];
			$Sum[5] = $row["CARDNAME"];
			$Sum[6] = $this->CurrentApteka->MnemoCod;
			$Sum[7] = number_format($row["TOTAL_FULL"],2,",","");
			$Sum[8] = number_format($row["CHECK_SUM"],2,",","");
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetSumFromDiscountAllApteks($Date1, $Date2, $Sort, $AptekaList, $CardCode=null) //Розбивка сум по знижкам по всіх аптеках
	{
		//Підготуємо тимчасову таблицю
		$ArrayFactory = new ArrayFactory();
		$FieldList = array();
		$FieldList[1] = "VARCHAR(8)"; //код
		$FieldList[2] = "VARCHAR(55)"; //Прізвище
		$FieldList[3] = "DECIMAL(10,2)";
		$FieldList[4] = "DECIMAL(10,2)";
		$FieldList[5] = "VARCHAR(20)";
		$FieldList[6] = "VARCHAR(2)";
		$FieldList[7] = "DECIMAL(10,2)";
		$FieldList[8] = "DECIMAL(10,2)";
		$FieldIndex = 9;

		//Групуємо по коду
		$GroupField = array();
		$GroupField[1] = 1;
		$GroupField[2] = 2;
		$GroupField[3] = 4;
		$GroupField[4] = 5;
		$GroupField[5] = 6;
		$GroupFieldIndex = 6;
		//Сумуємо суму
		$SumField = array();
		$SumField[1] = 3;
		$SumField[2] = 7;
		$SumField[3] = 8;
		$SumFieldIndex = 4;  
		
		//Сортування по полю № 2 (Прізвище)
		if ($Sort == 1) {$OrderField = 2;}
		//Сортування по полю № 1 (Код)
		if ($Sort == 2) {$OrderField = 1;}
		//Сортування по полю № 3 (Сума)
		if ($Sort == 3) {$OrderField = 3;}
		
		//Створюємо тимчасову таблицю
		$ArrayFactory->TempTableCreate($FieldList);
		
		//Вибераємо дані з баз для всіх активних аптек
		$DataTableAll = array();
		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$this->CurrentApteka = $AptekaList[$j];
				$this->Connect();
				$sql = "
					select D.cardcode, con.name, sum(DISCONT_SUM) as SUM1, CARDNAME, sum(TOTAL_FULL) as TOTAL_FULL, sum(CHECK_SUM) as CHECK_SUM, max(DISCONT_VAL) as DISCONT_VAL
					from V_RECEIPTS, T_DISCONT_CARD D, t_client_card cl, t_contragent con
					where
					(REC_DATE>='".$Date1." 00:00:00') and
					(REC_DATE<='".$Date2." 23:59:59') and
					(DISCONT_VAL<>0) and
					(DISCONT_ID=D.ID) and
					(D.ID=cl.id_card) and
					(con.id=cl.id_contragent)";
					if ($CardCode<>null)
					{
						$sql = $sql."and (D.cardcode='".$CardCode."')";
					}
					$sql = $sql."
					group by CARDNAME, con.name, cardcode			
				";
				//echo $sql;
				$Table = array(); //Для двовимірного масиву
				$i = 1;
				$result = ibase_query($this->DB, $sql);
				while ($row=ibase_fetch_assoc($result))
				{
					$Sum = array();
					$Sum[1] = $row["CARDCODE"];
					$Sum[2] = $row["NAME"];
					$Sum[3] = number_format($row["SUM1"],2,".","");
					$Sum[4] = $row["DISCONT_VAL"];
					$Sum[5] = $row["CARDNAME"];
					$Sum[6] = "1";//$this->CurrentApteka->MnemoCod;
					$Sum[7] = number_format($row["TOTAL_FULL"],2,".","");
					$Sum[8] = number_format($row["CHECK_SUM"],2,".","");
					$Table[$i] = $Sum;
					$i++;
				}
				$ArrayFactory->ArrayAdd($FieldList, $Table);
				$this->Disconnect();
			}
		}
		$Table = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
		$ArrayFactory->TempTableDrop();
		return $Table;
	}
	
	function ImportByBarCode($BarCode, $Date1, $Date2) //прихід по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT
			from t_import_doc_spec t1, t_import_doc t2
			where
    (t1.pid=t2.id) and
    (t1.barcode='".$BarCode."') and
    (t2.date_doc>='".$Date1."') and
    (t2.date_doc<='".$Date2."')
		";
		//echo $sql;
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
		}
		//$this->Disconnect();
		return $Sum;
	}
	
	function ImportByBarCodeWithPrice($BarCode, $Date1, $Date2) //прихід по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT, t1.pricein as PRICEIN
			from t_import_doc_spec t1, t_import_doc t2
			where
    (t1.pid=t2.id) and
    (t1.barcode='".$BarCode."') and
    (t2.date_doc>='".$Date1."') and
    (t2.date_doc<='".$Date2."')
		group by t1.PRICEIN
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$Sum = array();
		$Sum[1] = 0;
		$Sum[2] = 0;
		$i = 1;
		//$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum[1] = $row["QUANT"];
				$Sum[2] = $row["PRICEIN"];
			}
			
		}
		$Table[$i] = $Sum;
		$i++;
		//$this->Disconnect();
		//echo $Sum."<BR>";
		return $Table;
	}
	
	function ImportByBarCodeWithSum($BarCode, $Date1, $Date2) //прихід по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT, sum(t1.price*QUANT) as SUMA
			from t_import_doc_spec t1, t_import_doc t2
			where
    (t1.pid=t2.id) and
    (t1.barcode='".$BarCode."') and
    (t2.date_doc>='".$Date1."') and
    (t2.date_doc<='".$Date2."')

		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$Sum = array();
		$Sum[1] = 0;
		$Sum[2] = 0;
		$i = 1;
		//$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum[1] = $row["QUANT"];
				$Sum[2] = $row["SUMA"];
			}
			
		}
		$Table[$i] = $Sum;
		$i++;
		//$this->Disconnect();
		//echo $Sum."<BR>";
		//print_r($Table);
		return $Table;
	}
	
	function ExportByBarCode($BarCode, $Date1, $Date2) //переміщення по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT
			from t_export_doc_spec t1, t_export_doc t2
			where
    (t1.pid=t2.id) and
    (t1.barcode='".$BarCode."') and
    (t2.date_doc>='".$Date1." 00:00:00') and
    (t2.date_doc<='".$Date2." 23:59:59')
		";
		//echo $sql;
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
		}
		//$this->Disconnect();
		return $Sum;
	}

	function ExportByBarCodeWithSum($BarCode, $Date1, $Date2) //переміщення по штриху
	{
		//$this->Connect();
		$sql = "
		
		select sum(t1.QUANT) as QUANT, sum(t1.price*t1.QUANT) as SUMA
          from t_export_doc_spec t1, t_export_doc t2
            where
    (t1.pid=t2.id) and
    (t1.barcode='".$BarCode."') and
    (t2.date_doc>='".$Date1." 00:00:00') and
    (t2.date_doc<='".$Date2." 23:59:59')
		
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$Sum = array();
		$Sum[1] = 0;
		$Sum[2] = 0;
		$i = 1;
		//$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum[1] = $row["QUANT"];
				$Sum[2] = $row["SUMA"];
			}
			
		}
		$Table[$i] = $Sum;
		$i++;
		//$this->Disconnect();
		//echo $Sum."<BR>";
		return $Table;
	}

	function SaleByBarCodeWithPrice($BarCode, $Date1, $Date2) //продаж по штриху
	{
		//$this->Connect();
		$sql = "
		select sum(QUANT) as QUANT, t4.pricein as PRICEIN
		from t_rec_spec t1, t_receipts t2, t_barcode t3, t_price t4
		where
    (t1.id_receipt=t2.id) and
    (t1.id_barcode=t3.id) and
	  (t4.id = t1.id_price) and
    (t3.barcode='".$BarCode."') and
    (t2.rec_date>='".$Date1." 00:00:00') and
    (t2.rec_date<='".$Date2." 23:59:59')
	group by t4.pricein
		";
		//echo $sql."<br>";
		$Table = array(); //Для двовимірного масиву
		$Sum = array();
		$Sum[1] = 0;
		$Sum[2] = 0;
		$i = 1;
		//$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum[1] = $row["QUANT"];
				$Sum[2] = $row["PRICEIN"];
			}
			
		}
		$Table[$i] = $Sum;
		$i++;
		//$this->Disconnect();
		//echo $Sum."<BR>";
		return $Table;
	}
	
	function SaleByBarCodeWithSum($BarCode, $Date1, $Date2) //продаж по штриху
	{
		//$this->Connect();
		$sql = "

		select sum(QUANT) as QUANT, sum(t1.sale_price) as SUMA
        from t_rec_spec t1, t_receipts t2, t_barcode t3
        where
    (t1.id_receipt=t2.id) and
    (t1.id_barcode=t3.id) and

    (t3.barcode='".$BarCode."') and
    (t2.rec_date>='".$Date1." 00:00:00') and
    (t2.rec_date<='".$Date2." 23:59:59')
		
		";
		//echo $sql."<br>";
		$Table = array(); //Для двовимірного масиву
		$Sum = array();
		$Sum[1] = 0;
		$Sum[2] = 0;
		$i = 1;
		//$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum[1] = $row["QUANT"];
				$Sum[2] = $row["SUMA"];
			}
			
		}
		$Table[$i] = $Sum;
		$i++;
		//$this->Disconnect();
		//echo $Sum."<BR>";
		return $Table;
	}
	
	function SaleByBarCode($BarCode, $Date1, $Date2) //продаж по штриху
	{
		//$this->Connect();
		$sql = "
		select sum(QUANT) as QUANT
		from t_rec_spec t1, t_receipts t2, t_barcode t3
		where
    (t1.id_receipt=t2.id) and
		(t1.id_barcode=t3.id) and
    (t3.barcode='".$BarCode."') and
    (t2.rec_date>='".$Date1." 00:00:00') and
    (t2.rec_date<='".$Date2." 23:59:59')
		";
		//echo $sql."<br>";
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
			
		}
		//$this->Disconnect();
		//echo $Sum."<BR>";
		return $Sum;
	}

	function SaleByName($Name, $Date1, $Date2) //продаж по назві
	{
		//$this->Connect();
		$sql = "
		select sum(QUANT) as QUANT
		from t_rec_spec t1, t_receipts t2, t_barcode t3, t_nomen t4
		where
    (t1.id_receipt=t2.id) and
    (t1.id_barcode=t3.id) and
		(t4.id=t3.id_nomen) and
    (t4.name ='".str_replace('\'','',trim($Name))."') and
    (t2.rec_date>='".$Date1." 00:00:00') and
    (t2.rec_date<='".$Date2." 23:59:59')
		";
		//echo "<BR><BR>".$sql;
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
		}
		return $Sum;
	}
	
	function SaleByNameTimes($Name, $Date1, $Date2) //кількість продаж по назві
	{
		//$this->Connect();
		$sql = "
		select count(QUANT) as QUANT
		from t_rec_spec t1, t_receipts t2, t_barcode t3, t_nomen t4
		where
    (t1.id_receipt=t2.id) and
    (t1.id_barcode=t3.id) and
		(t4.id=t3.id_nomen) and
    (t4.name = '".trim($Name)."') and
    (t2.rec_date>='".$Date1." 00:00:00') and
    (t2.rec_date<='".$Date2." 23:59:59')
		";
		//echo "<BR><BR>".$sql;
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
		}
		return $Sum;
	}
	
	function RestByBarCode($BarCode) //залишок по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT
			from p_get_inv_for_sale('".$BarCode."')
		";
		//echo $sql;
		$Sum = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = $row["QUANT"];
			}
		}
		//$this->Disconnect();
		return $Sum;
	}
	
	function RestByBarCodeWithPrice($BarCode) //залишок по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT, sum(PRICE) as PRICE
			from p_get_inv_for_sale('".$BarCode."')
		";
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$Sum = array();
		$Sum[1] =0;
		$Sum[2] =0;
		$Table[$i] = $Sum;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = array();
				$Sum[1] =$row["QUANT"];
				$Sum[2] =$row["PRICE"];
				$Table[$i] = $Sum;
				$i++;
			}
		}
		//$this->Disconnect();
		return $Table;
	}
	
	function RestByBarCodeWithSum($BarCode) //залишок по штриху
	{
		//$this->Connect();
		$sql = "
			select sum(QUANT) as QUANT, sum(PRICE*QUANT) as SUMA
			from p_get_inv_for_sale('".$BarCode."')
		";
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$Sum = array();
		$Sum[1] =0;
		$Sum[2] =0;
		$Table[$i] = $Sum;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if (isset($row["QUANT"]))
			{
				$Sum = array();
				$Sum[1] =$row["QUANT"];
				$Sum[2] =$row["SUMA"];
				$Table[$i] = $Sum;
				$i++;
			}
		}
		//$this->Disconnect();
		return $Table;
	}
		
	function RestByName($Name, $Group=0) //залишок по назві
	{
		//$this->Connect();
		if ($Group==1)
		{
			$sql = "
				SELECT sum(QUANT) as QUANT ";
		}
		else
		{		
		  $sql = "
			  SELECT * ";
		}			
		$sql = $sql."
      FROM T_INVENTORY INV, T_BARCODE BR, T_NOMEN N, T_SERIES SR, T_PRICE P, T_UNIT U
        WHERE INV.QUANT > 0 AND INV.ID_BARCODE  =  BR.ID AND N.NAME = '".$Name."'
          AND SR.ID = INV.ID_SERIES AND N.ID = BR.ID_NOMEN
          AND P.ID_BARCODE = INV.ID_BARCODE AND P.ID_PART = INV.ID_PART AND P.ID_SERIES = INV.ID_SERIES
                AND P.ID = (SELECT MAX(T1.ID) FROM T_PRICE T1 WHERE T1.ID_BARCODE = INV.ID_BARCODE AND
          T1.ID_PART = INV.ID_PART AND T1.ID_SERIES = INV.ID_SERIES AND T1.DBEGIN = (SELECT MAX(T.DBEGIN) FROM T_PRICE T WHERE T.ID_BARCODE = INV.ID_BARCODE AND
          T.ID_PART = INV.ID_PART AND T.ID_SERIES = INV.ID_SERIES AND T.DBEGIN <= '".date("d.m.Y")."'))
          AND U.ID = N.ID_UNIT
		";
		//echo $sql."<BR><BR>";
		//$Sum = 0;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		//print_r($this->DB); echo "<br>";
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			if ($row["QUANT"]>0)
			{
			  $Sum[1] = $row["QUANT"];
			}
			else
			{
				$Sum[1] = 0;
			}
			if ($Group==0) //Не групувати (якісь звіти використовують додаткову інфу)
			{
			  $Sum[2] = $row["DINCOME"];
			  $Sum[3] = $row["DEND"];
			  $Sum[4] = $row["NAME"];
			  $Sum[5] = $row["BARCODE"];
			  $Sum[6] = "SERIES";
			  $Sum[7] = $row["ID_PART"];
			  $Sum[8] = $row["PRICE"];
			}
			$Table[$i] = $Sum;
			$i++;
		}
		//$this->Disconnect();
		return $Table;
	}
	
	function RestANDSaleForPeriod($Date1, $Date2, $CodeList) //Залишки і продажі за період
	{
		$this->Connect();
		$sql="
		select SNAME, NDIVISIBILITY, SCODE, NID, sum(REST) as REST, sum(SALE) as SALE
		from
		(
		select v.SNAME, v.NDIVISIBILITY, v.SCODE, v.NID,
		(select sum(I.QUANT) from T_INVENTORY I where I.ID_BARCODE = B.ID) as REST,
		(select sum(R.QUANT) from T_REC_SPEC R where R.ID_BARCODE = B.ID AND R.ID_RECEIPT  in
		(select id from T_RECEIPTS RT
		where
			RT.REC_DATE>='".$Date1." 00:00:00' and
			RT.REC_DATE<='".$Date2." 23:59:59'
		)) as SALE
			from V_NOMEN v, T_BARCODE b
			where (B.ID_NOMEN = V.NID)
			";
			//echo "<BR>count= ".count($CodeList)."<BR>";
			//print_r($CodeList);
			if (count($CodeList>0))
			{
				$sql=$sql."
				and
				(
				v.SCODE in(".$CodeList[0].")
				";
				for ($i=1; $i<count($CodeList); $i++)
				{
					$sql=$sql."
					or v.SCODE in(".$CodeList[$i].")
					";
				}
				
				$sql=$sql.")";
			}
			$sql=$sql."
			)
			group by SNAME, NDIVISIBILITY, SCODE, NID
			";
			if ($CodeList<>'') //Для пошуку в інших аптеках
			{
			$sql=$sql."
			having sum(REST)>=1
			";
			}
			else
			{
				$sql=$sql."
				having sum(SALE)>sum(REST)
				";
			}
		//$this->log($sql);
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$p_sql = ibase_prepare($this->DB, $sql);
		$result = ibase_execute($p_sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["SCODE"];
			$Sum[3] = $row["SNAME"];
			$Sum[4] = $row["NDIVISIBILITY"];
			if ($row["REST"]>0)
			{
			  $Sum[5] = $row["REST"];
			}
			else
			{
				$Sum[5] = 0;
			}
			if ($row["SALE"]>0)
			{
				$Sum[6] = $row["SALE"];
			}
			else
			{
				$Sum[6] = 0;
			}
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function RestANDSaleForPeriodWithBarCode($Date1, $Date2, $CodeList='', $BarCodeList='') //Залишки і продажі за період з штрихами
	{
		$this->Connect();
		$sql="
		select BARCODE, SNAME, NDIVISIBILITY, SCODE, NID, sum(REST) as REST, sum(SALE) as SALE
		from
		(
		select b.BARCODE, v.SNAME, v.NDIVISIBILITY, v.SCODE, v.NID,
		(select sum(I.QUANT) from T_INVENTORY I where I.ID_BARCODE = B.ID) as REST,
		(select sum(R.QUANT) from T_REC_SPEC R where R.ID_BARCODE = B.ID AND R.ID_RECEIPT  in
		(select id from T_RECEIPTS RT
		where
			RT.REC_DATE>='".$Date1." 00:00:00' and
			RT.REC_DATE<='".$Date2." 23:59:59'
		)) as SALE
			from V_NOMEN v, T_BARCODE b
			where B.ID_NOMEN = V.NID
			";
			if ($CodeList<>'')
			{
				$sql=$sql."
				and v.SCODE in(".$CodeList.")
				and b.BARCODE in(".$BarCodeList.")
				";
			}
			$sql=$sql."
			)
			group by BARCODE, SNAME, NDIVISIBILITY, SCODE, NID
			";
			if ($CodeList<>'') //Для пошуку в інших аптеках
			{
			$sql=$sql."
			having sum(REST)>1
			";
			}
			else
			{
				$sql=$sql."
				having sum(SALE)>sum(REST)
				";
			}
		//$this->log($sql);
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$p_sql = ibase_prepare($this->DB, $sql);
		$result = ibase_execute($p_sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["NID"];
			$Sum[3] = $row["SCODE"];
			$Sum[4] = $row["SNAME"];
			$Sum[5] = $row["NDIVISIBILITY"];
			if ($row["REST"]>0)
			{
			  $Sum[6] = $row["REST"];
			}
			else
			{
				$Sum[6] = 0;
			}
			if ($row["SALE"]>0)
			{
				$Sum[7] = $row["SALE"];
			}
			else
			{
				$Sum[7] = 0;
			}
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function LastPriceByNomenCode($Code) //ціна по nomenCode
	{
		//$this->Connect();
		$sql = "
			select p.price, p.pricein, p.id_part
			from t_barcode b, t_price p, t_nomen n
			where
			(p.id_barcode=b.id) and
			(b.id_nomen=n.id) and
			(n.code='".$Code."')
			order by p.dbegin DESC
			rows 1
		";
		//echo $sql."<BR>";
		$Sum = array();
		$Sum[1] =0;
		$Sum[2] =0;
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		{
			if ($row!=false)
			{
				$Sum = array();
				$Sum[1] =$row["PRICE"];
				$Sum[2] =$row["PRICEIN"];
			}
		}
		//$this->Disconnect();
		return $Sum;
	}

	
	function GetSumFromDoctor($Date1, $Date2) //Розбивка сум по лікарям
	{
		$this->Connect();
		//select DOCTOR_NAME, sum(NPRICE*NQUANT) as SUM1
		$sql = "
			select DOCTOR_NAME, sum(NSALE_PRICE) as SUM1
			from V_DOCTOR_SUM
			where (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')
			group by DOCTOR_NAME
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["DOCTOR_NAME"];
			$Sum[2] = $row["SUM1"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetZero($Type, $ID=0, $BarCode=0) //Залишки товарів
	{
		$this->Connect();
		if (($Type==0.9) || ($Type==2))
		{
			$sql = "select SNAME, sum(QUANT) as QUANT, SCODE, NID ";
		}
		else
		{			
			$sql = "select BARCODE, SNAME, QUANT, NDIVISIBILITY, SCODE, NID ";
		}
		$sql = $sql."from V_NOMEN_INV ";
		
		if ($Type==3)
		{
			$sql = $sql."where (QUANT>0)";
		}
		if ($ID<>0)
		{
			$sql = $sql."where (NID=".$ID.")";
		}
		if ($BarCode<>"")
		{
			$sql = $sql." and (BARCODE='".$BarCode."')";
		}
		
		if (($Type==0.9)||($Type==2))
		{
			$sql = $sql." group by SNAME, SCODE, NID";
			$sql = $sql." having sum(QUANT)=0 ";
		}

		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$p_sql = ibase_prepare($this->DB, $sql);
		//$result = ibase_query($this->DB, $sql);
		$result = ibase_execute($p_sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			if ($Type==0.9)
			{
				$Sum[1] = "";
				$Sum[4] = "";
			}
			else
			{
				if (isset($row["BARCODE"]))
				{
					$Sum[1] = $row["BARCODE"];
				}
				else
				{
					$Sum[1] = "";
				}
				if (isset($row["NDIVISIBILITY"]))
				{
					$Sum[4] = $row["NDIVISIBILITY"];
				}
				else
				{
					$Sum[4] =1;
				}
			}
			$Sum[2] = $row["SNAME"];
			if (isset($row["QUANT"]))
			{
				$Sum[3] = $row["QUANT"];
			}
			else
			{
				$Sum[3] = 0;
			}
			$Sum[5] = $row["SCODE"];
			$Sum[6] = $row["NID"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetZeroAllApteks($AptekaList) //Залишки товарів по всіх аптеках
	{
		$DataTableAll = array();
		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$this->CurrentApteka = $AptekaList[$j];
				$this->Connect();
				$sql = "
				select BARCODE, SNAME, QUANT
				from V_NOMEN_INV
				
				";
				//rows 4
		
				$Table = array(); //Для двовимірного масиву
				$i = 1;
				$result = ibase_query($this->DB, $sql);
				while ($row=ibase_fetch_assoc($result))
				{
					$Sum = array();
					$Sum[1] = $row["BARCODE"];
					$Sum[2] = $row["SNAME"];
					$Sum[3] = $row["QUANT"];
					$Sum[4] = $j;
					$Table[$i] = $Sum;
					$i++;
				}
				$this->Disconnect();
				$DataTableAll[$j]=$Table;
			}
			else
			{
				$DataTableAll[$j]=0;
			}
		}

		//Формую одну таблицю з трьох
		$Table = array();
		//Переший елемет добавляю вручну
		//$Table[1][1]=$DataTableAll[1][1][1];
		//$Table[1][2]=$DataTableAll[1][1][2];
		//$Table[1][2+$DataTableAll[1][1][4]]=$DataTableAll[1][1][3];
		$Count=0; //Кількість елеменів в кінцевому масиві по всіх аптеках

		for ($i=1; $i<=count($DataTableAll); $i++) //По кількості аптек
		{
			for ($j=1; $j<=count($DataTableAll[$i]); $j++) //По препаратах і кількостях в межах аптеки
			{
				//echo $DataTableAll[$i][$j][1]." ++++ ".$DataTableAll[$i][$j][2]." ++++ ".$DataTableAll[$i][$j][4];
				//echo "<BR>";
				
				$New = true; //такого препарату у кінцевій таблиці немає
				//echo count($Table)."<BR>";
				//for ($k=1; $k<count($Table); $k++)
				for ($k=1; $k<=$Count; $k++)
				{
					//echo $Table[$k][2]."==".$DataTableAll[$i][$j][2]."<BR>";
					//if ($Table[$k][2]==$DataTableAll[$i][$j][2])
					if (($Table[$k][1]==$DataTableAll[$i][$j][1]) && ($Table[$k][2]==$DataTableAll[$i][$j][2]))
					{
						$New=false;
						$Table[$k][2+$DataTableAll[$i][$j][4]]=$DataTableAll[$i][$j][3];
					}
					
				}
				if ($New) //не знайшов такого товару в таблицы, тому добавляю його туди
				{
					$Table[$Count+1][1]=$DataTableAll[$i][$j][1];
					$Table[$Count+1][2]=$DataTableAll[$i][$j][2];
					$Table[$Count+1][2+$DataTableAll[$i][$j][4]]=$DataTableAll[$i][$j][3];
					$Count++;
				}
				//echo count($DataTableAll[$i][$j]);
				
			}
			//echo count($DataTableAll[$i]);
			//echo "<BR><BR><BR>";
		}

		//echo count($Table)."<BR>";
		//В кінцевій таблиці забиваю нулями ті значеняя, яких не знайшов
		for ($i=1; $i<=count($Table); $i++)
		{
			for ($j=1; $j<=count($Table[$i]); $j++)
			{
				if (!isset($Table[$i][$j]))
				{
					$Table[$i][$j]=0;
				}
				//echo $Table[$i][$j]." = ";
			}
			//echo count($Table[$i]);
			//echo "<BR>";
		}
		
		return $Table;
	}
	
	function GetZeroAllApteksWithPrice($AptekaList) //Залишки товарів по всіх аптеках з цінами
	{
		//Підготуємо тимчасову таблицю
		$ArrayFactory = new ArrayFactory();
		$FieldList = array();
		$FieldList[1] = "VARCHAR(20)"; //Штрих-код
		$FieldList[2] = "VARCHAR(55)"; //Назва
		$FieldIndex = 3;

		//Групуємо по ціні
		$GroupField = array();
		$GroupField[1] = 1;
		$GroupField[2] = 2;
		$GroupFieldIndex = 3;
		//Сумуємо к-ть
		$SumField = array();
		$SumFieldIndex = 1;  

		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$FieldList[$FieldIndex] = "DECIMAL(10,2)";
				$SumField[$SumFieldIndex] = $FieldIndex;
				$FieldIndex = $FieldIndex + 1;
				$FieldList[$FieldIndex] = "DECIMAL(10,2)";
				$GroupField[$GroupFieldIndex] = $FieldIndex;
				$FieldIndex = $FieldIndex + 1;
				$GroupFieldIndex = $GroupFieldIndex + 1;
				$SumFieldIndex = $SumFieldIndex + 1;
			}
		}

		//Сортування по полю № 2 (назва)
		$OrderField = 2;
		//Створюємо тимчасову таблицю
		$ArrayFactory->TempTableCreate($FieldList);
		
		$ActiveApteksIndex = 0; //Кількість активний аптек для визначення кількості пустих колонок
		//Вибераємо дані з баз для всіх активних аптек
		$DataTableAll = array();
		//print_r($AptekaList);
		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$this->CurrentApteka = $AptekaList[$j];
//echo $AptekaList[$j]->FullName."<BR>";
				$this->Connect();
//echo "<BR>";
//echo "Connect<BR>";
				$sql = "
					select SBARCODE as BARCODE, SNAME, NQUANT as QUANT, NPRICE as PRICE
					from V_NOMEN_WITH_LAST_PRICE
					where NQUANT>0		
				";
				
				//P_GET_INV_REST_AND_PRICE
				
				//rows 4
		
				$Table = array(); //Для двовимірного масиву
				$i = 1;

//echo $sql."<BR>";
$this->log('start '.$AptekaList[$j]->MnemoCod);
				$result = ibase_query($this->DB, $sql);
$this->log('fetch '.$AptekaList[$j]->MnemoCod);				
//echo "query<BR>";
				while ($row=ibase_fetch_assoc($result))
				{
					$Sum = array();
					$Sum[1] = $row["BARCODE"];
					$Sum[2] = $row["SNAME"];
					for ($k=3; $k<$ActiveApteksIndex+3; $k++) {$Sum[$k]=0;} //Забиваю нулями клонки інших аптек
					$Sum[$ActiveApteksIndex+3] = $row["QUANT"];
					$Sum[$ActiveApteksIndex+4] = $row["PRICE"];
					//$Sum[$ActiveApteksIndex+3] = number_format($row["QUANT"],2,",","");
					//$Sum[$ActiveApteksIndex+4] = number_format($row["PRICE"],2,",","");
					for ($k=$ActiveApteksIndex+4+1; $k<=2+2*count($AptekaList); $k++) {$Sum[$k]=0;} //Забиваю нулями клонки інших аптек //12=2+2*5аптеки
					
					$Table[$i] = $Sum;
					$i++;
				}
$this->log('array add '.$AptekaList[$j]->MnemoCod);
				$ActiveApteksIndex = $ActiveApteksIndex + 2;
				$ArrayFactory->ArrayAdd($FieldList, $Table);
				$this->Disconnect();
				unset ($result);
				unset ($Table);
//echo "Disconnect<BR>";
			}
		}
$this->log('get array ');
		$Table = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
$this->log('end ');
		$ArrayFactory->TempTableDrop();
		return $Table;
	}

	function GetOborot($Barcode, $Date1, $Date2, $Ident="") //Оборот по штриху
	{
		if ($Ident=="") {$Ident="null";}
		if ($Barcode=="") {$Barcode="null";}
		$this->Connect();
		$sql = "
			select *
			from P_REP_NOMEN_CARD
			('".$Barcode."', ".$Ident.", '".$Date1." 00:00:00', '".$Date2." 23:59:59')
		";
		//echo $this->CurrentApteka->FullName;
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NUMB_DOC"];
			$Sum[2] = $row["DATE_DOC"];
			$Sum[3] = $row["NOMEN_NAME"];
			$Sum[4] = $row["SERIA_NAME"];
			$Sum[5] = $row["PARTNO"];
			$Sum[6] = $row["QUANT"];
			$Sum[7] = $row["PRICE"];
			$Sum[8] = $row["SALE_PRICE"];
			$Sum[9] = $row["ID_DOC"];

			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetOborot2($Barcode, $Date1, $Date2) //Оборот по штриху для дефектури
	{
		//$this->Connect();
		$sql = "
			select *
			from P_REP_NOMEN_INV_AND_TURN
			('".$Date1."', '".$Date2."')
			where barcode = '".$Barcode."'
		";

		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["NOMEN_CODE"];
			$Sum[3] = $row["NOMEN_NAME"];
			$Sum[4] = $row["NOMEN_NAME"];
			$Sum[5] = $row["REST_QUANT"];
			$Sum[6] = $row["IMPORT_QUANT"];
			$Sum[7] = $row["SALE_QUANT"];
			$Sum[8] = $row["EXPORT_QUANT"];

			$Table[$i] = $Sum;
			$i++;
		}
		//$this->Disconnect();
		return $Table;
	}
	function GetOborot3($ID_Part, $Date1, $Date2) //Оборот по партії без конекта
	{
	
		$sql = "
			select *
			from P_TURN_BY_PARTID
			(".$ID_Part.",'".$Date1." 00:00:00', '".$Date2." 23:59:59')
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["IN_QUANT"];
			$Sum[2] = $row["SALE_QUANT"];
			$Sum[3] = $row["OUT_QUANT"];
			$Table[$i] = $Sum;
			$i++;
		}
		return $Table;
	}

	function GetCheckList($Date1, $Date2) //Список чеків
	{
		$this->Connect();
		$sql = "
			select REC_DATE, NUMB, SELLER_NAME, TOTAL, TOTAL_FULL, TAX_TOTAL, CHECK_SUM, DISCONT_SUM, CREDIT_SUM, PRINT_NUMB, CARDPAYSUM
			from V_RECEIPTS
			where (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["REC_DATE"];
			$Sum[2] = $row["NUMB"];
			$Sum[3] = $row["SELLER_NAME"];
			//$Sum[4] = number_format($row["TOTAL"],2,",","");
			$Sum[4] = $row["TOTAL"];
			$Sum[5] = number_format($row["TOTAL_FULL"],2,",","");
			$Sum[6] = number_format($row["TAX_TOTAL"],2,",","");
			$Sum[7] = number_format($row["CHECK_SUM"],2,",","");
			$Sum[8] = number_format($row["DISCONT_SUM"],2,",","");
			$Sum[9] = number_format($row["CREDIT_SUM"],2,",","");
			$Sum[10] = $row["CARDPAYSUM"];
			if ($row["PRINT_NUMB"] == 1)
			{
				$Sum[11] = "+";
			}
			else
			{
				$Sum[11] = "";
			}
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetCheckListForDiscount($Date1, $Date2, $Discount, $AptekaList) //Список чеків для картки по всіх аптеках
	{
		//Підготуємо тимчасову таблицю
		$ArrayFactory = new ArrayFactory();
		$FieldList = array();
		$FieldList[1] = "TIMESTAMP"; 
		$FieldList[2] = "INTEGER";
		$FieldList[3] = "VARCHAR(20)";
		$FieldList[4] = "DECIMAL(10,2)";
		$FieldList[5] = "DECIMAL(10,2)";
		$FieldList[6] = "DECIMAL(10,2)";
		$FieldList[7] = "DECIMAL(10,2)";
		$FieldList[8] = "DECIMAL(10,2)";
		$FieldList[9] = "VARCHAR(10)";

		//Групуємо по коду
		$GroupField = array();

		//Сумуємо суму
		$SumField = array();
		
		$OrderField = 1;
		
		//Створюємо тимчасову таблицю
		$ArrayFactory->TempTableCreate($FieldList);
				//Вибераємо дані з баз для всіх активних аптек
		$DataTableAll = array();
		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$this->CurrentApteka = $AptekaList[$j];
				$this->Connect();
				$sql = "
					select r.rec_date, r.numb, r.seller_name, r.total, r.total_full, r.tax_total_full, r.check_sum, r.discont_sum
					 from t_receipts_card rc, v_receipts r, t_discont_card d
					where
					rc.id_receipts=r.id and
					rc.id_discont_card=d.id and
					r.rec_date>='".$Date1." 00:00' and
					r.rec_date<='".$Date2." 23:59' and
					d.cardcode='".$Discount."'
				";
				//echo $sql;
				$Table = array(); //Для двовимірного масиву
				$i = 1;
				$result = ibase_query($this->DB, $sql);
				while ($row=ibase_fetch_assoc($result))
				{
					$Sum = array();
					$Sum[1] = $row["REC_DATE"];
					$Sum[2] = $row["NUMB"];
					$Sum[3] = $row["SELLER_NAME"];
					$Sum[4] = $row["TOTAL"];
					$Sum[5] = $row["TOTAL_FULL"];
					$Sum[6] = $row["TAX_TOTAL_FULL"];
					$Sum[7] = $row["CHECK_SUM"];
					if ($row["DISCONT_SUM"]>0) //Для даних до 12.04.2016 сума знижки знаходиться в іншому полі
					{
						$Sum[8] = $row["DISCONT_SUM"];
					}
					else
					{
						$Sum[8] = $Sum[5]-$Sum[4];
					}
					$Sum[9] = $this->CurrentApteka->MnemoCod;
					$Table[$i] = $Sum;
					$i++;
				}
				//print_r($Table);
				$ArrayFactory->ArrayAdd($FieldList, $Table);
				$this->Disconnect();
			}
		}
		$Table = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
		$ArrayFactory->TempTableDrop();
		return $Table;
	}

	function GetCheckById($id) //Чек по номеру
	{
		$this->Connect();
		
		//Впершому рядку будуть загальні дані про чек
		$sql = "
			select REC_DATE, NUMB, SELLER_NAME, TOTAL, TOTAL_FULL, TAX_TOTAL, CHECK_SUM, DISCONT_SUM, CREDIT_SUM
			from V_RECEIPTS
			where (NUMB=".$id.")
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["REC_DATE"];
			$Sum[2] = $row["NUMB"];
			$Sum[3] = $row["SELLER_NAME"];
			$Sum[4] = $row["TOTAL"];
			$Sum[5] = $row["TOTAL_FULL"];
			$Sum[6] = $row["TAX_TOTAL"];
			$Sum[7] = $row["CHECK_SUM"];
			$Sum[8] = $row["DISCONT_SUM"];
			$Sum[9] = $row["CREDIT_SUM"];
			$Table[$i] = $Sum;
			$i++;
		}
		
		//В подальших рядках буде інформація з чеку
		$sql = "
			select SNOMEN_NAME, SUNIT_NAME, SSERIES_CODE, SBARCODE, NPRICE, NSALE_TAXSUM, SPARTNUMB, NQUANT, NSALE_PRICE, SNOMEN_CODE, V_REC_SPEC.DISCONT_SUM, NID_RETURN_REC
			from V_REC_SPEC, V_RECEIPTS
			where (V_RECEIPTS.NUMB=".$id.") and (V_RECEIPTS.ID=V_REC_SPEC.NID_RECEIPT)
		";

		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $i-1;
			$Sum[2] = $row["SNOMEN_NAME"];
			$Sum[3] = $row["SUNIT_NAME"];
			$Sum[4] = $row["SSERIES_CODE"];
			$Sum[5] = $row["SBARCODE"];
			$Sum[6] = $row["NPRICE"];
			$Sum[7] = $row["NSALE_TAXSUM"];
			$Sum[8] = $row["SPARTNUMB"];
			$Sum[9] = $row["NQUANT"];
			$Sum[10] = $row["NSALE_PRICE"];
            $Sum[11] = $row["SNOMEN_CODE"];
            $Sum[12] = $row["DISCONT_SUM"];
            $Sum[13] = $row['NID_RETURN_REC'];
			$Table[$i] = $Sum;
			$i++;
		}
		
		$this->Disconnect();
		return $Table;
	}

	function GetImportDocList($Date1, $Date2) //Список загружених документів
	{
		$this->Connect();
		$sql = "
			select ID_DOC_TYPE, DOC_NUMB, DATE_DOC, DATE_WORK, SHORT_NAME, NSUM, NTAXSUM, NCOUNT, ID, SNAME_USERS
			from V_IMPORT_DOC_EX
			where (DATE_DOC>='".$Date1."') and (DATE_DOC<='".$Date2."')
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["DOC_NUMB"];
			$Sum[2] = $row["DATE_DOC"];
			$Sum[3] = $row["DATE_WORK"];
			$Sum[4] = $row["SHORT_NAME"];
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[5] = number_format($row["NSUM"]*10,3,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[5] = number_format($row["NSUM"],3,",","");
			}
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[6] = number_format($row["NTAXSUM"]*10,3,",","");
			}
			else
			{
				$Sum[6] = number_format($row["NTAXSUM"],3,",","");
			}
			if (
				($row["ID_DOC_TYPE"]==81) || //Оновлення довідника
				($row["ID_DOC_TYPE"]==80) //Переоцінка
				)
			{
				$Sum[5]=0;
				$Sum[6]=0;
			}
			$Sum[7] = $row["NCOUNT"];
			$Sum[8] = $row["ID"];
			$Sum[9] = $row["SNAME_USERS"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetImportDocSum($Date1, $Date2) //Суми по загружених документах
	{
		$this->Connect();
		$sql = "
			select sum(NSUM) as NSUM, sum(NTAXSUM) as NTAXSUM, sum(NCOUNT) as NCOUNT
			from V_IMPORT_DOC_EX
			where (DATE_DOC>='".$Date1."') and (DATE_DOC<='".$Date2."')
			and (ID_DOC_TYPE=79)
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[1] = number_format($row["NSUM"]*10,3,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[1] = number_format($row["NSUM"],3,",","");
			}
			$Sum[2] = number_format($row["NTAXSUM"],3,",","");
			$Sum[3] = $row["NCOUNT"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetExportDocList($Date1, $Date2) //Список вигружених документів
	{
		$this->Connect();
		$sql = "
			select DOC_NUMB, DOC_TYPE_NAME, DATE_DOC, FILE_NAME, NPRICE_SUM, NTAXSUM, NCOUNT, ID, SNAME_USERS
			from V_EXPORT_DOC
			where (DATE_DOC>='".$Date1." 00:00:00') and (DATE_DOC<='".$Date2." 23:59:59')
		";
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["DOC_NUMB"];
			$Sum[2] = $row["DOC_TYPE_NAME"];
			$Sum[3] = $row["DATE_DOC"];
			$Sum[4] = $row["FILE_NAME"];
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[5] = number_format($row["NPRICE_SUM"]*10,3,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[5] = number_format($row["NPRICE_SUM"],3,",","");
			}
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[6] = number_format($row["NTAXSUM"]*10,3,",","");
			}
			else
			{
				$Sum[6] = number_format($row["NTAXSUM"],3,",","");
			}
			$Sum[7] = $row["NCOUNT"];
			$Sum[8] = $row["ID"];
			$Sum[9] = $row["SNAME_USERS"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetExportDocSum($Date1, $Date2) //Суми по вигруженим документам
	{
		$this->Connect();
		$sql = "
			select sum(NPRICE_SUM) as NPRICE_SUM, sum(NTAXSUM) as NTAXSUM, sum(NCOUNT) as NCOUNT
			from V_EXPORT_DOC
			where (DATE_DOC>='".$Date1." 00:00:00') and (DATE_DOC<='".$Date2." 23:59:59')
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[1] = number_format($row["NPRICE_SUM"]*10,3,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[1] = number_format($row["NPRICE_SUM"],3,",","");
			}
			$Sum[2] = number_format($row["NTAXSUM"],3,",","");
			$Sum[3] = $row["NCOUNT"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetImportDocById($id) //Загружений документ по номеру
	{
		$this->Connect();
		$sql = "
			select NSUM, NTAXSUM, NCOUNT, DOC_NUMB, DATE_DOC, ID
			from V_IMPORT_DOC_EX
			where (ID='".$id."')
		";
		//where (DOC_NUMB='".$id."') //по номеру може бути 2 дукумента в різні роки
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win")>0)
			{
				$Sum[1] = number_format($row["NSUM"]*10,2,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[1] = number_format($row["NSUM"],2,",","");
			}
			$Sum[2] = number_format($row["NTAXSUM"],2,",","");
			$Sum[3] = $row["NCOUNT"];
			$Sum[4] = $row["DOC_NUMB"];
			$Sum[5] = $row["DATE_DOC"];
			$Sum[6] = $row["ID"];
			$Table[$i] = $Sum;
			$i++;
		}
		
		//В подальших рядках буде інформація з документу
		$sql = "
			select NOMEN_NAME, BARCODE, QUANT, PRICE, 0 as TAXSUM, SERIA_NAME, PROD_NAME, PARTNO, VENDORNAME, NOMEN_CODE, INDOCNUM, EDRPOU
			from V_IMPORT_DOC_SPEC
			where (PID=".$Sum[6].")
		";
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $i-1;
			$Sum[2] = $row["NOMEN_NAME"];
			$Sum[3] = $row["BARCODE"];
			$Sum[4] = number_format($row["QUANT"],2,",","");
			$Sum[5] = number_format($row["PRICE"],2,",","");
			//$Sum[6] = $row["SUM"];
			$Sum[6] = number_format($row["QUANT"]*$row["PRICE"],2,",","");
			$Sum[7] = number_format($row["TAXSUM"]*$row["QUANT"],2,",","");
			$Sum[8] = $row["SERIA_NAME"];
			$Sum[9] = $row["PARTNO"];
			$Sum[10] = $row["PROD_NAME"];
			$Sum[11] = $row["VENDORNAME"];
			$Sum[12] = $row["NOMEN_CODE"];
			$Sum[13] = $row["INDOCNUM"];
			$Sum[14] = $row["EDRPOU"];
			$Table[$i] = $Sum;
			$i++;
		}
		
		$this->Disconnect();
		return $Table;
	}

	function GetExportDocById($id) //Вигружений документ по номеру
	{
		$this->Connect();
		$sql = "
			select NPRICE_SUM, NPRICE_TAXSUM, NCOUNT, DOC_NUMB, DATE_DOC, ID
			from V_EXPORT_DOC
			where (ID=".$id.")
		";
		//echo $sql."<BR>";
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			if (strpos($_SERVER["SERVER_SOFTWARE"],"Win2")>0)
			{
				$Sum[1] = number_format($row["NPRICE_SUM"]*10,2,",",""); //*10 якогось бена на віндових серваках зменшує суму на 10
			}
			else
			{
				$Sum[1] = number_format($row["NPRICE_SUM"],2,",","");
			}
			$Sum[2] = number_format($row["NPRICE_TAXSUM"],2,",","");
			$Sum[3] = $row["NCOUNT"];
			$Sum[4] = $row["DOC_NUMB"];
			$Sum[5] = $row["DATE_DOC"];
			$Sum[6] = $row["ID"];
			$Table[$i] = $Sum;
			$i++;
		}
		
		//В подальших рядках буде інформація з документу
		$sql = "
			select NOMEN_NAME, BARCODE, QUANT, PRICE, SALE_TAXSUM, SERIA_NAME, PROD_NAME, PARTNO, VENDORNAME
			from V_EXPORT_DOC_SPEC
			where (PID=".$Sum[6].")
		";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $i-1;
			$Sum[2] = $row["NOMEN_NAME"];
			$Sum[3] = $row["BARCODE"];
			$Sum[4] = number_format($row["QUANT"],2,",","");
			$Sum[5] = $row["PRICE"];
			//$Sum[6] = $row["SUM"];
			$Sum[6] = number_format($row["QUANT"]*$row["PRICE"],2,",","");
			$Sum[7] = number_format($row["SALE_TAXSUM"]*$row["QUANT"],2,",","");
			$Sum[8] = $row["SERIA_NAME"];
			$Sum[9] = $row["PARTNO"];
			$Sum[10] = $row["PROD_NAME"];
			$Sum[11] = $row["VENDORNAME"];
			$Table[$i] = $Sum;
			$i++;
		}
		
		$this->Disconnect();
		return $Table;
	}

	function GetBestPreparats($Count, $Date1, $Date2) //Найбільша кількість товарів
	{
		$this->Connect();
		$sql = "
			select SBARCODE, SNOMEN_NAME, count(SNOMEN_NAME) as COUNTTOV, sum(NQUANT) as QUANT, sum(NPRICE*NQUANT) as SumGRN, sum(NTAXSUM) as NTAXSUM
            from 
                ( select SBARCODE, SNOMEN_NAME, NQUANT, NTAXSUM, NPRICE
					from V_REC_SPEC, V_RECEIPTS
					where ((V_RECEIPTS.ID=V_REC_SPEC.NID_RECEIPT) and
					(REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59'))
				)	
			group by SNOMEN_NAME, SBARCODE
			order by COUNTTOV DESC
			rows ".$Count."
		";
		
		// order by SumGRN DESC
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		//$row=ibase_fetch_assoc($result);
		return $result;
		$this->Disconnect();
	}
	function GetBestPreparatsWithAnalitic($Count, $Date1, $Date2, $AnaliticCode) //Найбільша кількість товарів з аналітикою
	{
		$this->Connect();
		$sql = "
				select t.*, (select a.VANLVAL from V_NOMEN_ANL_VALUE a where
a.NID_NOMEN = t.NID_NOMEN and a.NID_BARCODE = t.NID_BARCODE and a.SANLCODE = ".$AnaliticCode."),
(select a.SANLCODE from V_NOMEN_ANL_VALUE a where a.NID_NOMEN = t.NID_NOMEN and a.NID_BARCODE = t.NID_BARCODE and a.SANLCODE = ".$AnaliticCode.")
from (select NID_NOMEN, NID_BARCODE, SBARCODE, SNOMEN_NAME,
count(SNOMEN_NAME) as COUNTTOV, sum(NQUANT) as QUANT, sum(NPRICE*NQUANT) as SumGRN, sum(NTAXSUM) as NTAXSUM from
( select SBARCODE, SNOMEN_NAME, NQUANT, NTAXSUM, NPRICE,
V_REC_SPEC.NID_NOMEN, V_REC_SPEC.NID_BARCODE from V_REC_SPEC, V_RECEIPTS where ((V_RECEIPTS.ID=V_REC_SPEC.NID_RECEIPT)
 and (REC_DATE>='".$Date1." 00:00:00') and (REC_DATE<='".$Date2." 23:59:59')) ) group by NID_NOMEN, NID_BARCODE, SNOMEN_NAME, SBARCODE order by SumGRN DESC rows ".$Count.") t
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID_NOMEN"];
			$Sum[2] = $row["SNOMEN_NAME"];
			$Sum[3] = $row["VANLVAL"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function DefecturaList() //Вивести дефектуру
	{
		$this->Connect();
/*
		$sql = "
			SELECT NID, SNAME, MIN_RESTS, QUANT 
			FROM V_NOMEN_RESTS T 
			WHERE T.MIN_RESTS - T.QUANT >= 0
			order by SNAME
		";
		*/
		$sql = "	
		select NID, SCODE, SNAME, MIN_RESTS, QUANT
    from (SELECT NID, SCODE, SNAME, MIN_RESTS, SUM(QUANT) QUANT
   FROM V_NOMEN_RESTS T 
   GROUP BY NID, SCODE, SNAME, MIN_RESTS) T
    WHERE T.MIN_RESTS - T.QUANT >= 0
    order by SNAME
				";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["MIN_RESTS"];
			$Sum[4] = $row["QUANT"];
			$Sum[5] = $row["SCODE"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function GetAllTovar() //Список товару для зміни дефектури
	{
		$this->Connect();
		$sql = "
				select vn.NID, vn.SNAME, vn.MIN_RESTS
				from V_NOMEN_EX vn
				order by vn.SNAME
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["MIN_RESTS"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllTovarWidthBarcode() //Список товару з штрихами та виробниками для привязки аналітик
	{
		$this->Connect();
		$sql = "
				select vn.NID, vn.SCODE, vn.SNAME, b.BARCODE, b.ID as B_ID, c.NAME as CONTRAGENT
				from V_NOMEN_EX vn, T_BARCODE b, T_PRODUCER p, T_CONTRAGENT c
				where
					(vn.NID=b.ID_NOMEN) and
					(b.ID_PRODUCER=p.ID) and
					(p.ID_CONTRAGENT=c.ID)
				order by vn.SNAME
				
		";
		//echo $sql;rows 10
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			//$Sum[1] = $row["NID"];
			$Sum[1] = $row["SCODE"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["B_ID"];
			$Sum[4] = $row["BARCODE"];
			$Sum[5] = $row["CONTRAGENT"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllTovarWithOborot($Date1, $Date2, $Period) //Список товару для зміни дефектури з інфою про рух
	{
		$this->Connect();
		/*
		$sql = "
				select NID, SNAME, MIN_RESTS
				from V_NOMEN_EX
				order by SNAME
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["MIN_RESTS"];
			
			$TableOborot = $this->GetOborot2("111", $Date1, $Date2);
			
			for ($k=2; $k<=count($TableOborot); $k++)
			{
				$Sum[4] = number_format($TableOborot[$k][6],2,",","");
				$Sum[5] = number_format($TableRow[$k][7],2,",","");
			}
			
			//$Sum[4] = 0; //Приход
			//$Sum[5] = 0; //Продажі
			$Sum[6] = 0; //Розрахункове значення
			
			$Table[$i] = $Sum;
			$i++;
		}
		*/
		$Start = explode(".",$Date1);
		$Start = mktime(0, 0, 0, $Start[1], $Start[0], $Start[2]);
		$End = explode(".",$Date2);
		$End = mktime(0, 0, 0, $End[1], $End[0], $End[2]);
		$diffInSeconds = abs($End - $Start);
		$diffInDays = ceil($diffInSeconds / 86400);
		//echo $diffInDays;
		
		/*
		$sql = "
			select *
			from P_REP_NOMEN_INV_AND_TURN
			('".$Date1."', '".$Date2."')
			";
			*/
			$sql = "
			select t.*, n.min_rests
			from p_rep_nomen_inv_and_turn
			('".$Date1."', '".$Date2."') t, t_nomen n
			where n.id = t.nid
			";
			//rows 50
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if ($row["MIN_RESTS"]>-2)
			{
				$Sum = array();
				$Sum[1] = $row["NOMEN_CODE"];
				$Sum[2] = $row["NOMEN_NAME"]; //$row["NOMEN_CODE"];
				$Sum[3] = $row["EXPORT_QUANT"];
				$Sum[4] = $row["IMPORT_QUANT"];
				$Sum[5] = $row["SALE_QUANT"];//$row["REST_QUANT"]
				//$Sum[6] = ceil(($Sum[5]+$Sum[3])/$diffInDays*$Period);
				$Sum[6] = ($Sum[5]+$Sum[3])/$diffInDays*$Period;
				$Sum[6] = number_format($Sum[6],2,".","");
				$Sum[7] = $row["NID"];
				//$Sum[7] = $row["REST_QUANT"]; //Залишок
				if ($Sum[6]==0)
				{
					$Sum[6] = -1;
				}
				$Sum[8] = $row["BARCODE"];
				
				$Table[$i] = $Sum;
				$i++;
			}
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllTovarWithSale($Period) //Список товару для зміни дефектури з інфою про продаж
	{
		$this->Connect();
		/*			
		$sql ="			
				select SNOMEN_CODE, SNOMEN_NAME, sum(I.QUANT) AS RESTQUANT, NQUANT, CNT
				from (select S.SNOMEN_CODE, S.SNOMEN_NAME, sum(S.NQUANT) as NQUANT, count(S.NQUANT) as CNT
                from V_REC_SPEC S, v_receipts R
                where
                  (S.nid_receipt=R.id) and
                  (R.REC_DATE>='".date("d.m.Y", strtotime("-".$Period." day"))." 00:00:00') and
                  (R.REC_DATE<='".date("d.m.Y")." 23:59:59')
                group by S.SNOMEN_NAME, S.SNOMEN_CODE), V_NOMEN_INV I
                where I.SCODE = SNOMEN_CODE
                group by  SNOMEN_CODE, SNOMEN_NAME, NQUANT, CNT
                order by SNOMEN_NAME
				
		";
		*/
		$sql ="
		select Z.NID, Z.SNOMEN_CODE, Z.SNOMEN_NAME, Z.RESTQUANT, M.NQUANT, M.CNT, Z.MIN_RESTS
        from
        (select I.NID, I.SCODE AS SNOMEN_CODE, I.SNAME AS SNOMEN_NAME, sum(I.QUANT) AS RESTQUANT, I.min_rests AS MIN_RESTS
        from V_NOMEN_INV I
        where MIN_RESTS>-2
        group by NID, SCODE, SNAME, MIN_RESTS) Z
        LEFT JOIN (select B.ID_NOMEN, sum(S.QUANT) NQUANT, count(S.QUANT) as CNT
                    from T_REC_SPEC S, T_receipts R, T_BARCODE B
                    where
                        (S.id_receipt=R.id) and
                        B.ID = S.ID_BARCODE and
						(R.REC_DATE>='".date("d.m.Y", strtotime("-".$Period." day"))." 00:00:00') and
                        (R.REC_DATE<='".date("d.m.Y")." 23:59:59')
                        group by B.ID_NOMEN) M
				on M.ID_NOMEN = Z.NID
				order by Z.SNOMEN_NAME
		";
				//залишок, кількіть проданого товару і кількіть продаж	
					
			//rows 5
			
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if
			(
			//($row["MIN_RESTS"]>-2)&&
			($row["CNT"]>=1)&&
			(number_format($row["NQUANT"],2,".","")>number_format($row["RESTQUANT"],2,".",""))
			)
			{
				$Sum = array();
				$Sum[1] = $row["SNOMEN_CODE"];
				$Sum[2] = $row["SNOMEN_NAME"]; //$row["NOMEN_CODE"];
				$Sum[3] = $row["RESTQUANT"];
				$Sum[4] = $row["NQUANT"];
				$Sum[5] = $row["CNT"];//$row["REST_QUANT"]
				if ($row["CNT"]==1)
				{
				  $Sum[6] = $Sum[4]-$row["RESTQUANT"];
				}
				else
				{
				  //$Sum[6] = $Sum[4]*$this->CurrentApteka->DefectudaKoef;
					$Sum[6] = $Sum[4]*$this->CurrentApteka->DefectudaKoef-$row["RESTQUANT"];
				}

				//$Table[] = $Sum;
				$Table[$i] = $Sum;
				$i++;
			}
		}
		
		//Аналізуємо довгий період для тих хто не продавався
		//echo "<p align=left>";
		
		//ibase_data_seek(0);
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			if
			(
			//($row["MIN_RESTS"]>-2)&&
			($row["CNT"]==0) ||
			($row["CNT"]==null)
			)
		    {
				//echo $row["SNOMEN_NAME"];
				$sql = "
				select Z.NID, Z.SNOMEN_CODE, Z.SNOMEN_NAME, Z.RESTQUANT, M.NQUANT, M.CNT, Z.MIN_RESTS
			from
			(select I.NID, I.SCODE AS SNOMEN_CODE, I.SNAME AS SNOMEN_NAME, sum(I.QUANT) AS RESTQUANT, I.min_rests AS MIN_RESTS
			from V_NOMEN_INV I
        where MIN_RESTS>-2
        group by NID, SCODE, SNAME, MIN_RESTS) Z
        LEFT JOIN (select B.ID_NOMEN, sum(S.QUANT) NQUANT, count(S.QUANT) as CNT
                    from T_REC_SPEC S, T_receipts R, T_BARCODE B
                    where
                        (S.id_receipt=R.id) and
                        B.ID = S.ID_BARCODE and
						(R.REC_DATE>='".date("d.m.Y", strtotime("-".$GLOBALS["glDefecturaDepthMonth"]." month"))." 00:00:00') and
                        (REC_DATE<='".date("d.m.Y")." 23:59:59')
						
                        group by B.ID_NOMEN) M
				on (M.ID_NOMEN = Z.NID)
				where (Z.NID=".$row["NID"].")
				order by Z.SNOMEN_NAME				
				";
				/*
				 select SNOMEN_CODE, SNOMEN_NAME, sum(I.QUANT) AS RESTQUANT, NQUANT, CNT
				from (select S.SNOMEN_CODE, S.SNOMEN_NAME, sum(S.NQUANT) as NQUANT, count(S.NQUANT) as CNT
                from V_REC_SPEC S, v_receipts R
                where
                  (S.nid_receipt=R.id) and
                  (R.REC_DATE>='".date("d.m.Y", strtotime("-".$GLOBALS["glDefecturaDepthMonth"]." month", strtotime($Date1)))." 00:00:00') and
				  (REC_DATE<='".$Date1." 23:59:59') and
				  (SNOMEN_CODE=".$row["NOMEN_CODE"].")
				  group by S.SNOMEN_NAME, S.SNOMEN_CODE), V_NOMEN_INV I
                where I.SCODE = SNOMEN_CODE
                group by  SNOMEN_CODE, SNOMEN_NAME, NQUANT, CNT
                order by SNOMEN_NAME
				*/
				
				//echo "<BR><BR>".$sql."<BR><BR>";
				
				//echo $GLOBALS["FormTovarDefecturaItem14"]." -- ".$Sum[2]." -- ".$Sum[8]." -- ".$Sum[1]." QUANT: ".$row["NQUANT"]." -- (1/div)*glDefecturaMinCount= ".(1/$Sum[11])*$GLOBALS["glDefecturaMinCount"]."<BR>";
				$result2 = ibase_query($this->DB, $sql);
				$row2=ibase_fetch_assoc($result2);
				//echo "  CNT=".$row2["CNT"]."   RESTQUANT=".$row2["RESTQUANT"]." <BR>";
				if
				(
					($row2["CNT"]>0) &&
					($row2["RESTQUANT"]==0)
				)
				{
						//echo "!!!!!!".$row["SNOMEN_NAME"];
						//echo $row2["CNT"]."<BR>";
						$Sum = array();
						$Sum[1] = $row["SNOMEN_CODE"];
						$Sum[2] = $row["SNOMEN_NAME"]; //$row["NOMEN_CODE"];
						$Sum[3] = $row["RESTQUANT"];
						$Sum[4] = $row["NQUANT"];
						$Sum[5] = $row["CNT"];//$row["REST_QUANT"]
						$Sum[6] = 1;

						$Table[] = $Sum;
						//$Table = array_values($Table);
				}
			}
		}
		
		
		
		
		//echo "</p><BR><BR";
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllTovarWithOborotEmptyCells($Date1, $Date2, $BeforeCount, $AfterCount) //Список товару з оборотами та пустими колонками
	{
		$this->Connect();
		
		
		$sql = "
			select *
			from P_REP_NOMEN_INV_AND_TURN
			('".$Date1."', '".$Date2."')
			where REST_QUANT>0			
			";
			//rows 50
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			//echo 'i='.$i.'<br>';
			$Sum = array();
			$j=1;
			$Sum[$j] = $row["BARCODE"]; $j=$j+1;
			$Sum[$j] = $row["NOMEN_NAME"]; $j=$j+1;//$row["NOMEN_CODE"];

			if ($BeforeCount<>0)
			{
				for ($EmptyCells=1; $EmptyCells<=$BeforeCount; $EmptyCells++)
				{
					$Sum[$j] = 0;
					$j = $j + 1;
				}
			}
			
			$Sum[$j] = $this->Null2Nol($row["IMPORT_QUANT"]); $j=$j+1;
			$Sum[$j] = $this->Null2Nol($row["SALE_QUANT"]); $j=$j+1;//$row["REST_QUANT"] //Прихід
			$Sum[$j] = $this->Null2Nol($row["EXPORT_QUANT"]); $j=$j+1;
			$Sum[$j] = $this->Null2Nol($row["REST_QUANT"]); $j=$j+1;//Залишок		

			if ($AfterCount<>0)
			{
				for ($EmptyCells=1; $EmptyCells<=$AfterCount; $EmptyCells++)
				{
					$Sum[$j] = 0;
					$j = $j + 1;
				}
			}
			
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllTovarWithOborotWithQuatity($Date1, $Date2, $Condition) //Список товару з інфою про рух з умовами
	{
		$this->Connect();
				
		$sql = "
			select BARCODE, NOMEN_NAME, REST_QUANT, SALE_QUANT, IMPORT_QUANT
			from P_REP_NOMEN_INV_AND_TURN
			('".$Date1."', '".$Date2."')
			
			";
			if ($Condition==1)// к-ть>0 І не продавався
			{
				$sql = $sql."
					 where
					 ((SALE_QUANT=0) or ((SALE_QUANT is null))) and (EXPORT_QUANT is null) and (REST_QUANT>0)
				";
			}
			//(REST_QUANT>0) and ((SALE_QUANT=0) or ((SALE_QUANT is null))) and (EXPORT_QUANT is null)
			//$sql = $sql."rows 1"; 
		//echo $sql;	exit;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["NOMEN_NAME"];
			
			//$Sum[5] = $row["EXPORT_QUANT"];
			$Sum[3] = $row["REST_QUANT"];//залишок
			$Sum[4] = $row["SALE_QUANT"];
			$Sum[5] = $row["IMPORT_QUANT"];

			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function DefecturaChange($id, $val) //Список товару для зміни дефектури
	{
		//$this->Connect();
		$sql = "
				execute procedure P_NOMEN_RESTS_UPDATE (".$id.", ".$val.")
		";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		//$this->Disconnect();
	}

	function GetSeriya($Date1) //Список товару з критичними серіями на дату
	{
		$this->Connect();
		$sql = "
				select * 
				from P_REP_CHECK_SERIES_DATE_END ('".$Date1."')
				order by seria_dend
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["SERIA_DEND"];
			$Sum[4] = $row["DAY_TO_END"];
			$Sum[5] = number_format($row["QUANT"],2,",","");
			$Sum[6] = $row["SERIA_NAME"];
            $Sum[7] = $row["SERIA_ID"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetRegistration($Date1) //Список товару з критичними серіями на дату
	{
		$this->Connect();
		$sql = "
				select * 
				from P_REP_CHECK_GOVREGDATE_END ('".$Date1."')
				order by GOVREGDAT
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["GOVREGDAT"];
			$Sum[4] = $row["DAY_TO_END"];
			$Sum[5] = number_format($row["QUANT"],2,",","");
			$Sum[6] = $row["GOVREGNUM"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetAllInsurer() //Список страхових компаній
	{
		$this->Connect();
		$sql = "
				select v.*, t.edrpou
        from V_INSURER v, T_CONTRAGENT t
        where v.NID_CONTRAGENT=t.ID
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["NID_CONTRAGENT"];
			$Sum[3] = $row["SNAME"];
			$Sum[4] = $row["EDRPOU"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetInsurerByID($ID) //Назва страхової компанії по ІД
	{
		$this->Connect();
		$sql = "
				select *
				from V_INSURER
				where NID_CONTRAGENT = ".$ID."
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["NID"];
			$Sum[2] = $row["NID_CONTRAGENT"];
			$Sum[3] = $row["SNAME"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function ReportStrah1($Date1, $Date2, $InsurerID) //Звіт для страхових компаній по прізвищах та товарах
	{
		$GLOBALS["glReportStrahPriceKoef"]=1;
		$InsurerName = $this->GetAllInsurer();
		foreach ($InsurerName as $row)
		{
			if ($row[2]==$InsurerID)
			{
				$temp = $GLOBALS["glReportStrahPriceKoefArr"];
				if (isset($temp[$row[3]]))
				{
					$GLOBALS["glReportStrahPriceKoef"]=$temp[$row[3]];
				}
			}
		}
		unset($temp);
				
		$this->Connect();
		$sql = "
				select *
				from P_REP_INSURANCE_CHECK('".$Date1."', '".$Date2."', ".$InsurerID.")
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["CHECK_NUMB"];
			$Sum[2] = $row["CLIENT_CODE"];
			$Sum[3] = $row["CLIENT_NAME"];
			$Sum[4] = $row["RECEIP_NUMB"];
			if ($row["SALE_DATE"]<>0)
			{
				$Sum[5] = date("d.m.Y",mktime(0,0,0,substr($row["SALE_DATE"],5,2),substr($row["SALE_DATE"],8,2),substr($row["SALE_DATE"],0,4)));	
			}
			else
			{
				$Sum[5] = $row["SALE_DATE"];
			}
			$Sum[6] = $row["NOMEN_NAME"];
			$Sum[7] = number_format($row["POS_PRICE"],2,",","");
			$Sum[8] = number_format($row["POS_QUANT"],2,",","");
			$Sum[9] = number_format($row["POS_SUM"],2,",","");
			$Sum[10] = number_format($row["RECEIP_SUM"],2,",","");
			$Sum[11] = number_format($row["CLIENT_SUM"],2,",","");
			$Sum[12] = $row["STYLE"];
			$Sum[13] = number_format($row["POS_SUM"] * $GLOBALS["glReportStrahPriceKoef"],2,",","");
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function ReportStrah2($Date1, $Date2, $InsurerID) //Звіт для страхових компаній по прізвищах та товарах
	{
		$this->Connect();
		$sql = "
				SELECT 
					C.SCARDCODE, 
					C.SNAME, 
					C.NID_DISCONT_CARD, 
					R.RECEIP_NUMB, 
					R.REC_DATE, 
					R.ID, 
					R.TOTAL_FULL, 
					list(S.SNOMEN_NAME,';') as SNOMEN_NAME, 
					list(S.NPRICE,';') as NPRICE, 
					list(S.NQUANT,';') as NQUANT, 
					list(S.NPRICE*S.NQUANT,';') as SUM1

				FROM V_RECEIPTS R, V_DISCONT_CARD C, V_REC_SPEC S WHERE S.NID_RECEIPT = R.ID AND
				R.DISCONT_ID = C.NID_DISCONT_CARD AND C.STYPE_CARD_CODE = '30'
				AND R.REC_DATE BETWEEN '".$Date1."' AND '".$Date2."' AND C.NID_COMPANY_OWNER = '".$InsurerID."'

				group by SCARDCODE, SNAME, NID_DISCONT_CARD, receip_numb, REC_DATE, id, total_full
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_object($result))
		{
			$Sum = array();
			
			$Sum[1] = $row->SCARDCODE;
			$Sum[2] = $row->SNAME;
			$Sum[3] = $row->NID_DISCONT_CARD;
			$Sum[4] = $row->RECEIP_NUMB;
			$Sum[5] = $row->REC_DATE;
			$Sum[6] = $row->ID;
			$Sum[7] = $row->TOTAL_FULL;
			$blob_data = ibase_blob_info($row->SNOMEN_NAME);
			$blob_hndl = ibase_blob_open($row->SNOMEN_NAME);
			//echo       "blob=".ibase_blob_get($blob_hndl, $blob_data[0])."<BR>";
			$Sum[8] = ibase_blob_get($blob_hndl, $blob_data[0]);
			$blob_data = ibase_blob_info($row->NPRICE);
			$blob_hndl = ibase_blob_open($row->NPRICE);
			$Sum[9] = ibase_blob_get($blob_hndl, $blob_data[0]);
			$blob_data = ibase_blob_info($row->NQUANT);
			$blob_hndl = ibase_blob_open($row->NQUANT);
			$Sum[10] = ibase_blob_get($blob_hndl, $blob_data[0]);
			$blob_data = ibase_blob_info($row->SUM1);
			$blob_hndl = ibase_blob_open($row->SUM1);
			$Sum[11] = ibase_blob_get($blob_hndl, $blob_data[0]);
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function ReportStrah3($Date1, $Date2, $InsurerID) //Звіт для страхових компаній для вигрузки
	{
		$this->Connect();
		
		if (!isset($GLOBALS["glReportStrahPriceKoef"]))
		{
			$GLOBALS["glReportStrahPriceKoef"]=1;
		}
		
		/*
		$sql = "
		SELECT DISTINCT C.SCARDCODE, C.SNAME, C.NID_DISCONT_CARD, R.*, RS.* FROM V_RECEIPTS R, V_REC_SPEC RS, V_DISCONT_CARD C WHERE
     R.DISCONT_ID = C.NID_DISCONT_CARD AND RS.NID_RECEIPT = R.ID AND C.STYPE_CARD_CODE = '30'
     AND R.REC_DATE BETWEEN '".$Date1."' and '".$Date2."' AND C.NID_COMPANY_OWNER = ".$InsurerID."
		";
		*/
		$sql = "
		SELECT DISTINCT SNOMEN_NAME, NSALE_TAXSUM, NPRICE, sum(NQUANT) as NQUANT, c.scompany_owner_name as company
			FROM V_RECEIPTS R, V_REC_SPEC RS, V_DISCONT_CARD C WHERE
     R.DISCONT_ID = C.NID_DISCONT_CARD AND RS.NID_RECEIPT = R.ID AND C.STYPE_CARD_CODE = '30'
     AND R.REC_DATE BETWEEN '".$Date1."' and '".$Date2."' AND c.scompany_owner_name = '".$InsurerID."'
			group by SNOMEN_NAME, NSALE_TAXSUM, NPRICE, COMPANY
			";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SNOMEN_NAME"];
			//Визначу ставку ПДВ
			if ($row["NSALE_TAXSUM"]>0)
			{
				$Sum[2]=20;
			}
			else
			{
				$Sum[2]=7;
			}
			$Sum[3] = $row["NPRICE"]/(1+$Sum[2]/100); //Ціна без ПДВ
			$Sum[3] = $Sum[3]*$GLOBALS["glReportStrahPriceKoef"];
			$Sum[4] = $row["NQUANT"];
			$Sum[5] = $Sum[3]*$Sum[4];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetTovarForDiscount($Date1, $Date2, $Discount, $AptekaList) //Список проданого товару по картці
	{
		//Підготуємо тимчасову таблицю
		$ArrayFactory = new ArrayFactory();
		$FieldList = array();
		$FieldList[1] = "TIMESTAMP"; 
		$FieldList[2] = "INTEGER";
		$FieldList[3] = "VARCHAR(20)";
		$FieldList[4] = "VARCHAR(50)";
		$FieldList[5] = "DECIMAL(10,2)";
		$FieldList[6] = "DECIMAL(10,2)";
		$FieldList[7] = "DECIMAL(10,2)";
		$FieldList[8] = "DECIMAL(10,2)";
		$FieldList[9] = "DECIMAL(10,2)";
		$FieldList[10] = "VARCHAR(20)";
		$FieldList[11] = "VARCHAR(10)";
		$FieldList[12] = "DECIMAL(10,2)";

		//Групуємо по коду
		$GroupField = array();

		//Сумуємо суму
		$SumField = array();
		
		$OrderField = 1;
		
		//Створюємо тимчасову таблицю
		$ArrayFactory->TempTableCreate($FieldList);
				//Вибераємо дані з баз для всіх активних аптек
		$DataTableAll = array();
		for ($j=1; $j<=count($AptekaList); $j++)
		{
			if ($AptekaList[$j]->Active==1)
			{
				$this->CurrentApteka = $AptekaList[$j];
				$this->Connect();
				$sql = "
					select
						r.rec_date, r.numb, rs.sbarcode, rs.snomen_name, rs.nquant, rs.nfull_price, rs.nsale_price,
						(coalesce(rs.discont_sum,0)+rs.discont_sum_old) as discont_sum,
						(rs.nsale_price*rs.nquant) as sum1, r.seller_name, rs.npricein
						from
						V_RECEIPTS r, V_REC_SPEC rs, t_receipts_card rc , v_discont_card dc
						where
							(r.ID=rs.NID_RECEIPT) and
							(rc.id_receipts=r.id) and
							(rc.id_discont_card=dc.nid_discont_card) and
							(dc.scardcode='".$Discount."') and
							(r.rec_date>='".$Date1." 00:00:00') and
							(r.rec_date<='".$Date2." 23:59:59')
					
					
					
				";
				//
				//echo $sql."<BR><BR>";
				$Table = array(); //Для двовимірного масиву
				$i = 1;
				$result = ibase_query($this->DB, $sql);
				while ($row=ibase_fetch_assoc($result))
				{
					$Sum = array();
					$Sum[1] = $row["REC_DATE"];
					$Sum[2] = $row["NUMB"];
					$Sum[3] = $row["SBARCODE"];
					$Sum[4] = $row["SNOMEN_NAME"];	
					$Sum[5] = $row["NQUANT"];
					$Sum[6] = $row["NFULL_PRICE"];
					$Sum[7] = $row["NSALE_PRICE"];
					$Sum[8] = $row["DISCONT_SUM"];
					$Sum[9] = $row["SUM1"];
					$Sum[10] = $row["SELLER_NAME"];
					$Sum[11] = $this->CurrentApteka->MnemoCod;
					$Sum[12] = $row["NPRICEIN"];
					$Table[$i] = $Sum;
					$i++;
				}
				$ArrayFactory->ArrayAdd($FieldList, $Table);
				$this->Disconnect();
			}
		}
		$Table = $ArrayFactory->GetArray($FieldList, $GroupField, $SumField, $OrderField);
		$ArrayFactory->TempTableDrop();
		return $Table;
	}
	
	function TempTableCreate($TableName, $FieldList) //Створюємо тимчасову таблицю
	{
		$this->Connect();
		
		//echo $this->CurrentApteka->IP;
		//echo $this->CurrentApteka->MnemoCod;
		
		$sql = "
			RECREATE table ".$TableName."
			(
		";
		for ($i=1; $i<=count($FieldList); $i++)
		{
			if ($i<>count($FieldList))
			{
				$sql = $sql."
				field_".$i." ".$FieldList[$i].",
				";
			}
			else
			{
				$sql = $sql."
				field_".$i." ".$FieldList[$i].")
				";
			}
		}
		
		//echo $sql.'<BR><BR>';
    //print_r($this);
		$result = ibase_query($this->DB, $sql);
		ibase_commit($this->DB);
		$this->Disconnect();
		//return $Table;
	}
	
	function TempTableDrop($TableName) //Видаляємо тимчасову таблицю
	{
		$this->Connect();
		
		$sql = "
			drop table ".$TableName."
		";		
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		ibase_commit($this->DB);
		$this->Disconnect();
		//return $Table;
	}
	
	function TempTableAddArray($TableName, $FieldList, $Array) //Добавляю масив в таблицю для групування
	{
		$this->Connect();
		
		
		for ($i=1; $i<=count($Array); $i++)
		{
			$sql = "
			insert into ".$TableName."(
			";
			for ($j=1; $j<=count($FieldList); $j++)
			{
				$sql = $sql."field_".$j;
				if ($j<>count($FieldList))
				{
					$sql = $sql.",";
				}
				else
				{
					$sql = $sql.")
					values (";
				}
			}
			for ($j=1; $j<=count($FieldList); $j++)
			{
				if ((preg_match("[CHAR]",$FieldList[$j])==1)||(preg_match("[TIME]",$FieldList[$j])==1))
				{
					//echo '<BR>i='.$i.'  j='.$j; print_r($Array); echo '<BR>';
					$sql = $sql."'".str_replace("'"," ",$Array[$i][$j])."'"; //preg_replace - позабираю апострофи в назвах preg_replace
				}
				else
				{
					//print_r($Array);
					$sql = $sql.$Array[$i][$j];
				}
				
				if ($j<>count($FieldList))
				{
					$sql = $sql.", ";
				}
				else
				{
					$sql = $sql.")";
				}
			}
			//echo "<BR><BR>".$sql."<BR>";
			$result = ibase_query($this->DB, $sql);
		}
		$this->Disconnect();
	}
	
	function TemtTableGetDate($TableName, $FieldList, $GroupField, $SumField, $OrderField) //Отримати дані з тимчасової таблиці
	{
		$this->Connect();
		$sql = "
				select ";
		for ($j=1; $j<=count($FieldList); $j++)
		{
			$ThisFieldIsSum = false;
			for ($k=1; $k<=count($SumField); $k++)
			{
				if ($j==$SumField[$k])
				{
					$sql = $sql."sum(FIELD_".$j.") as FIELD_".$j."";
					$ThisFieldIsSum = true;
				}
			}
			if (!$ThisFieldIsSum)
			{
				$sql = $sql."FIELD_".$j;
			}
						
			if ($j<>count($FieldList))
			{
				$sql = $sql.", ";
			}
			else
			{
				$sql = $sql." ";
			}
		}
		$sql = $sql."
				from ".$TableName."
		";
		if (count($GroupField)>0)
		{
			$sql=$sql."group by ";
			for ($j=1; $j<=count($FieldList); $j++)
			{
				for ($k=1; $k<=count($GroupField); $k++)
				{
					if ($j==$GroupField[$k])
					{
						$sql = $sql."FIELD_".$j;
						if ($k<>count($GroupField))
						{
							$sql = $sql.", ";
						}
						else
						{
							$sql = $sql." ";
						}	
					}
				}
			}
		}
		if ($OrderField<>0)
		{
			//$sql = $sql." order by FIELD_".$OrderField." DESC";
			$sql = $sql." order by FIELD_".$OrderField;
		}
//		echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_row($result))
		{
			$Sum = array();
			for ($j=1; $j<=count($FieldList); $j++)
			{
				$Sum[$j] = $row[$j-1];
			}
			$Table[$i] = $Sum;
			$i++;
		}
		//$col = ibase_field_info($result, 0);
		//echo $col["name"];
		$this->Disconnect();
		return $Table;
	}
	
	function GetPrice() //Залишки товарів з цінами
	{
		$this->Connect();
		$sql = "
			select SBARCODE, SNAME, SSERIACODE, NQUANT, NPRICE, SPARTNUMB, SPRODUSER, SCODE1C, SCODE, NTAXSUM 
			from V_NOMEN_WITH_LAST_PRICE
			where NQUANT>0
			order by SNAME
		";

		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SBARCODE"];
			$Sum[2] = $row["SNAME"];
			$Sum[3] = $row["SSERIACODE"];
			$Sum[4] = $row["NQUANT"];
			$Sum[5] = $row["NPRICE"];
			$Suma = $row["NQUANT"] * $row["NPRICE"];
			$Sum[6] = $Suma;
			$Sum[7] = $row["SPARTNUMB"];
			$Sum[8] = $row["SPRODUSER"];
			$Sum[9] = $row["SCODE1C"];
			$Sum[10] = $row["SCODE"];
			$Sum[11] = $row["NTAXSUM"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetPriceWithEDRPOU($EDRPOU) //Залишки товарів з цінами і ЕДРПУ
	{
		$this->Connect();
		$sql = "
			select DISTINCT
			 T.name,
       T.CODE,
       B.BARCODE,
       S.CODE as seria,
       C.NAME as vendor,
			 C.edrpou as EDRPOU,
       PT.PARTNUMB,
       sum(I.QUANT) as QUANT,
       P.PRICE,
			 P.PRICEIN
from T_NOMEN T,
     T_BARCODE B,
     T_PRICE P,
     T_INVENTORY I,
     T_SERIES S,
     T_PART PT,
     T_VENDOR VEND,
     T_CONTRAGENT C
where B.ID_NOMEN = T.ID and
      P.ID_BARCODE = B.ID and
      I.ID_BARCODE = P.ID_BARCODE and
      I.ID_PART = P.ID_PART and
      S.ID = P.ID_SERIES and
      PT.ID = P.ID_PART and
      I.ID_SERIES = P.ID_SERIES and
      VEND.ID = PT.ID_VENDOR and
			QUANT>0 and";
if ($EDRPOU<>"") {$sql=$sql." EDRPOU='".$EDRPOU."' and ";}
$sql = $sql."
      C.ID = VEND.ID_CONTRAGENT
      and P.ID IN (SELECT MAX(ID) FROM T_PRICE PR
        WHERE PR.ID_BARCODE = B.ID
              AND PR.ID_PART = PT.ID
              AND PR.ID_SERIES = S.ID)
group by
				T.name,
			  T.CODE,
       B.BARCODE,
       S.CODE,
       C.NAME,
			 C.edrpou,
       PT.PARTNUMB,
       P.PRICE,
			 P.PRICEIN
		";

		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["BARCODE"];
			$Sum[2] = $row["NAME"];
			//$Sum[3] = $row["SERIA"];
			//$Sum[3] = $row["VENDOR"]." (".$row["UDRPOU"].")";
			$Sum[3] = $row["VENDOR"];
			$Sum[4] = $row["PARTNUMB"];
			$Sum[5] = number_format($row["QUANT"],2,",","");
			$Sum[6] = number_format($row["PRICE"],2,",","");
			$Suma = $row["QUANT"] * $row["PRICE"];
			$Sum[7] = $Suma;
			$Sum[8] = number_format($row["PRICEIN"],2,",","");
			$SumaIN = $row["QUANT"] * $row["PRICEIN"];
			$Sum[9] = $SumaIN;
		
			//$Sum[8] = $row["NUMB"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function GetZakaz() //Показати, що замовили на касах
	{
		$this->Connect();
		/*
		$sql = "
			select *
			from V_ORDER
			order by ID
		";
		*/
		$sql = "
		select T.ID,
       T.ID_NOMEN,
       N.NAME,
       T.QUANT,
       N.MIN_RESTS,
       T.USER_LOGIN, T.
       DREG,
       n.code,
       n.DIVISIBILITY
		from T_ORDER T, T_NOMEN N
		where N.ID = T.ID_NOMEN";

		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["ID"];
			$Sum[2] = $row["ID_NOMEN"];
			$Sum[3] = $row["NAME"];
			$Sum[4] = $row["QUANT"];
			$Sum[5] = $row["MIN_RESTS"];
			$Sum[6] = $row["USER_LOGIN"];
			$Sum[7] = $row["DREG"];
			$Sum[8] = $row["CODE"];
			$Sum[9] = $row["DIVISIBILITY"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function DellZakaz($id) //Видалити, що замовили на касах
	{
		$this->Connect();
		$sql = "
			execute procedure P_ORDER_DELETE (".$id.")
		";
		$result = ibase_query($this->DB, $sql);
		$this->Disconnect();
	}
	
	function ShowSalesForAnalitic($Date1, $Date2, $ID_Analitic, $Code1, $Code2) //Отримати продані товари по аналітиці (Оптіма a.code = 1)
	{
		$this->Connect();
		$sql = "
				select  *
				from P_REP_RECEIPTS_BY_ANALITIC('".$ID_Analitic."', '".$Date1."')
		";
		//rows 1000
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $Code1; //Код аптеки
			$Sum[2] = $Code2; //Код закажчика
			$Sum[3] = $this->CurrentApteka->FullName;
			$Sum[4] = $this->CurrentApteka->Address;
			$Sum[5] = $row["SNOMEN_NAME"];
			$Sum[6] = $row["BARCODE"];
			$Sum[7] = date("Ymd", strtotime($row["REC_DATE"]));
			$Sum[8] = date("H:i:s", strtotime($row["REC_DATE"]));
			$Sum[9] = number_format($row["QUANT"], 4, ".", "");
			if ($Sum[9]==0)
			{
				$Sum[10] = 0;
			}
			else
			{
				$Sum[10] = $row["SALE_PRICE"]/$Sum[9]; //Ціна із знижкою (У вови замість ціни сума)
			}
			$Sum[11] = $row["NUMB_DOC"];
			$Sum[12] = $row["RECEIPT_NUMB"]; // $row["NUMB"]; //Рецепт
			
			if ($ID_Analitic=='1')//Для Оптіми
			{
				//if ($row["SSERIES_CODE"]=="БЕЗ СЕРІЇ") {$row["SSERIES_CODE"]="-";}
				$Sum[13] = $row["SSERIES_CODE"];
				$Sum[14] = $row["NID_NOMEN"];
				$Sum[15] = $row["SVANLVAL"];
				$Sum[16] = $row["INDOCNUM"];
				$Sum[17] = $row["REST_QUANT"];
				$Sum[18] = $row["PRICEIN"];
				$Sum[19] = $row["PARTNUMB"];
				$Sum[20] = $row["EDRPOU"];
				$Sum[21] = $row["ID_PART"];
			}
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function OptimaSalesControl($Date1, $ID_Analitic, $Code1, $Code2) //Отримати продані товари по аналітиці (Оптіма a.code = 1)
	{
		$this->Connect();
		/*
		$sql = "
				select  p.indocnum, sum(p.quant*p.pricein) as STOTAL
				from P_REP_RECEIPTS_BY_ANALITIC('".$ID_Analitic."', '".$Date1."') p
				where (p.edrpou='21642228') and (p.quant>0)
				group by p.indocnum
				order by p.indocnum
		";
		*/
		$sql = "
				select  p.indocnum, p.quant, p.pricein
				from P_REP_RECEIPTS_BY_ANALITIC('".$ID_Analitic."', '".$Date1."') p
				where (p.edrpou='21642228') and (p.quant>0)
				order by p.indocnum
		";
		//rows 1000
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			//$Sum[1] = $Code1; //Код аптеки
			//$Sum[2] = $Code2; //Код закажчика
			$Sum[1] = $row["INDOCNUM"];
			$Sum[2] = $row["QUANT"];
			$Sum[3] = $row["PRICEIN"];

			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	

	function ShowZeroForAnalitic($Date1, $Date2, $ID_Analitic, $Code1, $Code2) //Отримати залишкі товари по аналітиці (Оптіма a.code = 1)
	{
		$this->Connect();
		/*
		$sql = "
			select i.*, vna.VANLVAL
      from v_nomen_inv i , V_NOMEN_ANL_VALUE vna
			where
			exists(
				select 1
				from v_nomen_anl a
				where
					a.nid_nomen = i.nid and
					a.sbarcode = i.barcode and
					a.nid_nomen = vna.nid_nomen and
					a.sanalytic_code = '".$ID_Analitic."'
				)			
		";
		*/
		$sql = "
				select  *
				from P_REP_RECEIPTS_BY_ANALITIC('".$ID_Analitic."', '".$Date1."')		
		";
		//rows 1000
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SNOMEN_NAME"];
			$Sum[2] = $row["BARCODE"];			
			$Sum[3] = $row["PRICEIN"];
			$Sum[4] = $row["PARTNUMB"];
			if ($row["SSERIES_CODE"]=="БЕЗ СЕРІЇ") {$row["SSERIES_CODE"]="-";}
			$Sum[5] = $row["SSERIES_CODE"];
			$Sum[6] = $row["REST_QUANT"];
			$Sum[7] = $row["SVANLVAL"];
			$Sum[8] = $row["INDOCNUM"];
			$Sum[9] = $row["EDRPOU"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function array_to_CSV($data, $FileName) //Записати масив в CSV
	{
			$outstream = fopen($FileName, 'w+');
			//for ($i=1; $i<=count($data); $i++)
			foreach ($data as $row)
			{
				//$row = $data[$i];				
				/*
				if (gettype($row)=='double')
				{
					$row = number_format($row, 2, ",", "");
				}
				*/
				fputcsv($outstream, $row, ';', ' ');
			}
			fclose($outstream);
	}
	
	function multi_attach_mail($to, $files, $sendermail, $subject, $message) //Відправити лист з вкладенням
	{ 
     // email fields: to, from, subject, and so on
     $from = $sendermail; 
    //$subject = date("d.M H:i")." F=".count($files);
     $headers = "From: $from";
  
     // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 
     // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
     // multipart boundary //text/plain
    $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
     "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
 
     // preparing attachments
     for($i=0;$i<count($files);$i++){
         if(is_file($files[$i])){
             $message .= "--{$mime_boundary}\n";
             $fp =    @fopen($files[$i],"rb");
         $data =    @fread($fp,filesize($files[$i]));
                     @fclose($fp);
             $data = chunk_split(base64_encode($data));
             $message .= "Content-Type: application/octet-stream; name=\"".basename($files[$i])."\"\n" . 
            "Content-Description: ".basename($files[$i])."\n" .
             "Content-Disposition: attachment;\n" . " filename=\"".basename($files[$i])."\"; size=".filesize($files[$i]).";\n" . 
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
             }
         }
     $message .= "--{$mime_boundary}--";
     $returnpath = "-f" . $sendermail;
     $ok = mail($to, $subject, $message, $headers, $returnpath); 
    if($ok){ return $i; } else { return 0; }
  }
	
	function SendMail_via_GMAIL($mailTO, $mailToString, $mailFromString, $mailFromEmail, $Subject, $MailBody, $MailBodyHTML, $Attachment)
	{
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSMTP(); // telling the class to use SMTP

		try {
			$mail->CharSet = "windows-1251";
			$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
			//$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
			$mail->Username   = $GLOBALS["glGMAIL_user"];  			// GMAIL username
			$mail->Password   = $GLOBALS["glGMAIL_password"];      // GMAIL password
			$mail->AddAddress($mailTO, $mailToString);
			$mail->SetFrom($GLOBALS["glGMAIL_user"], $mailFromString);
			$mail->AddReplyTo($mailFromEmail, $mailFromString);
			$mail->Subject = $Subject;
			//$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			$mail->Body = $MailBody;
			$mail->MsgHTML($MailBodyHTML);
			for ($i=0; $i<count($Attachment); $i++)
			{
				echo "Attachment: ".$Attachment[$i]."<BR>";
				$mail->AddAttachment($Attachment[$i]);
				//$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
			}
			$mail->Send();
			echo "Message Sent OK</p>\n";
		} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
		}
	}
	
	function GetAllAnalitic() //Список аналітик
	{
		$this->Connect();
		$sql = "
				select *
				from t_analytic
				order by ID
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["ID"];
			$Sum[2] = $row["CODE"];
			$Sum[3] = $row["NAME"];
			$Sum[4] = $row["COMMENTS"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function SaveAnalitic($Nomen, $Barcode, $Analitic, $Value) //Зберегти аналітику для товару
	{
		//$this->Connect();
		/*
		$sql = "
				execute procedure P_NOMEN_ANL_BASE_INSERT (".$Nomen.", ".$Barcode.", ".$Analitic.")
		";
		*/
		$sql = "
				execute procedure P_NOMEN_ANL_INSERT ('".$Nomen."', '".$Barcode."', '".$Analitic."')
		";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		$AnaliticID = $row["ID"];
		if (trim($Value)<>'')
		{
			$sql = "
				execute procedure P_NOMEN_ANL_VALUE_INSERT (".$AnaliticID.", '".$Value."')
			";
		}
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		//$this->Disconnect();
	}
	
	function ViewAnalitic($AnaliticID) //Переглянути аналітику
	{
		$this->Connect();

		$sql = "
		select z.*, vna.VANLVAL from (select distinct vn.SBARCODE, vn.SNOMEN_NAME, vn.NID_NOMEN, vn.snomen_code,
 vn.NID_BARCODE, vn.NID, c.NAME as CONTRAGENT, vn.NID_ANALYTIC
 from V_NOMEN_ANL vn, T_BARCODE b, T_PRODUCER p, T_CONTRAGENT c
 where (vn.NID_BARCODE=b.ID) and (b.ID_PRODUCER=p.ID) and
 (p.ID_CONTRAGENT=c.ID)
 and (vn.SANALYTIC_CODE='".$AnaliticID."')) z
 left join V_NOMEN_ANL_VALUE vna on vna.NID_NOMEN_ANL = z.NID and vna.NID_BARCODE = z.NID_BARCODE
 order by z.SNOMEN_NAME
 
		";
		//rows 100
		//echo $sql;
		
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SBARCODE"];
			$Sum[2] = $row["SNOMEN_NAME"];
			$Sum[3] = $row["NID_NOMEN"];
			$Sum[4] = $row["NID_BARCODE"];
			$Sum[5] = $row["NID"];
			$Sum[6] = $row["CONTRAGENT"];
			$Sum[7] = $row["VANLVAL"];
			$Sum[8] = $row["SNOMEN_CODE"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function CodeInAnalitic($AnaliticID, $TovarCod) //Чи е товар в аналітиці
	{
		//$this->Connect();

		$sql = "
		select n.ID
		from t_nomen_anl na, t_nomen n, t_analytic ana
		where
			(na.id_nomen=n.id) and
			(na.id_analytic=ana.id) and
			(n.code='".$TovarCod."') and
			(ana.code='".$AnaliticID."')
		rows 1
		";
		//echo $sql;
		
		$result = ibase_query($this->DB, $sql);
		$row=ibase_fetch_assoc($result);
		//$this->Disconnect();
		if ($row==false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function RowAnaliticByNomenCode($AnaliticID, $NomenCode) //Дані з аналітики для одного елементу
	{
		$this->Connect();
		$sql = "
		select z.*, vna.VANLVAL from (select distinct vn.SBARCODE, vn.SNOMEN_NAME, vn.NID_NOMEN, vn.SNOMEN_CODE,
	vn.NID_BARCODE, vn.NID, c.NAME as CONTRAGENT
	from V_NOMEN_ANL vn, T_BARCODE b, T_PRODUCER p, T_CONTRAGENT c
	where (vn.NID_BARCODE=b.ID) and (b.ID_PRODUCER=p.ID) and
	(p.ID_CONTRAGENT=c.ID)
	and (vn.SANALYTIC_CODE='".$AnaliticID."')
	and (vn.SNOMEN_CODE='".$NomenCode."')
	) z
	left join V_NOMEN_ANL_VALUE vna on vna.NID_NOMEN_ANL = z.NID
	order by z.SNOMEN_NAME
			";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["SBARCODE"];
			$Sum[2] = $row["SNOMEN_NAME"];
			$Sum[3] = $row["NID_NOMEN"];
			$Sum[4] = $row["NID_BARCODE"];
			$Sum[5] = $row["NID"];
			$Sum[6] = $row["CONTRAGENT"];
			$Sum[7] = $row["VANLVAL"];
			$Sum[8] = $row["SNOMEN_CODE"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}
	
	function DeleteAnalitic($ID) //Видалити аналітику для товару
	{
		//$this->Connect();
		$sql = "
				execute procedure P_NOMEN_ANL_BASE_DELETE (".$ID.")
		";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		//$this->Disconnect();
	}

	function SumFromAparat($Date1, $Date2) //Суми по апаратам
	{
		$this->Connect();
		$sql = "
		select
        PAY_DESK,
        sum(CHECK_SUM) as SUM1,
        sum(TAX_TOTAL_FULL) as SUM2
	from V_RECEIPTS
        where
            (PRINT_DATE is not null) and
            (REC_DATE>='".$Date1."') and
            (REC_DATE<='".$Date2."')
	group by pay_desk
		";
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$TotalSum1=0;
		$TotalSum2=0;
		$TotalSum3=0;
		$TotalSum4=0;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["PAY_DESK"];
			$Sum[2] = $row["SUM1"];
			$Sum[3] = $row["SUM2"];
			$Sum[4] = 0;
			$Sum[5] = 0;
			$TotalSum1=$TotalSum1+$Sum[2];
			$TotalSum2=$TotalSum2+$Sum[3];
			$TotalSum3=$TotalSum3+$Sum[4];
			$TotalSum4=$TotalSum4+$Sum[5];
			$Table[$i] = $Sum;
			$i++;
		}
		$Sum = array();
		$Sum[1] = "";
		$Sum[2] = $TotalSum1;
		$Sum[3] = $TotalSum2;
		$Sum[4] = $TotalSum3;
		$Sum[5] = $TotalSum4;
		$Table[$i] = $Sum;

		$this->Disconnect();
		return $Table;
	}
	
	function OborotByParts($PartID, $Date) //Оборотка по партіях
	{
		$this->Connect();
		$sql = "
			select *
			from P_REP_OBOROTKA('".$Date."')
			";
		if ($PartID<>"")
		{
			$sql = $sql."
				where( PARTNO='".$PartID."')";
		}
		else
		  $sql = $sql."
			  where
			    (SNAME_NOMEN_NKL<>SNAME_NOMEN_BASE)
			and (BARCODE_NKL<>BARCODE_BASE)
			and (QUANT_DIFF<>0)
			";
		
		//echo $sql;
		$Table = array(); //Для двовимірного масиву
		$i = 1;
		$result = ibase_query($this->DB, $sql);
		while ($row=ibase_fetch_assoc($result))
		{
			$Sum = array();
			$Sum[1] = $row["PARTNO"];
			$Sum[2] = $row["SNOMEN_CODE"];
			$Sum[3] = $row["SNAME_NOMEN_NKL"];
			$Sum[4] = $row["SNAME_NOMEN_BASE"];
			$Sum[5] = $row["BCODE1C"];
			$Sum[6] = $row["BARCODE_NKL"];
			$Sum[7] = $row["BARCODE_BASE"];
			$Sum[8] = $row["PRICE"];
			$Sum[9] = $row["QUANT_IMP"];
			$Sum[10] = $row["SUM_IMP"];
			$Sum[11] = $row["QUANT_SALE"];
			$Sum[12] = $row["SUM_SALE"];
			$Sum[13] = $row["QUANT_OUT"];
			$Sum[14] = $row["SUM_OUT"];
			$Sum[15] = $row["QUANT_REST"];
			$Sum[16] = $row["SUM_REST"];
			$Sum[17] = $row["QUANT_REAL"];
			$Sum[18] = $row["QUANT_DIFF"];
			$Table[$i] = $Sum;
			$i++;
		}
		$this->Disconnect();
		return $Table;
	}

	function ChangeCheckPayType($ID) //Змінити оплату в чеку Карткою-Готівкою
	{
		$this->Connect();
		$sql = "
			update T_RECEIPTS
			set CARDPAYSUM=TOTAL
			where NUMB=".$ID."
		";

		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		//$row=ibase_fetch_assoc($result);
		$this->Disconnect();
		//return $Table;
	}
	
	function FixReturn() //Виправити суми в поверненнях
	{
		$this->Connect();
		$sql = "
			update T_RECEIPTS
            set TOTAL_FULL=TOTAL,
            CARDPAYSUM=0
            where
            (total<0) and
            (total<>total_full)
		";
		//echo $sql;
		$result = ibase_query($this->DB, $sql);
		$this->Disconnect();
	}
	
	function ShowSalesForAnalitic2($Date1, $Date2, $AnaliticCode, $GroupType) //Продажі по аналітиці за період
	{
		$this->Connect();
		$Table = array();
		$i = 1;
		if ($GroupType==1) //Детально по чекам
		{
      /*
			$sql = "
			select REC_DATE, NUMB, NAME_USERS, SNOMEN_NAME, SBARCODE, c1.name as PRODUCER, c2.NAME as VENDOR, PARTNUMB,
   ser.CODE as SERIE, QUANT, r.TOTAL_FULL, DISCONT, SALE_PRICE, TOTAL, prc.price
   
   from T_REC_SPEC t, T_RECEIPTS r, V_NOMEN_ANL a, T_USERS u, T_BARCODE b, T_PART p,
      T_PRODUCER pr, T_VENDOR v, T_CONTRAGENT c1, T_CONTRAGENT c2, T_SERIES ser, T_PRICE prc
   where r.id = t.id_receipt
   and t.id_barcode = a.NID_BARCODE
   and a.SANALYTIC_CODE = '".$AnaliticCode."'
   and cast(REC_DATE as date) between '".$Date1."' and '".$Date2."'
   and u.ID = r.SELLER
   and b.id = t.id_barcode
   and p.id = t.id_part
   and pr.id = b.id_producer
   and c1.id = pr.id_contragent
   and v.id = p.id_vendor
   and c2.id = v.id_contragent
   and t.ID_SERIES = ser.ID
   and prc.id = t.id_price		
		";
      */
      $sql = "
      select r.REC_DATE, r.NUMB, NAME_USERS, SNOMEN_NAME, SBARCODE, c1.name as PRODUCER,
       c2.NAME as VENDOR, PARTNUMB, ser.CODE as SERIE, QUANT, r.TOTAL_FULL, DISCONT,
       SALE_PRICE, TOTAL, prc.price, r.credit_sum,
       (select tr.numb from t_receip tr join t_receip_sale_link l on tr.id = l.id_receip where l.id_sale = r.id) as REC_NUM,
       (select NVALUE from P_GET_NOMEN_ANL_VALUE(a.sanalytic_code, t.id_barcode)) as ANL_VALUE
from T_REC_SPEC t,
     T_RECEIPTS r,
     V_NOMEN_ANL a,
     T_USERS u,
     T_BARCODE b,
     T_PART p,
     T_PRODUCER pr,
     T_VENDOR v,
     T_CONTRAGENT c1,
     T_CONTRAGENT c2,
     T_SERIES ser,
     T_PRICE prc

where r.id = t.id_receipt and
      t.id_barcode = a.NID_BARCODE and
      a.SANALYTIC_CODE = '".$AnaliticCode."' and
      cast(r.REC_DATE as date) between '".$Date1."' and '".$Date2."' and
      u.ID = r.SELLER and
      b.id = t.id_barcode and
      p.id = t.id_part and
      pr.id = b.id_producer and
      c1.id = pr.id_contragent and
      v.id = p.id_vendor and
      c2.id = v.id_contragent and
      t.ID_SERIES = ser.ID and
      prc.id = t.id_price
      ";
    
    
			
			//echo $sql."<BR>";
			$result = ibase_query($this->DB, $sql);
			while ($row=ibase_fetch_assoc($result))
			{
				$Sum = array();
				$Sum[1] = $row["REC_DATE"];
				$Sum[2] = $row["NUMB"];
				$Sum[3] = $row["NAME_USERS"];
				$Sum[4] = $row["SNOMEN_NAME"];
				$Sum[5] = $row["SBARCODE"];
				$Sum[6] = $row["PRODUCER"];
				$Sum[7] = $row["VENDOR"];
				$Sum[8] = $row["PARTNUMB"];
				$Sum[9] = $row["SERIE"];
				$Sum[10] = $row["QUANT"];
				$Sum[11] = $row["PRICE"];
				//$Sum[12] = $row["TOTAL_FULL"];
				$Sum[12] = $Sum[10]*$Sum[11];
				//$Sum[13] = 0;//$row["DISCONT"]; //Після оновлення всіх баз поставити новий відсоток
				//$Sum[14] = $row["PRICE"];
				$Sum[13] = $row["TOTAL"];
        $Sum[14] = $row["ANL_VALUE"];
        $Sum[15] = $row["REC_NUM"];
				
				$Table[$i] = $Sum;
				$i++;
			}
		}
		if ($GroupType==2) //Групуємо по продавцях
		{
			$sql = "
			select NAME_USERS, sum(QUANT*prc.PRICE) as TOTAL
			
			from T_REC_SPEC t, T_RECEIPTS r, V_NOMEN_ANL a, T_USERS u, T_BARCODE b, T_PART p,
      T_PRODUCER pr, T_VENDOR v, T_CONTRAGENT c1, T_CONTRAGENT c2, T_SERIES ser, T_PRICE prc
			
   where r.id = t.id_receipt
   and t.id_barcode = a.NID_BARCODE
   and a.SANALYTIC_CODE = '".$AnaliticCode."'
   and cast(REC_DATE as date) between '".$Date1."' and '".$Date2."'
   and u.ID = r.SELLER
   and b.id = t.id_barcode
   and p.id = t.id_part
   and pr.id = b.id_producer
   and c1.id = pr.id_contragent
   and v.id = p.id_vendor
   and c2.id = v.id_contragent
   and t.ID_SERIES = ser.ID
   and prc.id = t.id_price
			
			group by NAME_USERS
			";
			$result = ibase_query($this->DB, $sql);
			while ($row=ibase_fetch_assoc($result))
			{
				$Sum = array();
				$Sum[1] = $row["NAME_USERS"];
				//$Sum[2] = $row["TOTAL_FULL"];
				$Sum[2] = $row["TOTAL"];
				$Table[$i] = $Sum;
				$i++;
			}
		}
		//echo $sql;
		$this->Disconnect();
		return $Table;
	}
	
	function log($message)
	{
		$LogFileName='rep.log';
		$rep_log=fopen($LogFileName,'a+');
		/*
		if (file_exists($LogFileName))
		{
			$rep_log=fopen($LogFileName,'a+');
		}
		else
		{
	    
		}
		*/
	  $LogString = date("d.m.Y H:i:s").' '.$message.'
';
	  fputs($rep_log,$LogString);
	  fclose($rep_log); 
	}
	
	function SetFarmGroup($Code, $Name)
	{
		$this->Connect();
		$sql = "
				execute procedure P_PREPARAT_GROUP_INSERT (".$Code.", '".$Name."', '')
				";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		$this->Disconnect();
	}
	
	function LinkFarmGroup($NomenCode, $GroupCode)
	{
		//$this->Connect();
		$sql = "
				select ID
				from T_PREPARAT_GROUP
				where CODE=".$GroupCode."
				";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		$ID=ibase_fetch_assoc($result);
		$ID = $ID["ID"];
		$sql = "
				update T_NOMEN
				set ID_GROUP=".$ID."
				where CODE=".$NomenCode."
				";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		//$this->Disconnect();
	}
	
	function LinkCardToAnalitic($CardCode, $AnaliticCode)
	{
		$this->Connect();
		$ID = 0;
		$sql = "
				SELECT * FROM P_CARD_ANL_INSERT('Ковальчук', '".$CardCode."', '".$AnaliticCode."', 'DISCOUNT', 'PRC', 'DISCOUNT', 'YES', 'NO')
				";
		//echo $sql."<BR>";
		$result = ibase_query($this->DB, $sql);
		$ID=ibase_fetch_assoc($result);
		if ($ID["ID"]>0)
		{
			$ID = $ID["ID"];
		}
		//echo $sql."<BR>";
	  $this->Disconnect();
		return $ID;
	}

    function UpdateSerieDateExp($seriya_id, $seriya_name, $barcode, $quant, $date)
    {
        $this->Connect();
        $sql = '
				SELECT 
				  T_INVENTORY.ID,
				  T_INVENTORY.ID_BARCODE,
				  T_INVENTORY.ID_SERIES,
				  T_INVENTORY.ID_PART
				FROM
				  T_INVENTORY, T_BARCODE
				where  
				  T_INVENTORY.ID_BARCODE = T_BARCODE.ID and 				  
				  T_INVENTORY.ID_SERIES = '.$seriya_id.' and
				  T_BARCODE.BARCODE = \''.$barcode.'\'				  
				';
        $result = ibase_query($this->DB, $sql);
        $inventory_rows = ibase_fetch_assoc($result);
        $id_inventory = $inventory_rows['ID'];
        $id_barcode = $inventory_rows['ID_BARCODE'];
        $id_series = $inventory_rows['ID_SERIES'];
        $id_part = $inventory_rows['ID_PART'];

        $new_serie_name = $seriya_name;
        if ($seriya_name=='БЕЗ СЕРІЇ' || empty($seriya_name)) {
            $result = false;
            $new_serie_name = $this->generateRandomString();
            while ($result === true) {
                $sql = 'select ID from T_SERIES where CODE=\'' . $new_serie_name . '\'';
                $result = ibase_query($this->DB, $sql);
            }

            //Створюю нову серію
            $sql = 'select gen_id(ID_GEN, 1) from rdb$database;';
            $result = ibase_query($this->DB, $sql);
            $id_series = ibase_fetch_assoc($result);
            $id_series = $id_series['GEN_ID'];

            $sql = 'insert into T_SERIES (ID, CODE, DEND) values ('.$id_series.', \''.$new_serie_name.'\', \''.$date.'\')';
            ibase_query($this->DB, $sql);

            //Оновимо інфу по серії
            //T_INV_SPEC, T_PRICE, T_REC_SPEC

            $sql = 'update T_INVENTORY set ID_SERIES='.$id_series.' where ID='.$id_inventory;
            ibase_query($this->DB, $sql);

            $sql = 'update T_PRICE set ID_SERIES='.$id_series.' where ID_SERIES='.$seriya_id.' and ID_BARCODE='.$id_barcode.' and ID_PART='.$id_part;
            ibase_query($this->DB, $sql);

            $sql = 'update T_REC_SPEC set ID_SERIES='.$id_series.' where ID_SERIES='.$seriya_id.' and ID_BARCODE='.$id_barcode.' and ID_PART='.$id_part;
            ibase_query($this->DB, $sql);
        } else {
            //Добавимо дату для існуючої серії
            $sql = 'update T_SERIES set DEND=\''.$date.'\' where ID='.$id_series;
            ibase_query($this->DB, $sql);
        }

        //return $inventory_rows;
        return [
            'series_name' => $new_serie_name,
            'series_id' => $id_series,
        ];
        $this->Disconnect();
    }

    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
	
	function FTP_Put($ServerURL, $UserName, $Password, $FileName, $RemotDir='')
	{
		$conn_id = ftp_connect($ServerURL) or die("Не удалось установить соединение с ".$ServerURL);
		$login_result = ftp_login($conn_id, $UserName, $Password); 
		// проверка соединения
		if ((!$conn_id) || (!$login_result))
		{
			die("Не удалось подключиться к FTP-серверу!");
		}
		// включение пассивного режима
		ftp_pasv($conn_id, true);
		
		if ($RemotDir<>'')
		{
			if (ftp_chdir($conn_id, $RemotDir))
			{
				echo "PWD: ".ftp_pwd($conn_id)."<BR>";
			}
			else
			{
				echo "error change dir ".$RemotDir."<BR>";
			}
		}
		// загрузка файла 
		if (ftp_put($conn_id, basename($FileName), $FileName, FTP_ASCII))// FTP_BINARY
		{
			echo $FileName." успешно загружен на сервер\n ".basename($FileName)." <BR>";
		}
		else
		{
			echo "Не удалось загрузить $file на сервер\n<BR>";
		}
		
		//ftp_mkdir($conn_id,'111');
		//echo "PWD: ".ftp_pwd($conn_id)."<BR>";
		//$contentsArray = ftp_nlist($conn_id, ' -la');
		//print_r($contentsArray);
		
		ftp_close($conn_id);
	}

	function runSQL($sql)
	{
		//$this->Connect();
        echo $sql.'<br>';
		$result = ibase_query($this->DB, $sql);
//		if ($result===false){
//		    echo '<b>error:</b> ';
//        }
//		while ($row=ibase_fetch_assoc($result))
	   // $this->Disconnect();
		return $result;
	}

  
  function ExecURL($url, $post=array()){    
    $curler = curl_init();
    curl_setopt($curler, CURLOPT_URL, $url);
    curl_setopt($curler, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($curler, CURLOPT_CONNECTTIMEOUT, 20); 
    curl_setopt($curler, CURLOPT_TIMEOUT, 20);  	    

    //if ($proxy!=''){
        //curl_setopt($curler, CURLOPT_PROXY, $proxy);
        //curl_setopt($curler, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');

    //}
    //curl_setopt($curler, CURLOPT_PROXY, '23.88.112.108:80');
    
    curl_setopt($curler, CURLOPT_USERAGENT, 'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
    curl_setopt($curler, CURLOPT_FOLLOWLOCATION, 1);  
    curl_setopt($curler, CURLOPT_SSL_VERIFYPEER, FALSE); // this line makes it work under https
    if(!empty($post)){
        curl_setopt($curler, CURLOPT_POST, 1);
        curl_setopt($curler, CURLOPT_POSTFIELDS, http_build_query($post));        
    }
    /*
    if(!empty($post_file)){
        curl_setopt($curler, CURLOPT_POST, true);
        curl_setopt($curler, CURLOPT_POSTFIELDS, $post_file);          
    }
    */
    
    $content = curl_exec($curler);

    $code=curl_getinfo($curler,CURLINFO_HTTP_CODE);
	
    if ($code!='200'){
        curl_setopt($curler, CURLOPT_USERAGENT, '');
        $content = curl_exec($curler);
        $code=curl_getinfo($curler,CURLINFO_HTTP_CODE);
    }
	
    curl_close($curler);
    return $content;
  }
  
}
?>