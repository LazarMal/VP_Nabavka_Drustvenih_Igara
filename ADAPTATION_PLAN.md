# ADAPTATION_PLAN — Arhitektura i status projekta

**Projekat:** Evidentiranje reklamacija društvenih igara
**Referenca:** [PROJECT_SPEC.md](PROJECT_SPEC.md), [REQUIREMENTS.md](REQUIREMENTS.md)

---

## 1. Poslovni domen

| Stavka | Vrednost |
|--------|----------|
| Proces | Evidentiranje reklamacija neispravnih društvenih igara dobavljaču |
| Dokument | Zapisnik o reklamaciji društvenih igara |
| Baza | `reklamacije_drustvenih_igara_vp_2026` |
| Celina | `reklamacija` |
| Deo | `stavka_reklamacije` |
| Šifarnici | `drustvena_igra`, `kategorija_igre` |
| Nezavisna tabela | `korisnik` |

---

## 2. Relacioni model

```
reklamacija (celina)
├── IDReklamacije
├── BrojReklamacije (UNIQUE)
├── DatumReklamacije
├── Dobavljac
├── Napomena
└── ReklamacijuEvidentirao

stavka_reklamacije (deo)
├── IDStavkeReklamacije
├── IDReklamacije (FK)
├── SifraIgre (FK → drustvena_igra)
├── Kolicina
├── Cena
└── RazlogReklamacije

drustvena_igra (šifarnik)
kategorija_igre (šifarnik)
korisnik (nezavisna)
```

---

## 3. OOP veze

| Veza | Implementacija |
|------|----------------|
| **Nasleđivanje** | `DBReklamacija`, `DBStavkaReklamacije`, `DBDrustvenaIgra` → `Tabela` |
| **Kompozicija** | `ReklamacijaEntitet` sadrži `ListaStavki` (`StavkaReklamacijeEntitet`) |
| **Asocijacija** | `StavkaReklamacijeEntitet` sadrži `DrustvenaIgraEntitet` |

---

## 4. Slojevi aplikacije

| Sloj | Lokacija | Uloga |
|------|----------|-------|
| Ruter | `Ruter.php` | Rutiranje stranica i sesija |
| Controller | `kontroler/stranice/`, `kontroler/akcije/` | Koordinacija zahteva |
| Model | `model/servisi/`, `model/entiteti/` | Poslovna logika i transformacija |
| Repository | `repozitorijumi/` | SQL pristup bazi |
| View | `pogledi/`, `delovi/` | Prikaz korisničkom interfejsu |
| Tehnološke klase | `tehnoloskeKlase/` | Konekcija, Tabela, Transakcija |
| REST | `api/` | JSON servis za društvene igre |

---

## 5. Status modula

| Modul | Status |
|-------|--------|
| Baza podataka (5 tabela, FK, UNIQUE) | ✅ Završeno |
| Stored procedure `DodajDrustvenuIgru` | ✅ Završeno |
| SQL VIEW-ovi za katalog igara | ✅ Završeno |
| Login i sesije | ✅ Završeno |
| Šifarnik društvenih igara (CRUD) | ✅ Završeno |
| CRUD reklamacija | ✅ Završeno |
| Master-detail create (transakcija) | ✅ Završeno |
| Master-detail edit (transakcija) | ✅ Završeno |
| Lista i filter reklamacija | ✅ Završeno |
| Detaljni prikaz zapisnika | ✅ Završeno |
| Brisanje reklamacije | ✅ Završeno |
| Tri vrste štampe | ✅ Završeno |
| Validacije (client + server) | ✅ Završeno |
| REST servis za igre | ✅ Završeno |
| MVC tok (Controller → Model → Repository) | ✅ Završeno |
| RazlogReklamacije kroz sve slojeve | ✅ Završeno |
| Seminarska dokumentacija | ⏳ Sledeća faza |

---

## 6. Poslovna pravila (implementirana)

- `BrojReklamacije` je jedinstven i obavezan
- `Dobavljac` je slobodan tekst
- `RazlogReklamacije` je obavezan na svakoj stavci (max 255)
- `Kolicina` i `Cena` moraju biti > 0
- Zapisnik mora imati najmanje jednu stavku
- `ReklamacijuEvidentirao` se popunjava iz sesije
- `Ukupno` = `Kolicina × Cena` (izračunato, ne persistirano)
- Nema zabrane duplikata iste igre u jednom zapisniku
- Nema proizvoljnih gornjih limita za količinu i cenu

---

## 7. Van opsega

- PHP framework
- SP/VIEW/REST isključivo nad `reklamacija`
- Zatvorena lista dobavljača
- Dodatna poslovna polja van specifikacije
