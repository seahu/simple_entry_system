#!/usr/bin/env python
# -*- coding: utf8 -*-

import struct
import time
import serial
import os
import requests
import RPi.GPIO as GPIO
import smbus
#load pins config
import imp
cfg=imp.load_source('config', '/etc/seahu/pin_config.py')

# nastaveni pinu pro prepinani smeru komunikace na RS485 sbernici
pin_RST=cfg.RS485_RE_DE #11
GPIO.setmode(GPIO.BOARD)
#GPIO.setmode(GPIO.BCM)
GPIO.setup(pin_RST, GPIO.OUT)
GPIO.output(pin_RST, 1) #defaul value=transmission

# nastaveni pinu pro ovladani relay
base_board_addr=cfg.addr2_i2c	#0x38
relay1=0

#globalni promenne
data=() #prijimaci fronta paketu
logLevel=0 # 0-nic, 1-chyby, 2-zpracovani paktu, 3-info o prijmu/odeslani paktu, 4-zabrazeni dat prijmu/osedlani paketu, 5-zobrazeni casovych znacek od posledni prijasy synchronizacni hlavcky ze ctecek, 6-hlavicky funkci
xOnTime=0 # casova znaka pro signal udrzijici ctecku na zivu
startTime=0 #jan pro ladeni rychlsoti potvrzeni
relayOnTime=0 # cas kdy se ma zavrit rele dverniho zamku
relayDelay=3 # doba po kterou bude otevrene rele pri povoleni vstupu

ser = serial.Serial(
    port='/dev/ttyS0',
    baudrate = 57600,
    parity=serial.PARITY_NONE,
    stopbits=serial.STOPBITS_ONE,
    bytesize=serial.EIGHTBITS,
    timeout=0.1
    )
print ("Test RS485 Slave side:")

ser.flushInput()


#pomocna fce pro logovani 
def log(uroven, log):
	global logLevel
	if uroven>logLevel : return
	print ("LOG:"+str(uroven)+" "*uroven+log)

# pomocna fce pro zobrazeni obsahu ciselneho pole v hex podobe
def printArrInHex(arr):
	str="[ "
	#for i in arr: str=str+" 0x"+format(i,'02x')+","
	for i in arr: str=str+" "+format(i,'02x')
	str=str+" ]"
	#print(str)
	return str

# pomocna funkce pro prevod pole cisel na souvisy text (bez mezer) reprezentujici hexa hodnoty (pro vytvoreni nazvu adresare na zaklade mac adresy ctecky)
def tupleToHex(arr):
	str=""
	for i in arr: str=str+format(i,'02x')
	return str

# encode BCD (vubec zde nepouzivam)
def enBCD(i):
	if i> 99: return 0x99 # vtsi cislo nez 99 nejde v DBC codu ulozit
	decs=i % 10
	units=i-(decs*10)
	return decs*16+units

# decode BDC (vubec zde nepouzivam)
def deBCD(i):
	d=((i & 0xF0)>>4)*10 + (i & 0x0F)
	return d

# pomocna fce pro ovladani pinu na IOexpanderu na ktrem je povesene rele
def set_bit_of_io_expander(addr_i2c, bit_number, set):
	bus = smbus.SMBus(1) # 1 is number of i2c bus
	mask = 1<<bit_number
	inverse_mask=mask^0xFF
	if set==1 :
		bus.write_byte(addr_i2c, bus.read_byte(addr_i2c)|mask) # this more complication because save state other pins
	else :
		bus.write_byte(addr_i2c, bus.read_byte(addr_i2c)&inverse_mask)

# pomocna fce pro ovladani pinu na IOexpanderu na ktrem je povesene rele
def get_bit_of_io_expander(addr_i2c, bit_number):
	bus = smbus.SMBus(1)
	mask = 1<<bit_number
	if bus.read_byte(addr_i2c)&mask==0 :
		return 0
	else :
		return 1

# sepnuti rele dverniho zamku status=0 pro otevreni status=1 pro zavreni
def set_relay(status):
	global base_board_addr # i2c adresa GPIO expanderu s relatky
	global relay1 # cislo portu i2c GPIO expanderu na krerm je pripojene rele dverniho zamku
	global relayOnTime # cas kdy se ma zavrit rele dverniho zamku
	global relayDelay # doba po kterou bude otevrene rele pri povoleni vstupu

	set_bit_of_io_expander(base_board_addr, relay1, status)
	if status==0:
		relayOnTime=time.time()+relayDelay
	else:
		relayTime=1

