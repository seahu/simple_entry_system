<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Tracy\Debugger;


class HomepagePresenter extends BasePresenter
{
	/** @var Nette\Database\Context */
   private $database;
	//protected i2cAddr1=0x20; // my frist devolepment baseboard i2c address
	//protected i2cAddr1=0x24; // my frist devolepment displayboard i2c address
	protected $i2cAddr1=0x38; // production baseboard i2c address
	protected $i2cAddr2=0x3c; // production displayboard i2c address


   public function __construct(Nette\Database\Context $database)
   {
        $this->database = $database;
   }
    
	public function renderDefault()
	{
		// naplneni promenych pro sablonu
      $this->template->number_all = $this->database->table('card')->count('*');  
      $this->template->number_in = $this->database->table('card')->where('status', 1)->count('*');
      $this->template->number_groups = $this->database->table('group')->count('*');
      $this->template->number_card_readers = $this->database->table('card_reader')->count('*');
      $this->template->number_logs = $this->database->table('log')->count('*');
      $this->template->number_logs_need_sync = $this->database->table('log')->where('sync',0)->where('card.PK!=""')->count('*');
      $this->template->entry = $this->database->table('log')->where('card_reader.direction', 1)->order('id DESC')->limit(20);
      $this->template->exits = $this->database->table('log')->where('card_reader.direction', 0)->order('id DESC')->limit(20);
		
		// stav rele2 - tj. fire tlacitko (pro trvale otevreni dveri pri pozaru)
		$output=shell_exec ("/usr/bin/sudo /usr/sbin/i2cget -y 1 ".$this->i2cAddr1);
		//Debugger::barDump($output, 'output');
		//Debugger::barDump(strlen($output), 'len');
		$val=substr($output, 0, 4);
		Debugger::barDump($val, 'val str1');
		$val=intval(hexdec($val));
		//Debugger::barDump($val, 'val int');
		//relays
		if (($val&0x02)!=0) $this->template->relay2=false;
		else  $this->template->relay2=true;

	}
	
	//zapsani bitu do i2c registru - pro ovladani relatka fire tlacitka 
	protected function set_i2c($mask, $status, $i2cAddr=null)
	{
		if ($i2cAddr==null) $i2cAddr=$this->i2cAddr1;
		$i2cAddr=dechex ( $i2cAddr );
		Debugger::barDump("/usr/bin/sudo /usr/sbin/i2cget -y 1 0x$i2cAddr", 'get');
		$output=shell_exec ("/usr/bin/sudo /usr/sbin/i2cget -y 1 0x$i2cAddr");
		$val=substr($output, 0, 4);
		Debugger::barDump($val, 'val');
		$val=intval(hexdec($val));
		if (($val&$mask)!=0) $actual_status=false; //off
		else  $actual_status=true; //on
		if ($actual_status!=$status) { //need change
			if ($status==true) $val=$val&(~$mask); // set 0
			else $val=$val|($mask); // set 1
			$val=dechex ( $val );
			Debugger::barDump("/usr/bin/sudo /usr/sbin/i2cset -y 1 0x$i2cAddr 0x$val", 'set');
			$output=shell_exec ("/usr/bin/sudo /usr/sbin/i2cset -y 1 0x$i2cAddr 0x$val");
		}
	}

	// ovladani relatka
	public function renderControl($status=false )
	{
		// set relay
		$this->set_i2c(0x02, !$status);  //need change
		$this->redirect('Homepage:');
	}
	
	
}
