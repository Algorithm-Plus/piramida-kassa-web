<?php
class Apteka
{
	var $FullName;
	var $ButtonName;
	var $MnemoCod;
	var $IP;
	var $BaseName;
	var $User;
	var $Password;
	var $Address;
	var $Tel;
	var $Active;
	var $DefectudaKoef;
	var $StoreID;

	function __construct($FullName, $ButtonName, $MnemoCod, $IP, $BaseName, $User, $Password, $Address, $Tel, $DefectudaKoef, $StoreID)
	{
		$this->FullName = $FullName;
		$this->ButtonName = $ButtonName;
		$this->MnemoCod = $MnemoCod;
		$this->IP = $IP;
		$this->BaseName = $BaseName;
		$this->User = $User;
		$this->Password = $Password;
		$this->Address = $Address;
		$this->Tel = $Tel;
		$this->DefectudaKoef = $DefectudaKoef;
		$this->StoreID = $StoreID;
		//$this->Connect();
		//$this->Active = 1;
	}

	function Connect()
	{    
    $res = preg_match('/(.*)\/(.*)/', $this->IP, $port);    
    if ($res){      
      $connect_string = 'tcp://'.$port[1].':'.$port[2];
    }
    else{
      $connect_string = 'tcp://'.$this->IP.':3050';
    }
    $socket = @stream_socket_client($connect_string, $errno, $errstr, 2);
    if (!$socket) {
      echo $connect_string."<br />\n";
      echo "$errstr ($errno)<br />\n";
      $this->Active = 0;	
    }
    else{
      $result = ibase_connect(
			$this->IP.":".$this->BaseName,
			$this->User,
			$this->Password
			);
      if ($result==false)
      {
        $this->Active = 0;
      }
      else
      {
        //echo "Ok";
        $this->Active = 1;
      }
    }
    
		
	}
}
?>