# zjisteni stavu rele dverniho zamku
def get_relay():
	global base_board_addr
	global relay1
	return get_bit_of_io_expander(base_board_addr, relay1)
	
# pokud je potreba vypne rele
def checkOffRealy():
	global relayOnTime # cas kdy se ma zavrit rele dverniho zamku
	if time.time()<relayOnTime: return # jeste nenasel cas na zavreni rele
	if get_relay()==1: return # rele je jiz vyple
	set_relay(1)

		
	

	
# funkce pro ziskani paketu ze streamu dat ziskanych ze seriove linky 
# paket se rozlisuje tim, ze na zacatku je priznak 0x00, 0x55, 0x55, 0x55, 0x55
# a konec se pozna podle delky zasale uvnitr paketu nebo ze zacina dalsi paket , nebo ze se na seriove lince
# delsi dobu nedostavaji zadna data
# protoze je mozne, ze se najednou z buferu seriove linky precte vice paketu
# tak je to takto slozite

def wait_to_start_packet():
	log(6,"FUNKCE: wait_to_start_packet()")
	global data
	global xOnTime 
	synchron_vlajka=[0x00, 0x55, 0x55, 0x55, 0x55]
	lastContact=0
	log(3,"Cekam na synchronizacni znacku.")
	while 1:
		#log(5,"wait")
		if len(data)>=5:
			if data==(0, 0x55, 0x55, 0x55, 0x55):
				return
			else:
				data=data[1:] # vyhod fronty dat prnvu Byte
		#pokus prijmout dalsi Byte
		receive = ser.read(1)
		if len(receive)==0: # nic nedoslo (nic se nedeje)
			checkOffRealy() # kdyz je potreba vypni rele
			if (time.time()-lastContact)> 0.1 : #pokud je delsi dobu klid
				if time.time()-xOnTime>16: sendXonPacket() # a je uz potreba poslat udrzovaci paket, tak ho posli
			continue
		else : # dosel dalsi Byte
			lastContact=time.time() #poznacim si kdy naposledy neco doslo
			data=data+struct.unpack('B'*len(receive), receive)
			#log(5,"new data: %s" %( printArrInHex(data)) )

# funkce, ktere prijme paket v surove podobe bez parsovani a jakychkoliv kontrol
def recivre_packet():
	global data
	global startTime
	log(6,"FUNKCE: recivre_packet()")
	dataLen=100 #maximalni delka paketu
	GPIO.output(pin_RST, 0) #receiver
	last_time=time.time()
	wait_to_start_packet() # cekej na zacatek paketu
	startTime=time.time()
	# aktivne kontroluj, zda-li v salanych datech neni znacka zacatku dalsiho paketu (tj. konec tohoto) a pokud ne tak kontroluj cas posledni aktivity na seriove lince
	x=5; # x je misto odkud se kontroluje znacka zacatku noveho paketu (konce tohoto), 5 proto, ze konec kontroluji az za svou znackou pocatku ktare ma 5 bytu.
	while 1:
		#nejdrive zkontroluj stavajici frontu zda-li neobsahuje vlajku zacatku dalsiho paketu (=konec predchazejiciho paketu) pokud ano tak ho vrat a kdyz ne tak precti dalsi data z linky a celou kontrolu proved znovu
		if (len(data)>=10):
			if data[-5:]==(0, 0x55, 0x55, 0x55, 0x55): #pokud konec paketu odpovida synchronizacni vlajce
				sendData=data[:-5] # odesilat se boudou data krome posledni 5B obsahujich synch. flajku dalsiho paketu
				data=data[-5:] # pole dat pro zpracovani nasledujiciho paketu se orizne o data aktualniho apaketu
				log(3, "Prijmam paket ukonceny zacatkem dalsiho paketu.")
				return sendData
		receive = ser.read(1)
		if len(receive)==0 : # nic jsem neprijal
			if (time.time()-last_time)>0.05 : # vyprsel timeout
				sendData=data
				log(3,"Prijimam paket ukonceny dlouhou dobou necinosti.")
				data=() # vyprazdni globani zasobnik dat ziskanych ze seriove linky
				return sendData # kdyz dlouho nic nedostavam tak vrat co mas (jinymi slovy kdyz dlouho nic nedostavam tak nemusim cekat az se objevi zacatek dalsiho paketu)
		else : #prjaty dalso Byte
			last_time=time.time()
			data=data+struct.unpack('B'*len(receive), receive)
			# protoze na nektere pakety se musi odpovedet temer okamzite nemuzu cekat az dojde dalsi paket ci vyprsi timeou, ale v prubehu stahovani paketu zjistim jeho delku a po prijeti daneho poctu dat hnet paket zariznu
			if len(data)>=8: dataLen=data[7]+2+8 # na 7. pozici je v daketu zapsana delka dat ke ktere se jeste prictou 2B kontrolniho souctu 5 5B synchronizace + 2B adresy +1B byte s delkou a dostaneme celkovou delu paketu
			if len(data)==dataLen:
				sendData=data
				log(3,"Prijimam paket ukonceny svou delkou.")
				data=() # vyprazdni globani zasobnik dat ziskanych ze seriove linky
				return sendData # kdyz dlouho nic nedostavam tak vrat co mas (jinymi slovy kdyz dlouho nic nedostavam tak nemusim cekat az se objevi zacatek dalsiho paketu)

