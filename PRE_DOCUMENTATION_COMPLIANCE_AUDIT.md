# PRE-DOCUMENTATION STRICT COMPLIANCE AUDIT — M17A

Datum audita: 13.08.2026.  
Opseg: stvarni kod/SQL/UI posle M15; bez dokumentacije M16 i bez izmena aplikacije.  
Autoriteti pročitani: originalni profesorov PDF `docs/izvori/VP Plan rada sk 2025 26 STANJE 7 6 2026 dopunjena struktura dokumenta seminarskog rada.pdf`, prijava teme `docs/izvori/Prijava_VP_Nabavka_drustvenih_igara.pdf`, zatim stvarni repository. Planning statusi nisu korišćeni kao dokaz.

# 1. Executive summary

Opšta procena: obavezna funkcionalna struktura je statički prisutna i PHP sintaksa je ispravna, ali projekat još nije bezbedan za predaju. Postoje delimični runtime HTTP dokazi za izmenu, detalj, listu, brisanje i štampe, ali oni ne dokazuju semantičku ispravnost svih tokova. Glavni razlozi za korekciju su nedostajuća login validacija, pogrešni direct-action povratni URL-ovi, aktivni 404 asseti, upload defekt, kontradiktoran README, delimičan MVC i nedovršena runtime matrica.

- BLOCKER: **0**
- HIGH: **3**
- MEDIUM: **11**
- LOW: **10**
- Obaveznih 20 bodova: **statički uglavnom realizovano, ali još nije potpuno odbranjivo bez korekcija i runtime provere**.
- MVC opcionih 10 bodova: **PARTIAL**; slojevi postoje, ali tok nije dosledan i view/action slojevi prelaze granice.
- REST opcionih 10 bodova: **RUNTIME_NOT_VERIFIED**; statički je demonstrabilan servis sa ruterom i domenom igara.
- Trenutno sprečava bezbednu predaju: nedostajuća login validacija, pogrešni error/back redirect nivoi i nepotpuna runtime matrica. Pre predaje treba rešiti i MEDIUM aktivne defekte: 404 assete, upload putanju, stari README/naziv baze i MVC rizik.

Brojevi iznad predstavljaju **24 jedinstvena problema iz sekcije 26**, a ne zbir ponovljenih uticaja istog problema u matricama zahteva.

# 2. Original professor requirements matrix

