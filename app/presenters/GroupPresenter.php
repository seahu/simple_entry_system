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

class GroupPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

//-----------------------------------------------------------------
//------ SEZNAM SKUPIN -----------------------------------
//-----------------------------------------------------------------
	// ---- vylistovani skupin -----------
    public function renderShowList()
    {
			$groups = $this->database->table('group')->order("name");
			$this->template->groups = $groups;
			$arr=array();
			foreach ($groups as $group) {
				Debugger::barDump($group->id, 'group_id'.$group->id);
				$all = $this->database->table('group_list')->where('id_group', $group->id)->count('*');
				$in = $this->database->table('group_list')->where('id_group', $group->id)->where('card.status',1 )->count('*');
				if ($all!=0) {
					$perc = (int)((100*$in)/$all);
				}
				else {
					$perc = 0;
				}
				Debugger::barDump($all, 'all'.$group->id);
				Debugger::barDump($in, 'in'.$group->id);
				$arr[$group->id]=array("all"=>$all, "in"=>$in, "perc"=>$perc);
				$this->template->arr=$arr;
    		}
    }
	

//-----------------------------------------------------------------
//------ VYTVORENI NOVE SKUPINY -----------------------------------
//-----------------------------------------------------------------
	// tovarnicka pro formular nove skupiny
    protected function createComponentNewGroupForm()
    {
        $form = new UI\Form;
        $form->addText('name', 'Název:');
        $form->addSubmit('insert', 'vlozit');
        $form->onSuccess[] = array($this, 'newGroup');
        return $form;
    }

    // volá se po úspěšném odeslání formuláře
    public function newGroup(UI\Form $form, $values)
    {
        // ...
        $this->database->table('group')->insert(array(
        'name' => $values->name,
    		));

        $this->flashMessage('Byl úspěšně registrována nova skupina:'.$values->name);
        
        //$this->redirect('Homepage:');
        $this->redirect('Group:showNewGroup');
    }
    
    public function renderShowNewGroup()
    {
    	//jen definice pro router, aby nasel cestu a priradil si sablonu showNewCardReader.late v ni je pak vlozena komponenta newCardForm ktra vytvori a vozi formular definovany vyse
    	//if (count($this->listAddressNoRegisteredCardReaders())>0) $this->template->newAddressis=TRUE;
    	//else $this->template->newAddressis=FALSE;
    }
   
//-----------------------------------------------------------------
//------ EDITACE NAZVU SKUPINY  -----------------------------------
//-----------------------------------------------------------------
	// ---- editace nazvu skupiny -----
    protected function createComponentGroupNameForm($id)
    {
        $form = new UI\Form;
        $form->addHidden('id');
        $form->addText('name', 'Název skupiny:');
        $form->addSubmit('edit', 'upravit');
        $form->onSuccess[] = array($this, 'editGroupName');
        //Debugger::dump($this->template->cardReaders);
        //Debugger::dump($this->template->cardReaders);
        if (isset($this->template->groups)) {
        	  $form['id']->setValue($this->template->groups->id);
	        $form->setDefaults(array(
			    	'name' => $this->template->groups->name,
				));
				}
        return $form;
    }
    
    // volá se po úspěšném odeslání formuláře
    public function editGroupName(UI\Form $form, $values)
    {
        /*
        $this->database->table('card')->where('id', $values->id)->update(
        	Array('card_code' => $values->card_code)
        );
        */
        $this->database->table('group')->where('id', $values->id)->update($values);
        
        // ...
        $this->flashMessage("Název skupiny byl změněn na: ".$values->name);

        //$this->redirect('Homepage:');
        $this->redirect('Group:showList');
    }
	
	// tato fce je volana z odkazu na strance
    public function renderShowEditGroupName($id_group)
    {
		$groups = $this->database->table('group')->get($id_group);
      $this->template->groups = $groups;
     	//dal jen definice pro router, aby nasel cestu a priradil si sablonu showNewCardReader.late v ni je pak vlozena komponenta newCardForm ktra vytvori a vozi formular definovany vyse
    }