# funkce, ktera se pokusi ziskat pravoplatny packet (provadi kontroly a vrati korektni rozparsovany packet nebo false)
# nutno podoknout ze rozeznavam dva druhy paketu normalni a overovaci (overovaci nema ID paketu ani data)
def get_packet():
	global startTime
	log(6,"FUNKCE: get_packet()")
	data=recivre_packet() # zde jsou data lokalni prmennou
	log(5, "Doba prijimani paketu: %d ms delky %d B." %((time.time()-startTime)*1000, len(data) ) )
	log(4, "Prijaty packet: %s" %(printArrInHex(data)) )
	if len(data)<17 : 
		log(1, "Prilis kratky paket")
		return False # paket musi mit min 10B
	if data[0:5]!=(0, 0x55, 0x55, 0x55, 0x55): 
		log(1, "Paket nezacina hlavickou")
		return False
	dest=data[5]
	sour=data[6]
	lenOfData=data[7]
	if len(data)<(8+lenOfData+2) :
		log(1, "Nesedi delka paketu")
		return False # nesedi delka dat
	flag=data[8]
	fernetVersion=data[9]
	lenOfHead=data[10]
	mac=data[11:15]
	crcData=data[5:8+lenOfData]
	inData=data[16:8+lenOfData]
	crc1=data[8+lenOfData]
	crc2=data[8+lenOfData+1]
	CRC1=0
	for i in crcData: CRC1=CRC1^i
	CRC2=0
	for i in crcData: CRC2=CRC2+i
	CRC2=(~CRC2) & 0x000000FF
	if crc1!=CRC1: 
		log(1, "Nesedi CRC1")
		return False;
	if crc2!=CRC2: 
		log(1, "Nesedi CRC2")
		return False;
	if not((dest==0xFF or dest==0xFD)):
		log(3, "Paket neni urcen pro ridici jednotku (dest:%X)" %dest)
		return False;
	if flag&0xF0==0x80 : #paket vyzaduje potvrzeni
		log(3, "Paket vyzaduje potvrzeni prijmu")
		sendPacket(flag&0x0f+0x40,mac,None) #kdyz je potreba co nejrychli posli potvrzovaci paket
	if flag&0xF0==0x40 :
		ID=None
		log (3, "dest: %X,   sour: %X,   len: %X ,   vers:%X,   ID: None,   mac: %s" %(int(dest), int(sour), int(lenOfData), int(fernetVersion), printArrInHex(mac)) )
	else:
		ID=data[15]
		log (3, "dest: %X,   sour: %X,   len: %X ,   vers:%X,   ID: %X,   mac: %s" %(int(dest), int(sour), int(lenOfData), int(fernetVersion),int(ID), printArrInHex(mac)) )
	return {"dest":dest,"sour":sour,"mac":mac,"flag":flag,"id":ID,"data":inData}

