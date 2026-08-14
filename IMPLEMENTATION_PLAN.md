# IMPLEMENTATION_PLAN — Status implementacije

**Projekat:** Evidentiranje reklamacija društvenih igara
**Referenca:** [PROJECT_SPEC.md](PROJECT_SPEC.md), [REQUIREMENTS.md](REQUIREMENTS.md), [ADAPTATION_PLAN.md](ADAPTATION_PLAN.md)

---

## Legenda statusa

| Oznaka | Značenje |
|--------|----------|
| ✅ | Završeno i provereno |
| ⏳ | U toku / sledeća faza |
| — | Nije započeto |

---

## Milestone 1 — Baza podataka i domen

| Stavka | Status |
|--------|--------|
| 5 tabela: korisnik, kategorija_igre, drustvena_igra, reklamacija, stavka_reklamacije | ✅ |
| FK relacije celina–deo–šifarnik | ✅ |
| UNIQUE BrojReklamacije | ✅ |
| Kolone BrojReklamacije, ReklamacijuEvidentirao, RazlogReklamacije | ✅ |
| Baza: reklamacije_drustvenih_igara_vp_2026 | ✅ |

---

## Milestone 2 — Login

| Stavka | Status |
|--------|--------|
| Prijava korisnika i sesija | ✅ |
| Zaštita poslovnih stranica | ✅ |
| Odjava | ✅ |

---

## Milestone 3 — Šifarnik društvenih igara

| Stavka | Status |
|--------|--------|
| CRUD društvenih igara | ✅ |
| CRUD kategorija igara | ✅ |
| Unos igre preko stored procedure | ✅ |
| Pregled kataloga preko VIEW-ova | ✅ |
| Upload slike igre | ✅ |

---

## Milestone 4 — Unos reklamacije (master-detail)

| Stavka | Status |
|--------|--------|
| Forma master + stavke na jednoj stranici | ✅ |
| Transakcioni unos (reklamacija + stavke) | ✅ |
| RazlogReklamacije na stavkama | ✅ |
| Validacije unosa | ✅ |

---

## Milestone 5 — Lista i filter

| Stavka | Status |
|--------|--------|
| Tabelarni prikaz reklamacija | ✅ |
| Filter po broju, datumu, dobavljaču | ✅ |
| Prikaz stavki u listi | ✅ |

---

## Milestone 6 — Detaljni prikaz

| Stavka | Status |
|--------|--------|
| Pojedinačni prikaz zapisnika | ✅ |
| Sve stavke sa Razlog reklamacije | ✅ |
| Rekapitulacija | ✅ |

---

## Milestone 7 — Izmena reklamacije

| Stavka | Status |
|--------|--------|
| Izmena master podataka | ✅ |
| Izmena postojećih stavki | ✅ |
| Dodavanje novih stavki | ✅ |
| Brisanje uklonjenih stavki | ✅ |
| Transakciona izmena | ✅ |

---

## Milestone 8 — Brisanje

| Stavka | Status |
|--------|--------|
| Brisanje reklamacije sa potvrdom | ✅ |
| CASCADE brisanje stavki | ✅ |

---

## Milestone 9 — Štampa svih / filtriranih

| Stavka | Status |
|--------|--------|
| Štampa svih reklamacija | ✅ |
| Štampa filtriranih reklamacija | ✅ |

---

## Milestone 10 — Parametarska štampa zapisnika

| Stavka | Status |
|--------|--------|
| Parametarska štampa jednog zapisnika | ✅ |
| Layout po prijavljenom dokumentu | ✅ |
| PODACI O REKLAMACIJI, STAVKE, REKAPITULACIJA | ✅ |

---

## Milestone 11 — Validacije

| Stavka | Status |
|--------|--------|
| Client-side validacije (HTML/JS) | ✅ |
| Server-side validacije (PHP) | ✅ |
| Jedinstvenost BrojReklamacije i SifraIgre | ✅ |
| RazlogReklamacije validacija | ✅ |

---

## Milestone 12 — Stored procedure i VIEW

| Stavka | Status |
|--------|--------|
| SP DodajDrustvenuIgru | ✅ |
| VIEW-ovi za katalog igara | ✅ |
| Korišćenje iz PHP aplikacije | ✅ |

---

## Milestone 13 — MVC

| Stavka | Status |
|--------|--------|
| Controller → Model → Repository → View | ✅ |
| READ tok kroz repository sloj | ✅ |
| WRITE tok kroz repository sloj | ✅ |

---

## Milestone 14 — REST servis

| Stavka | Status |
|--------|--------|
| REST ruter | ✅ |
| Endpointi za društvene igre | ✅ |

---

## Milestone 15 — End-to-end integracija

| Stavka | Status |
|--------|--------|
| Login → katalog → unos → lista → detalj → izmena → brisanje → štampa | ✅ |

---

## Milestone 16 — Dokumentacija seminarskog rada

| Stavka | Status |
|--------|--------|
| Sekcije DOC-01–15 | ⏳ |
| Dijagrami, screenshoti, opis koda | ⏳ |

---

## Milestone 17 — Finalna provera usklađenosti

| Stavka | Status |
|--------|--------|
| Provera svih zahteva iz PROJECT_SPEC | ⏳ |
| Ažuriranje REQUIREMENTS statusa | ✅ |

---

## Milestone 18 — Priprema za odbranu

| Stavka | Status |
|--------|--------|
| Mock odbrana: SP, VIEW, REST, OOP, parametarska štampa | ⏳ |
| Predaja na GitHub i učionicu | ⏳ |

---

## Dependency graph

```mermaid
flowchart TD
  M1[M1 Baza] --> M2[M2 Login]
  M1 --> M3[M3 Šifarnik]
  M2 --> M4[M4 Unos reklamacije]
  M3 --> M4
  M4 --> M5[M5 Lista+filter]
  M5 --> M6[M6 Detalj]
  M4 --> M7[M7 Izmena]
  M6 --> M7
  M5 --> M8[M8 Brisanje]
  M5 --> M9[M9 Štampa]
  M6 --> M10[M10 Parametarska]
  M4 --> M11[M11 Validacija]
  M1 --> M12[M12 SP/VIEW]
  M3 --> M12
  M4 --> M13[M13 MVC]
  M3 --> M14[M14 REST]
  M11 --> M15[M15 E2E]
  M12 --> M15
  M13 --> M15
  M14 --> M15
  M15 --> M16[M16 Dokumentacija]
  M16 --> M17[M17 Provera]
  M17 --> M18[M18 Odbrana]
```

---

## Sledeći koraci

1. Kreirati seminarsku dokumentaciju (M16)
2. Finalna provera usklađenosti (M17)
3. Priprema i predaja za odbranu (M18)
