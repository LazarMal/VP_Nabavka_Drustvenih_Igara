# ADAPTATION_PLAN — Minimalna adaptacija šablona

**Projekat:** Nabavka društvenih igara  
**Referenca:** [PROJECT_SPEC.md](PROJECT_SPEC.md), [REQUIREMENTS.md](REQUIREMENTS.md)  
**Princip:** KEEP AS-IS > DOMAIN ADAPT > EXTEND > FIX > CREATE NEW > REWRITE

---

## 1. Odluke koje ne menjamo

| Odluka | Zahtevi |
|--------|---------|
| Dobavljač = slobodan tekst (1A) | VAL-08, DB-12, DEF-06 |
| Katalog pun CRUD, ali ne zamenjuje CRUD naloga (2A) | FUN-KAT-01/02, FUN-10 |
| Bez zabrane duplikata iste igre u nalogu | VAL-16, DEF-07 |
| Bez proizvoljnih gornjih limita količine/cene | VAL-11/12/15, DEF-08 |
| SP/VIEW/REST adaptirati na katalog; **ne** automatski dodavati nad `nabavka` | TECH-SP-VIEW-03 |

---

## 2. Klasifikacija modula

### 2.1 KEEP AS-IS

| Modul | Fajlovi | Opravdanje (ID) |
|-------|---------|-----------------|
| Tehnološke bazne klase | `tehnoloskeKlase/BaznaKonekcija.php`, `BaznaTabela.php`, `BaznaTransakcija.php`, `BaznaParametriKonekcije.xml` | TECH-01, OOP-01, OOP-05, TECH-09 |
| Autentifikacija (osnovni tok) | `kontroler/akcije/prijavaprovera.php`, `repozitorijumi/DBKorisnik.php`, `pogledi/prijava.php` | TECH-04, FUN-01, DB-01 |
| Ruter koncept | `Ruter.php` (struktura switch, `proveriSesiju`) | TECH-07, TECH-01 |
| Kompozicija entiteta | `model/entiteti/NabavkaEntitet.php` (`ListaStavki`, `DajUkupnuVrednost`) | OOP-02 |
| Transakcioni unos (mehanizam) | `BaznaTransakcija`, pattern u `nabavkaSnimi.php` | TECH-09, FUN-03 |
| Layout/CSS infrastruktura | `css/stil.php`, `css/style.css`, `delovi/zaglavlje*.php`, `footer*.php` | NF-03 (vizuelni okvir) |
| Master-detail JS mehanika | `NovaNabavka.php` (dodaj/obriši red, izračun ukupno) | TECH-08, DB-16 |
| REST ruter pattern | `api/router.php` (switch po akciji) | REST-02 |
| MVC folder struktura | `kontroler/`, `model/`, `pogledi/` | MVC-02 |
| FK CASCADE stavka→nabavka | SQL `ON DELETE CASCADE` na `stavka_nabavke` | DB-06 |

---

### 2.2 DOMAIN ADAPT

