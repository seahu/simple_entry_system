<?php
namespace App\Presenters;

use Nette,
	 Nette\Application\UI,
    Nette\Application\UI\Form;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Tracy\Debugger;

/*
// ---- ukazka jmen funkci a jejich automaticke pouziti ----
NazevPresenter.php
	renderShowNazev2()
	
showNazev2.latte
	<a n:href="Nazev:ShowNazev2">← csv</a> //odkaz na funkci renderShowNazev2() v presenteru NazevPresenter.php
*/

class CardReadersPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

	// ---- vylistovani karet -----------
    public function renderShowList()
    {
			$cardReaders = $this->database->table('card_reader');
			$this->template->cardReaders = $cardReaders;
    }
	
	//--- nova ctecka ----
	protected function listAddressCardReaders() {
		$dirs=scandir("/tmp/card_readers");
		//return ['ruční příchod','ruční odchod','4564646','b5a6ffdd','dfdf4545'];
		$dirs=array_diff( $dirs, ["."] );
		$dirs=array_diff( $dirs, [".."] );
		$dirs[]="ruční příchod"; // pridani virtualnich ctecek pro rucni zadani pro vratneho
		$dirs[]="ruční odchod";
		return $dirs;
	}
	
	// priprava options polozek do html formulare (pouze nerigistrovane karty)
	protected function listAddressNoRegisteredCardReaders() {
	 	$all_addressis=$this->listAddressCardReaders();
	 	$addressis=array();
	 	foreach($all_addressis as $address){
	 		$record=$this->database->table('card_reader')->where('address', $address);
	 		//Debugger::dump(count($record));
	 		if (count($record)==0)  $addressis[$address]=$address;
	 	}
		return $addressis;
	 }

	// priprava options polozek do html formulare (pouze rigistrovane karty)
	protected function listAddressRegisteredCardReaders() {
	 	$addressis=array();
	 	$cardReaders=$this->database->table('card_reader');
	 	foreach ($cardReaders as $cardReader){
	 		$addressis[$cardReader->address]=$cardReader->address;	
	 	}
	 	//Debugger::dump(count($record));
		return $addressis;
	 }
	


    protected function createComponentNewCardReaderForm()
    {
		 $addressis=$this->listAddressNoRegisteredCardReaders();
    	 $direction = array(
    				'0' => 'ven',
    				'1' => 'dovnitř',
					);
        $form = new UI\Form;
        $form->addText('comment', 'Komentář:');
        $form->addSelect('address', 'Adresa:', $addressis);
        $form->addRadioList('direction', 'Směr:', $direction);
        $form->addSubmit('insert', 'vlozit');
        $form->onSuccess[] = array($this, 'newCardReader');
        return $form;
    }

    		// volá se po úspěšném odeslání formuláře
    public function newCardReader(UI\Form $form, $values)
    {
        // ...
        
        $this->database->table('card_reader')->insert(array(
        'comment' => $values->comment,
        'address' => $values->address,
        'direction' => $values->direction,
    		));

        $this->flashMessage('Byl úspěšně registrována nova čtečka karet:'.$values->comment);
        
        //$this->redirect('Homepage:');
        $this->redirect('CardReaders:showNewCardReader');
    }
    
    public function renderShowNewCardReader()
    {
    	//jen definice pro router, aby nasel cestu a priradil si sablonu showNewCardReader.late v ni je pak vlozena komponenta newCardForm ktra vytvori a vozi formular definovany vyse
    	if (count($this->listAddressNoRegisteredCardReaders())>0) $this->template->newAddressis=TRUE;
    	else $this->template->newAddressis=FALSE;
    }
   

	// ---- editace ctecky kartet -----
    protected function createComponentCardReaderForm($id)
    {
		 //$addressis=$this->listAllAddressCardReaders();
		 $addressis=$this->listAddressRegisteredCardReaders();
    	 $status = array(
   				'0' => 'ven',
    				'1' => 'dovnitř',
					);
        $form = new UI\Form;
        $form->addHidden('id');
        $form->addText('comment', 'Komentář:');
        $form->addSelect('address', 'Adresa:', $addressis);
        $form->addRadioList('direction', 'Směr:', $status);
        $form->addSubmit('edit', 'upravit');
        $form->onSuccess[] = array($this, 'editCardReader');
        //Debugger::dump($this->template->cardReaders);
        //Debugger::dump($this->template->cardReaders);
        if (isset($this->template->cardReader)) {
        	  $form['id']->setValue($this->template->cardReader->id);
	        $form->setDefaults(array(
			    	'comment' => $this->template->cardReader->comment,
	    			'address' => $this->template->cardReader->address,
	    			'direction' =>$this->template->cardReader->direction,
				));
				}
        return $form;
    }
    
    // volá se po úspěšném odeslání formuláře
    public function editCardReader(UI\Form $form, $values)
    {
       
        /*
        $this->database->table('card')->where('id', $values->id)->update(
        	Array('card_code' => $values->card_code)
        );
        */
        $this->database->table('card_reader')->where('id', $values->id)->update($values);
        
        // ...
        $this->flashMessage("Čtečka karet: ".$values->comment.", byla editovana.");

        //$this->redirect('Homepage:');
        $this->redirect('CardReaders:showList');
    }

	// ---- mazani karty ------
    public function renderDeleteCardReader($id)	//skutecne spracovani smazani zaznamu (nove potvrzeni o smazani resi pomoci jquery, takze pro smazani nepotrebuji zadnou mezistanku s potvrzeni, ale rovnou volam tutu fci primo ze sblony jako odkaz)
    {
			//overeni existence karty
        $cardReader = $this->database->table('card_reader')->get($id);
        $comment=$cardReader->comment;
        $address=$cardReader->address;
        if (!$cardReader) $this->error('Not found');
			$this->database->table('card_reader')->where('id', $id)->delete();
			$this->flashMessage('Čtečka '.$comment.' s adresou '.$address.' byla úspěšně smazana');
			$this->redirect('CardReaders:showList');
    }


	// ---- zobrazen podrobnosti jedne ctecky ----
    public function renderShowCardReader($id)
    {
        //$this->template->post = $this->database->table('posts')->get($postId);
        $cardReader = $this->database->table('card_reader')->get($id);
        $this->template->cardReader = $cardReader;
    }


}



