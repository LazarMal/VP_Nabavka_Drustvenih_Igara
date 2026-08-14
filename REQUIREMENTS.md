# REQUIREMENTS — Pregled trenutnog stanja

**Projekat:** Evidentiranje reklamacija društvenih igara (VP 2025/26)
**Referenca:** [PROJECT_SPEC.md](PROJECT_SPEC.md)
**Baza:** `reklamacije_drustvenih_igara_vp_2026`

**Statusi:** `IMPLEMENTED` | `VERIFIED` | `MISSING`

---

## Legenda

| Status | Značenje |
|--------|----------|
| IMPLEMENTED | Implementirano u kodu i SQL skripti |
| VERIFIED | Implementirano i runtime provereno |
| MISSING | Još nije urađeno |

---

## NF — Nefunkcionalni

| ID | Zahtev | Status |
|----|--------|--------|
| NF-01 | Baza na srpskom | VERIFIED |
| NF-02 | Kod na srpskom | VERIFIED |
| NF-03 | UI na srpskom | VERIFIED |
| NF-04 | Poslovna veb aplikacija | VERIFIED |
| NF-05 | Programski kod isporuka | IMPLEMENTED |
| NF-06 | Dokumentacija seminarskog rada | MISSING |

---

## TECH — Tehnološki

| ID | Zahtev | Status |
|----|--------|--------|
| TECH-01 | PHP bez frameworka | VERIFIED |
| TECH-02 | Minimum 4 tabele (5 u projektu) | VERIFIED |
| TECH-03 | 3 povezane tabele (celina–deo–šifarnik) | VERIFIED |
| TECH-04 | Nezavisna tabela korisnik | VERIFIED |
| TECH-05 | Stored procedure | VERIFIED |
| TECH-06 | SQL VIEW | VERIFIED |
| TECH-07 | Multipage aplikacija | VERIFIED |
| TECH-08 | Master-detail unos na jednoj formi | VERIFIED |
| TECH-09 | Transakcija pri unosu/izmeni | VERIFIED |
| TECH-10 | Master-detail tabelarni prikaz | VERIFIED |
| TECH-11 | Client-side validacija | VERIFIED |
| TECH-12 | Server-side validacija | VERIFIED |

---

## DB — Baza i domen

| ID | Zahtev | Status |
|----|--------|--------|
| DB-01 | Tabela korisnik | VERIFIED |
| DB-02 | Tabela kategorija_igre | VERIFIED |
| DB-03 | Tabela drustvena_igra | VERIFIED |
| DB-04 | Tabela reklamacija (celina) | VERIFIED |
| DB-05 | Tabela stavka_reklamacije (deo) | VERIFIED |
| DB-06 | Relacija reklamacija 1:N stavka_reklamacije | VERIFIED |
| DB-07 | Relacija drustvena_igra 1:N stavka_reklamacije | VERIFIED |
| DB-08 | Relacija kategorija_igre 1:N drustvena_igra | VERIFIED |
| DB-09 | BrojReklamacije | VERIFIED |
| DB-10 | BrojReklamacije UNIQUE | VERIFIED |
| DB-11 | DatumReklamacije | VERIFIED |
| DB-12 | Dobavljac slobodan tekst | VERIFIED |
| DB-13 | Napomena | VERIFIED |
| DB-14 | ReklamacijuEvidentirao | VERIFIED |
| DB-15 | SifraIgre, Kolicina, Cena, RazlogReklamacije | VERIFIED |
| DB-16 | Ukupno = Kolicina × Cena (izračunato) | VERIFIED |
| DB-17 | Rekapitulacija izračunata | VERIFIED |
| DB-18 | Surrogate PK (IDReklamacije, IDStavkeReklamacije) | VERIFIED |
| DB-19 | Mapiranje kolona šifarnika igara | VERIFIED |

---

## OOP

| ID | Zahtev | Status |
|----|--------|--------|
| OOP-01 | Nasleđivanje repository → Tabela | VERIFIED |
| OOP-02 | Kompozicija ReklamacijaEntitet → ListaStavki | VERIFIED |
| OOP-03 | Asocijacija StavkaReklamacijeEntitet → DrustvenaIgraEntitet | VERIFIED |
| OOP-04 | Repozitorijumi nasleđuju Tabela | VERIFIED |
| OOP-05 | Konekcija i Transakcija bazne klase | VERIFIED |

---

## FUN — Glavni dokument (reklamacija)

| ID | Zahtev | Status |
|----|--------|--------|
| FUN-01 | Login | VERIFIED |
| FUN-02 | Unos master-detail na jednoj formi | VERIFIED |
| FUN-03 | Unos u transakciji | VERIFIED |
| FUN-04 | Izmena master podataka | VERIFIED |
| FUN-05 | Izmena stavki (dodavanje/izmena/brisanje) | VERIFIED |
| FUN-06 | Brisanje zapisnika | VERIFIED |
| FUN-07 | Tabelarni prikaz reklamacija | VERIFIED |
| FUN-08 | Filter reklamacija | VERIFIED |
| FUN-09 | Pojedinačni prikaz sa svim stavkama | VERIFIED |
| FUN-10 | CRUD glavnog dokumenta (ne zamenjen katalogom) | VERIFIED |

