# REQUIREMENTS — Traceability matrica

**Projekat:** Nabavka društvenih igara (VP 2025/26)  
**Referenca:** [PROJECT_SPEC.md](PROJECT_SPEC.md)  
**Statusi:** `SATISFIED_BY_TEMPLATE` | `NEEDS_ADAPTATION` | `MISSING` | `BLOCKED` | `VERIFIED`

---

## Legenda kolona

| Kolona | Opis |
|--------|------|
| **Source** | Profesor PDF / Prijava DOCX / Odluka 1A-2A |
| **Interpretation** | Kako se odnosi na temu nabavke igara |
| **Template status** | Stanje u profesorovom PHP šablonu (Biblioteka) |
| **Required adaptation** | Šta treba uraditi |
| **Planned location** | Gde će biti implementirano |
| **Verification** | Kako proveriti |
| **Status** | Trenutno stanje šablona |

---

## NF — Nefunkcionalni

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| NF-01 | Baza na srpskom | Profesor PDF | Nazivi tabela/kolona na srpskom | Delimično (`nabavka`, `knjiga` latinica) | Preimenovanje/adaptacija na domen igara | `bazapodataka/*.txt` | Pregled SQL skripte | VERIFIED |
| NF-02 | Kod na srpskom | Profesor PDF | Poruke/UI na srpskom | Da (ćirilica u UI) | Adaptacija preostalih knjižnih tekstova | `pogledi/`, `delovi/`, akcije | Pregled UI tekstova | IMPLEMENTED |
| NF-03 | UI na srpskom | Profesor PDF | Svi ekrani na srpskom | Da | Tekstovi „knjiga“→„igra“, „nabavka knjiga“→„nalog“ | `pogledi/`, `delovi/` | Pregled ekrana | IMPLEMENTED |
| NF-04 | Poslovna veb aplikacija | Profesor PDF | Nabavka igara za klub | Da (biblioteka/nabavka) | Domain adapt | Cela aplikacija | Pregled namene | NEEDS_ADAPTATION |
| NF-05 | Programski kod isporuka | Profesor PDF | GitHub + učionica | Da (šablon postoji) | Nastavak na adaptiranom kodu | Repo root | Repo nije prazan | SATISFIED_BY_TEMPLATE |
| NF-06 | Dokumentacija isporuka | Profesor PDF | Seminarski PDF/DOC | Nema u repo | Kreirati pri milestone 16 | `docs/` (finalno) | Fajl postoji | MISSING |

---