| ID | Originalni zahtev | Obavezni/opcioni | Status | Severity | Evidence | Runtime evidence | Problem | Minimal correction |
|---|---|---|---|---|---|---|---|---|
| PROF-NF-01 | Baza podataka na srpskom jeziku | Obavezni | VERIFIED | — | `bazapodataka/BazaPodataka.txt:7-86`; nazivi tabela, kolona i seed poslovne vrednosti su srpski | Nije izvršen clean import | `Biblioteka_VP_2026` jeste srpski, ali je stari domen; to je zaseban MEDIUM consistency problem | Domenski naziv baze uskladiti pre predaje |
| PROF-NF-02 | Programski kod na srpskom jeziku | Obavezni | AMBIGUOUS_RISK | LOW | Aktivni identifikatori su mešavina srpskih (`Knjiga`, `Zanr`) i ustaljenih engleskih tehničkih termina (`Controller`, `Model`, `filter`, `options`) | N/A | Profesor ne definiše da li zahtev obuhvata sve identifikatore ili prvenstveno domenske nazive/poruke | Ne raditi rizičan rename bez razjašnjenja; spremiti odbranu semantičkog mapiranja |
| PROF-NF-03 | Korisnički interfejs na srpskom jeziku | Obavezni | VERIFIED | LOW | Svi aktivni UI stringovi su srpski; koriste i ćirilicu i latinicu/ASCII transliteraciju | Delimični runtime HTTP dokazi postoje, vizuelni pregled nije dokumentovan | Pismo i dijakritici nisu profesorov uslov; postoje presentation/spelling rizici (`IGAR`) | Opciono ujednačiti pravilnu srpsku latinicu |
| PROF-NF-04 | Veb aplikacija poslovne orijentacije, samostalna tema | Obavezni | VERIFIED | — | Registrovana nabavka, master-detail nalog, PHP/DB poslovni tok | Delimični runtime dokazi za nalog postoje | — | Nije potrebna |
| PROF-TECH-01 | PHP bez frameworka | Obavezni | VERIFIED | — | 69 PHP fajlova; nema Composer/framework bootstrap-a | PHP lint izvršen | Nema nalaza frameworka | Nije potrebna |
| PROF-TECH-02 | Relaciona baza, najmanje 4 tabele | Obavezni | VERIFIED | — | SQL definiše 5 tabela: `kategorija_igre`, `drustvena_igra`, `KORISNIK`, `nabavka`, `stavka_nabavke` | Clean import nije izvršen | — | Runtime clean import |
| PROF-TECH-03 | 3 povezane tabele: celina, deo, šifarnik | Obavezni | VERIFIED | — | `nabavka`→`stavka_nabavke`→`drustvena_igra`, FK na SQL linijama 58-65 | DB nije runtime inspektovan | — | Runtime FK test |
| PROF-TECH-04 | 1 nezavisna tabela korisnik | Obavezni | VERIFIED | — | `KORISNIK`, SQL 28-38; nema FK | Login nije runtime testiran | — | Runtime login |
| PROF-TECH-05 | Stored procedure postoji i koristi se | Obavezni | RUNTIME_NOT_VERIFIED | HIGH | `DodajDrustvenuIgru`, SQL SP 27-50; CALL u `DBKnjigaSP.php:14-20`; forma `unosSP` | CALL nije izvršen | Statički spoj postoji, runtime nije dokazan | Import SP i demonstrirati unos |
| PROF-TECH-06 | Pogledi postoje i koriste se | Obavezni | PARTIAL | MEDIUM | Dva VIEW-a, SQL 3-25; samo `svipodacioidrutvenimigramasaslikom` ima dokazanu PHP/list/API upotrebu | Aktivni VIEW SELECT nije zasebno runtime verifikovan | `svipodacioidrutvenimigrama` je definisan, ali nema PHP potrošača | Ili koristiti oba VIEW-a ili ukloniti nekorišćeni i odbraniti aktivni VIEW kriterijum |
| PROF-OOP-01 | OOP rešenje sa nasleđivanjem tehnoloških baznih klasa | Obavezni | VERIFIED | — | `DBKnjiga`, `DBKnjigaV`, `DBKnjigaSP`, `DBZanr`, `DBNabavka`, `DBStavkaNabavke`, `DBKorisnik` extends `Tabela` | Objekti nisu runtime praćeni | Izvedene klase su aktivno pozvane | Pokazati tok na odbrani |
| PROF-OOP-02 | Kompozicija: Celina sadrži listu delova | Obavezni | VERIFIED | — | `NabavkaEntitet::$ListaStavki`, inicijalizacija i `DodajStavku`, `NabavkaEntitet.php:13,23,26-29`; koristi `nabavkaSnimi.php:66-91` | — | — | Nije potrebna |
| PROF-OOP-03 | Asocijacija: celina/deo sadrži objekat šifarnika | Obavezni | VERIFIED | — | `StavkaNabavkeEntitet::$DrustvenaIgra`; konstruktor 13-19; stvarni `KnjigaEntitet` u create toku | — | Legacy naziv klase je poseban jezički rizik | Nije funkcionalno potrebna |
| PROF-TECH-07 | Multipage aplikacija | Obavezni | VERIFIED | — | `Ruter.php`, više pogleda i GET/POST ruta | Navigacija nije browser-testirana | — | Runtime navigacija |
| PROF-VAL-01 | Client-side HTML/JavaScript validacije | Obavezni | PARTIAL | HIGH | Nabavka i katalog forme imaju validacije; login forma nema `required`/`maxlength` | Browser ponašanje nije provereno | Login polja nemaju osnovnu client validaciju; uniqueness tumačenje je dvosmisleno | Dodati required/maxlength login poljima; UI uniqueness rešiti najjednostavnije prihvatljivim obrascem |
| PROF-VAL-02 | Server-side PHP validacije | Obavezni | PARTIAL | HIGH | Nabavka/katalog imaju PHP validacije; `prijavaprovera.php:3-4` direktno čita POST bez presence/empty/length provere | Nevalidni POST nije runtime testiran | Login server validacija nedostaje; error povratni linkovi su delom pogrešni | Validirati login input pre DB upita; ispraviti direct-action putanje |
| PROF-MD-01 | Master-detail unos na jednoj formi | Obavezni | VERIFIED | — | `pogledi/NovaNabavka.php`: jedna forma, master 64-100, stavke 105-145 | Submit nije testiran | — | Runtime create |
| PROF-MD-02 | Unos celine i delova uz transakciju | Obavezni | RUNTIME_NOT_VERIFIED | HIGH | `NabavkaModel::SnimiNovuNabavku`, START 154, master 157-165, details 172-178, završetak 180 | Create/rollback nisu izvršeni | Statički transakcija obuhvata master i sve detail redove, ali atomsko ponašanje nije runtime dokazano | Runtime create i rollback test |
| PROF-MD-03 | Tabelarni prikaz podataka | Obavezni | PARTIAL | — | `desnoNabavkeLista.php:63-124`, detalji i rekapitulacija | Lista/detail rute imaju HTTP 200, sadržaj nije potvrđen | — | Runtime pregled sadržaja |
| PROF-FUN-01 | Login | Obavezni | PARTIAL | HIGH | forma `desnoprijava.php`; `prijavaprovera.php`; `DBKorisnik`; sesija u `Ruter.php` | Login/logout nisu dokumentovano izvršeni | Nedostaju client/server required/length validacije login polja | Dodati validacije i runtime testirati login/logout |
| PROF-FUN-02 | CRUD glavne tabele koja izražava suštinu dokumenta | Obavezni | PARTIAL | HIGH | CREATE/READ/UPDATE/DELETE nabavke postoje kroz rute 151-295 | Postoje HTTP 200/302 dokazi za listu, detalj, izmenu i brisanje; create i semantika nisu potpuno dokazani | Runtime dokaz je nepotpun | Minimalna E2E matrica |
| PROF-FUN-03 | Unos celine sa delovima na jednoj formi i transakcijom | Obavezni | RUNTIME_NOT_VERIFIED | HIGH | Isto kao PROF-MD-01/02 | Create i rollback runtime nisu provereni | — | Runtime test |
| PROF-FUN-04 | Brisanje glavnog zapisa | Obavezni | PARTIAL | — | ruta `obrisiNabavku`; confirm `desnoNabavkeLista.php:83-86`; CASCADE FK | POST 302 i povratna lista 200 zabeleženi; orphan stanje nije provereno | Runtime semantika nepotpuna | Brisanje naloga sa više stavki i orphan provera |
| PROF-FUN-05 | Izmena glavnog zapisa | Obavezni | PARTIAL | HIGH | ruta/form/action/model/repo za master i stavke | Edit forma 200, POST 302, detail 200 zabeleženi | Error povratne putanje su pogrešne; detail semantika nije dokumentovana | Runtime edit matrix |
| PROF-FUN-06 | Tabelarni prikaz sa filterom | Obavezni | RUNTIME_NOT_VERIFIED | HIGH | filter UI `desnoNabavkeLista.php:15-25`; query `NabavkaModel.php:20-47` | Lista 200 postoji, filter nije posebno dokazan | — | Runtime kombinacije filtera |
| PROF-FUN-07 | Pojedinačni zapis celine sa svim delovima | Obavezni | PARTIAL | — | ruta `nabavkaDetalj`; `DajNabavkuPoID`; `DajStavkeNabavke`; `desnoNabavkaDetalj.php` | Detail HTTP 200 zabeležen | Sadržaj/sve stavke nisu nezavisno potvrđeni | Otvoriti nalog sa više stavki |
| PROF-PRINT-01 | Štampa spiska svih | Obavezni | PARTIAL | — | ruta `stampaNabavke`; `desnoStampaNabavke.php`; `@media print` | Ruta je vratila HTTP 200 | Print preview i sadržaj nisu potvrđeni | Print preview svih |
| PROF-PRINT-02 | Štampa filtriranog spiska | Obavezni | RUNTIME_NOT_VERIFIED | HIGH | filter URL se gradi u `desnoNabavkeLista.php:30-41`; ista query grana `Ruter.php:230-238` | Nema dokazano različitog filtriranog skupa | — | Filter pa print preview |
| PROF-PRINT-03 | Parametarska štampa pojedinačnog prijavljenog dokumenta | Obavezni | PARTIAL | — | `stampaJednogNaloga`; `desnoStampaONalogu.php:29-76` sadrži sva polja, stavke i rekapitulaciju | Parametarski POST vratio je HTTP 200 | Print preview/semantika nisu potvrđeni | Parametarski test postojećeg/nepostojećeg broja |
| PROF-VAL-03 | UI: sve popunjeno | Obavezni | PARTIAL | HIGH | Nabavka/katalog proveravaju required; Napomena je opravdano opciona; login nema client/server required proveru | Browser nije testiran | Login obavezna polja nisu validirana | Dodati client i server required proveru login polja |
| PROF-VAL-04 | UI: odgovarajući tip | Obavezni | VERIFIED | — | date/number HTML; PHP DateTime/int/float provere | Nevalidni POST nije izvršen | — | Runtime negativni testovi |
| PROF-VAL-05 | UI: dužina | Obavezni | PARTIAL | HIGH | Nabavka/katalog imaju maxlength + PHP strlen; login nema proveru prema VARCHAR(30) | — | Login length validacija nedostaje | Dodati maxlength i server proveru login polja |
| PROF-VAL-06 | UI: domen ispravnih vrednosti | Obavezni | VERIFIED | — | kategorija iz šifarnika + server provera; igra FK/provera; količina/cena >0 | — | — | Runtime manipulisan POST |
| PROF-VAL-07 | UI: jedinstvenost zapisa | Obavezni | PARTIAL | MEDIUM | server + DB UNIQUE za `BrojNaloga`; server/PK za `SifraIgre` | Nema client/AJAX provere | Strogo se može očekivati provera pre submit-a | Dodati minimalnu client/UI proveru uz zadržavanje server/DB zaštite |
| PROF-MVC-01 | MVC arhitektura | Opcioni 10 | PARTIAL | MEDIUM | `Ruter.php`→controllers→models→repositories→views postoji za nabavku | Delimični HTTP tok postoji | View poziva controller za stavke; katalog write akcije idu direktno na repo; profesor ne definiše stroge granice | Minimalno očistiti dva demonstraciona toka ili jasno odbraniti pragmatični MVC |
| PROF-REST-01 | REST servis sa ruterom | Opcioni 10 | RUNTIME_NOT_VERIFIED | MEDIUM | `api/router.php` akcije `igre`/`igra`; parametar `sifraIgre`; JSON header | HTTP pozivi nisu izvršeni | Nema HTTP status kodova; relativni include zavisi od CWD | Runtime JSON test i `__DIR__` putanje |
| PROF-DOC-01 | Dokumentacija: opis namene aplikacije | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet po naredbi | — | Van M17A code-audit opsega | Obraditi u M16 |
| PROF-DOC-02 | Dokumentacija: korisničko uputstvo sa ekranima | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-03 | Dokument, analiza, tipovi i domeni | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-04 | Kratak opis alata | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-05 | Dijagram slučajeva korišćenja | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-06 | Relacioni model celog dokumenta/rešenja | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-07 | Dijagram klasa sa atributima, metodama i vezama | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-08 | Kod: prijava korisnika | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-09 | Kod: CRUD glavne tabele | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-10 | Kod: štampa i parametarska štampa | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-11 | Kod: osnovne validacije | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-12 | Kod: stored procedure i pogledi | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-13 | Kod: nasleđivanje, asocijacija, kompozicija | Obavezni za finalnu predaju | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 |
| PROF-DOC-14 | Opciono dokumentovanje MVC-a | Opciono | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 ako se traže bodovi |
| PROF-DOC-15 | Opcionо dokumentovanje REST-a i rutera | Opciono | NOT_APPLICABLE | — | M16 nije započet | — | Van opsega | Obraditi u M16 ako se traže bodovi |
| PROF-SUB-01 | Kod i SQL su deo seminarskog rada | Obavezni za predaju | VERIFIED | — | PHP i dva SQL `.txt` fajla postoje | — | — | Nije potrebna |
| PROF-SUB-02 | Dokumentacija je deo seminarskog rada | Obavezni za predaju | NOT_APPLICABLE | — | M17A je eksplicitno pre M16 | — | Van opsega ovog audita | Izraditi u M16 |
| PROF-SUB-03 | Predaja na Google učionicu ≥1 dan pre pismene odbrane | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Planirati rok |
| PROF-SUB-04 | Predaja na GitHub ≥1 dan pre pismene odbrane | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Planirati rok |
| PROF-SUB-05 | Potrebne su pismena i usmena/praktična odbrana | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Planirati odbranu |
| PROF-SUB-06 | Neuspešna odbrana poništava rad; nova tema | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Predati tek odbranjiv rad |
| PROF-SUB-07 | Nekompletan rad se ne razmatra; nema dopune u istom roku | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Predati tek posle finalnog audita |
| PROF-SUB-08 | Na dan ispita se rad ne brani osim po opravdanom zahtevu | Procesni | NOT_APPLICABLE | — | Ne može se dokazati kodom | — | Procesni zahtev | Uskladiti termin sa nastavnikom |