#odesle raw data
def sendRawPacket(data):
	global startTime
	log(6,"FUNKCE: sendRawPacket(%s)" %(printArrInHex(data)) )
	log(3, "Odesliam data:")
	log(4, "Odesilam data: %s" %(printArrInHex(data)) )
	binData=""
	for i in data:
		binData=binData+struct.pack('B',i)
	# pockej dokud nebude na sbernici klid (to jsem zavrh protoze, kdyz je na sbernici klid  ser.read(1) se mi vrati az po timeoutu seriove linky a to uz muze byt pro ptvrzovaci pakety pozde)
	#while 1:
	#	if len(ser.read(1))==0 : break
	#	time.sleep(0.001)
	# pak prepni na vysilani
	GPIO.output(pin_RST, 1) #transmission
	t=time.time()
	log(5, "Doba zacatku vyslani od prijeti paketu: %d ms" %((t-startTime)*1000 ) )
	ser.write(binData) # zapis data
	time.sleep(12.0*len(binData)/57600) #pockej nez se data poslou -vypocet doby posilani dat po seriove lince (s rezervou, teprve po uplynuti teto doby muzu smenit vysilani na prijem, jinak by bylo vysilani preruseno) (PS: musi tam byt 10.0 jinak to posicta s flota cisly ale celymi cisly a vrati 0)
	GPIO.output(pin_RST, 0) #receiver
	log(5, "Doba odesilani paktu %d ms delky %d B." %((time.time()-t)*1000, len(data)) )
	log(5, "Doba konce vysilani od prijeti paketu: %d ms" %((time.time()-startTime)*1000 ) )
	return
	
# seklda a odesle packet
def sendPacket(flag,mac,ID,inData=(),dest=0xFE,sour=0xFD ):
	log(6,"FUNKCE: sendPacket(0x%X, %s, %s , %s, 0x%X, 0x%X)" %(flag, printArrInHex(mac), str(ID), printArrInHex(inData), dest, sour) )
	log(3, "Odesilam paket.")
	head=(0x00,0x55,0x55,0x55,0x55)
	# rozlisuji dva typy paketu s ID a bez ID (ty bez ID jsou jen potvrzujici a nemaji data)
	if ID==None: #bez dat je potvrzujici
		data=(dest,sour,0x07,flag,0xF2,0x04,mac[0],mac[1],mac[2],mac[3])
	else: # s daty
		data=(dest,sour,len(inData)+8,flag,0xF2,0x04,mac[0],mac[1],mac[2],mac[3],ID)+inData
	CRC1=0
	for i in data: CRC1=CRC1^i
	CRC2=0
	for i in data: CRC2=CRC2+i
	CRC2=(~CRC2) & 0x000000FF
	crc=(CRC1,CRC2)
	data=head+data+crc
	sendRawPacket(data)
	return

# odesle kontrolni packet
def sendCheckPacket(orig_flag, new_flag,mac):
	log(6,"FUNKCE: sendCheckPacket(0x%X, 0x%X)" %(orig_flag, new_flag,mac) )
	sendPacket
	log(3, "Odesilam kontrolni paket.")
	flag=(orig_flag & 0x0000000F) + (new_flag & 0x000000F0)
	return sendPacket(flag,mac,None)

# odesle XON paket (paket informujici ctecku ze system je on-line - takova vizulizace stavu systemu primo na ctecce)
def sendXonPacket():
	global xOnTime #cas posledniho deslani XonPacketu
	log(5,"FUNKCE: sendXonPacket()")
	log(3, "Odsilam Xon paket.")
	# flag=0x03,source=0xFD,dest=0xFF
	sendRawPacket((0x00, 0x55, 0x55, 0x55, 0x55, 0xFF, 0xFD, 0x0C, 0x03, 0x00, 0x19, 0x02, 0x07, 0x19, 0x01, 0x40, 0x02, 0x12, 0x0A, 0x0B, 0x58, 0x4F)) # v datech je zakodovane datum a cas, ten se stejne nikde nekontroluje tak kaslu na to abych tam posilal aktualni datum a cas
	xOnTime=time.time() # aktualizuj cas odeslani poslednih XonPacketu
	return