## TECH — Tehnološki

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| TECH-01 | PHP bez frameworka | Profesor PDF | — | Da | Nema | Ceo projekat | Nema Laravel/Symfony | SATISFIED_BY_TEMPLATE |
| TECH-02 | Min 4 tabele | Profesor PDF | korisnik, kategorija, igra, nabavka, stavka | 5 tabela (`zanr`, `knjiga`, `KORISNIK`, `nabavka`, `stavka_nabavke`) | Rename/adapt šifarnika | `bazapodataka/BazaPodataka.txt` | COUNT tabela ≥ 4 | VERIFIED |
| TECH-03 | 3 povezane (celina-deo-šifarnik) | Profesor PDF | nabavka–stavka–drustvena_igra | Da (nabavka–stavka–knjiga) | FK na `drustvena_igra` | SQL + repozitorijumi | ER dijagram | VERIFIED |
| TECH-04 | Nezavisna korisnik | Profesor PDF | Login tabela | Da (`KORISNIK`) | KEEP | `repozitorijumi/DBKorisnik.php` | Tabela bez FK na poslovne | SATISFIED_BY_TEMPLATE |
| TECH-05 | Stored procedure | Profesor PDF | SP za katalog | Da (`DodajKnjigu`) | Adapt na igre | `bazapodataka/Pogledi i Stored procedure.txt`, `DBKnjigaSP.php` | CALL iz PHP | NEEDS_ADAPTATION |
| TECH-06 | Pogledi VIEW | Profesor PDF | VIEW za katalog | Da (`svipodacioknjigama*`) | Adapt na igre | SQL, `DBKnjigaV.php` | SELECT iz VIEW | NEEDS_ADAPTATION |
| TECH-07 | Multipage | Profesor PDF | Ruter + više stranica | Da (`Ruter.php`, `pogledi/`) | Proširiti rute za nabavku | `Ruter.php` | Više URL stranica | SATISFIED_BY_TEMPLATE |
| TECH-08 | MD unos jedna forma | Profesor PDF | Nova nabavka forma | Da (`NovaNabavka.php`) | Polja dokumenta + igre | `pogledi/NovaNabavka.php` | Jedna forma master+detail | IMPLEMENTED |
| TECH-09 | Transakcija pri unosu | Profesor PDF | nabavkaSnimi | Da (`BaznaTransakcija`, `nabavkaSnimi.php`) | Ukloniti merge; adapt polja | `kontroler/akcije/nabavkaSnimi.php` | Rollback test | IMPLEMENTED |
| TECH-10 | MD tabelarni prikaz | Profesor PDF | Lista naloga sa stavkama | Delimično (lista bez filter/CRUD) | Filter, izmena, brisanje | `NabavkeLista.php`, `desnoNabavkeLista.php` | Tabela master+detail | IMPLEMENTED |
| TECH-11 | Client-side validacija | Profesor PDF | JS/HTML | Delimično (`proveriNabavku`) | Adapt pravila (1A, VAL) | `NovaNabavka.php`, forme | DevTools + prazna forma | NEEDS_ADAPTATION |
| TECH-12 | Server-side validacija | Profesor PDF | PHP u akcijama | Delimično (pogrešan domen dobavljača) | Adapt `nabavkaSnimi.php` | `kontroler/akcije/*.php` | POST sa nevalidnim podacima | NEEDS_ADAPTATION |

---

## DB — Baza i domen

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| DB-01 | Tabela korisnik | Profesor PDF | Login | Da | KEEP | SQL, `DBKorisnik.php` | SELECT login | SATISFIED_BY_TEMPLATE |
| DB-02 | kategorija_igre | Mapiranje | zanr→kategorija | `zanr` postoji | RENAME/adapt | SQL | Tabela postoji | IMPLEMENTED |
| DB-03 | drustvena_igra | Mapiranje | knjiga→igra | `knjiga` postoji | RENAME/adapt kolona | SQL, entiteti | Tabela postoji | IMPLEMENTED |
| DB-04 | nabavka celina | Profesor PDF + prijava | Nalog master | Da, nepotpuna | +BrojNaloga, +NalogEvidentirao | SQL, `NabavkaEntitet` | Kolone postoje | VERIFIED |
| DB-05 | stavka_nabavke deo | Profesor PDF + prijava | Detail | Da | ISBN→SifraIgre | SQL, `StavkaNabavkeEntitet` | FK na igru | VERIFIED |
| DB-06 | nabavka 1:N stavka | Profesor PDF | — | Da | KEEP | SQL FK | ER | SATISFIED_BY_TEMPLATE |
| DB-07 | igra 1:N stavka | Mapiranje | — | Da (knjiga) | Adapt FK | SQL | FK | VERIFIED |
| DB-08 | kategorija 1:N igra | Šablon | — | Da (zanr–knjiga) | Adapt | SQL | FK | VERIFIED |
| DB-09 | BrojNaloga | Prijava DOCX | Poslovni ID | **Nema** | ADD kolona | SQL, forme | Kolona u bazi | VERIFIED |
| DB-10 | BrojNaloga UNIQUE | Odluka | Ne spajati naloge | **Nema**; merge u kodu | UNIQUE + ukloniti PronadjiNabavku merge | SQL, `DBNabavka.php`, `nabavkaSnimi.php` | Dva ista broja odbijena | IMPLEMENTED |
| DB-11 | DatumNabavke | Prijava | — | Da | KEEP | SQL | DATE NOT NULL | SATISFIED_BY_TEMPLATE |
| DB-12 | Dobavljac slobodan tekst | Odluka 1A | — | VARCHAR; select u UI | Text input, ukloniti listu | Forme, validacija | Unos произvoljnog teksta | IMPLEMENTED |
| DB-13 | Napomena | Prijava | — | Da | KEEP | SQL | NULL OK | SATISFIED_BY_TEMPLATE |
| DB-14 | NalogEvidentirao | Prijava | — | **Nema** | ADD kolona | SQL, unos iz sesije | Kolona popunjena | VERIFIED |
| DB-15 | SifraIgre, Kolicina, Cena | Prijava | Detail polja | Da (ISBN, Kolicina, Cena) | Rename ISBN | SQL, stavke | Detail red | VERIFIED |
| DB-16 | Ukupno izračunato | Odluka | Kolicina*Cena | JS + SQL calc | KEEP calculated | JS, prikaz, štampa | Nema kolone Ukupno u DB | SATISFIED_BY_TEMPLATE |
| DB-17 | Rekapitulacija izračunata | Prijava | Broj stavki, suma | Delimično u listi | U prikaz, filter, štampu | Views, print | Sum matches | IMPLEMENTED |
| DB-18 | Surrogate PK | D | IDNabavke, IDStavke | Da | KEEP | SQL | AUTO_INCREMENT | SATISFIED_BY_TEMPLATE |
| DB-19 | Mapiranje kolona šifarnika | Mapiranje | SifraIgre, Proizvodjac… | knjiga kolone | Rename u SQL i PHP | Entiteti, repozitorijumi | Imena kolona | IMPLEMENTED |

