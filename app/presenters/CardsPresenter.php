<?php
namespace App\Presenters;

use Nette,
	 Nette\Application\UI,
    Nette\Application\UI\Form;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;

/*
// ---- ukazka jmen funkci a jejich automaticke pouziti ----
NazevPresenter.php
	renderShowNazev2()
	
showNazev2.latte
	<a n:href="Nazev:ShowNazev2">← csv</a> //odkaz na funkci renderShowNazev2() v presenteru NazevPresenter.php
*/

class CardsPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

	// ---- vylistovani karet -----------
	public function list_cards($searchText,$from=0,$count=5)
	{
        if ($this->template->searchText!="") {
        	$cards = $this->database->table('card')
        			->where('name LIKE ? OR card_code LIKE ?', "%".$searchText."%","%".$searchText."%")
        			->order("name")
        			->limit($count, $from);
        	}
        else {
        	//$cards = $this->database->table('card')->limit($count, $from);
        	$cards = $this->database->table('card')->order("name");
        }
		  return $cards;
	}

    public function renderShowList()
    {
        //$this->template->post = $this->database->table('posts')->get($postId);
        if (!isset($this->template->searchText)) $this->template->searchText="";
        if (!isset($this->template->from)) $this->template->from=0;
        $cards=$this->list_cards($this->template->searchText,$this->template->from);
      	//$cards = $this->database->table('cards')->limit(5);
        if (!$cards) {
        		$this->error('Post not found');
        }
        $this->template->cards = $cards;
    }

    public function renderShowListPritomni()
    {
        $this->template->cards = $this->database->table('card')->where('status',1);
    }
	
	public function renderShowCsvList()
	{
        if (!isset($this->template->searchText)) $this->template->searchText="";
        if (!isset($this->template->from)) $this->template->from=0;
        $cards=$this->list_cards($this->template->searchText);
        $this->template->cards = $cards;
	}
	
	// -- vyhledavani karet -----
    protected function createComponentCardSearchForm()
    {
        $form = new UI\Form;
        $form->addgroup('Hledani');
        $form->addText('searchText', 'Hledany text:');
        $form->addSubmit('search', 'Hledat');
        $form->onSuccess[] = array($this, 'searchCard');
    		return $form;
    }
	
	public function searchCard(UI\Form $form, $values)
	{
		$this->template->searchText=$values->searchText;
		$this->renderShowList();
		//$this->redirect('Cards:showList'); //presmeruje celou stranku a tim se strati data predana formularem
	}
	
	//--- nova karta ----
    protected function createComponentNewCardForm($cardId)
    {

    	 $status = array(
    				'0' => 'zakazano',
    				'1' => 'povoleno',
					);
        $form = new UI\Form;
        $form->addText('card_code', 'Kod karty:');
        $form->addText('name', 'jmeno:');
        $form->addRadioList('status', 'Stav:', $status);
        $form->addSubmit('insert', 'vlozit');
        $form->onSuccess[] = array($this, 'newCard');
        return $form;
    }

    		// volá se po úspěšném odeslání formuláře
    public function newCard(UI\Form $form, $values)
    {
        // ...
        
        $this->database->table('card')->insert(array(
        'name' => $values->name,
        'card_code' => $values->card_code,
        'status' => $values->status,
    		));

        $this->flashMessage('Byl úspěšně registrován nova karta:'.$values->name);
        
        //$this->redirect('Homepage:');
        $this->redirect('Cards:showNewCard');
    }
    
    public function renderShowNewCard($cardId)
    {
    	//jen definice pro router, aby nasel cestu a priradil si sablonu showNewCard.late v ni je pak vlozena komponenta newCardForm ktra vytvori a vozi formular definovany vyse  
    }
   

	// ---- editace karty -----
    protected function createComponentCardForm($cardId)
    {

    	 $access = array(
    				'0' => 'zakazano',
    				'1' => 'povoleno',
					);
        $form = new UI\Form;
        $form->addHidden('id');
        $form->addText('card_code', 'Kod karty:');
        $form->addText('name', 'jmeno:');
        $form->addRadioList('access', 'Stav:', $access);
        $form->addSubmit('edit', 'upravit');
        $form->onSuccess[] = array($this, 'editCard');
        if (isset($this->template->card)) {
        	  $form['id']->setValue($this->template->card->id);
	        $form->setDefaults(array(
			    	'card_code' => $this->template->card->card_code,
	    			'name' => $this->template->card->name,
	    			'access' =>$this->template->card->access,
				));
				}
        return $form;
    }
    
    // volá se po úspěšném odeslání formuláře
    public function editCard(UI\Form $form, $values)
    {
        // ...
        $this->flashMessage('Byl jste úspěšně registrován.:'.$values->name.$values->id);
        
        /*
        $this->database->table('cards')->where('id', $values->id)->update(
        	Array('card_code' => $values->card_code)
        );
        */
        $this->database->table('card')->where('id', $values->id)->update($values);
        
        //$this->redirect('Homepage:');
        $this->redirect('Cards:showList');
    }

	// ---- mazani karty ------
    public function renderShowConfirmationDeleteCard($cardId) // vytvoreni stranky z dotazem na potvrzeni smazani
    {
			//overeni existence karty
        $card = $this->database->table('card')->get($cardId);
        if (!$card) {
        		$this->error('Post not found');
        }
        $this->template->card = $card;			
    }

    protected function createComponentConfirmationDeletCardForm() //nove nepouzivam jan jako ukzaka - formular s jedinym tlacitkem pro potvrazeni smazani bude vlozen do stranky definovane vyse
    {
        $form = new UI\Form;
        if (isset($this->template->card)){
        	$form->addHidden('id', $this->template->card->id);
        }
        else $form->addHidden('id');
        $form->addSubmit('delete', ("ano"));
        $form->onSuccess[] = array($this, 'deleteCardFromConfirmation');
    		return $form;
    }    
		
    public function deleteCardFromConfirmation(UI\Form $form, $values) //nove nepouzivam jan jako ukzaka - vola se pro spracovani formulare definovaneho viz yse
    {
    	$this->renderDeleteCard($values->id);
    }

    public function renderDeleteCard($cardId)	//skutecne spracovani smazani zaznamu (nove potvrzeni o smazani resi pomoci jquery, takze pro smazani nepotrebuji zadnou mezistanku s potvrzeni, ale rovnou volam tutu fci primo ze sblony jako odkaz)
    {
			//overeni existence karty
        $card = $this->database->table('card')->get($cardId);
        $card_code=$card->card_code;
        $name=$card->name;
        if (!$card) $this->error('Post not found');
        	$this->database->table('group_list')->where('id_card', $cardId)->delete();
			$this->database->table('card')->where('id', $cardId)->delete();
			$this->flashMessage('Karta '.$card_code.' '.$name.' byla úspěšně smazana');
			$this->redirect('Cards:showList');
    }
    
    public function renderInsertCardEntry($id_card){
    	$this->handCardEntry($id_card,'HandEnterReaderID');
    }

    public function renderInsertCardExit($id_card){
    	$this->handCardEntry($id_card,'HandExitReaderID');
    }

    
    public function handCardEntry($id_card,$configNameHnadReader)
    {
    	// vlozeni zaznamu o vstupu
    	// nejprve vytazeni id ctecky pro rucni vlozeni zaznamu (vcetne vsech kontrol existence konfigurace a ctecky)
    	$config = $this->database->table('config')->where('name',$configNameHnadReader)->limit(1)->fetch();
    	if ( !$config ){
    		$this->flashMessage('Záznam nemůže být vložen, protože nebyla nalezena konfigurační volba pro určení čtečky karet ručního vložení příchodu.');
    		$this->redirect('Cards:showList');
    		return;
    	}
    	$id_card_reader = (int)$config->value;
    	$card_reader= $this->database->table('card_reader')->get($id_card_reader);
    	if ( !$card_reader ){
    		$this->flashMessage('Záznam nemůže být vložen, protože nebyla nalezena čtečka karek s ID='.$id_card_reader);
    		$this->redirect('Cards:showList');
    		return;
    	}
    	// kontrola existence karty
      $card=$this->database->table('card')->get($id_card);
      if (!$card) {
      	$this->flashMessage('Záznam nemůže být vložen, karta s ID :'.$id_card.' nebyla nalezena.');
      	$this->redirect('Cards:showList');
      	return;
      }
      // vlozeni zaznamu
      $this->database->table('log')->insert(array(
	      'card_id' => $id_card,
   	   'card_reader_id' => $id_card_reader,
      	'timestamp' => new Nette\Database\SqlLiteral('NOW()')
  		));
  		// uprava statusu
  		$this->database->table('card')->where('id',$id_card)->update(array('status'=>$card_reader->direction));
  		// zobrazeni hlasky
  		if ($card_reader->direction==1) $this->flashMessage('Kartě:'.$card->card_code.' '.$card->name.' byl úspěšně zapsán příchod.');
  		else $this->flashMessage('Kartě:'.$card->card_code.' '.$card->name.' byl úspěšně zapsán odchod.');
      $this->redirect('Cards:showList');
    	return;
    }


	// ---- zobrazen podrobnosti jedne karty ----
    public function renderShowCard($cardId)
    {
        //$this->template->post = $this->database->table('posts')->get($postId);
        $card = $this->database->table('card')->get($cardId);
        if (!$card) {
        		$this->error('Post not found');
        }
        $this->template->card = $card;
    }


}



