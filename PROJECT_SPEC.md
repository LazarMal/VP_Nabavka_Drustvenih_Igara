# PROJECT_SPEC — Nabavka društvenih igara (VP 2025/26)

**Student:** Lazar Malešev, SI 44/22  
**Poslovni proces:** Nabavka društvenih igara za klub društvenih igara  
**Poslovni dokument:** Nalog za nabavku društvenih igara  
**Izvori:** `docs/izvori/VP Plan rada sk 2025 26 STANJE 7 6 2026 dopunjena struktura dokumenta seminarskog rada.pdf`, `docs/izvori/Prijava_VP_Nabavka_drustvenih_igara.docx` (original), `docs/izvori/Prijava_VP_Nabavka_drustvenih_igara.pdf` (potvrđen isti sadržaj)

**Legenda tipa zahteva:**
- **E** — eksplicitni profesorov zahtev
- **T** — interpretacija za registrovanu temu/dokument
- **D** — implementaciona odluka (nije profesorov zahtev)

---

## 1. Nefunkcionalni (NF)

| ID | Zahtev | Tip |
|----|--------|-----|
| NF-01 | Baza podataka mora biti na srpskom jeziku (nazivi tabela, kolona, podaci u bazi prema domenu aplikacije). | E |
| NF-02 | Programski kod mora biti na srpskom jeziku (poruke korisniku, UI tekstovi, domenski nazivi prema konvenciji projekta). | E |
| NF-03 | Korisnički interfejs mora biti na srpskom jeziku. | E |
| NF-04 | Aplikacija mora biti veb aplikacija poslovne orijentacije. | E |
| NF-05 | Seminarski rad mora sadržati programski kod i SQL skriptu. | E |
| NF-06 | Seminarski rad mora sadržati dokumentaciju. | E |

---

## 2. Tehnološki (TECH)

| ID | Zahtev | Tip |
|----|--------|-----|
| TECH-01 | Backend tehnologija: PHP **bez** primene frameworka. | E |
| TECH-02 | Relaciona baza podataka sa **minimum 4 tabele**. | E |
| TECH-03 | Od tih tabela, **3 su povezane relacijom** (celina, deo, šifarnik). | E |
| TECH-04 | **1 nezavisna tabela** korisnik (login). | E |
| TECH-05 | U bazi mora postojati i biti korišćena **stored procedure**. | E |
| TECH-06 | U bazi moraju postojati i biti korišćeni **pogledi (VIEW)**. | E |
| TECH-07 | Aplikacija mora biti **multipage** (više stranica/ekrana). | E |
| TECH-08 | Master-detail unos: celina i delovi se unose **na jednoj ekranskoj formi**. | E |
| TECH-09 | Master-detail unos mora koristiti **transakcije** u bazi. | E |
| TECH-10 | Master-detail podaci moraju imati **tabelarni prikaz**. | E |
| TECH-11 | Validacije: **client-side** (HTML, JavaScript). | E |
| TECH-12 | Validacije: **server-side** (PHP). | E |

---

## 3. Baza podataka i domen (DB)

| ID | Zahtev | Tip |
|----|--------|-----|
| DB-01 | Tabela `korisnik` — nezavisna tabela za autentifikaciju. | E / T |
| DB-02 | Tabela `kategorija_igre` — šifarnik (adaptacija `zanr`). | T / D |
| DB-03 | Tabela `drustvena_igra` — šifarnik/katalog igara (adaptacija `knjiga`). | T / D |
| DB-04 | Tabela `nabavka` — **celina** / glavni poslovni dokument. | E / T |
| DB-05 | Tabela `stavka_nabavke` — **deo** / detail redovi naloga. | E / T |
| DB-06 | Relacija `nabavka` 1:N `stavka_nabavke`. | E / T |
| DB-07 | Relacija `drustvena_igra` 1:N `stavka_nabavke` (FK na šifru igre). | T / D |
| DB-08 | Relacija `kategorija_igre` 1:N `drustvena_igra` (opciono po šablonu). | D |
| DB-09 | Polje `BrojNaloga` u `nabavka` — poslovni identifikator naloga. | T |
| DB-10 | `BrojNaloga` mora biti **jedinstven** (UNIQUE); ne spajati naloge po datumu i dobavljaču. | T / D |
| DB-11 | Polje `DatumNabavke` (DATE, obavezno). | T |
| DB-12 | Polje `Dobavljac` (VARCHAR, obavezno) — **slobodan tekst**. | T / D |
| DB-13 | Polje `Napomena` (VARCHAR, opciono). | T |
| DB-14 | Polje `NalogEvidentirao` u `nabavka` — ko je evidentirao nalog. | T |
| DB-15 | Detail: `SifraIgre`, `Kolicina`, `Cena` po stavci. | T |
| DB-16 | `Ukupno` po stavci = `Kolicina * Cena` — **izračunato**, ne obavezno persistirati. | T / D |
| DB-17 | Rekapitulacija: ukupan broj stavki i ukupna vrednost — **izračunati** iz detail redova. | T / D |
| DB-18 | `IDNabavke` / `IDStavke` — interni surrogate PK po potrebi implementacije. | D |
| DB-19 | Mapiranje šifarnika: `ISBN`→`SifraIgre`, `Autor`→`Proizvodjac`, `OznakaZanra`→`OznakaKategorije`. | D |