---

## OOP

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| OOP-01 | Nasleđivanje DB→Tabela | Profesor PDF | — | Da (`DBKnjiga extends Tabela`) | Adapt imena klasa | `repozitorijumi/`, `tehnoloskeKlase/` | extends Tabela | SATISFIED_BY_TEMPLATE |
| OOP-02 | Kompozicija Nabavka→stavke | Profesor PDF | ListaStavki | Da (`NabavkaEntitet`) | KEEP | `model/entiteti/NabavkaEntitet.php` | Lista u entitetu | SATISFIED_BY_TEMPLATE |
| OOP-03 | Asocijacija stavka→šifarnik | Profesor PDF | Knjiga→DrustvenaIgra | Da (`StavkaNabavkeEntitet::$Knjiga`) | Rename entitet | `StavkaNabavkeEntitet.php` | FK objekat | IMPLEMENTED |
| OOP-04 | Repozitorijumi nasleđuju Tabela | Profesor PDF | — | Da | Adapt imena | `repozitorijumi/` | class extends Tabela | SATISFIED_BY_TEMPLATE |
| OOP-05 | Konekcija, Transakcija | Profesor PDF | — | Da | KEEP | `tehnoloskeKlase/` | Transakcija u unosu | SATISFIED_BY_TEMPLATE |

---

## FUN — Glavni dokument (nabavka)

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| FUN-01 | Login | Profesor PDF | — | Da (`prijavaprovera.php`, sesija) | KEEP; proveriti index.php sesiju | `kontroler/akcije/prijavaprovera.php` | Login/logout | IMPLEMENTED |
| FUN-02 | Unos MD jedna forma | Profesor PDF | Nalog | Da (`novaNabavka`) | +BrojNaloga, +evidentirao, igre | `NovaNabavka.php` | Form submit | IMPLEMENTED |
| FUN-03 | Unos transakcija | Profesor PDF | — | Da | Fix merge (DEF-01) | `nabavkaSnimi.php` | DB rollback | IMPLEMENTED |
| FUN-04 | Izmena master | Profesor PDF | — | **Nema** | Nova ruta, forma, akcija | `Ruter.php`, nova forma, `nabavkaIzmeni.php` | UPDATE nabavka | IMPLEMENTED |
| FUN-05 | Izmena stavki | Profesor PDF | — | **Nema** | U istoj izmeni MD | `nabavkaIzmeni.php`, repo | UPDATE/DELETE/INSERT stavki | IMPLEMENTED |
| FUN-06 | Brisanje naloga | Profesor PDF | — | **Nema** | Akcija + CASCADE | `nabavkaObrisi.php`, ruta | DELETE + confirm | IMPLEMENTED |
| FUN-07 | Tabelarni prikaz | Profesor PDF | — | Delimično (`nabavke`) | Tabular lista sa akcijama | `NabavkeLista.php` | Tabela naloga | IMPLEMENTED |
| FUN-08 | Filter | Profesor PDF | — | **Nema** za nabavku | Filter forma (datum, broj, dobavljač…) | `desnoNabavkeLista.php`, controller | GET filter | IMPLEMENTED |
| FUN-09 | Pojedinačni prikaz | Profesor PDF | Svi delovi | Delimično (u listi expanded) | Dedicated detail view ili jasna sekcija | `nabavkaDetalj` ruta | Svi stavke vidljive | IMPLEMENTED |
| FUN-10 | CRUD ≠ katalog | Odluka 2A | Nabavka obavezno | Katalog pun CRUD; nabavka ne | Implementirati FUN-04–09 | Nabavka modul | Katalog ne zamenjuje | IMPLEMENTED |

