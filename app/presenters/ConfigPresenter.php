<?php
namespace App\Presenters;

use Nette,
	 Nette\Application\UI,
    Nette\Application\UI\Form;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Tracy\Debugger;
use \Exception;

/*
// ---- ukazka jmen funkci a jejich automaticke pouziti ----
NazevPresenter.php
	renderShowNazev2()
	
showNazev2.latte
	<a n:href="Nazev:ShowNazev2">← csv</a> //odkaz na funkci renderShowNazev2() v presenteru NazevPresenter.php
*/

class ConfigPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

	public function renderDefault()
	{
   }

	// -- formular editace nastaveni -----
    protected function createComponentConfigForm()
    {
    		// vytvoreni formulare
        $form = new UI\Form;
        //$form->addgroup('Nastavení');
        $rows=$this->database->table('config');
        foreach ($rows as $row) {
        		$form->addText($row->id, $row->description);
        }
        $form->addSubmit('edit', 'Editovat');
        $form->onSuccess[] = array($this, 'editConfig');
        // naplneni daty
        $arr=array();
        foreach ($rows as $row) $arr[$row->id] = $row->value;
        $form->setDefaults($arr);
    		return $form;
    }

    // volá se po úspěšném odeslání formuláře
    public function editConfig(UI\Form $form, $values)
    {
        // ...
        //$this->flashMessage('Byl jste úspěšně registrován.:'.$values->name.$values->id);
        
        /*
        $this->database->table('cards')->where('id', $values->id)->update(
        	Array('card_code' => $values->card_code)
        );
        */
        Debugger::barDump($values, 'values');
        foreach($values as $key => $value){
        	Debugger::barDump($value, 'value');
        	$this->database->table('config')->where('id', $key)->update(array('value'=>$value));
        }
        
        //$this->redirect('Homepage:');
        //$this->redirect('Cards:showList');
    }

	 // funkce ktera promaze vse    
    public function renderClearAll()
    {
    	$this->database->table('log')->delete();
    	$this->database->table('group_list')->delete();
    	$this->database->table('group')->delete();
    	$this->database->table('card')->delete();
    	$this->redirect('Config:default');
    }
 }
