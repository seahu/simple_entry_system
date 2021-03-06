<?php
namespace App\Presenters;

use Nette,
	 Nette\Application\UI,
    Nette\Application\UI\Form;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Tracy\Debugger;

Debugger::enable();
/*
// ---- ukazka jmen funkci a jejich automaticke pouziti ----
NazevPresenter.php
	renderShowNazev2()
	
showNazev2.latte
	<a n:href="Nazev:ShowNazev2">← csv</a> //odkaz na funkci renderShowNazev2() v presenteru NazevPresenter.php
*/

class HistoryPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderShowEnter($cardId)
    {
    	//jen definice pro router, aby nasel cestu a priradil si sablonu showEnter.latte v ni je pak vlozena komponenta enterForm ktra vytvori a vozi formular definovany vyse  
    }

	// ---- dotazovy formular -----
    protected function createComponentFilterForm()
    {
		 $addressis= $this->database->table('card_reader')->fetchPairs('id','comment');
		 $none=array("none"=>"nezalezi");
		 $addressis=$none+$addressis;
		 Debugger::barDump($addressis, 'Addressis');
        $form = new UI\Form;
        $form->addgroup('Hledani');
        $form->addText('searchText', 'Jméno nebo kód karty:');
        $form->addSelect('card_reader_id', 'Čtečka karet:', $addressis);
        $form->addText('datetime_from', 'od:')->setType('datetime-local');
        $form->addText('datetime_to', 'do:')->setType('datetime-local');
        $form->addSubmit('search', 'Hledat');
        $form->onSuccess[] = array($this, 'searchHistory');
   	  return $form;
    }
    
	public function searchHistory(UI\Form $form, $values)
	{
		$this->template->values=$values;
		$this->renderShowHistory();
		//$this->redirect('Cards:showList'); //presmeruje celou stranku a tim se strati data predana formularem
	}

    // volá se po úspěšném odeslání formuláře
    public function renderShowHistory()
    {
    	$this->prepareHistoryList();
    }

	// csv seznam
	public function renderShowHistoryCsvList()
	{
		$this->prepareHistoryList();
		$history=$this->database->table('log');
		$this->template->history = $history;
		//Debugger::dump($this->template->history);	
	}
	
	 protected function prepareHistoryList()
	 {
    	if (isset($this->template->values)){
	       $values=$this->template->values;
	       $history=$this->database->table('log','cards','card_readers');
	       if (isset($values->searchText)) { 
	       	if ($values->searchText!="") $history=$history->where('card.name LIKE ? OR card.card_code LIKE ?', "%".$values->searchText."%","%".$values->searchText."%");
	       }
	       if (isset($values->card_reader_id)) {
	       	if ($values->card_reader_id!="none") {
	       		if ($values->card_reader_id!="") $history=$history->where('card_reader_id', $values->card_reader_id);
	       	}
	       }
	       if (isset($values->datetime_from)) {
	       	if ($values->datetime_from!="") 	$history=$history->where('timestamp >= ?',$values->datetime_from);
	       }
	       if (isset($values->datetime_to)) {
	       	if ($values->datetime_to!="") 	$history=$history->where('timestamp <= ?',$values->datetime_to);
	       }
    	}
    	else {
    			$history=$this->database->table('log')->order('id DESC')->limit(500);
    	}
       if (!$history) {
       		$this->error('Post not found');
       }
       Debugger::barDump($history, 'History'); 
       $this->template->history = $history;		    	
	 }
	 
	 public function renderShowNearHistory($card_id)
	 {
	 	Debugger::$maxDepth = 5;
	 	$this->template->card = $this->database->table('card')->get($card_id); // pro zobrazeni jmena drzitele karty v latte sablone 
	 	$last_direction=0; // pocitam s tim puvodni stav na zacatku zobrazovani historie je neprihlasen
	 	$arr=array();
	 	for($i=10; $i>=0; --$i){ // zpracuj den po dni
	 		// nalezeni i-teho dne
	 		$from=$i+1;
	 		$to=$i;
	 		$datum_cs=date("d.m.Y", time()-$i*24*60*60);
	 		$datum_od=date("Y-m-d", time()-$i*24*60*60)." 00:00:00";
	 		$datum_do=date("Y-m-d", time()-$i*24*60*60)." 24:00:00";
			//$rows=$this->database->table('log')->where('card_id',$card_id)->where("timestamp>DATE_SUB(now(),INTERVAL ? DAY)",$from)->where("timestamp<DATE_SUB(now(),INTERVAL ? DAY)",$to);
			//$days=$this->database->table('log')->where('card_id',$card_id)->where("timestamp>DATE_SUB(now(),INTERVAL ? DAY)",$from)->where("timestamp<=DATE_SUB(now(),INTERVAL ? DAY)",$to);
			$days=$this->database->table('log')->where('card_id',$card_id)->where("timestamp>?",$datum_od)->where("timestamp<=?",$datum_do);
			
			// priprava casovych znacek i-teho dne (obsahujicich pole s polozkami cas, prepocet casu na minuty, smer pruchodu prichod/odchod(1/0))
			$timestamps=array();
			foreach($days as $day){
				$d=explode ( " " , $day->timestamp);
				//Debugger::barDump($d, 'datum');
				$time=$d[1];
				$time=explode ( ":" , $time);
				$H=(int)$time[0];
				$M=(int)$time[1];
				$min=$H*60+$M;				
				$timestamps[]=array('time'=>$d[1], 'min'=>$min, 'direction'=>$day->card_reader->direction);
			}
			// priprava grafu i-teho dne
			Debugger::barDump("$last_direction $datum_cs", 'last_direction');
			$y=0;
			$graf=array();
			if($last_direction==1) $graf[$y]=array('start'=>0);
			foreach($timestamps as $stamp){
				if ( $stamp['direction']==$last_direction ) continue;
				if ( $stamp['direction']==0 ){ //ukonceni casti grafu
					$graf[$y]['end']=$stamp['min'];
					++$y;
				}
				else {
					$graf[$y]=array('start'=>$stamp['min']);
				}
				$last_direction=$stamp['direction'];
			}
			if ($last_direction==1){// konec dne a karta je stale pritomna
				$graf[$y]['end']=24*60;
			}
			
			// ulozeni znacek a grafu daneho dne do pole
			Debugger::barDump($timestamps, 'timestamps');
			Debugger::barDump($graf, 'graf');
			//Debugger::barDump($datum, 'datum');
			$arr[$datum_cs]=array('timestamps'=>$timestamps, 'graf'=>$graf);
	 	}
	 	
	 	//$arr=array("timestamps"=>"21", "graf"=>"22");
	 	$this->template->arr=$arr;
	 	//$this->template->arr=array();ro
	 	Debugger::barDump($arr, 'arr');
	 }


}