---

## FUN-KAT — Katalog (pomoćni)

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| FUN-KAT-01 | Pun CRUD kataloga | Odluka 2A | drustvena_igra | Pun CRUD knjiga | Domain adapt svih knjiga ekrana | `KnjigeController`→`IgreController`, itd. | CRUD igara | IMPLEMENTED |
| FUN-KAT-02 | Ne zamenjuje FUN glavnog | Odluka 2A | — | Šablon fokus na knjizi | Eksplicitno odvojiti u docs | Dokumentacija | Matrica | SATISFIED_BY_TEMPLATE (razumevanje) |

---

## VAL — Validacija

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| VAL-01 | Obavezna polja | Profesor PDF | — | Delimično | Kompletirati za nabavku | Forme + akcije | Prazna polja odbijena | NEEDS_ADAPTATION |
| VAL-02 | Tip podatka | Profesor PDF | — | Delimično | Adapt (ne ISBN regex za igru ako drugačija šifra) | `nabavkaSnimi.php` | Pogrešan tip | NEEDS_ADAPTATION |
| VAL-03 | Dužina | Profesor PDF | — | Delimično (napomena 255) | maxlength + server | Forme | Prekoračenje | NEEDS_ADAPTATION |
| VAL-04 | Domen vrednosti | Profesor PDF | Šifra igre, količina, cena, datum | Pogrešno (lista dobavljača) | 1A + minimalni domen | Akcije | FK igra, >0 | NEEDS_ADAPTATION |
| VAL-05 | Jedinstvenost | Profesor PDF | BrojNaloga, šifra igre | ISBN unique za knjigu | BrojNaloga UNIQUE | SQL, akcije | Duplikat odbijen | NEEDS_ADAPTATION |
| VAL-06 | BrojNaloga | Prijava + VAL | — | **Nema polja** | ADD validacija | Forme, PHP | Required + unique | IMPLEMENTED |
| VAL-07 | DatumNabavke | Prijava | — | Da | KEEP | `nabavkaSnimi.php` | Invalid date | SATISFIED_BY_TEMPLATE |
| VAL-08 | Dobavljac tekst | Odluka 1A | — | Select lista | Text + length | Forme | Slobodan unos | IMPLEMENTED |
| VAL-09 | Napomena dužina | Prijava | — | Da (255) | KEEP | PHP | >255 odbijeno | SATISFIED_BY_TEMPLATE |
| VAL-10 | DrustvenaIgra FK | Prijava | — | ISBN validacija | FK postoji u katalogu | PHP | Nepostojeća igra | IMPLEMENTED |
| VAL-11 | Kolicina > 0 int | Odluka | — | Ima + gornja 100 | Ukloniti gornju granicu | PHP, JS | 0 odbijeno; -1 odbijeno | IMPLEMENTED |
| VAL-12 | Cena > 0 decimal | Odluka | — | Ima + gornja 100000 | Ukloniti gornju granicu | PHP, JS | 0 odbijeno | IMPLEMENTED |
| VAL-13 | Min 1 stavka | Profesor PDF | — | Da | KEEP | JS + PHP | 0 stavki odbijeno | SATISFIED_BY_TEMPLATE |
| VAL-14 | NalogEvidentirao | Prijava | Iz sesije | **Nema** | Auto iz $_SESSION | `nabavkaSnimi.php` | Polje popunjeno | IMPLEMENTED |
| VAL-15 | Bez proizvoljnih limita | Odluka | — | Šablon ima limite | Ukloniti 100/100000 | Akcije | Nema gornjeg limita | IMPLEMENTED |
| VAL-16 | Bez zabrane duplikata igre | Odluka | — | Šablon zabranjuje | **Ukloniti** proveru duplikata | `nabavkaSnimi.php` | Ista igra 2x dozvoljena | IMPLEMENTED |

