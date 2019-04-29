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

class EdookitPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;
    private $time_index; // takova finta, aby kdyz je za sebou vice dotazu, aby nemnely stejnou casovou znacku (nahrada milisekund) 
    private $PkSkRok;
    private $SkolniRokNazev;
    private $SkolniRok;
    private $Pololeti;
    private $SkolaNazev;
    private $SkolaNazevZkracen;
    private $SkolaAdresa;
    private $tridy;
    private $PracovniciZarazeni;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->time_index = 0;
    }

	public function renderDefault()
	{
      $this->checkVersion();
      $this->nastaveni();
      $this->template->SkolaNazev = $this->SkolaNazev;
      $this->template->SkolniRok = $this->SkolniRok;
      $this->template->Pololeti = $this->Pololeti;
   }

	public function renderOsobySync()
	{
      if ($this->checkVersion()==false) {
      	$this->redirect('Edookit:');
      	return;
      }
      $this->nastaveni();
      $this->template->SkolaNazev = $this->SkolaNazev;
      $this->template->SkolniRok = $this->SkolniRok;
      $this->template->Pololeti = $this->Pololeti;
      $this->syncTridy();
      $this->template->pocetTrid = count($this->tridy);
      $this->syncZaci();
      $this->syncPracovnici();
   }

	public function renderPruchodySync()
	{
      if ($this->checkVersion()==false) {
      	$this->redirect('Edookit:');
      	return;
      }
      $this->nastaveni();
      $this->syncPruchody();
   }


	public function renderPokus()	// ciste pro zkouseni a tstovani komunikace s edookitem
	{
      if ($this->checkVersion()==false) {
      	$this->redirect('Edookit:');
      	return;
      }
      $this->nastaveni();
  		//$lines=$this->sendRequest("pracovnici");
		//$xml = simplexml_load_string($lines);
		//Debugger::barDump($xml, 'tridy');
		//$this->template->xmlLines = $lines;
		$this->template->xmlLines = "";
		//$this->syncZarazeni();
		$this->syncPracovnici();
   }



	/**
	* Vzorová implementace odeslání GET požadavku na rozhraní pro docházkové
	* systémy Webové služby SAS.
	*/

	/** Vytvoří hash pro ověřená identity klienta */
	private function hmac($secret, $method, $path, $timestamp, $userPass)	{
		$hashData = $method."+".$path."+".$timestamp."+".$userPass;
		$result = hash_hmac( "sha1" , $hashData , $secret );
		return $result;
	}

	/** Odešle požadavek na server */
	private function sendRequesto($uri, $timestamp, $authString, $ident) {// Vytvoří REST clienta
		$opts = array(
			'http'=>array(
				'method'=>"GET",
				'header'=>	"com.edookit.Client: $ident\r\n" .
								"com.edookit.Auth: $authString\r\n".
								"com.edookit.Time: $timestamp\r\n".
								"Connection: close\r\n"
			)
		);
		Debugger::barDump($opts, 'opts');
		$context = stream_context_create($opts);
		$lines = file_get_contents($uri, false, $context);
		return $lines;
	}
	
	private function sendRequest($path="verze",$xml=""){
		$path="/api/dochazka/v2/".$path;
		if ($xml=="") $metod="GET"; //pokud je potreba zaslat zaznam o pruchodu posila se pomoci http metody POST jinak se pouziva metoda GET
		else $metod="POST";
		// URL edookitu pro zaky a rodice
		$URL="https://spss.edookit.net";
		// Identifikace vstupniho systemu
		$IDENT = "SPSSTAVBRNO";
		// Klíč vstupniho systému pro hash-funkci
		$SECRET = "734d3e7a5d1d0bdbfc08672b4d588e386127b9b5";
		// Značka a heslo školy
		$schoolSign="apiuser1";
		$schoolPass="limp7irawl61knh9soxaucbg8cbjp595fhfuc4qd";
	
		$url=$URL.$path;
		// Časové razítko odeslání požadavku
		$timestamp = date("Y-m-d H:i:s").".".sprintf("%'.03d", ++$this->time_index);
		if ($this->time_index==999) $this->time_index=0; // po tisici za sebou jdoucich dotazech vynuluj citac (1000 dotazu bude trvat urcite dele nez 1s takze duplicity se bat nemusim) 
		//$timestamp = date("Y-m-d H:i:s").".000";
		// Autorizační řetězec
		$hmac = hash_hmac( "sha1" , $metod."+".$path."+".$timestamp."+".$schoolPass , $SECRET );
		$authString = $schoolSign.":".$hmac; // Sestaví autorizační řetězec klienta 
		Debugger::barDump($authString, 'authString');
		// Odeslání požadavku
		if ($xml!=""){ // pripadna priprava na odeslani xml obsahu
			//$content=http_build_query( array("xml" => $xml) );
			$content=$xml;
			$xmlHeader="Content-type: application/xml\r\n";
		}
		else {
			$xmlHeader="";
		}
		$opts = array(
			'http'=>array(
				'method'=>$metod,
				'header'=>	"com.edookit.Client: $IDENT\r\n" .
								"com.edookit.Auth: $authString\r\n".
								"com.edookit.Time: $timestamp\r\n".
								$xmlHeader.
								"Connection: close\r\n"
			)
		);
		if ($xml!="") $opts['http']['content']=$content; // pripadne rozsireni opts o xml obsah
		Debugger::barDump($opts, 'opts');
		$context = stream_context_create($opts);
		//$this->template->context=implode(" ",$opts);
		$this->template->context=print_r($opts, true);
		$this->template->url=$url;
		$lines = @file_get_contents($url, false, $context);
		return $lines;
	}

	private function checkVersion(){
		$lines=$this->sendRequest("verze");
		$xml = simplexml_load_string($lines);
		$version = $xml->VerzeRozhrani;
		$arr =  explode(".", $version);
		if ($arr[0]!="2" or $arr[1]!="9") {
			$this->flashMessage("Vstupní system vyžaduje api verze 2.9.X, EDOOKIT ma však api verze $version .");
			return false;
		}
		return true;
	}
	
	private function nastaveni(){
		$lines=$this->sendRequest("nastaveni");
		$xml = simplexml_load_string($lines);
	   $this->PkSkRok = $xml->PkSkRok; 
	   $this->SkolniRokNazev = $xml->SkolniRokNazev; 
	   $this->SkolniRok = $xml->SkolniRok;
	   $this->Pololeti = $xml->Pololeti;
	   $this->SkolaNazev = $xml->SkolaNazev;
	   $this->SkolaNazevZkracen = $xml->SkolaNazevZkracen;
	   $this->SkolaAdresa = $xml->SkolaAdresa;
	}
	
	private function syncTridy(){
		$lines=$this->sendRequest("tridy");
		$xml = simplexml_load_string($lines);
		$this->tridy = $xml;
		Debugger::barDump($xml, 'tridy');
		foreach ($xml as $trida) {
			Debugger::barDump($trida, 'trida');
			$rows = $this->database->table('group')->where('name', $trida->Zkratka)->count('*');
			if ($rows==0) $this->database->table('group')->insert(array('name' => $trida->Zkratka,));
		}
	}

	private function syncZaci(){
		$lines=$this->sendRequest("zaci");
		$xml = simplexml_load_string($lines);
		$new=0;
		$edit=0;
		foreach ($xml as $zak) {
			// kontrola zda-li se jedna o aktualniho zaka
			if ($zak->PkTrida=="") continue; // preskoc zaky kteri nemaji vyplnene PkTrida
			if ($zak->Karta=="") continue; // preskoc zaky kteri nemaji vyplnenou Kartu
			// zjisteni nazvu tridy z PkTrida
			$dal=false; // pracovavan je ty zaky ktery maji platnou tridu (tj. tridu ktera lze v seznamu aktualnich trid nalest)
			foreach ($this->tridy as $trida) {
				if ( strval($trida->PkTrida) == strval($zak->PkTrida) ) {
					$Zkratka_tridy=$trida->Zkratka;
					$dal=true;
					break;
				}
			}
			if ($dal==false) continue;
			Debugger::barDump( $zak->Prijmeni." ".$Zkratka_tridy, 'dal');
			// editace pripadne pridani noveho zaka (karty)
			$card = $this->database->table('card')->where('card_code', $zak->Karta);
			if (count($card)!=0) { // karta existuje
				$card = $card->fetch();
				if ($zak->Prijmeni." ".$zak->Jmeno != $card->name or $zak->PkZak!=$card->Pk ) {
					$this->database->table('card')->where('id', $card->id)->update( array( 'name' => $zak->Prijmeni." ".$zak->Jmeno, 'Pk'=>$zak->PkZak, 'access'=>1 ) );
					++$edit;
				}
			}
			else { // karta neexistuje - bude pridana
				$this->database->table('card')->insert( array( 'name' => $zak->Prijmeni." ".$zak->Jmeno, 'card_code'=>$zak->Karta, 'Pk'=>$zak->PkZak, 'access'=>1 ) );
				++$new;
			}
			// zarazeni do spravne skupiny
			$group=$this->database->table('group')->where('name', $Zkratka_tridy)->fetch();
			$card=$this->database->table('card')->where('card_code', $zak->Karta)->fetch();
			$rows = $this->database->table('group_list')->where('id_group', $group->id)->where('id_card', $card->id)->count('*');
			if ($rows==0){
				$this->database->table('group_list')->where('id_card', $card->id)->delete(); // nejdrive smazu pripadny stary neaktualni zaznam
				$this->database->table('group_list')->insert( array( 'id_group' => $group->id, 'id_card'=>$card->id ) ); // pak vytvorim novy
			}
		}
		$this->template->new=$new;
		$this->template->edit=$edit;
	}


	//neodzkousenou 
	private function syncPracovnici(){
		// ziskani seznamu zarazeni pracovniku pro cleneni pracovniku do skupin (skupiny pracovniku vytvarim za letu jen kdyz je to skutecne potreba, jinak by tam bylo spoustu prazdnych skupin)
		$lines=$this->sendRequest("pracovnici/zarazeni");
		$xml = simplexml_load_string($lines);
		$this->PracovniciZarazeni = $xml;
		Debugger::barDump($xml, 'PracovniciZarazeni');
		// ted konecne samotni samestnanci
		$lines=$this->sendRequest("pracovnici");
		$xml = simplexml_load_string($lines);
		$new=0;
		$edit=0;
		foreach ($xml as $pracovnik) {
			// kontrola zda-li se jedna o aktualniho zaka
			if ($pracovnik->PkZarazeni=="") continue; // preskoc pracovniky kteri nemaji vyplnene zarazeni
			if ($pracovnik->Karta=="") continue; // preskoc pracovniky kteri nemaji vyplnenou Kartu
			if ($pracovnik->AktivniEvidence!="1") continue; // preskoc neakditvni pracovniky
			// zjisteni nazvu zarazeni z PkZarazeni
			$dal=false; // zpracovavan je ty pracovniky ktery se da dohledat zarazeni
			foreach ($this->PracovniciZarazeni as $zarazeni) {
				if ( strval($zarazeni->PkZarazeni) == strval($pracovnik->PkZarazeni) ) {
					$Zkratka_zarazeni=$zarazeni->Zkratka;
					$dal=true;
					break;
				}
			}
			if ($dal==false) continue;
			Debugger::barDump( $pracovnik->Prijmeni." ".$Zkratka_zarazeni, 'dal');
			// editace pripadne pridani noveho zaka (karty)
			$card = $this->database->table('card')->where('card_code', $pracovnik->Karta);
			if (count($card)!=0) { // karta existuje
				$card = $card->fetch();
				if ($pracovnik->Prijmeni." ".$pracovnik->Jmeno != $card->name or $pracovnik->PkZak!=$card->Pk ) {
					$this->database->table('card')->where('id', $card->id)->update( array( 'name' => $pracovnik->Prijmeni." ".$pracovnik->Jmeno, 'Pk'=>$pracovnik->PkZak, 'access'=>1 ) );
					++$edit;
				}
			}
			else { // karta neexistuje - bude pridana
				$this->database->table('card')->insert( array( 'name' => $pracovnik->Prijmeni." ".$pracovnik->Jmeno, 'card_code'=>$pracovnik->Karta, 'Pk'=>$pracovnik->PkZak, 'access'=>1 ) );
				++$new;
			}
			// zarazeni do spravne skupiny
			// vytvoreni skupiny pokud neexistuje
			$rows = $this->database->table('group')->where('name', $Zkratka_zarazeni)->count('*');
			if ($rows==0) $this->database->table('group')->insert(array('name' => $Zkratka_zarazeni));
			// kontrola clenstvi
			$group=$this->database->table('group')->where('name', $Zkratka_zarazeni)->fetch();
			$card=$this->database->table('card')->where('card_code', $pracovnik->Karta)->fetch();
			$rows = $this->database->table('group_list')->where('id_group', $group->id)->where('id_card', $card->id)->count('*');
			if ($rows==0){
				$this->database->table('group_list')->where('id_card', $card->id)->delete(); // nejdrive smazu pripadny stary neaktualni zaznam
				$this->database->table('group_list')->insert( array( 'id_group' => $group->id, 'id_card'=>$card->id ) ); // pak vytvorim novy
			}
		}
		$this->template->new_pracovnici=$new;
		$this->template->edit_pracovnici=$edit;
	}

	private function syncPruchody(){
		$pruchody=$this->database->table('log')->where('sync', 0);
		$i=0;
		$err=0;
		$xml="";
		foreach ($pruchody as $pruchod) {
			if ($pruchod->card->Pk=="") continue; // pokud nema karta (uzivatel) definovane Pk tak ho nespracovavej
			$timestamp=strtotime($pruchod->timestamp);
			$datum=date("Y-m-d", $timestamp);
			$cas=date("H:i:s", $timestamp);
			if ($pruchod->card_reader->direction==0) $smer="O";
			else $smer="P";
			$xml="<?xml version=\"1.0\" encoding=\"UTF-8\"?>
					<Pruchod>
						<PkUzivatel>".$pruchod->card->Pk."</PkUzivatel>
						<TypUzivatele>Z</TypUzivatele>
						<Datum>".$datum."</Datum>
						<Cas>".$cas."</Cas>
						<Smer>".$smer."</Smer>
						<Hlavni>1</Hlavni>
						<BranaId>".$pruchod->card_reader->comment."</BranaId>
						<CteckaId>".$pruchod->card_reader->id."</CteckaId>
					</Pruchod>
			";
			Debugger::barDump( $xml, 'xml');
			// pokus pruchod poslat do edookitu
			if ($this->sendRequest("pruchody",$xml)==false) ++$err;
			// zapis o uspesne synchronizaci
			$this->database->table('log')->where('id', $pruchod->id)->update(array('sync'=>1));
			++$i;
		}
		$this->template->xml=$xml;
		$this->template->entries=$i;
		$this->template->err=$err;
		Debugger::barDump( $xml, 'xml');
	}
}