---

## 4. OOP (OOP)

| ID | Zahtev | Tip |
|----|--------|-----|
| OOP-01 | **Nasleđivanje:** semantički nezavisne domenske klase nasleđuju tehnološke bazne klase (konekcija, upiti, SP, pogledi). | E |
| OOP-02 | **Kompozicija:** klasa Celina (`NabavkaEntitet`) sadrži listu delova (`ListaStavki`). | E |
| OOP-03 | **Asocijacija:** klasa Deo (`StavkaNabavkeEntitet`) sadrži/referencira objekat šifarnika (`DrustvenaIgraEntitet`). | E |
| OOP-04 | Repozitorijumi (`DB*`) nasleđuju `Tabela` iz `tehnoloskeKlase/BaznaTabela.php`. | E / D |
| OOP-05 | Konekcija i transakcija kroz `Konekcija`, `Transakcija` bazne klase. | E / D |

---

## 5. Funkcionalni — glavni dokument `nabavka` (FUN)

| ID | Zahtev | Tip |
|----|--------|-----|
| FUN-01 | **Login** korisnika pre pristupa poslovnim funkcijama. | E |
| FUN-02 | **Unos** naloga: master (podaci o nabavci) + detail (stavke igara) na **jednoj formi**. | E |
| FUN-03 | Unos naloga mora koristiti **transakciju** (celina + svi delovi atomarno). | E |
| FUN-04 | **Izmena** postojećeg naloga (master podaci). | E |
| FUN-05 | **Izmena** stavki postojećeg naloga (detail). | E |
| FUN-06 | **Brisanje** naloga (celine). | E |
| FUN-07 | **Tabelarni prikaz** svih naloga. | E |
| FUN-08 | **Filtriranje** naloga u tabelarnom prikazu. | E |
| FUN-09 | **Prikaz pojedinačnog** naloga sa **svim** stavkama (detail). | E |
| FUN-10 | CRUD se odnosi na **glavnu tabelu** koja izražava suštinu dokumenta — **ne** zadovoljava se CRUD-om nad katalogom igara. | E / T |

---

## 6. Funkcionalni — katalog `drustvena_igra` (pomoćni šifarnik) (FUN-KAT)

| ID | Zahtev | Tip |
|----|--------|-----|
| FUN-KAT-01 | Adaptirati puni CRUD kataloga iz šablona (`knjiga`→`drustvena_igra`) radi upravljanja šifarnikom. | D |
| FUN-KAT-02 | CRUD kataloga **ne zamenjuje** obavezni CRUD nad glavnim dokumentom (FUN-02–FUN-09). | D |

---

## 7. Validacija (VAL)

| ID | Zahtev | Tip |
|----|--------|-----|
| VAL-01 | Provera **obaveznih polja** (client + server). | E |
| VAL-02 | Provera **odgovarajućeg tipa podatka** (client + server). | E |
| VAL-03 | Provera **dužine podatka** (client + server). | E |
| VAL-04 | Provera **domena ispravnih vrednosti** (client + server). | E |
| VAL-05 | Provera **jedinstvenosti zapisa** gde je poslovno potrebno (client + server). | E |
| VAL-06 | `BrojNaloga`: obavezno, dužina prema koloni, **jedinstveno**. | T / D |
| VAL-07 | `DatumNabavke`: obavezno, validan datum. | T |
| VAL-08 | `Dobavljac`: obavezno, dužina prema koloni; **slobodan tekst** (bez zatvorene liste). | T / D |
| VAL-09 | `Napomena`: dužina prema koloni ako je uneta. | T |
| VAL-10 | `DrustvenaIgra` / `SifraIgre` u stavci: obavezno, mora postojati u katalogu (FK/domen). | T |
| VAL-11 | `Kolicina`: obavezno, pozitivan ceo broj (`> 0`). | T / D |
| VAL-12 | `Cena`: obavezno, pozitivna decimalna vrednost (`> 0`). | T / D |
| VAL-13 | Nalog mora imati **najmanje jednu** detail stavku pri unosu/izmeni. | E / T |
| VAL-14 | `NalogEvidentirao`: popunjava se pri unosu (npr. iz sesije prijavljenog korisnika). | T / D |
| VAL-15 | Tehničko ograničenje tipa kolone (INT, DECIMAL) ≠ poslovno pravilo; **ne uvoditi** proizvoljne gornje granice (npr. 1–100, 1–100000) bez specifikacije. | D |
| VAL-16 | Duplikat iste igre u jednom nalogu: **nije** profesorov zahtev; ne uvoditi zabranu kao poslovno pravilo (šablonska zabrana = postojeće ponašanje šablona). | D |

