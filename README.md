# Evidentiranje reklamacija društvenih igara

PHP veb aplikacija bez framework-a za poslovni proces **evidentiranja reklamacija neispravnih društvenih igara dobavljaču**.

Poslovni dokument: **Zapisnik o reklamaciji društvenih igara**.

Baza podataka: `reklamacije_drustvenih_igara_vp_2026`

## Funkcionalnosti

- prijava korisnika i rad sa sesijom
- šifarnik društvenih igara i kategorija igara
- unos društvene igre (regularan unos i unos preko stored procedure)
- pregled kataloga igara preko SQL VIEW-ova
- CRUD nad reklamacijama (glavni poslovni dokument)
- master-detail unos reklamacije sa stavkama na jednoj formi
- polje **Razlog reklamacije** na svakoj stavci
- transakcioni create i edit (master + stavke)
- lista reklamacija sa filterom
- detaljni prikaz zapisnika
- brisanje reklamacije
- štampa svih reklamacija
- štampa filtriranih reklamacija
- parametarska štampa jednog zapisnika
- REST servis za društvene igre
- MVC organizacija (Controller → Model → Repository → View)
- client-side i PHP server-side validacije

## Tehnologije

- PHP (bez framework-a)
- MySQL
- HTML, CSS, JavaScript
- XAMPP / phpMyAdmin

## Struktura domena

| Tabela | Uloga |
|--------|-------|
| `reklamacija` | glavni dokument (celina) |
| `stavka_reklamacije` | stavke zapisnika (deo) |
| `drustvena_igra` | šifarnik igara |
| `kategorija_igre` | šifarnik kategorija |
| `korisnik` | nezavisna tabela za login |
