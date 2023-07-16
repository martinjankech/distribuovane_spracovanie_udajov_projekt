# php_crud_single_page_app
Projekt na DSD prepojenie 3 vzdialených uzlov 
# Zadanie projektu
Cieľom projektu je vytvorenie distribuovaného informačného systému aspoň z 3 uzlov. Každý uzol bude fyzický počítač v konfigurácii servera webového aj databázového. Odporúčaný je Wamp, Xampp, Lamp, ale nie je podmienkou.  
Uzly sú navzájom prepojené buď priamo cez switch, alebo cez net pomocou VPN napr. Log MeIn Hamachi. 
Na každom uzle beží tá istá aplikácia (webová aplikácia) a databáza s rovnakou štruktúrou. 
Transakcia vytvorená na hociktorom uzle sa prejaví na domácom uzle (kde vznikla) a zároveň sa prejaví aj na ostatných uzloch. Takto sa zabezpečí konzistentnosť databáz. Odporúčame používať rovnaký SRBD a rovnaký typ DB, ale nie je to podmienka. Referenčná integrita môže byť porušená len na dobu, kým sa údaje z inicializačného uzla replikujú na ďalšie uzly. Spúšťanie replikácie môže byť automatické (pri vzniku transakcie), alebo manuálne (na stlačenie tlačidla). 
Minimálna požiadavka na udelenie zápočtu je 20b a sú splnené vyššie opísané kritériá. 
Na dosiahnutie vyššieho počtu bodov až do 40b je potrebné vyriešiť replikáciu dát po tom ako došlo k výpadku komunikácie medzi uzlami. 
Pri výpadku každý uzol pracuje autonómne ďalej, ale len tie záznamy môže upravovať,  ktoré vznikli na danom uzle (napr. uzol 1 len záznamy vytvorené uzlom 1). Vytváraním nových Query vznikajú nové  záznamy v tabuľkách. DDB sa dostáva do stavu nekonzistentnosti. Po obnovení spojenia sa replikujú záznamy, ktoré ešte replikované neboli. Automatickým alebo manuálnym spustením. Po replikácii sa nachádzajú v DB na každom uzle rovnaké záznamy a konzistentnosť údajov je obnovená. 
Max. počet bodov získajú riešenia ktoré sú funkčné, prehľadné a elegantné. 
# popis projektu 
V tejto práci sme vytvorili distribuovaný informačný  systém pre pre jednoduchú filmovú databázu. Naša webová aplikácia obsahuje úvodnú stránku, kde sa používateľ musí zaregistrovať. Po úspešnej registrácii sa môže prihlásiť do svojho účtu, kde si môže prezerať záznamy o filmoch, ako aj nové záznamy pridávať, editovať a zmazať. V práci sme pomocou hamachi prepojili 4 uzly, na ktoré sú dané dáta replikované(automaticky). Taktiež sme zabezpečili automatickú synchronizáciu dát na jednotlivých uzloch a to pomocou pomocného textového súboru, na ktorý sa zapisujú IP adresy a SQL príkazy uzlov, pre ktoré nastala chyba spojenia. Po obnovení spojenia sú záznamy na dané uzly(pri ktorých takto vznikla nekonzistencia) replikované. Po výpadku môže vypadnutý uzol upravovať len tie záznamy, ktoré boli ním vytvorené a taktiež ostatné uzly nemôžu upravovať záznamy vypadnutého uzla. V priebehu prace si naše riešenie podrobnejšie popíšeme. 
# databaza 
Na začiatku sme si premysleli, ako bude vyzerať databáza pre našu webovú aplikáciu. Po dôkladnom premyslení sme prišli nato, že budeme potrebovať 2 tabuľky. Jednu pre ukladanie záznamov o registrovaných používateľoch a druhú na ukladanie záznamov o filmoch.
Pri jednotlivých filmoch bude vždy jasné, ktorý používateľ ich vytvoril a na akom uzle boli vytvorené. V databáze taktiež zaznamenávame, ktorý používateľ film naposledy upravil a taktiež na akom uzle boli naposledy upravené. Pre náš program  je kľúčový atribút Node_Created ktorý uchováva na akom uzle daný záznam vznikol, pretože len ten uzol môže pri výpadku na daných záznamoch robiť zmeny, ktorý dané záznamy vytvoril.  
# Ukážka štruktúry tabuliek
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/40c2e66a-0d03-48a6-adf3-b21cb733c3aa)
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/19fcebfc-f43f-4cd3-9c9a-22cf71c64a7f)
# Súbor db.php 
V tejto triede sa nachádzajú 4 metódy pomocou ktorých vieme získavať, vkladať editovať a mazať záznamy z/do databázy. Metódy sú napísané  tak, aby sa dali použiť na viacerých miestach v aplikácií. (teda SQL string sa vytvára na základe vložených parametrov a pri rozšírení programu nemusíme písať nove SQL stringy ale len použiť tieto metódy s inými parametrami). Metódy majú buď 2 alebo 3 parametre a to meno tabuľky a  dáta + podmienky ktoré sa nachládajú v asociatívnom poli. Po vložení týchto 2 až 3 parametrov sa nám postupne vytvorí SQL string, ktorý sa vykoná len na uzloch, ktoré sa nachádzajú v poli aviableconnection(teda uzly ktoré sú pripojené ). Pre tie uzly, ktoré sú v poli notaviableconnetion sa daný SQL string nevykoná, ale zapíše sa do pomocného súboru notaviablenodes.txt. 
Textový súbor notaviablenodes.txt číta metóda z config.php s názvom sychronize, ktorá sa pokúša pripojiť na uzly ktorých IP adresa a priradený SQL príkaz sa nachádza v tomto textovom súbore. Pokiaľ je pripojenie obnovené, tak je daný SQL príkaz pre daný uzol vykonaný a dáta sú tak synchronizované. Všetky príkazy, ktoré už boli vykonané sa z daného textového súboru vymažú. 
# pouzite technologie 
* VPN- Hamachi 
* Server a Databáza – cez Xamp – Apache + phpMyAdmin (MySQL)
* Frontend – HTML, CSS, JavaScript, jQuery + Ajax – pre single Page aplikáciu,  Bootstrap. 
* Backend – PHP
# Ukážka login.php 
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/dcef8a50-63c1-472f-aa4a-71c44cb56af2)
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/67239884-51eb-4e78-99c4-98a67707a147)
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/0e0c234a-82b1-4278-86aa-085cd67fdc40)
# Ukážka index.php 
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/02d295a5-ece9-4c30-adc4-7f387bcb5a1f)
# Ukážka login.php po výpadku 1 uzla (simulujeme odpojením uzla z hamachi)
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/d6729213-f20d-4fb1-a010-72683c5dfe00)
* Na vypadnutom uzle(25.42.132.140) naopak môžeme upravovať len tie záznamy, ktoré vznikli na ňom a ostatné sú zablokované.
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/fa01830f-4461-45a0-aeba-e2882c3c6cc0)
# Ukážka pri výpadku uzla a registrácii používateľa 
Ak sa registrujeme a jeden uzol je odpojený tak je logika aplikácie rovnaká. 
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/08f84060-0bdd-452b-91da-b0aff8cdca16)
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/85d9776e-4ad4-4b48-b89a-707bb19fc822)
Pre odpojený uzol sa zapíše insert do  pomocného textového súboru a po obnovení spojenia je používateľ opäť o tom oboznámený na login.php stránke plus užívateľ je zapísaný aj do tohto uzla
![image](https://github.com/martinjankech/distribuovane_spracovanie_udajov_projekt/assets/63880926/3c0f7d46-038c-4403-8f5a-588b3c3e316a)
# Záver
V tejto ukážke sme si popísali postup, akým sme vytvárali  našu distribuovanú aplikáciu. Myslíme si, že sa nám zadanie podarilo splniť v plnom rozsahu, keďže sme zabezpečili ako aj automatickú replikáciu údajov na všetky uzly, tak aj synchronizáciu údajov po tom, ako došlo k výpadku a databázy odpojených uzlov sa dostali do nekonzistencie. Taktiež sme zabezpečili to, že keď nám vypadne spojenie medzi uzlami, tak tieto uzly môžu pracovať autonómne ďalej, ale upravovať môžu iba tie záznamy, ktoré vytvorili(pre ostatné majú zablokované tlačidlá) V tejto ukážke sme opísali  hlavnú logiku a funkcie, ktorými náš program  zadanú funkcionalitu zabezpečuje. Samozrejme, že zdrojový kód je oveľa rozsiahlejší, ale nevideli sme dôvod čitateľa zaťažovať  opisom html css a jquery funkcií, ktoré len robia náš program dynamickejší a štýlovejší, ale neplnia samotnú podstatu zadania. 