---

## 8. Štampa (PRINT)

| ID | Zahtev | Tip |
|----|--------|-----|
| PRINT-01 | **Štampa spiska svih** naloga (glavna tabela). | E |
| PRINT-02 | **Štampa filtriranog** spiska naloga. | E |
| PRINT-03 | **Parametarska štampa** pojedinačnog naloga. | E |
| PRINT-04 | Parametarska štampa mora vizuelno i semantički odgovarati prijavljenom dokumentu „Nalog za nabavku društvenih igara“. | E / T |
| PRINT-05 | Parametarska štampa — sekcija PODACI O NABAVCI: Broj naloga, Datum nabavke, Dobavljač, Napomena. | T |
| PRINT-06 | Parametarska štampa — SPISAK IGARA: Stavka, Društvena igra, Cena po komadu, Količina, Ukupno. | T |
| PRINT-07 | Parametarska štampa — REKAPITULACIJA: Ukupan broj stavki, Ukupna vrednost nabavke. | T |
| PRINT-08 | Parametarska štampa — „Nalog evidentirao“. | T |
| PRINT-09 | Parametarska štampa mora prikazati master-detail odnos podataka. | E |

---

## 9. Registrovani poslovni dokument — polja (TOP)

| ID | Zahtev | Tip |
|----|--------|-----|
| TOP-01 | Naziv poslovnog procesa: Nabavka društvenih igara za klub društvenih igara. | T |
| TOP-02 | Naziv dokumenta: Nalog za nabavku društvenih igara. | T |
| TOP-03 | Master polja: Broj naloga, Datum nabavke, Dobavljač, Napomena. | T |
| TOP-04 | Detail kolone: Stavka (redni broj), Društvena igra, Cena po komadu, Količina, Ukupno. | T |
| TOP-05 | Rekapitulacija: Ukupan broj stavki, Ukupna vrednost nabavke. | T |
| TOP-06 | Dodatno polje: Nalog evidentirao. | T |

---

## 10. Stored procedure i pogledi (TECH-SP / TECH-VIEW)

| ID | Zahtev | Tip |
|----|--------|-----|
| TECH-SP-01 | Postoji SQL stored procedure korišćena iz PHP aplikacije. | E |
| TECH-SP-02 | Stored procedure adaptirana na domen društvenih igara (adaptacija `DodajKnjigu`→`DodajDrustvenuIgru` ili ekvivalent). | D |
| TECH-VIEW-01 | Postoji SQL VIEW korišćen iz PHP aplikacije. | E |
| TECH-VIEW-02 | VIEW adaptiran na domen (adaptacija `svipodacioknjigama*`→ekvivalent za igre). | D |
| TECH-SP-VIEW-03 | Dodatna SP/VIEW/REST nad `nabavka` **nije obavezna** ako katalog adaptacija dokazivo zadovoljava kriterijum. | D |

---

## 11. MVC — opciono (MVC)

| ID | Zahtev | Tip |
|----|--------|-----|
| MVC-01 | Organizacija rešenja primenom arhitekture MVC (opciono, 10 bodova). | E |
| MVC-02 | Odvojeni model, view i kontroler slojevi u adaptiranom rešenju. | E |
| MVC-03 | Poslovna logika nabavke i kataloga učestvuje koherentno u MVC toku. | D |

---

## 12. REST — opciono (REST)

| ID | Zahtev | Tip |
|----|--------|-----|
| REST-01 | REST servis sa ruterom (opciono, 10 bodova). | E |
| REST-02 | Ruter (`api/router.php` ili ekvivalent) mapira akcije na endpoint resurse. | E |
| REST-03 | REST endpointi adaptirani na domen društvenih igara (adaptacija `knjige`/`knjiga`). | D |

---

## 13. Dokumentacija seminarskog rada (DOC)