# 3. Registered topic/document matrix

| Polje | UI unos | DB | Validacija | Detail | Edit | Print | Status | Evidence |
|---|---|---|---|---|---|---|---|---|
| Broj naloga | Da | `nabavka.BrojNaloga` | required, ≤50, server unique, DB UNIQUE | Da | Da, unique excluding current | Da | RUNTIME_NOT_VERIFIED | `NovaNabavka.php`; SQL 43/48; create/edit akcije |
| Datum nabavke | Da, date | DATE NOT NULL | HTML date + strict `Y-m-d` | Da | Da | Da | RUNTIME_NOT_VERIFIED | SQL 44; obe akcije |
| Dobavljač | Slobodan tekst | VARCHAR(100) NOT NULL | required + length | Da | Da | Da | VERIFIED | Nema zatvorene liste |
| Napomena | Opciono | VARCHAR(255) NULL | length ako je uneta | Da | Da | Da | VERIFIED | Usklađeno sa prijavom bez izmišljenog required pravila |
| Nalog evidentirao | readonly prikaz | VARCHAR(100) NOT NULL | server uzima sesiju | Da | Prikaz, ne menja se | Da | VERIFIED | `nabavkaSnimi.php:4,22,49-50`; nema POST poverenja |
| Stavka | Izvedeni redni broj | Ne persistira se | Lista min 1 | Da | Da | Da | VERIFIED | Brojač pri prikazu |
| Društvena igra | select iz kataloga | `SifraIgre` FK | required, format, postojanje | Da | Da | Da | VERIFIED | FK + `IgraPostojiUKatalogu` |
| Cena po komadu | number | DECIMAL(10,2) NOT NULL | >0 client/server | Da | Da | Da | VERIFIED | Bez proizvoljnog gornjeg limita |
| Količina | number | INT NOT NULL | integer >0 client/server | Da | Da | Da | VERIFIED | Bez proizvoljnog gornjeg limita |
| Ukupno | JS/readonly | Izvodi se | Količina×Cena | Da | Da | Da | VERIFIED | SQL alias `Ukupno` i računanje prikaza |
| Ukupan broj stavki | Izvodi se | Ne persistira se | Brojač redova | Da | Posredno | Da | VERIFIED | Detail/list/print brojači |
| Ukupna vrednost | Izvodi se | Ne persistira se | Suma detail iznosa | Da | Posredno | Da | VERIFIED | Detail/list/print suma |

Semantika teme i dokumenta: **VERIFIED statički** — UI i parametarska štampa koriste „Nalog za nabavku društvenih igara“ i registrovanu strukturu. Vizuelna 1:1 sličnost sa prijavom: **RUNTIME_NOT_VERIFIED**.

# 4. Serbian language audit

## 4.1 Database

Status: **VERIFIED** za jezik; **MEDIUM topic-consistency risk**.

- SAFE: tabele i poslovne kolone `kategorija_igre`, `drustvena_igra`, `nabavka`, `stavka_nabavke`, `korisnik`, `BrojNaloga`, `Dobavljac`, itd.
- AMBIGUOUS_DOMAIN_RISK: `Biblioteka_VP_2026` u oba SQL fajla i XML konfiguraciji jeste srpski naziv, ali predstavlja staru temu.
- AMBIGUOUS_LANGUAGE_RISK: `KORISNIK` i kolone velikim slovima nisu jezički problem, ali su nekonzistentni.
- SAFE: seed vrednosti igara i kategorija odgovaraju domenu; ime korisnika na ćirilici je srpski jezik.

DATABASE_LANGUAGE: **VERIFIED**

## 4.2 Program code

Status: **AMBIGUOUS_RISK**, severity **LOW**.

Legacy nalazi su aktivni i semantički mapiraju na igre:

- `KnjigaEntitet`, `DBKnjiga`, `DBKnjigaV`, `DBKnjigaSP`
- `KnjigeController`, `KnjigaModel`
- `DajKnjiguPoISBN`, `UcitajKnjiguPoISBN`
- `DBZanr`, `$ZanrObject`
- `SlikeKnjiga`
- `knjigaSnimi.php`, `knjigaSnimiSP.php`, `knjigaIzmeni.php`, `KnjigaObrisi.php`
- ruta `knjige`, promenljive `$optionsKnjige`, `$rezultatKnjige`

Klasifikacija: **SAFE_TECHNICAL_LEGACY funkcionalno**, uz **AMBIGUOUS_LANGUAGE_RISK** samo zato što profesor ne definiše opseg zahteva. `Knjiga` i `Zanr` jesu srpske reči, ali su semantički zastarele; `Controller`, `Model`, `filter` i `options` su engleski tehnički termini. Kod zaista radi nad `drustvena_igra`/`kategorija_igre`, a legacy imena nisu korisnički vidljiva. Globalni rename nije obavezna korekcija bez nastavnikovog razjašnjenja i ima visok regresioni rizik.

CODE_LANGUAGE: **AMBIGUOUS_RISK**

## 4.3 User interface

Status: **VERIFIED** za srpski jezik, uz **LOW presentation-quality risk**.

- Aktivni sadržaj nema vidljive termine knjiga/ISBN/žanr.
- Aktivni javni ekran `desnopocetna.php` i banner koriste ćirilicu; autentifikovani UI pretežno latinicu.
- Mnogi aktivni stringovi koriste ASCII transliteraciju: `Greska`, `sifra`, `moze`, `azuriranje`, `stampa`, `pocetna`, `Dobavljac`, `Drustvena`.
- To je srpski jezik, ali nije finalni preferirani standard pravilne srpske latinice.
- Mešanje pisama samo po sebi nije VIOLATED jer profesor ne propisuje pismo.
- Postoje konkretne pravopisne/skraćene formulacije poput `DRUSTVENIH IGAR`, koje su presentation problem.

UI_LANGUAGE: **VERIFIED**

## 4.4 Legacy terminology list

| Nalaz | Lokacija/uloga | Klasifikacija |
|---|---|---|
| knjig/ISBN/zanr | Aktivne klase, metode, rute, fajlovi | TECHNICAL_LEGACY / AMBIGUOUS_LANGUAGE_RISK |
| `Biblioteka_VP_2026` | Aktivni SQL + XML | AMBIGUOUS DOMAIN RISK / MEDIUM |
| Stari README sadržaj | Root `README.md` | ACTIVE PROBLEM / MUST_FIX pre predaje |
| autor/biblioteka email | `menilevofinal.php`, trenutno neuključen | DEAD/UNREFERENCED |
| book/genre/supplier/purchase/order/game | Nema aktivnih poslovnih engleskih termina; `Plan B Games` je naziv proizvođača | SAFE |
| error/delete/edit/save/print | Samo CSS/SQL/tehnički kontekst; nema aktivnih engleskih UI komandi | SAFE |