---

## FUN-KAT — Šifarnik društvenih igara

| ID | Zahtev | Status |
|----|--------|--------|
| FUN-KAT-01 | Pun CRUD kataloga igara | VERIFIED |
| FUN-KAT-02 | Katalog ne zamenjuje CRUD reklamacija | VERIFIED |

---

## VAL — Validacija

| ID | Zahtev | Status |
|----|--------|--------|
| VAL-01 | Obavezna polja (client + server) | VERIFIED |
| VAL-02 | Tip podatka (client + server) | VERIFIED |
| VAL-03 | Dužina podatka (client + server) | VERIFIED |
| VAL-04 | Domen ispravnih vrednosti | VERIFIED |
| VAL-05 | Jedinstvenost zapisa | VERIFIED |
| VAL-06 | BrojReklamacije obavezno i jedinstveno | VERIFIED |
| VAL-07 | DatumReklamacije validan | VERIFIED |
| VAL-08 | Dobavljac slobodan tekst | VERIFIED |
| VAL-09 | Napomena dužina | VERIFIED |
| VAL-10 | SifraIgre postoji u katalogu | VERIFIED |
| VAL-11 | Kolicina > 0 (ceo broj) | VERIFIED |
| VAL-12 | Cena > 0 (decimalna) | VERIFIED |
| VAL-13 | Minimum jedna stavka | VERIFIED |
| VAL-14 | ReklamacijuEvidentirao iz sesije | VERIFIED |
| VAL-15 | Bez proizvoljnih gornjih limita | VERIFIED |
| VAL-16 | Bez zabrane duplikata iste igre u zapisniku | VERIFIED |
| VAL-17 | RazlogReklamacije obavezno, max 255 | VERIFIED |

---

## PRINT — Štampa

| ID | Zahtev | Status |
|----|--------|--------|
| PRINT-01 | Štampa svih reklamacija | VERIFIED |
| PRINT-02 | Štampa filtriranih reklamacija | VERIFIED |
| PRINT-03 | Parametarska štampa jednog zapisnika | VERIFIED |
| PRINT-04 | Izgled kao prijavljeni dokument | VERIFIED |
| PRINT-05 | Sekcija PODACI O REKLAMACIJI | VERIFIED |
| PRINT-06 | Sekcija STAVKE REKLAMACIJE (uključujući Razlog reklamacije) | VERIFIED |
| PRINT-07 | REKAPITULACIJA | VERIFIED |
| PRINT-08 | Reklamaciju evidentirao | VERIFIED |
| PRINT-09 | Master-detail u štampi | VERIFIED |

---

## TOP — Registrovani dokument

| ID | Zahtev | Status |
|----|--------|--------|
| TOP-01 | Naziv poslovnog procesa | VERIFIED |
| TOP-02 | Naziv dokumenta (Zapisnik o reklamaciji) | VERIFIED |
| TOP-03 | Master polja | VERIFIED |
| TOP-04 | Detail kolone | VERIFIED |
| TOP-05 | Rekapitulacija | VERIFIED |
| TOP-06 | Reklamaciju evidentirao | VERIFIED |

---

## TECH-SP / TECH-VIEW

| ID | Zahtev | Status |
|----|--------|--------|
| TECH-SP-01 | Stored procedure u upotrebi | VERIFIED |
| TECH-SP-02 | SP DodajDrustvenuIgru | VERIFIED |
| TECH-VIEW-01 | SQL VIEW u upotrebi | VERIFIED |
| TECH-VIEW-02 | VIEW za katalog igara | VERIFIED |
| TECH-SP-VIEW-03 | SP/VIEW nad reklamacijom nije obavezno | IMPLEMENTED |

---

## MVC — Opciono

| ID | Zahtev | Status |
|----|--------|--------|
| MVC-01 | MVC arhitektura | VERIFIED |
| MVC-02 | Odvojeni Model, View, Controller | VERIFIED |
| MVC-03 | Reklamacija u MVC toku (Controller → Model → Repository) | VERIFIED |

---

## REST — Opciono

| ID | Zahtev | Status |
|----|--------|--------|
| REST-01 | REST servis | VERIFIED |
| REST-02 | REST ruter | VERIFIED |
| REST-03 | Endpointi za društvene igre | VERIFIED |

---

## DOC — Dokumentacija seminarskog rada

| ID | Zahtev | Status |
|----|--------|--------|
| DOC-01 … DOC-15 | Sve sekcije seminarskog rada | MISSING |

---

## SUB — Predaja i odbrana

| ID | Zahtev | Status |
|----|--------|--------|
| SUB-01 … SUB-07 | Pravila predaje i odbrane | IMPLEMENTED (procesni zahtev) |

---

## Rezime

| Status | Broj |
|--------|------|
| VERIFIED | 95 |
| IMPLEMENTED | 4 |
| MISSING | 16 (DOC-01–15 + NF-06) |

**Napomena:** Seminarska dokumentacija (DOC-01–15, NF-06) još nije kreirana u repozitorijumu.