| ID | Zahtev | Tip |
|----|--------|-----|
| DOC-01 | Sekcija 1: Opis namene aplikacije u kontekstu poslovnog procesa. | E |
| DOC-02 | Sekcija 2: Korisničko uputstvo (izgledi ekrana i kratak opis korišćenja). | E |
| DOC-03 | Sekcija 3: Dokument i analiza dokumenta (izgled, tabela elementarnih podataka, tipovi, domeni, objašnjenje domena). | E |
| DOC-04 | Sekcija 4: Kratak opis primenjenih alata. | E |
| DOC-05 | Sekcija 5.1: Dijagram slučajeva korišćenja. | E |
| DOC-06 | Sekcija 5.2: Relacioni model podataka (ceo dokument + rešenje). | E |
| DOC-07 | Sekcija 5.3: Dijagram klasa (atributi, metode, veze). | E |
| DOC-08 | Sekcija 6.1.1: Kod — prijava korisnika. | E |
| DOC-09 | Sekcija 6.1.2: Kod — CRUD operacije nad glavnom tabelom. | E |
| DOC-10 | Sekcija 6.1.3: Kod — štampa i parametarska štampa. | E |
| DOC-11 | Sekcija 6.2: Kod — osnovne validacije. | E |
| DOC-12 | Sekcija 6.3: Kod — stored procedure i pogledi. | E |
| DOC-13 | Sekcija 6.4: Kod — nasleđivanje, asocijacija, kompozicija. | E |
| DOC-14 | Sekcija 6.5 (opciono): MVC — model, view, kontroler. | E |
| DOC-15 | Sekcija 6.6 (opciono): REST servis i ruter. | E |

---

## 14. Predaja i odbrana (SUB)

| ID | Zahtev | Tip |
|----|--------|-----|
| SUB-01 | Predaja dokumentacije, koda i SQL na Google učionicu najkasnije 1 dan pre ispita / pismene odbrane. | E |
| SUB-02 | Predaja koda na GitHub najkasnije 1 dan pre pismene odbrane (ispitni rok). | E |
| SUB-03 | Pismena odbrana je uslov za usmenu odbranu. | E |
| SUB-04 | Nekompletni radovi (nedostaje dokumentacija ili kod, ili obavezni delovi) se ne razmatraju — ostaju za sledeći rok. | E |
| SUB-05 | Nema dopune rada za isti ispitni rok nakon predaje. | E |
| SUB-06 | Za odbranu potrebno položiti i pismenu i usmenu odbranu. | E |
| SUB-07 | Nezadovoljavajuća odbrana → rad se poništava, novi rad na novu temu. | E |

---

## 15. Konflikti i defekti šablona (DEF) — za ispravku pri implementaciji

| ID | Opis | Tip |
|----|------|-----|
| DEF-01 | `DBNabavka::PronadjiNabavku(Datum, Dobavljac)` spaja naloge — u sukobu sa UNIQUE `BrojNaloga`. | D (šablon) |
| DEF-02 | Parametarska štampa u šablonu = knjiga, ne prijavljeni nalog. | D (šablon) |
| DEF-03 | CRUD nad `nabavka` nepotpun: nema izmena, brisanja, filtera, štampe, parametarske. | D (šablon) |
| DEF-04 | Šema `nabavka` nema `BrojNaloga`, `NalogEvidentirao`. | D (šablon) |
| DEF-05 | `NabavkaModel::DajStavkeNabavke` koristi GROUP BY — rizik za veran detail prikaz. | D (šablon) |
| DEF-06 | Validacija dobavljača u šablonu = zatvorena lista knjižara — u sukobu sa 1A. | D (šablon) |
| DEF-07 | `nabavkaSnimi.php` zabranjuje duplikat knjige — šablonsko ponašanje, ne zahtev. | D (šablon) |
| DEF-08 | Gornje limite Količina/Cena u šablonu (100, 100000) — nisu specifikacija. | D (šablon) |
| DEF-09 | Mogući dead route brisanja knjige (`obrisiKnjigu` u listi vs akcija). | D (šablon) |
| DEF-10 | `index.php` na učitavanju uništava sesiju — rizik za login tok. | D (šablon) |

---

## 16. Ambiguities (AMB) — dokumentovati, ne pretvarati u zahtev

| ID | Tema | Bezbedna interpretacija |
|----|------|-------------------------|
| AMB-01 | Duplikat iste igre u jednom nalogu | Nedefinisano; ne uvoditi zabranu kao poslovno pravilo |
| AMB-02 | Format `BrojNaloga` | UNIQUE string; dužina kolone; bez izmišljenog regex-a |
| AMB-03 | Gornji limiti Količina/Cena | Samo `> 0` + tip; bez proizvoljnih gornjih granica |

---

## Ukupan broj atomarnih zahteva

| Kategorija | Broj |
|------------|------|
| NF | 6 |
| TECH | 12 |
| DB | 19 |
| OOP | 5 |
| FUN | 10 |
| FUN-KAT | 2 |
| VAL | 16 |
| PRINT | 9 |
| TOP | 6 |
| TECH-SP/VIEW | 5 |
| MVC | 3 |
| REST | 3 |
| DOC | 15 |
| SUB | 7 |
| DEF | 10 |
| AMB | 3 (informativno) |
| **Ukupno (proverljivi zahtevi, bez AMB)** | **118** |