## 4.5 Language verdict

- DATABASE_LANGUAGE: **VERIFIED**
- CODE_LANGUAGE: **AMBIGUOUS_RISK**
- UI_LANGUAGE: **VERIFIED**

# 5. Database audit

Mapiranje je pravilno:

- celina: `nabavka`
- deo: `stavka_nabavke`
- šifarnik: `drustvena_igra`
- pomoćni šifarnik: `kategorija_igre`
- nezavisna tabela: `KORISNIK`

PK: svih pet tabela imaju PK.  
FK: igra→kategorija RESTRICT/CASCADE; stavka→nabavka CASCADE/CASCADE; stavka→igra RESTRICT/CASCADE.  
UNIQUE: `BrojNaloga` ima `UQ_NABAVKA_BROJNALOGA`; `SifraIgre` je PK.  
NOT NULL: obavezna polja dokumenta i stavke imaju NOT NULL; Napomena/slika/cena kataloga su nullable.  
Orphan zaštita: dizajn sprečava orphan stavke, brisanje naloga kaskadno briše detail, brisanje korišćene igre je RESTRICT.

Profesorovi kriterijumi su statički pokriveni. Sledeće nisu eksplicitni zahtevi, već odvojene engineering preporuke:

- Nema DB CHECK za `Kolicina > 0` i `Cena > 0`; aplikacija to proverava.
- `KORISNICKOIME` nema UNIQUE; profesor to ne navodi eksplicitno.
- `UkupanBrojIgara` je redundantno stanje; izmena kategorije igre ne koriguje staru/novu kategoriju. **MEDIUM**.
- Naziv baze je stari domen, iako je na srpskom. **MEDIUM**.

# 6. Stored procedure audit

Status: **PARTIAL**.

- SQL naziv: `DodajDrustvenuIgru`.
- Parametri: 5, redom `SifraIgreParametar`, `NazivParametar`, `ProizvodjacParametar`, `OznakaKategorijeParametar`, `NazivFajlaSlikeParametar`.
- Upisuje u `drustvena_igra`, zatim uvećava `kategorija_igre.UkupanBrojIgara`.
- PHP redosled i broj parametara se podudaraju u `DBKnjigaSP.php:14-20`.
- Aktivna demonstraciona ruta: `Ruter.php?stranica=unosSP` → `desnounosSP.php` → `knjigaSnimiSP.php` → `DBKnjigaSP`.
- Nema stare SQL book reference u definiciji.

Rizik: SP nema eksplicitnu transakciju između INSERT i UPDATE. Profesor ne zahteva da baš SP bude transakciona, ali drugi statement može ostati neizvršen nakon uspešnog INSERT-a. **MEDIUM quality/data-integrity risk**.

# 7. VIEW audit

Status: **RUNTIME_NOT_VERIFIED**.

- SQL definiše `svipodacioidrutvenimigrama` i `svipodacioidrutvenimigramasaslikom`.
- Kolone odgovaraju domenu: `SifraIgre`, `Naziv`, `Proizvodjac`, `NazivKategorije`, opcionalno slika.
- `DBKnjigaV::DajSvePodatkeOKnjigama`, katalog i REST čitaju `svipodacioidrutvenimigramasaslikom`.
- `svipodacioidrutvenimigrama` nema pronađenog PHP/API potrošača.
- Aktivna javna i prijavljena lista kataloga koriste taj repository preko `KnjigeController`.
- Filter pokriva šifru, naziv i proizvođača.

Rizici: jedan od dva definisana VIEW-a je nekorišćen; aktivni filter se konkatenira bez escape/prepared zaštite u `DBKnjigaV.php:8-11`. **MEDIUM**.

# 8. OOP audit

## 8.1 Inheritance

Status: **VERIFIED**.

| FILE | CLASS | EXTENDS | RELEVANT METHOD / aktivna upotreba |
|---|---|---|---|
| `repozitorijumi/DBNabavka.php` | DBNabavka | Tabela | Dodaj/Izmeni/ObrisiNabavku |
| `repozitorijumi/DBStavkaNabavke.php` | DBStavkaNabavke | Tabela | Dodaj/Izmeni/ObrisiStavku |
| `repozitorijumi/DBKnjiga.php` | DBKnjiga | Tabela | katalog CRUD |
| `repozitorijumi/DBKnjigaV.php` | DBKnjigaV | Tabela | čitanje VIEW-a |
| `repozitorijumi/DBKnjigaSP.php` | DBKnjigaSP | Tabela | CALL procedure |
| `repozitorijumi/DBZanr.php` | DBZanr | Tabela | kategorije |
| `repozitorijumi/DBKorisnik.php` | DBKorisnik | Tabela | login |

## 8.2 Composition

Status: **VERIFIED**.

`NabavkaEntitet::$ListaStavki` je inicijalizovan kao niz, puni se metodom `DodajStavku`, koristi se u create toku i može računati ukupnu vrednost metodom `DajUkupnuVrednost`.

## 8.3 Association

Status: **VERIFIED**.

`StavkaNabavkeEntitet::$DrustvenaIgra` je objekat `KnjigaEntitet`, ne samo string FK. Konstruktor i `IzRedaBaze` stvarno kreiraju/primaju objekat, a create model koristi `$stavka->DrustvenaIgra->SifraIgre`.

## 8.4 Encapsulation/other OOP evidence

Status: **PARTIAL**.

- Kontroleri i `NabavkaModel` koriste private konekcione atribute.
- Domenski entiteti i većina repository atributa su public; enkapsulacija je slaba.
- Konstruktori, objekti i kolekcije postoje i aktivni su.
- Override/polimorfizam nije dokazan, ali nije eksplicitni projektni zahtev u osnovnoj matrici; pojavljuje se kao primer ispitnog pitanja.
- OOP nije dekorativan: create nabavke koristi objektni graf i repository nasleđivanje.

# 9. Master-detail create + transaction audit

Status: **RUNTIME_NOT_VERIFIED**.

Statički tok:

`Ruter.php?stranica=novaNabavka` → jedna forma `NovaNabavka.php` → dinamički detail redovi → `nabavkaSnimi.php` → `NabavkaEntitet` + lista `StavkaNabavkeEntitet` → `NabavkeController::SnimiNovuNabavku` → `NabavkaModel::SnimiNovuNabavku` → transakcija → INSERT master → `LAST_INSERT_ID()` → INSERT svih stavki → COMMIT/ROLLBACK.

- Minimum jedna stavka: client i server.
- Nema merge-a po Datum+Dobavljač.
- Duplikat iste igre nije zabranjen.
- Partial save je statički sprečen transakcijom.
- Rollback grana postoji, ali nije runtime izazvana.

# 10. Master-detail edit audit

Status: **PARTIAL**.

- Postojeći master i detalji se učitavaju.
- Master podaci se menjaju.
- Postojeća stavka se menja po ID-u i proverava pripadnost nalogu.
- Nova stavka se dodaje bez ID-a.
- Izostavljena postojeća stavka se briše.
- Minimum jedna stavka se proverava.
- Sve je u jednoj transakciji.
- `PostojiBrojNalogaOsim` izuzima trenutni zapis.
- Postoje delimični runtime dokazi: edit forma HTTP 200, edit POST 302 i povratni detail HTTP 200. Oni ne potvrđuju svaku semantičku izmenu niti rollback.

Rizik: error/back URL u `nabavkaIzmeni.php` koristi `../Ruter.php`, što iz direktne akcije vodi na nepostojeći `kontroler/Ruter.php`. **HIGH integracioni problem**.

# 11. Main procurement CRUD audit

| Operacija | Ruta | Controller | Model/repository/action | View | Status |
|---|---|---|---|---|---|
| CREATE | `novaNabavka` + action | `NabavkeController::SnimiNovuNabavku` | `NabavkaModel` + DBNabavka/DBStavka | `NovaNabavka.php` | RUNTIME_NOT_VERIFIED |
| READ LIST | `nabavke` | `DajSveNabavke` | `NabavkaModel::DajSveNabavke` | `NabavkeLista.php` | PARTIAL |
| FILTER | `nabavke?filtriraj=1` | `DajNabavkePoFilteru` | model query | `desnoNabavkeLista.php` | RUNTIME_NOT_VERIFIED |
| READ ONE | `nabavkaDetalj&id=` | master + stavke | model queries | `NabavkaDetalj.php` | PARTIAL |
| UPDATE | `nabavkaIzmeniForm` + action | `IzmeniNabavku` | transakcioni model/repo | `NabavkaIzmeniForm.php` | PARTIAL |
| DELETE | `obrisiNabavku` | `ObrisiNabavku` | repo DELETE + DB CASCADE | confirm u listi | PARTIAL |