# zkontroluje kartu oproti vstupnimu systemu a vrati True-povoleni vstupu nebo False-zamitnuti vstupu
def check_card_access(card_code, mac):
	log(5,"FUNKCE: check_card_access(%s, %s)" %(card_code, mac) )
	#"http://10.10.109.36/access/action/show-check-card-code?card_code=15154654&card_reader_address=4564646"
	url="http://localhost/access/action/show-check-card-code"
	url=url+"?card_code="+card_code+"&card_reader_address="+mac
	log(3, "Wget: %s" %url )
	t=time.time()
	r = requests.get(url)
	log(6, "Doba odpovedni na kontrolu karty z nadrazeneho systemu: %d ms." %((time.time()-t)*1000) )
	print r.status_code
	print r.content
	if r.status_code!=200 :
		return False # jediny spravny navratovy kod je 200 jinak je nejaky problem
	if r.content=="access" :
		return True
	else :
		return False
	
#----- konec funkci zacin hlavni program ---------
log(5,"FUNKCE: HLAVNI PROGRAM")
check_card_access("fsdfsd", "aas")

macs=[] #seznam znamych adres ctecek
while 1==1:
	packet=get_packet()
	if packet==False:
		log(1, "Paket ve spatnem formatu.")
		continue
	# obsluha konfigurace ctecek
	if packet["id"]==0x0B: # id=0x02 => FN_EVDEVICEPOWERUP (ohlaseni zapnuti ctecky)
		log(2,"Ctecka s mac: %s se prave zapla" %(printArrInHex(packet["mac"])) )
	if packet["id"]==0x02: # id=0x02 => FN_EVNOTCONFIGURE (ctecka hlasi ze je nenakonfigurovana)
		log(2, "Ctecka hlasi ze je nenakonfigurovana.")
		if packet["mac"] not in macs : # pridani nove ctecky do meho seznamu ctecek abyh ho mohl pozdeji propagovat nadrzene php aplikaci
			dirName="/tmp/card_readers/%s" %(tupleToHex(packet["mac"]) )
			log(3, "Vytvarim adresar: %s" %dirName)
			if not os.path.exists(dirName):
				os.makedirs(dirName)
			#macs.append(packet["mac"])
		flag=0x00
		ID=0x03
		log(2, "Odeslani konfigurace ctecky.")
		sendPacket(flag,packet["mac"],ID) # id=0x03 => odeslani nakonfigurovani ctecky menlo by dojit potvrzeni s ID=0xFC (konfigurace spociva v nastaveni ctecky na tento ridici modul tj. dest=0xFD)
	elif packet["id"]==0xFC: # id=0xFC => FN_EVPRESENT ( ctecka ohlasuje prijeti nove konfigurace)
		log(2, "Ctecka ohlasuje prijeti nove konfigurace.")
		flag=0x00
		ID=0x09
		log(2,"Odeslani potvrzeni ohlaseni o prijeti konfigurace")
		sendPacket(flag,packet["mac"],ID) # id=0x09 => odeslani potvrzení přijetí FN_EVPRESENT řídící jednotkou
	# obsluha karet
	elif packet["id"]==0x91: # id=0x91 => Zpráva FN_DC_CARDREADED o přečtení čipu (karty)
		log(2,"Preceteni karty")
		if len(packet["data"])<3: # kontrola zdali data obsahuji vubec delku kodu karty
			cardCode=""
			log(1,"Bad format packed with ID=0x91, so short.")
			continue
		else:
			lenCardCode=packet["data"][2]
			log (4, "data: %s" %printArrInHex(packet["data"]))
			log (4, "Delka kodu karty: %d" %lenCardCode )
			if len(packet["data"])< (3+lenCardCode): # kontrola zda-li se do dat vubec muze vejic cela dekla kodu karty
				cardCode=""
				log(1,"Bad format packed with ID=0x91, so short.")
				continue
			else:
				cardCode=packet["data"][3:lenCardCode+3]
				log (4, "data codu karty: %s" %printArrInHex(cardCode))
				cardString=""
				for c in cardCode:
						cardString=cardString+chr(c)
				log(2,"Kod karty: %s" %cardString)
		if check_card_access(cardString, tupleToHex(packet["mac"]))==True:
			# povoleni vstupu
			log(2,"Zaslani povoleni vstupu.")
			flag=0x83
			ID=0xE0
			timeRealyOn=30 #cas sepnuti rele v des. sec
			# sekladani dat obsahuujici sepnuti rele po zadanou doubu=rosviceni zelene led na stejnou dobu a zahrani pozitivne ladene melodie
			inData=(0x06, 0x3A, 0x00, timeRealyOn, 0x00, 0x00, 0x00, 0x14, 0x90, 0x03, 0x00, 0x03, 0xA0, 0x0F, 0x32, 0x00, 0x00, 0x00, 0x0A, 0x00, 0x88, 0x13, 0x64, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00) 
			sendPacket(flag,packet["mac"],ID,inData) # id=0xE0 => povoleni pristupu, melo by nasledovat potvrzeni od ctecky ze povoleni prijala s id=0x14.
			set_relay(0);
		else :
			# zamitnuti pristupu
			log(2,"Zaslani zamitnuti vstupu.")
			flag=0x83
			ID=0x90
			#sekladani dat obsahujicich zahrani melodie pro zamitnuti pristupu
			inData=(0x03, 0x00, 0x03, 0x88, 0x13, 0x32, 0x00, 0x94, 0x11, 0x32, 0x00, 0xA0, 0x0F, 0x32, 0x00, 0xAC, 0x0D, 0x32, 0x00, 0xB8, 0x0B, 0x32, 0x00, 0x00, 0x00, 0x00, 0x00)
			sendPacket(flag,packet["mac"],ID,inData) # id=0x90 => zamitnuti pristupu, melo by nasledovat potvrzeni od ctecky ze zamitnuti prijala s id=0x13.
			set_relay(1);
	elif packet["id"]==0x14: # id=0x14 => potrzeni z ctecky, ze prijala zpravu o povoleni vstupu
		log(1, "Card reader accept messge for allow enter.") # jen ze zajimavosti vypisu info jinak
		continue # zpravu muzu ignorovat
	elif packet["id"]==None: # id=0x13 => potrzeni z ctecky, ze prijala zpravu o zamitnuti vstupu
		if (packet["flag"]&0xF0==0x40) :
			log(2, "Ctecka ptvrzuje prijem prikazu pro povoleni ci zamitnuti vstupu.") # jen ze zajimavosti vypisu info jinak
		continue # zpravu muzu ignorovat
	else:
		log(2, "Paket nerospoznan.")		
		
	