| Modul | Fajlovi | Adaptacija | Opravdanje (ID) |
|-------|---------|------------|-----------------|
| SQL šema | `bazapodataka/BazaPodataka.txt` | `zanr`→`kategorija_igre`, `knjiga`→`drustvena_igra`, kolone mapiranja, +`BrojNaloga`, +`NalogEvidentirao`, `ISBN`→`SifraIgre` u stavci | DB-02–08, DB-09–15, DB-19, TOP-03–06 |
| SQL SP/VIEW | `bazapodataka/Pogledi i Stored procedure.txt` | `DodajKnjigu`→`DodajDrustvenuIgru`, views za igre | TECH-05/06, TECH-SP-01/02, TECH-VIEW-01/02 |
| Entitet knjiga | `model/entiteti/KnjigaEntitet.php` | → `DrustvenaIgraEntitet.php` | DB-03, OOP-03 |
| Entitet stavka | `model/entiteti/StavkaNabavkeEntitet.php` | `$Knjiga`→`$DrustvenaIgra`, `ISBN`→`SifraIgre` | OOP-03, DB-15 |
| Repozitorijumi knjiga | `DBKnjiga.php`, `DBKnjigaV.php`, `DBKnjigaSP.php`, `DBZanr.php` | Rename/adapt upite i tabele | FUN-KAT-01, TECH-SP/VIEW |
| Model servisi | `model/servisi/KnjigaModel.php` | → `DrustvenaIgraModel.php` | FUN-KAT-01 |
| Kontroler knjige | `kontroler/stranice/KnjigeController.php` | → `IgreController.php` (ili adapt in-place) | FUN-KAT-01, MVC-01 |
| Akcije knjiga | `knjigaSnimi.php`, `knjigaIzmeni.php`, `KnjigaObrisi.php`, `knjigaSnimiSP.php` | Domain + validacija šifre igre | FUN-KAT-01, VAL-02/05 |
| Pogledi/delovi knjiga | `pogledi/unos.php`, `unosSP.php`, `KnjigeLista.php`, `KnjigaIzmeniForm.php`, `KnjigeStampa.php`, `KnjigeParametarskaStampa.php`, `delovi/desno*.php` | Tekstovi, polja, kolone | FUN-KAT-01, NF-02/03 |
| REST API | `api/knjige.php`, `api/knjiga.php` | → `igre.php`, `igra.php`; router akcije | REST-01/03 |
| Nabavka repozitorijumi | `DBNabavka.php`, `DBStavkaNabavke.php` | Kolone, join na `drustvena_igra`, ukloniti merge logiku | DB-04/05/10, DEF-01 |
| Nabavka model | `model/servisi/NabavkaModel.php` | Upiti bez GROUP BY agregacije po default; filter | DEF-05, FUN-08 |
| Nabavka UI | `pogledi/NovaNabavka.php`, `NabavkeLista.php`, `delovi/desnoNabavkeLista.php` | Polja dokumenta, igre select, tekstovi | TOP-02–05, FUN-02/07 |
| Nabavka akcija unos | `kontroler/akcije/nabavkaSnimi.php` | 1A dobavljač, VAL domen, BrojNaloga, evidentirao, bez duplikat-zabrane | VAL-04–16, DEF-06–08 |
| Meni | `delovi/menilevoadmin.php` | Linkovi i nazivi za igre i naloge | NF-03, TOP-01/02 |
| README | `README.md` | Opis teme nabavke igara | NF-04 |

---

### 2.3 EXTEND

| Modul | Fajlovi (novi ili prošireni) | Proširenje | Opravdanje (ID) |
|-------|------------------------------|------------|-----------------|
| CRUD nabavka — izmena | `kontroler/akcije/nabavkaIzmeni.php`, `pogledi/NabavkaIzmeniForm.php`, `delovi/desnoNabavkaIzmeniForm.php`, ruta `izmenaNabavkaForm` | MD edit master+detail | FUN-04, FUN-05 |
| CRUD nabavka — brisanje | `kontroler/akcije/nabavkaObrisi.php`, ruta | DELETE sa confirm | FUN-06 |
| Filter nabavki | `desnoNabavkeLista.php`, `NabavkeController`, `NabavkaModel` | GET filter (broj, datum, dobavljač) | FUN-08 |
| Pojedinačni prikaz | ruta `nabavkaDetalj`, view | Detalj jednog naloga | FUN-09 |
| Štampa naloga | `pogledi/NabavkeStampa.php`, `delovi/desnoStampaNabavke.php`, ruta `stampaNabavke` | Print all | PRINT-01 |
| Filtrirana štampa | isti + filter param | Print filtered | PRINT-02 |
| Parametarska štampa naloga | `pogledi/NabavkaParametarskaStampa.php`, `StampaPodatakaONalogu.php`, rute | Layout po prijavi DOCX | PRINT-03–09 |
| Ruter | `Ruter.php` | Nove case grane za nabavku | FUN-04–09, PRINT-01–03 |
| Validacija komplet | sve forme nabavke + akcije | Client+server komplet | VAL-01–14 |
| Dokumentacija | `docs/seminarski/` (finalno) | 15 sekcija | DOC-01–15 |