Glavni CRUD je stvarno nabavka, ne katalog. Statički zahtev je implementiran; delimični runtime dokaz postoji, ali potpun runtime dokaz nedostaje.

# 12. List/filter/detail audit

- Lista: prikazuje sva master polja, detail stavke i rekapitulaciju.
- Filter UI: Broj naloga, Datum nabavke, Dobavljač.
- Filter query koristi sva tri kriterijuma i AND kombinaciju.
- Detail: master + sve stavke + rekapitulacija.
- `Napomena` i `NalogEvidentirao` su prisutni.
- Nema GROUP BY gubitka duplih igara.

Status: **PARTIAL** — lista i detail rute imaju HTTP 200 dokaz; filter kombinacije i semantika svih prikazanih stavki nisu runtime potvrđeni.

# 13. Print audit

## 13.1 All

Status: **PARTIAL**. Ruta bez filter parametara poziva `DajSveNabavke`; za svaki nalog prikazuje master, sve stavke i rekapitulaciju. Ruta ima HTTP 200 dokaz. Print preview i sadržaj nisu vizuelno potvrđeni. Print CSS skriva samo `.no-print`.

## 13.2 Filtered

Status: **RUNTIME_NOT_VERIFIED**. Lista prenosi identične filter parametre; router ulazi u istu filter metodu. Statički ne postoji fallback na sve kada je `filtriraj=1`.

## 13.3 Parametric registered document

Status: **PARTIAL**. Parametarski POST ima HTTP 200 dokaz. Statički prikazuje tačno jedan nalog po jedinstvenom `BrojNaloga`, sva četiri master polja, svih pet kolona detaila, obe rekapitulacije i `Nalog evidentirao`. Nema book/ISBN termina. Matematički koristi SQL `Kolicina * Cena` i sumu redova. Print preview i semantika konkretnog dokumenta nisu potvrđeni.

# 14. Validation matrix

| POLJE | REQUIRED CLIENT | REQUIRED SERVER | TYPE CLIENT | TYPE SERVER | LENGTH CLIENT | LENGTH SERVER | DOMAIN CLIENT | DOMAIN SERVER | UNIQUE CLIENT | UNIQUE SERVER | DB CONSTRAINT | STATUS | RISK |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| BrojNaloga | Da | Da | text | string | 50 | 50 | bez izmišljenog regex-a | non-empty | Ne | Da, edit excludes current | UNIQUE, NOT NULL | PARTIAL | UI uniqueness ambiguity |
| DatumNabavke | Da | Da | date | strict DateTime Y-m-d | browser | format | browser date | valid calendar date | N/A | N/A | DATE NOT NULL | VERIFIED | runtime negative test |
| Dobavljac | Da | Da | text | string | 100 | 100 | slobodan tekst | slobodan tekst | N/A | N/A | VARCHAR(100) NOT NULL | VERIFIED | — |
| Napomena | Ne | Ne | text | string | 255 | 255 | opciono | opciono | N/A | N/A | VARCHAR(255) NULL | VERIFIED | „sve popunjeno“ ambiguity |
| SifraIgre (stavka) | Da | Da | select | string | opcije | ≤13 | katalog | format + postoji | N/A | N/A | FK, NOT NULL | VERIFIED | — |
| Kolicina | Da | Da | number step=1 | integer | tip | tip | >0 | >0 | N/A | N/A | INT NOT NULL | VERIFIED | nema DB CHECK |
| Cena | Da | Da | number step=.01 | float | tip | tip | >0 | >0 | N/A | N/A | DECIMAL NOT NULL | VERIFIED | nema DB CHECK |
| Naziv igre | Da | Da | text | string | 100 | 100 | non-empty | non-empty | N/A | N/A | NOT NULL | VERIFIED | — |
| Proizvodjac | Da | Da | text | string | 100 | 100 | non-empty | non-empty | N/A | N/A | NOT NULL | VERIFIED | — |
| Kategorija igre | Da | Da | select | string | CHAR(2) kroz opcije | FK ključ | lista kategorija | DB postojanje | N/A | N/A | FK, NOT NULL | VERIFIED | — |
| Slika | Ne | Ne | accept + JS ext | extension | nema max | filename ≤100 nije eksplicitno provereno | jpg/jpeg/png | extension only | N/A | N/A | VARCHAR(100) NULL | PARTIAL | path/MIME/dužina |
| SifraIgre (katalog) | Da | Da | regex | regex | 13 | 13 | alfanumerički | alfanumerički | Ne | Da | PK | PARTIAL | UI uniqueness ambiguity |
| Korisničko ime | Ne | Ne | text | direktan POST string | Nema | Nema | Nema | Nema | N/A | N/A | VARCHAR(30) NOT NULL | MISSING | obavezna login validacija |
| Lozinka/šifra | Ne | Ne | password | direktan POST string | Nema | Nema | Nema | Nema | N/A | N/A | VARCHAR(30) NOT NULL | MISSING | obavezna login validacija |

Poslovna regresija: Dobavljač ostaje slobodan tekst; Količina/Cena samo >0; nema gornjih limita; duplikat igre je dozvoljen; BrojNaloga je jedinstven; evidentirao dolazi iz sesije.

# 15. Login/session audit

Status: **PARTIAL**.

- Forma POST-uje korisničko ime/šifru.
- Server proverava `KORISNIK` i postavlja sesiju.
- Zaštićene rute pozivaju `proveriSesiju`.
- Logout prazni sesiju, briše cookie i uništava sesiju.
- `NalogEvidentirao` se formira server-side iz `$_SESSION["korisnik"]`.

Rizici:

- Login forma nema `required`/`maxlength`; server direktno čita `$_POST` bez presence, empty i length provere, iako SQL kolone imaju 30 karaktera. **HIGH**.
- Login SQL direktno konkatenira input bez escape/prepared statementa: **MEDIUM QUALITY_RISK**.
- Lozinke su plaintext u SQL-u: **LOW QUALITY_RISK**, nije eksplicitni bodovni kriterijum.
- Direktne akcije bez sesije koriste `Location:../index.php`, pogrešan nivo iz `kontroler/akcije`: **HIGH**.

# 16. Catalogue/lookup audit

Status: **RUNTIME_NOT_VERIFIED**.

Statički postoje lista/filter, normalni unos, SP unos, izmena, brisanje, FK zabrana brisanja korišćene igre, izbor kategorije i PK jedinstvenost. Katalog se koristi kao select šifarnik u stavkama nabavke.

Konkretni problemi:

- Upload `$location = '../SlikeKnjiga/'` iz `kontroler/akcije` cilja `kontroler/SlikeKnjiga`, dok je stvarni folder u root-u. Povratna vrednost `move_uploaded_file` se ignoriše, pa DB može sačuvati nepostojeću sliku. **MEDIUM aktivni katalog defekt**; upload nije obavezni seminarski kriterijum.
- Promena kategorije igre ne ažurira `UkupanBrojIgara` stare i nove kategorije. **MEDIUM**.
- Normalni/SP create error linkovi koriste `../Ruter.php`, pogrešan relativni nivo. **HIGH**.
- Upload proverava samo ekstenziju, ne MIME/sadržaj i koliziju imena. **LOW QUALITY_RISK**.
- `KnjigaObrisi.php` direktno čita `$_POST['sifraIgre']` bez `isset`/format/existence provere pre čitanja kategorije i korekcije brojila. **LOW robustness risk**.
- Katalog CRUD ne unosi/menja nullable kolonu `drustvena_igra.Cena`, iako forma nabavke koristi `data-cena` za auto-popunu; za novo dodate igre cena ostaje ručni unos u nalogu. **LOW active consistency risk**, ne registrovani obavezni podatak kataloga.