# flag - neni rozliseni podle ucelu, ale podle typu rekace
# 0x80 (0x81,0x82,0x83) jsou paktety, ktere vyzaduji potvrzeni
# 0x40 (0x41,0x42,0x43) je potvrzeni paketu
# 0x00 (0x01,0x02,0x03) jsou pakety nevyzadujici potvrzeni

# source a destination adresy
# ridici jednotka vzdy 0xFD
# ctecka ma vzdy 0xFE + ver. 0xF2 + mac (tj. kromne 0xFE je ve verzi 0xf2 jeste pouzita mac adresa)

#rozeznavani zprav:
#  Ohlášení zapnutí čtečky FN_EVDEVICEPOWERUP - bez reakce (muzu zahodit)
#  Ohlášení nenakonfigurované čtečky FN_EVNOTCONFIGURE					id=0x02
#		ma reakce: Odeslani Potvrzení ohlášení čtečky řídící jednotkou FN_CMHWCONFTERM (odeslani konfigurace)
#  Ohlášení nakonfigurované čtečky FN_EVPRESENT							id=0xFC
#		ma reakce: Odeslani Potvrzení přijetí FN_EVPRESENT řídící jednotkou (potvrdim ctecce ze je spravne nakonfigurovana)
# Zpráva FN_DC_CARDREADED o přečtení čipu
#		ma reakce: Potvrzení od řídící jednotky (odeslani potvrzeni o prijeti zpravy)
#		mozna dalsi reakce1: Povolení vstupu od řídící jednotky
#			rekace ctecky: Potvrzení přijetí povelu o otevření
#		mozna dalsi reakce2: Zamítnutí vstupu od řídící jednotky
#			reakce ctecky: Potvrzení přijetí povelu o zamitnuti


	
	
	
	
	
	
	
