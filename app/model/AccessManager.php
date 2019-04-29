<?php

// !!! takovy pokus presunout logigu funkcnosti z presenteru sem do modelu,
// !!! ale zatim na to kaslu, logika programu zetim neni tak slozita aby se mi to vypatilo.
// !!! takze to tu zustava jen jako ukazka jak na to

// model je potreba zaregistrovat v "app/config/config.neon"
//services:
//    router: App\RouterFactory::createRouter
//    - App\Model\AccessManager
//
// v presenteru, kde model chceme pouzit je potreba na zacatku tridy uvest:
//class HomepagePresenter extends Nette\Application\UI\Presenter
//{
//    /** @var AccessManager */
//    private $accessManager;
//
//    public function __construct(AccessManager $accessManager)
//    {
//        $this->accessManager = $accessManager;
//    }

namespace App\Model;
use Nette;

/**
 * Users management.
 */
class AccessManager 
{
   use Nette\SmartObject;

   /** @var Nette\Database\Context */
   private $database;

   public function __construct(Nette\Database\Context $database)
   {
       $this->database = $database;
   }

	/**
	 * kontrola zda-li zadana karta ma povolen nebo ne
	 * vrati pole pobsahujici polozku result obsahujici vysledek false/true a polozku error obsahujici pripadny duvod erroru nebo prazdny retezec
	 * funkce zaroven zapise udaost do logu a patricne upravi status uzivatele karty
	 * funkce ktera je volana programem vycitajicim ctecky karet
	 * adresa na ktere obsluzny program ctecek karet komunikuje s timto programem je:
	 * http://10.10.109.36/access/action/show-check-card-code?card_code=15154654&card_reader_address=4564646
	 */
   public function CheckCardCode($card_code, $card_reader_address){

    	// nejprve vytazeni id carty
    	$card = $this->database->table('card')->where('card_code',$card_code)->limit(1)->fetch();
    	if ( !$card ){
    		return ["result"=>false, "error"=>"can't fid card_code"]
    	}
    	$id_card = (int)$card->id;
    	
    	// pote vytazeni id ctecky
    	$card_reader = $this->database->table('card_reader')->where('address',$card_reader_address)->limit(1)->fetch();
    	if ( !$card_reader ){
    		return ["result"=>false, "error"=>"can't fid card reader address"]
    	}
    	$id_card_reader = (int)$card_reader->id;

    	// kontrola povoleni pristupu pro danou kartu
		if ($card->access==0){
			return ["result"=>false, "error"=>""]
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
  		return ["result"=>true, "error"=>""]
    }

 }