---

## PRINT

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| PRINT-01 | Štampa svih naloga | Profesor PDF | — | **Nema** (samo knjige) | Nova štampa | `NabavkeStampa.php`, ruta | Print all orders | MISSING |
| PRINT-02 | Štampa filtriranih | Profesor PDF | — | **Nema** | Filter param u štampi | `Ruter.php`, stampa view | Filter applied | MISSING |
| PRINT-03 | Parametarska štampa | Profesor PDF | Jedan nalog | Samo knjiga | Nova parametarska za nabavku | `NabavkaParametarskaStampa.php` | Single order print | MISSING |
| PRINT-04 | Izgled kao prijava | Profesor PDF + prijava | Nalog dokument | Knjiga layout | Novi layout po DOCX | `StampaNaloga.php` | Visual compare DOCX | MISSING |
| PRINT-05 | PODACI O NABAVCI | Prijava | 4 polja | N/A | U štampi | Print view | Polja prisutna | MISSING |
| PRINT-06 | SPISAK IGARA | Prijava | 5 kolona | N/A | Tabela stavki | Print view | Kolone match | MISSING |
| PRINT-07 | REKAPITULACIJA | Prijava | 2 polja | N/A | Sum/broj stavki | Print view | Tačni iznosi | MISSING |
| PRINT-08 | Nalog evidentirao | Prijava | — | N/A | U footer štampе | Print view | Ime korisnika | MISSING |
| PRINT-09 | MD u štampi | Profesor PDF | — | Knjiga nema MD | Master+detail u print | Print view | Header+rows | MISSING |

---

## TOP — Registrovani dokument

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| TOP-01 | Naziv procesa | Prijava DOCX | — | N/A u app | UI tekst, docs | Welcome, docs | Tekst match | NEEDS_ADAPTATION |
| TOP-02 | Naziv dokumenta | Prijava DOCX | — | „Nabavka knjiga“ | Preimenovati | UI, print | Tekst match | IMPLEMENTED |
| TOP-03 | Master polja | Prijava DOCX | — | Delimično | +BrojNaloga | Forme, DB | Sva 4 polja | IMPLEMENTED |
| TOP-04 | Detail kolone | Prijava DOCX | — | Knjiga kolone | Adapt headers | Forme, print | 5 kolona | IMPLEMENTED |
| TOP-05 | Rekapitulacija | Prijava DOCX | — | Delimično u listi | U sve prikaze | Views | Match | IMPLEMENTED |
| TOP-06 | Nalog evidentirao | Prijava DOCX | — | **Nema** | ADD | DB, form, print | Polje vidljivo | IMPLEMENTED |

---

## TECH-SP / TECH-VIEW

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| TECH-SP-01 | SP u upotrebi | Profesor PDF | — | Da (`DodajKnjigu`, `unosSP`) | Adapt | SQL, `DBKnjigaSP.php` | CALL radi | NEEDS_ADAPTATION |
| TECH-SP-02 | SP domen igara | Mapiranje | — | Knjiga SP | Rename SP + params | SQL | Insert igra | IMPLEMENTED |
| TECH-VIEW-01 | VIEW u upotrebi | Profesor PDF | — | Da (`DBKnjigaV`) | Adapt | SQL, repo | SELECT view | NEEDS_ADAPTATION |
| TECH-VIEW-02 | VIEW domen igara | Mapiranje | — | Knjiga views | Rename columns | SQL | Join kategorija | IMPLEMENTED |
| TECH-SP-VIEW-03 | Ne auto SP/VIEW nabavka | Odluka | — | N/A | Out of scope | — | Katalog dokazuje | SATISFIED_BY_TEMPLATE (plan) |