# 17. Multipage audit

Status: **VERIFIED**.

Postoje realni odvojeni pogledi, centralni router, više URL ruta, GET filteri/ID-evi, POST forme za create/edit/delete/login i samo lokalna JS dinamika detail redova. Rešenje nije SPA.

# 18. MVC audit

Status: **PARTIAL**. Opcionih 10 bodova nosi MEDIUM rizik jer profesor ne definiše stroge granice MVC-a, ali folderi sami nisu dovoljan dokaz.

READ detail trace:

`Ruter.php:nabavkaDetalj` → `NabavkeController::DajNabavkuPoID/DajStavkeNabavke` → `NabavkaModel` → SQL → `pogledi/NabavkaDetalj.php`.

WRITE create trace:

`NovaNabavka.php` → `nabavkaSnimi.php` → `NabavkeController::SnimiNovuNabavku` → `NabavkaModel::SnimiNovuNabavku` → repositories.

Odstupanja:

- `desnoNabavkeLista.php` i `desnoStampaNabavke.php` pozivaju controller iz view-a radi detail query-ja.
- Katalog write akcije direktno koriste DB/repository, bez model/controller toka.
- `kontroler/akcije` sadrži validaciju i orkestraciju koja funkcioniše kao proceduralni controller, ali nije dosledno uklopljena.

# 19. REST audit

Status: **RUNTIME_NOT_VERIFIED**.

- `api/router.php?akcija=igre`
- `api/router.php?akcija=igra&sifraIgre=...`
- JSON Content-Type je postavljen.
- Lista i pojedinačni resurs koriste domain VIEW.
- Nedostajući parametar i nepostojeća igra vraćaju validan JSON.
- Stare `knjige/knjiga` akcije nisu u routeru; stari endpoint fajlovi su uklonjeni.

Rizici: relativni `require`/config path umesto `__DIR__`; runtime validnost JSON-a nije proverena. HTTP 400/404/500 statusi su **LOW opciona REST preporuka**, ne profesorov eksplicitni kriterijum. Opcionih 10 bodova je statički odbranjivo tek posle dva uspešna HTTP testa.

# 20. Portability audit

Status: **PARTIAL**.

Pozitivno:

- Nema `C:\Users\`, `D:\xaamp\`, `D:\xampp\`, `C:\xampp\`, `file://` niti ličnih localhost URL putanja u stvarnim projektnim fajlovima.
- Aktivne URL reference koriste `Ruter.php` sa tačnim case-om.
- Većina backend include/config putanja koristi `__DIR__`.
- CSS i image URL-ovi su relativni.

Problemi:

- Upload putanja je relativna sa pogrešnim nivoom.
- Aktivni UI referencira nepostojeće `images/sredinagore.jpg`, `images/sredinadole.jpg` i `images/blue-background3.jpg`; runtime server log beleži ponovljene 404 odgovore.
- API i login imaju CWD-zavisne relativne include/config putanje.
- Folder `SlikeKnjiga` mora postojati i biti writable; kod ga ne kreira niti prijavljuje grešku.
- DB credentials (`root`, prazna šifra) očekuju standardni lokalni setup; promena XML-a je dozvoljena konfiguracija, ne lična hardkodovana putanja.
- Naziv baze je konzistentan SQL↔XML, ali pogrešnog domena.

# 21. Clean database setup audit

Status: **RUNTIME_NOT_VERIFIED**.

Statički redosled je dovoljan za clean install:

1. `BazaPodataka.txt` kreira bazu, bira je, kreira tabele u FK-bezbednom redosledu i seed login/kategorije/igre.
2. `Pogledi i Stored procedure.txt` bira istu bazu, kreira VIEW-e i SP uz DELIMITER.
3. XML koristi isti naziv baze i UTF-8 je postavljen.

Rizici:

- Clean import nije izvršen.
- Table skripta nije idempotentna za postojeću bazu (tabele nemaju `IF NOT EXISTS`/DROP); za čist install to nije problem.
- Ne postoji jedna objedinjena skripta/redosled instrukcija; README trenutno daje pogrešan domen, ne setup.
- Seed login postoji (`lkazi`/`lk`), ali plaintext je quality risk.

# 22. Static hygiene / legacy audit

| Nalaz | Klasifikacija | Obrazloženje |
|---|---|---|
| Aktivni Knjiga/ISBN/Zanr nazivi | TECHNICAL_LEGACY + AMBIGUOUS_RISK | Semantički rade nad igrama, nisu UI termini |
| `README.md` biblioteka/knjige/stara štampa/grupisanje i zastareli PRINT statusi u `REQUIREMENTS.md` | ACTIVE_PROBLEM | Profesor dobija kontradiktoran opis/status projekta; planning status nije dokaz, ali je defense risk |
| Aktivni `sredinagore.jpg`, `sredinadole.jpg`, `blue-background3.jpg` | ACTIVE_PROBLEM | Runtime log potvrđuje 404 na više ekrana |
| `menilevofinal.php` biblioteka email/stari tekst | DEAD_UNREFERENCED | Include je komentarisana u `index.php` |
| `css/style.css` uz aktivni `css/stil.php` | DEAD/UNREFERENCED | Stari statički CSS može zbuniti, ali ne utiče na tok |
| TODO/FIXME/XXX/var_dump/print_r/debug/dummy | SAFE | Nema relevantnih nalaza |
| `die`/`exit` | SAFE uz izuzetke putanja | Validacija i redirect; nisu debug ostaci |
| Dupli `session_start()` u router-included delete akcijama | ACTIVE LOW RUNTIME PROBLEM | Runtime Notice potvrđen za `nabavkaObrisi.php:2` |
| `placeholder` | SAFE | HTML pomoćni tekst |
| `ruter.php` lowercase | SAFE | Nema nalaza u stvarnim aktivnim fajlovima |

# 23. PHP lint results

Komanda je koristila isključivo `D:\xaamp\php\php.exe -l` bez upisivanja putanje u projekat.

- Lintovano: **69 PHP fajlova**
- Syntax error: **0**
- Status: **VERIFIED**

# 24. Ambiguous professor-requirement risks

## A. „Baza podataka, programski kod i korisnički interfejs na srpskom jeziku“

- ORIGINALNI PROFESOROV TEKST: PDF linije 85-87.
- NAŠE REŠENJE: DB/UI poslovni termini su srpski; kod koristi srpske legacy termine i engleske tehničke termine.
- NAJSTROŽE RAZUMNO TUMAČENJE: nastavnik može zahtevati srpske domenske identifikatore, ali dokument ne definiše konvenciju za tehničke identifikatore.
- RIZIK: LOW/AMBIGUOUS_RISK.
- NAJBEZBEDNIJA MINIMALNA KOREKCIJA: ne raditi rizičan rename bez razjašnjenja; spremiti jasno semantičko mapiranje za odbranu.

## B. „Jedinstvenost zapisa“ na korisničkom interfejsu

- ORIGINALNI TEKST: PDF 105-107.
- NAŠE REŠENJE: server i DB pouzdano odbijaju duplikat; client pre submit-a ne proverava.
- NAJSTROŽE TUMAČENJE: UI/client mora prijaviti zauzet ključ pre glavnog submit-a.
- RIZIK: MEDIUM.
- MINIMALNA KOREKCIJA: jedan mogući izbor je mali JSON endpoint i blur/submit provera; prihvatljiv može biti i drugi jasan client/UI obrazac uz obavezno zadržavanje server/DB zaštite.

## C. Koliko strogo MVC mora biti razdvojen

- ORIGINALNI TEKST: „Organizacija rešenja primenom arhitekture MVC“, bez precizne definicije slojeva.
- NAŠE REŠENJE: folderi i glavni nabavka tok imaju MVC lanac; view radi N+1 controller pozive, katalog akcije direktno koriste repo.
- NAJSTROŽE TUMAČENJE: view ne pristupa controller/data sloju i svi write tokovi idu kroz controller/model.
- RIZIK: MEDIUM za opcionih 10.
- MINIMALNA KOREKCIJA: pripremiti podatke pre include view-a i uskladiti jedan katalog write tok.