---

### 2.4 FIX

| Defekt | Fajlovi | Ispravka | Opravdanje (ID) |
|--------|---------|----------|-----------------|
| DEF-01 Merge naloga | `DBNabavka.php`, `nabavkaSnimi.php` | Ukloniti `PronadjiNabavku`; uvek novi red po BrojNaloga | DB-10, FUN-03 |
| DEF-05 GROUP BY stavke | `NabavkaModel.php` | SELECT pojedinačnih redova; redni broj u prikazu | FUN-09, TOP-04 |
| DEF-06 Zatvoreni dobavljači | `NovaNabavka.php`, `nabavkaSnimi.php` | `<input type="text">`; ukloniti `in_array` listu | VAL-08 |
| DEF-07 Duplikat igre | `nabavkaSnimi.php` | Ukloniti `$provereniISBN` zabranu | VAL-16 |
| DEF-08 Gornji limiti | `nabavkaSnimi.php`, JS | Ukloniti 100/100000; zadržati >0 | VAL-11/12/15 |
| DEF-09 Dead route brisanja | `delovi/desnoKnjigeLista.php`, `Ruter.php` | uskladiti rutu/akciju (posle adapt na igre) | FUN-KAT-01 |
| DEF-10 index.php sesija | `index.php` | Ukloniti `session_destroy` na landing ili odvojiti od prijave | FUN-01 |

---

### 2.5 REMOVE ONLY IF NECESSARY

| Stavka | Uslov uklanjanja | Opravdanje |
|--------|------------------|------------|
| Knjižni seed podaci (Laguna, ISBN knjiga) | Zamenjuju se seed igara pri SQL adaptaciji | DB-02/03 |
| `PronadjiNabavku()` metoda | Posle uklanjanja poziva — dead code | DEF-01 |
| Zatvorena lista dobavljača u PHP/JS | Odmah pri 1A adaptaciji | DEF-06 |

**Ne uklanjati:** SP/VIEW/REST modul kataloga, MVC strukturu, transakcione klase, master-detail JS.

---

## 3. SP / VIEW / REST strategija

| Tehnologija | Odluka | Opravdanje |
|-------------|--------|------------|
| Stored procedure | **DOMAIN ADAPT** na katalog igara (`DodajDrustvenuIgru`) | TECH-05, TECH-SP-01/02 |
| VIEW | **DOMAIN ADAPT** (`svipodacioidrutvenimigrama*`) | TECH-06, TECH-VIEW-01/02 |
| REST | **DOMAIN ADAPT** endpointi `igre`/`igra` | REST-01/03 |
| SP/VIEW/REST nad `nabavka` | **Ne planirati** u ovoj fazi | TECH-SP-VIEW-03 — katalog dokazuje kriterijum; mali churn |

---

## 4. Mapiranje šablona → ciljni domen

```
knjiga              → drustvena_igra
zanr                → kategorija_igre
ISBN                → SifraIgre
Autor               → Proizvodjac
OznakaZanra         → OznakaKategorije
nabavka             → nalog (ista tabela, proširena poljima)
stavka_nabavke      → stavka (FK SifraIgre)
korisnik            → korisnik (KEEP)
```

---

## 5. Rizici adaptacije

| Rizik | Mitigacija |
|-------|------------|
| Preimenovanje tabela lomi FK | Jedan koherentan SQL skript + redosled migracije |
| Global replace knjiga→igra pokvari stringove | Ciljano po fajlovima iz ove tabele |
| Regresija katalog CRUD | Verifikovati FUN-KAT pre nabavka CRUD |
| Parametarska štampa najkritičnija | Milestone 10, vizuelna provera vs DOCX |

---

## 6. Van opsega (namerno)

- Uvođenje PHP frameworka
- Redis, API auth, role-based access izvan šablona
- Automatski SP/VIEW/REST isključivo nad `nabavka`
- Zatvorena lista dobavljača
- Poslovna zabrana duplikata igre u nalogu
- Proizvoljne gornje granice količine/cene