---

## MVC — Opciono

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| MVC-01 | MVC arhitektura | Profesor PDF opciono | 10 bodova | Delimično (folderi) | Proširiti za nabavku | `kontroler/`, `model/`, `pogledi/` | Tok request→controller→view | NEEDS_ADAPTATION |
| MVC-02 | Model, View, Controller | Profesor PDF | — | Da za knjige/nabavke | Adapt kontrolere | `NabavkeController`, model servisi | Odvojeni slojevi | SATISFIED_BY_TEMPLATE |
| MVC-03 | Nabavka u MVC toku | Odluka | — | Akcije van kontrolera | Prebaciti/organizovati akcije | `kontroler/akcije/` | Konzistentan tok | NEEDS_ADAPTATION |

---

## REST — Opciono

| ID | Requirement | Source | Interpretation | Template status | Required adaptation | Planned location | Verification | Status |
|----|-------------|--------|----------------|-----------------|---------------------|------------------|--------------|--------|
| REST-01 | REST servis | Profesor PDF opciono | 10 bodova | Da (`api/`) | Adapt | `api/` | JSON response | NEEDS_ADAPTATION |
| REST-02 | Ruter | Profesor PDF | — | Da (`api/router.php`) | Adapt akcije | `api/router.php` | GET ?akcija= | SATISFIED_BY_TEMPLATE |
| REST-03 | Domen igara | Mapiranje | — | knjige/knjiga | Rename endpoints | `api/igre.php`, `api/igra.php` | JSON igara | NEEDS_ADAPTATION |

---

## DOC — Dokumentacija

| ID | Requirement | Source | Template status | Required adaptation | Status |
|----|-------------|-----------------|---------------------|--------|
| DOC-01 … DOC-15 | Sve sekcije strukture | Profesor PDF | **Nema** u repo | Kreirati u milestone 16 | MISSING |

---

## SUB — Predaja i odbrana

| ID | Requirement | Source | Template status | Status |
|----|-------------|-----------------|--------|
| SUB-01 … SUB-07 | Pravila predaje/odbrane | Profesor PDF | N/A (proces) | SATISFIED_BY_TEMPLATE (procesni zahtev, isporuka kasnije) |

---

## DEF — Defekti šablona (za FIX pri implementaciji)

| ID | Opis | Status | Required adaptation |
|----|------|--------|---------------------|
| DEF-01 | Merge po Datum+Dobavljac | IMPLEMENTED | Ukloniti PronadjiNabavku merge |
| DEF-02 | Parametarska = knjiga | MISSING funkcionalnost | Nova štampa naloga |
| DEF-03 | Nepun CRUD nabavka | IMPLEMENTED | FUN-04–09 (bez štampe M9+) |
| DEF-04 | Nedostaju polja | VERIFIED | DB-09, DB-14 (SQL kolone) |
| DEF-05 | GROUP BY stavke | IMPLEMENTED | Pojedinačni redovi stavki |
| DEF-06 | Zatvoreni dobavljači | IMPLEMENTED | 1A text |
| DEF-07 | Zabrana duplikata | IMPLEMENTED | Ukloniti (VAL-16) |
| DEF-08 | Gornji limiti | IMPLEMENTED | Ukloniti (VAL-15) |
| DEF-09 | Dead route brisanja | IMPLEMENTED | Ruta `obrisiKnjigu` u `Ruter.php` |
| DEF-10 | index.php session_destroy | IMPLEMENTED | Uklonjeno sa landing; odjava preko `Ruter.php?stranica=odjava` |

---

## Rezime statusa (proverljivi zahtevi, bez AMB/SUB procesnih)

| Status | Broj |
|--------|------|
| VERIFIED | 18 |
| SATISFIED_BY_TEMPLATE | 15 |
| IMPLEMENTED | 47 |
| NEEDS_ADAPTATION | 18 |
| MISSING | 13 |
| BLOCKED | 0 |

**Napomena:** Brojevi uključuju FUN-KAT, VAL, PRINT, TOP, TECH-SP/VIEW, MVC, REST, DEF kao zasebne stavke u matrici. DOC (15) svi MISSING.