## D. Da li SP/VIEW moraju biti nad glavnom tabelom

- ORIGINALNI TEKST zahteva SP i poglede, bez vezivanja za glavnu tabelu.
- NAŠE REŠENJE koristi oba nad aktivnim katalogom koji je šifarnik glavnog dokumenta.
- NAJSTROŽE RAZUMNO TUMAČENJE: moraju biti stvarno korišćeni u aplikaciji; to je ispunjeno statički.
- RIZIK: LOW/AMBIGUOUS_RISK.
- MINIMALNA KOREKCIJA: nije potrebna bez eksplicitnog zahteva; demonstrirati aktivnu upotrebu.

# 25. Defense-readiness evidence map

| Professor may ask | File/class/SQL to show | What proves requirement | Risk |
|---|---|---|---|
| DB schema | `bazapodataka/BazaPodataka.txt` | 5 tabela, PK/FK/UNIQUE/CASCADE/seed | Naziv baze biblioteka |
| Transakcija | `NabavkaModel::SnimiNovuNabavku` + `BaznaTransakcija.php` | START, master, details, COMMIT/ROLLBACK | Rollback nije runtime demonstriran |
| Inheritance | `DBNabavka extends Tabela`, ostali DB* | stvarni extends + aktivne metode | Legacy imena |
| Composition | `NabavkaEntitet::$ListaStavki` | lista detail objekata | Niz nije tipiziran |
| Association | `StavkaNabavkeEntitet::$DrustvenaIgra` | objekat šifarnika u delu | Klasa se zove KnjigaEntitet |
| SP | SQL + `DBKnjigaSP.php` + `unosSP` | definicija, CALL, aktivna forma | Runtime nije dokazano |
| VIEW | SQL + `DBKnjigaV.php` + lista | VIEW se koristi u listi/filteru | Unescaped filter |
| Client validation | `NovaNabavka.php`, edit/katalog/login forme | required/type/length/domain | Login required/length nedostaje; UI uniqueness ambiguity |
| Server validation | `nabavkaSnimi.php`, `nabavkaIzmeni.php`, `prijavaprovera.php` | strict datum, FK, >0, unique; login gap | Login validation i povratni linkovi |
| CRUD nabavke | `Ruter.php`, controller/model/repo/views | svih 6 operacija glavnog dokumenta | Runtime |
| Filter/detail | `NabavkaModel`, list/detail views | filter query + sve stavke | Runtime |
| Tri štampe | route 218-264 + print delovi | all/filtered/one document | Print preview |
| MVC | dva trace-a iz sekcije 18 | realni slojevi | PARTIAL |
| REST | `api/router.php`, `igre.php`, `igra.php` | ruter + 2 JSON resursa | Runtime |
| UI assets | aktivni views + `images/` | vizuelna celovitost | Tri potvrđena 404 asseta |

# 26. Problems ordered by severity

## BLOCKER

Nema statički dokazanog BLOCKER-a: SQL struktura, obavezni tokovi i PHP sintaksa postoje. To nije tvrdnja da runtime radi.

## HIGH

### H-01 Nedostaje obavezna login validacija
- Professor criterion: client-side i server-side osnovne validacije.
- Evidence: `desnoprijava.php:62,79` nema `required`/`maxlength`; `prijavaprovera.php:3-4` direktno čita POST; SQL kolone su VARCHAR(30).
- Why: jedan obavezni korisnički tok ne zadovoljava required/length matricu.
- Minimal correction: client required/maxlength i server presence/empty/length provera pre DB upita.
- Regression test: missing POST, prazno, 31+ karakter, validan i pogrešan login.

### H-02 Pogrešni relativni direct-action error/session URL-ovi
- Criterion: validacije, sesija, E2E.
- Evidence: direktno pozvani `nabavkaSnimi.php:11`, `nabavkaIzmeni.php:27,30,73`, `knjigaSnimi.php:17-80`, `knjigaSnimiSP.php:17-82` i unauth direct-action redirecti.
- Why: validaciona greška vodi na nepostojeći `/kontroler/Ruter.php`; direktan pristup bez sesije na pogrešan index.
- Minimal correction: koristiti `../../Ruter.php?...`.
- Regression test: izazvati greške u direktnim create/edit akcijama i kliknuti POVRATAK; direktno otvoriti akciju bez sesije. Router-included delete akcije proveriti zasebno.

### H-03 Nepotpuna runtime verifikacija
- Criterion: svi funkcionalni zahtevi.
- Evidence: postoje HTTP dokazi za edit/detail/list/all-print/parametric-print/delete, ali ne za clean install, create, rollback, filter semantiku, REST i kompletnu validation matricu.
- Why: 200/302 status ne dokazuje da su podaci i sadržaj semantički ispravni.
- Minimal correction: izvršiti sekciju 27.
- Regression test: ista checklist-a mora proći na clean instalaciji.

## MEDIUM

### M-01 Upload putanja ne cilja stvarni folder
- Criterion: aktivna opciona katalog funkcija i prenosivost; upload nije obavezni seminarski kriterijum.
- Evidence: `$location='../SlikeKnjiga/'` u tri direktne akcije; stvarni folder je root `SlikeKnjiga`; rezultat move-a se ignoriše.
- Minimal correction: `__DIR__ . '/../../SlikeKnjiga/'` i provera rezultata.
- Test: normalni unos, SP unos i izmena sa slikom.

### M-02 Naziv baze je stari domen
- Criterion: tematska konzistentnost, ne jezik baze.
- Evidence: `Biblioteka_VP_2026` u oba SQL fajla i XML-u.
- Minimal correction: uskladiti domenski naziv i izvršiti clean import.
- Test: login, katalog, nabavka, SP/VIEW, REST na novoj bazi.

### M-03 README i traceability dokumenti su kontradiktorni
- Criterion: predaja i defense readiness.
- Evidence: `README.md:1-17` opisuje biblioteku; `REQUIREMENTS.md` i dalje označava PRINT-01–09 kao MISSING i više SP/VIEW/validation stavki kao NEEDS_ADAPTATION iako kod postoji.
- Minimal correction: uskladiti README i traceability statuse u dozvoljenom documentation koraku; kod ostaje izvor istine.
- Test: opis/statusi odgovaraju stvarnim rutama i dokazima.

### M-04 Aktivni UI asseti vraćaju 404
- Criterion: funkcionalan UI i portability.
- Evidence: nedostaju `sredinagore.jpg`, `sredinadole.jpg`, `blue-background3.jpg`; runtime log potvrđuje ponovljene 404.
- Minimal correction: dodati tačne assete ili ukloniti/popraviti reference.
- Test: browser Network nema 404 na svim aktivnim ekranima.

### M-05 Nema eksplicitne client/UI uniqueness provere
- Criterion: jedinstvenost na korisničkom interfejsu.
- Evidence: samo server + DB.
- Minimal correction: jedan mogući izbor je async/blur; prihvatljivost potvrditi sa nastavnikom.
- Test: duplikat create/edit pre submit-a i manipulisan POST.

### M-06 SQL input konkatenacija
- Criterion: robustnost funkcija/odbrane.
- Evidence: login `DBKorisnik.php:28` i VIEW filter `DBKnjigaV.php:8-11`; opcije igre u `Ruter.php:162-164` izlaze bez `htmlspecialchars`.
- Minimal correction: prepared statement ili najmanje escape za SQL; context-aware HTML escaping za option vrednosti/tekst.
- Test: navodnici/specijalni znaci, HTML payload i normalan login/filter/nova nabavka.

### M-07 Brojač kategorije postaje netačan pri izmeni
- Criterion: katalog/data integrity.
- Evidence: `knjigaIzmeni.php` menja kategoriju bez korekcije brojila.
- Minimal correction: transakciono decrement/increment kada se kategorija promeni.
- Test: promeniti kategoriju i proveriti oba brojila.

### M-08 CWD-zavisne REST/login putanje
- Criterion: prenosivost.
- Evidence: API `require "../..."`; login konstruktor koristi relativni string.
- Minimal correction: `__DIR__`.
- Test: direktno pozvati endpoint/action na drugom host setup-u.