//-----------------------------------------------------------------
//------ MAZANI SKUPINY -----------------------------------
//-----------------------------------------------------------------
	// tato fce je volana z odkazu na strance
    public function renderDeleteGroup($id)	//skutecne spracovani smazani zaznamu (nove potvrzeni o smazani resi pomoci jquery, takze pro smazani nepotrebuji zadnou mezistanku s potvrzeni, ale rovnou volam tutu fci primo ze sblony jako odkaz)
    {
			//overeni existence tridy
        $group = $this->database->table('group')->get($id);
        $name=$group->name;
        if (!$group) $this->error('Not found');
        $this->database->table('group_list')->where('id_group', $id)->delete();
			$this->database->table('group')->where('id', $id)->delete();
			$this->flashMessage('Skupina '.$name.' byla úspěšně smazana');
			$this->redirect('Group:showList');
    }

//-----------------------------------------------------------------
//------ CLENOVE SKUPINY -----------------------------------
//-----------------------------------------------------------------
	// ---- pomocna fce - vylistovani karet (clenu) -----------
	protected function list_cards($searchText,$from=0,$count=5)
	{
		Debugger::barDump($searchText, 'searchText-3');
        if ($searchText!="") {
			Debugger::barDump($searchText, 'searchText-4');
        	$cards = $this->database->table('card')
        			->where('name LIKE ? OR card_code LIKE ?', "%".$searchText."%","%".$searchText."%")
        			->limit($count, $from);
        	}
        else {
			Debugger::barDump($searchText, 'searchText-5');
        	$cards = $this->database->table('card')->limit(0, 0);
        }
		  return $cards;
	}

	// ---- zobrazen clenstvi jedne skupiny ----
    public function renderShowGroup($id_group, $searchText="")
    {
    	//$database->query(
       //     'SELECT * FROM test WHERE x = ?', 'string'
        //);
        //$this->template->post = $this->database->table('posts')->get($postId);
        //Debugger::barDump($this->template->searchText, 'searchText-2');
      if (!isset($this->template->searchText)) $this->template->searchText=$searchText;  
        
        $groups = $this->database->table('group')->get($id_group);
        $this->template->groups = $groups;
        Debugger::barDump($groups, 'groups');

        $group_list = $this->database->table('group_list')->where('id_group', $id_group)->order("card.name");
        $this->template->group_list = $group_list;
        Debugger::barDump($group_list, 'group_list');

				
		$this->template->cards = $this->list_cards($this->template->searchText);
        
    }

	// ---- pridani karty (uzivatele) do skupiny ----    
    public function renderAddCardIntoGroup($id_card, $id_group, $searchText)
    {
        $rows = $this->database->table('group_list')->where('id_group', $id_group)->where('id_card', $id_card)->count('*');
        Debugger::barDump($rows, 'rows');
        
			if ($rows>0) {
				$this->flashMessage('Zaznam jiz jexistuje');
			}
			else {
				$this->database->table('group_list')->insert(array(
				'id_group' => $id_group,
				'id_card' => $id_card,
				));
				$this->flashMessage('Byl pridan novy zaznam');
			}
			//$this->renderShowGroup($id_group, $searchText);
			$this->redirect('Group:showGroup', $id_group, $searchText);
    }

	// ---- odebrani karty (uzivatele) ze skupiny ----    
    public function renderDelCardFromGroup($id_card, $id_group, $searchText)
    {
    	$this->database->table('group_list')->where('id_group', $id_group)->where('id_card', $id_card)->delete();
    	$this->redirect('Group:showGroup', $id_group, $searchText);
    }

	// -- tovarnicka pro vyhledavaci formular karet -----
    protected function createComponentCardSearchForm()
    {
        $form = new UI\Form;
        $form->addgroup('Hledani');
        $form->addHidden('id');
        $form->addText('searchText', 'Hledany text:');
        $form->addSubmit('search', 'Hledat');
        $form->onSuccess[] = array($this, 'searchCard');
		if (isset($this->template->groups)) {
			$form['id']->setValue($this->template->groups->id);
		}
		if (isset($this->template->searchText)) {
			$form->setDefaults(array(
		    	'searchText' => $this->template->searchText,
			));
		}
		return $form;
    }
	// -- zavola se po uspesnem odeslani formulare hledani
	public function searchCard(UI\Form $form, $values)
	{
		Debugger::barDump($values->searchText, 'searchText-1');
		//$this->template->searchText = $values->searchText;
		$this->renderShowGroup($values->id, $values->searchText);
		//$this->redirect('Group:showGroup', $values->id, $values->searchText); //presmeruje celou stranku a tim se strati data predana formularem
	}


}



