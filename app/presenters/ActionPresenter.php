<?php
namespace App\Presenters;

use Nette,
	 Nette\Application\UI,
    Nette\Application\UI\Form;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Tracy\Debugger;

//Debugger::enable();
\Tracy\Debugger::$showBar = FALSE;
/*
// ---- ukazka jmen funkci a jejich automaticke pouziti ----
NazevPresenter.php
	renderShowNazev2()
	
showNazev2.latte
	<a n:href="Nazev:ShowNazev2">← csv</a> //odkaz na funkci renderShowNazev2() v presenteru NazevPresenter.php
*/

class ActionPresenter extends BasePresenter
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
    protected function createComponentEnterForm()
    {
    	 //$addressis= $this->database->table('card_reader')->fetchPairs('id','address'); // jen jako ukzka vrati pole id=>addres, ale ja chci address=>adress
		 $addressis= $this->database->table('card_reader')->fetchPairs('address','comment'); 
		 
		//Debugger::dump($addressis);
    	 $status = array(
    				'0' => 'zakazano',
    				'1' => 'povoleno',
					);
        $form = new UI\Form;
        $form->addText('card_code', 'Kod karty:');
        $form->addSelect('card_reader_address', 'Adresa čtečky:', $addressis); 
        $form->addSubmit('insert', 'vlozit');
        $form->onSuccess[] = array($this, 'newEnter');
        return $form;
    }
    
    // volá se po úspěšném odeslání formuláře
    public function newEnter(UI\Form $form, $values)
    {
        // ...
        $card=$this->database->table('card')->where("card_code",$values->card_code)->limit(1)->fetch();
        if (!$card) {
        		$this->flashMessage('Karta s kodem :'.$values->card_code.' nebyla nalezena.');
        		$this->redirect('Action:showEnter');
        		return;
        }
        $id_card=$card->id;
        //Debugger::barDump($id_card, 'id_card');
        //Debugger::barDump($values->card_reader_address, 'card_reader_Address'); 

	     $card_reader=$this->database->table('card_reader')->where("address",$values->card_reader_address)->limit(1)->fetch();
	     if (!$card_reader) {
        		$this->flashMessage('Čtečka karet s adresou :'.$values->card_reader_address.' nebyla nalezena.');
        		$this->redirect('Action:showEnter');
        		return;
        }
        $id_card_reader=$card_reader->id;
		  //Debugger::barDump($id_card_reader, 'id_card reader');

        $this->database->table('log')->insert(array(
        'card_id' => $id_card,
        'card_reader_id' => $id_card_reader,
        'timestamp' => new Nette\Database\SqlLiteral('NOW()')
    		));
			
        $this->flashMessage('Akce byla úspěšně zapsana.');
        
        $this->redirect('Action:showEnter');
    }

	// funkce ktera je volana programem vycitajicim ctecky karet
	// adresa na ktere obsluzny program ctecek karet komunikuje s timto programem je:
	//  http://10.10.109.36/access/action/show-check-card-code?card_code=15154654&card_reader_address=4564646
    public function renderShowCheckCardCode($card_code, $card_reader_address){

    	// nejprve vytazeni id carty
    	$card = $this->database->table('card')->where('card_code',$card_code)->limit(1)->fetch();
    	if ( !$card ){
    		// nove pokud neni karta nalezena tak bude vytvorena nova bezejmenna karta
    		$this->database->table('card')->insert(array(
        		'name' => "bezejmenná",
        		'card_code' => $card_code,
        		'status' => 0,
    			));
    		// ziskani id z naveho zaznamu
    		$card = $this->database->table('card')->where('card_code',$card_code)->limit(1)->fetch();
    		if ( !$card ){
	    		$this->template->error="can't fid card_code";
    			$this->template->result="deny ";
    		}
    	}
    	$id_card = (int)$card->id;
    	
    	// pote vytazeni id ctecky
    	$card_reader = $this->database->table('card_reader')->where('address',$card_reader_address)->limit(1)->fetch();
    	if ( !$card_reader ){
    		$this->template->error="can't fid card reader address";
    		$this->template->result="deny ";
    		return;
    	}
    	$id_card_reader = (int)$card_reader->id;

    	// kontrola povoleni pristupu pro danou kartu
		if ($card->access==0){
			$this->template->error="";
			$this->template->result="deny";
			return;
		}

    	// vlozeni zaznamu o vstupu
      // vlozeni zaznamu
      $this->database->table('log')->insert(array(
	      'card_id' => $id_card,
   	   'card_reader_id' => $id_card_reader,
      	'timestamp' => new Nette\Database\SqlLiteral('NOW()')
  		));
  		// uprava statusu
  		$this->database->table('card')->where('id',$id_card)->update(array('status'=>$card_reader->direction));
  		// zobrazeni hlasky
  		$this->template->error="";
  		$this->template->result="access";
    	return;

    }



}