### M-09 MVC je delimičan
- Criterion: opcionih 10 MVC.
- Evidence: controller pozivi u view-u i direktan repo u katalog akcijama.
- Minimal correction: pripremiti podatke pre view-a i uskladiti demonstracioni write tok ili jasno odbraniti pragmatični MVC.
- Test: read detail i write create trace.

### M-10 Jedan definisani VIEW nije korišćen
- Criterion: profesor zahteva poglede i njihovu stvarnu primenu.
- Evidence: `svipodacioidrutvenimigrama` postoji u SQL-u, ali PHP/API koristi samo `svipodacioidrutvenimigramasaslikom`.
- Minimal correction: povezati prvi VIEW sa opravdanim aktivnim tokom ili ukloniti suvišan VIEW i jasno demonstrirati korišćeni pogled.
- Test: trace SQL VIEW → repository/API → aktivni ekran.

### M-11 SP ne garantuje atomarnost svoja dva upita
- Criterion: data integrity/odbranjivost SP funkcije; nije eksplicitni zahtev da SP bude transakciona.
- Evidence: `DodajDrustvenuIgru` izvršava INSERT pa UPDATE unutar `BEGIN...END`, ali bez `START TRANSACTION`/handler rollback-a.
- Minimal correction: transakcija/handler u proceduri ili spoljašnja PHP transakcija koja obuhvata CALL.
- Test: namerno izazvati neuspeh UPDATE dela i proveriti da INSERT nije ostao.

## LOW

### L-01 Aktivni legacy identifikatori
- AMBIGUOUS_RISK/semantic debt, ne dokazana jezička povreda.
- Minimal correction: ne rename-ovati bez razjašnjenja; dokumentovati mapiranje.

### L-02 UI pismo/dijakritici i pravopis
- Presentation-quality risk (`Greska`, `sifra`, `IGAR`); UI je i dalje na srpskom.
- Minimal correction: ciljano ujednačiti aktivne stringove.

### L-03 REST nema HTTP status semantiku
- Opcionа quality preporuka, ne profesorov eksplicitni kriterijum.
- Minimal correction: 400/404/500 gde odgovara.

### L-04 Dupli session_start proizvodi runtime Notice
- Evidence: `nabavkaObrisi.php:2` uključen posle router session start-a; Notice je runtime potvrđen.
- Minimal correction: `session_status()` guard.

### L-05 Plaintext lozinke
- QUALITY_RISK; nije eksplicitni bodovni kriterijum.
- Minimal correction: password_hash/password_verify uz seed migraciju.

### L-06 Upload proverava samo ekstenziju i ne rešava kolizije
- QUALITY_RISK.
- Minimal correction: MIME + generisano jedinstveno ime.

### L-07 Dupliran katalog query na index putanji
- Evidence: `Ruter.php:index` učitava controller, zatim `index.php` ponavlja isto.
- Minimal correction: jedan vlasnik učitavanja.

### L-08 Mrtvi template ostaci
- Evidence: `menilevofinal.php`, `css/style.css`.
- Minimal correction: dokazati nereferenciranost pa ukloniti ili jasno arhivirati.

### L-09 Brisanje igre nema potpunu POST/existence validaciju
- Evidence: `KnjigaObrisi.php` čita `$_POST['sifraIgre']` bez `isset` i pre korekcije brojila ne potvrđuje postojanje igre.
- Minimal correction: required/format/existence provera pre transakcije.

### L-10 Katalog Cena nije deo CRUD-a, a koristi se za auto-popunu naloga
- Evidence: `drustvena_igra.Cena` je nullable; katalog create/edit je ne postavlja; `Ruter.php:novaNabavka` je šalje kao `data-cena`.
- Minimal correction: ili uključiti cenu u katalog CRUD kao pomoćni podatak ili ukloniti očekivanje auto-popune i ostaviti eksplicitno ručni unos.

# 27. Manual runtime verification checklist

Samo testovi koji se ne mogu zaključiti statički:

1. **Clean DB setup**
   - Koraci: na praznom MySQL-u importovati `BazaPodataka.txt`, zatim SP/VIEW fajl; podesiti XML samo ako credentials odstupaju.
   - Očekivano: 5 tabela, 2 VIEW-a, 1 SP, seed login.
   - Dokazuje: DB, clean install, portability.

2. **Login/session/logout**
   - Koraci: poslati missing/prazna/preduga login polja, zatim login seed nalogom; direktno otvoriti zaštićene rute u incognito; logout pa Back/refresh.
   - Očekivano: nevalidni input je kontrolisano odbijen bez notice-a; validan login radi; sesija je zaštićena i uništena na logout-u.
   - Dokazuje: PROF-FUN-01, client/server validacije i NalogEvidentirao trust.

3. **Katalog + VIEW + SP + upload**
   - Koraci: filter; normalni unos sa slikom; SP unos; izmena uključujući kategoriju/sliku; pokušaj brisanja korišćene igre.
   - Očekivano: sve operacije rade, slike postoje, FK zabrana radi.
   - Dokazuje: katalog, SP, VIEW, upload, validacije.

4. **Master-detail create i rollback**
   - Koraci: kreirati nalog sa dve stavke iste igre; zatim izazvati detail DB grešku u kontrolisanom test okruženju.
   - Očekivano: duplikat igre dozvoljen; uspešan nalog ima sve stavke; neuspešan nema ni master ni detail.
   - Dokazuje: kompozicija/asocijacija u toku i transakcija.

5. **Lista/filter/detail/edit/delete**
   - Koraci: filter po svakom kriterijumu i kombinaciji; otvoriti nalog sa više stavki; izmeniti master, jednu stavku, dodati i obrisati stavku; pokušati duplikat BrojNaloga; obrisati nalog.
   - Očekivano: tačni skupovi, rekapitulacija, unique izuzetak trenutnog zapisa, nema orphan stavki.
   - Dokazuje: glavni CRUD.

6. **Sve tri štampe**
   - Koraci: print preview svih; filtrirati pa preview; parametarski štampati postojeći i nepostojeći broj.
   - Očekivano: all/filtered razlikuju skup; pojedinačni dokument sadrži sva registrovana polja i samo jedan nalog; navigacija je skrivena u printu.
   - Dokazuje: PRINT zahteve.

7. **REST**
   - Koraci: pozvati `akcija=igre`, validnu/nevalidnu `akcija=igra`, missing param.
   - Očekivano: validan JSON bez PHP warning HTML-a.
   - Dokazuje: opcionih REST 10.

8. **Server validation i povratne putanje**
   - Koraci: poslati prazna/preduga/pogrešna polja, 1.5 količinu, 0 cenu, nepostojeću igru i duplikat ključa; kliknuti POVRATAK.
   - Očekivano: svaki input odbijen i povratak vodi na postojeću stranicu.
   - Dokazuje: client/server validation integraciju.

9. **Aktivni UI asseti**
   - Koraci: otvoriti svaki aktivni ekran uz browser Network panel.
   - Očekivano: nema 404 za `sredinagore.jpg`, `sredinadole.jpg`, `blue-background3.jpg` niti PHP notice-a.
   - Dokazuje: UI integraciju i portability.

# 28. FINAL PRE-DOCUMENTATION VERDICT

**FIX_BEFORE_DOCUMENTATION**

Razlog: nema statičkog BLOCKER-a — ključne obavezne strukture za 20 bodova postoje i svih 69 PHP fajlova prolazi lint. Postoje delimični runtime HTTP dokazi za izmenu, listu, detalj, brisanje i štampe, ali nisu dovoljni za semantičku verifikaciju celog projekta. Tri HIGH nalaza su: nedostajuća login validacija, pogrešni direct-action error/session URL-ovi i nepotpuna runtime matrica. Jedanaest MEDIUM nalaza uključuju aktivne 404 assete, upload putanju, stari README/traceability i naziv baze, input/output injection rizike, delimičan MVC, nekorišćeni VIEW i neatomsku SP. REST je statički dobar kandidat za opcionih 10; MVC trenutno nosi veći, ali ne automatski diskvalifikujući rizik. M16 ne treba započeti dok se HIGH problemi ne razreše i minimalna runtime checklist-a ne prođe.
