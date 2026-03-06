# Plattform-Handbuch für Eltern-Administratoren

Steinerschule Langnau - Elternaktivitäten-Plattform

**Letzte Aktualisierung:** März 2026

Dieses Handbuch erklärt die Plattform aus beiden Perspektiven: Was Eltern sehen und tun, und was Administratoren im Hintergrund verwalten. So verstehen Sie als Admin genau, wie sich Ihre Aktionen auf die Eltern auswirken.

> **Tipp:** Für die technische Admin-Panel-Dokumentation (alle Felder, Buttons, Filteroptionen) siehe das separate [Admin-Handbuch](ADMIN_HANDBUCH.md).

---

## Inhaltsverzeichnis

1. [Die Plattform auf einen Blick](#1-die-plattform-auf-einen-blick)
2. [Was Eltern sehen: Der erste Besuch](#2-was-eltern-sehen-der-erste-besuch)
3. [Registrierung und Anmeldung](#3-registrierung-und-anmeldung)
4. [Die Pinnwand: Helfer gesucht](#4-die-pinnwand-helfer-gesucht)
5. [Elternaktivitäten: Arbeitsgruppen und Gruppen](#5-elternaktivitäten-arbeitsgruppen-und-gruppen)
6. [Der Schichtkalender](#6-der-schichtkalender)
7. [Der Schulkalender](#7-der-schulkalender)
8. [Forum und Diskussionen](#8-forum-und-diskussionen)
9. [Profil und Meine Schichten](#9-profil-und-meine-schichten)
10. [Ankündigungen: Nachrichten an alle](#10-ankündigungen-nachrichten-an-alle)
11. [Die Verbindung: Was Admin tut → Was Eltern sehen](#11-die-verbindung-was-admin-tut--was-eltern-sehen)
12. [Häufige Fragen](#12-häufige-fragen)

---

## 1. Die Plattform auf einen Blick

### Wofür ist die Plattform da?

Die Plattform löst ein konkretes Problem: Bisher wurden Helfer für Schulanlässe über lange E-Mail-Ketten und WhatsApp-Gruppen gesucht. Das führte dazu, dass:
- Nachrichten untergingen
- Immer die gleichen Eltern sich meldeten
- Organisatoren nicht wussten, ob genug Helfer da sind
- Fragen wie "Braucht ihr noch Hilfe beim Aufbau?" mehrfach gestellt wurden

Die Plattform bietet stattdessen:
- **Live-Helferzähler** - "3 von 5 Helfern gefunden" auf einen Blick
- **Ein-Klick-Anmeldung** - Kein kompliziertes Verfahren
- **Automatische Kontaktlisten** - Organisatoren sehen sofort, wer hilft
- **Datenschutz** - Helfernamen sind nur für angemeldete Nutzer sichtbar

### Die vier Hauptbereiche

| Bereich | URL | Was steht dort? |
|---|---|---|
| **Pinnwand** | `/pinnwand` | Aktuelle Hilfegesuche mit Schichtanmeldung |
| **Elternaktivitäten** | `/elternaktivitaeten` | Verzeichnis aller Arbeitsgruppen |
| **Schichtkalender** | `/kalender` | Monatsübersicht aller Helferschichten |
| **Schulkalender** | `/schulkalender` | Offizielle Schultermine und Ferien |

### Wer sieht was?

| Inhalt | Ohne Anmeldung | Mit Anmeldung | Admin |
|---|---|---|---|
| Pinnwand-Einträge lesen | Ja | Ja | Ja |
| Helfernamen sehen | Nein | Ja | Ja |
| Für Schichten anmelden | Nein | Ja | Ja |
| Forum lesen | Ja | Ja | Ja |
| Forum schreiben | Nein | Ja | Ja |
| Kalender ansehen | Ja | Ja | Ja |
| Aktivitäten ansehen | Ja | Ja | Ja |
| Kontaktdaten sehen | Teilweise | Ja | Ja |
| Admin-Panel | Nein | Nein | Ja |

---

## 2. Was Eltern sehen: Der erste Besuch

### Die Startseite

Wenn ein Elternteil die Webseite zum ersten Mal besucht, wird er direkt zur **Pinnwand** (`/pinnwand`) weitergeleitet. Das ist Absicht - die Pinnwand zeigt die aktuellsten und dringendsten Hilfegesuche.

### Die Navigation

Oben auf jeder Seite sehen Eltern diese Menüpunkte:

**Nicht angemeldet:**
```
[Logo] Elternaktivitäten    Pinnwand | Elternaktivitäten | Kalender | Schulkalender    [Anmelden] [Registrieren]
```

**Angemeldet:**
```
[Logo] Elternaktivitäten    Pinnwand | Elternaktivitäten | Kalender | Schulkalender    [Max Mustermann ▾]
                                                                                         → Profil bearbeiten
                                                                                         → Meine Schichten
                                                                                         → Admin Panel (nur Admins)
                                                                                         → Abmelden
```

Auf dem Handy wird die Navigation zu einem Hamburger-Menü (☰) zusammengeklappt.

### Die Fusszeile

Unten auf jeder Seite stehen Links zu:
- **Datenschutz** (`/datenschutz`)
- **Impressum** (`/impressum`)
- **Kontakt** (`/kontakt`)
- Copyright-Hinweis: "© 2026 Buğra Ergin. Entwickelt für die Elterngemeinschaft."

---

## 3. Registrierung und Anmeldung

### Registrierung: So melden sich Eltern an

**URL:** `/register`

Ein Elternteil muss sich registrieren, um Schichten zu übernehmen oder im Forum zu schreiben. Das Formular ist bewusst einfach gehalten:

**Schritt 1:** Klick auf "Registrieren" (oben rechts)

**Schritt 2:** Formular ausfüllen
- **Name** - Vor- und Nachname
- **E-Mail-Adresse** - Wird für die Anmeldung und Kontakt verwendet
- **Passwort** - Mindestens 8 Zeichen (bewusst einfach, keine komplizierten Regeln)
- **Passwort bestätigen** - Nochmals eingeben

**Schritt 3:** "Registrieren" klicken

Das Formular hat einen unsichtbaren Spam-Schutz (Honeypot), der automatische Bot-Registrierungen verhindert. Eltern merken davon nichts.

> **Als Admin wissen:** Neue Registrierungen erscheinen sofort in der Benutzerverwaltung. Neue Benutzer haben standardmässig keine Admin-Rechte. Sie können sofort Schichten übernehmen und im Forum schreiben.

### Anmeldung: So loggen sich Eltern ein

**URL:** `/login`

**Formular:**
- **E-Mail-Adresse**
- **Passwort**
- **"Angemeldet bleiben"** - Checkbox, damit man nicht jedes Mal das Passwort eingeben muss
- **"Passwort vergessen?"** - Link zur Passwort-Zurücksetzung per E-Mail

**Sicherheit:** Nach 5 fehlgeschlagenen Anmeldeversuchen wird die Anmeldung für 1 Minute gesperrt. Das schützt vor automatisierten Angriffen, ohne dass Eltern sich Sorgen machen müssen.

### Passwort vergessen

Eltern können ihr Passwort über "Passwort vergessen?" zurücksetzen:
1. E-Mail-Adresse eingeben
2. Link per E-Mail erhalten
3. Neues Passwort setzen (mindestens 8 Zeichen)

---

## 4. Die Pinnwand: Helfer gesucht

Die Pinnwand ist das Herzstück der Plattform. Hier suchen Organisatoren nach Helfern.

### Was Eltern auf der Pinnwand sehen

**URL:** `/pinnwand`

#### Die Übersichtsseite

Ganz oben steht ein blauer Infokasten:
> "Dringende Hilfe gesucht! Hier posten die Elternaktivitäten wenn sie besondere Unterstützung brauchen."

Darunter sind **Kategorie-Filter** als farbige Buttons:
- Alle Kategorien
- Anlass (blau)
- Haus & Umgebung Taskforces (grün)
- Produktion (gelb)
- Organisation (lila)
- Verkauf (rosa)

Jeder Pinnwand-Eintrag wird als Karte dargestellt:

```
┌──────────────────────────────────────────────────────┐
│ 🔴 Dringend    Anlass                                │
│                                                      │
│ Weihnachtsbazar - Helfer gesucht                     │
│                                                      │
│ 📅 15.12.2026, 10:00 - 18:00 Uhr                    │
│ 📍 Grosse Halle                                      │
│                                                      │
│ Für unseren Weihnachtsbazar suchen wir noch          │
│ fleissige Helfer für verschiedene Stände...           │
│                                                      │
│ von Maria Müller                                     │
│ 💬 3 Diskussionsbeiträge                             │
│                                                      │
│ ████████░░░░  3/5 angemeldet          Bevorstehend   │
└──────────────────────────────────────────────────────┘
```

**Die Statusanzeigen:**
- **Bevorstehend** - Liegt in der Zukunft
- **Heute** - Findet heute statt
- **Laufend** - Hat bereits begonnen

**Die Prioritätslabels** (nur von Super Admins vergeben):
- 🔴 **Dringend** - Sofortige Hilfe nötig
- 🟡 **Wichtig** - Bitte bald melden
- ⚡ **Last Minute** - Kurzfristig
- ⭐ **Featured** - Hervorgehoben

#### Die Detailseite eines Eintrags

**URL:** `/pinnwand/{slug}` (z.B. `/pinnwand/weihnachtsbazar-helfer-gesucht-a1b2c3`)

Wenn ein Elternteil auf einen Eintrag klickt, sieht er:

1. **Die Beschreibung** - Vollständiger Text des Hilfegesuchs
2. **Kontaktinformationen** - Name des Organisators, E-Mail und Telefon (mit "Anzeigen"-Buttons für Datenschutz)
3. **Zwei Tabs** (falls Forum und Schichten aktiviert):
   - **Diskussion** - Forum zum Fragen stellen
   - **Schichten** - Verfügbare Helferschichten

### Schichtanmeldung: So melden sich Eltern als Helfer

Die Schichtanmeldung ist der wichtigste Vorgang auf der Plattform. So funktioniert es aus Elternsicht:

#### Voraussetzung
- Man muss angemeldet (eingeloggt) sein
- Ohne Anmeldung sieht man einen gelben Hinweis: "Melden Sie sich an, um sich für Schichten anzumelden"

#### Der Anmeldeprozess

Auf der Detailseite eines Pinnwand-Eintrags sieht ein angemeldetes Elternteil die Schichten:

```
┌──────────────────────────────────────────────────────┐
│ Aufbau                                               │
│ Samstag, 15.12.2026, 08:00 - 10:00 Uhr              │
│                                                      │
│ ████████░░░░  3/5 angemeldet                         │
│                                                      │
│ Angemeldete: Anna M., Peter K., Lisa S.              │
│ + 1 Person bereits angemeldet (offline)              │
│                                                      │
│                              [ Anmelden ]            │
└──────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────┐
│ Cafeteria                                            │
│ Samstag, 15.12.2026, 10:00 - 14:00 Uhr              │
│                                                      │
│ ████████████  5/5 angemeldet                         │
│                                                      │
│ Angemeldete: Anna M., Peter K., ...                  │
│                                                      │
│                              [ Voll besetzt ]        │
└──────────────────────────────────────────────────────┘
```

**Ablauf:**
1. Elternteil klickt auf **"Anmelden"** bei der gewünschten Schicht
2. Die Anmeldung erfolgt sofort (kein Bestätigungsdialog)
3. Der Helferzähler aktualisiert sich live: "3/5" wird zu "4/5"
4. Der eigene Name erscheint in der Helferliste
5. Der Button wechselt zu **"Abmelden"** (rot)

**Abmelden:**
1. Klick auf den roten **"Abmelden"**-Button
2. Die Abmeldung erfolgt sofort
3. Der Zähler geht zurück

**Wichtig für Eltern zu wissen:**
- Helfernamen sind nur für angemeldete Nutzer sichtbar (Datenschutz)
- "Offline"-Anmeldungen sind Personen, die der Organisator manuell eingetragen hat (z.B. per Telefon zugesagt)
- Wenn eine Schicht "Voll besetzt" ist, kann man sich nicht mehr anmelden

> **Als Admin wissen:** Die Anmeldung läuft über die API (`POST /api/shifts/{shift}/volunteers`). Im Admin-Panel sehen Sie die Anmeldungen unter Pinnwand → Eintrag bearbeiten → Tab "Schichten". Dort sehen Sie:
> - **Benötigt** - Wie viele Helfer gebraucht werden
> - **Offline-Zusagen** - Manuell eingetragene Helfer
> - **Online-Anmeldungen** - Über die Plattform angemeldete Helfer
> - **Total** - Summe aus Offline + Online (grün = genug, orange = noch Plätze frei, rot = niemand)

### Der magische Bearbeitungslink

Organisatoren, die kein Admin-Konto haben, können ihren Pinnwand-Eintrag trotzdem bearbeiten. Dafür gibt es einen speziellen Link:

**Format:** `/pinnwand/{slug}/edit?token={geheimer-token}`

Dieser Link wird beim Erstellen des Eintrags generiert und an den Organisator geschickt. Damit kann er:
- Titel und Beschreibung ändern
- Datum und Ort anpassen
- Status ändern (veröffentlicht/archiviert)
- Forum und Schichten ein-/ausschalten
- **Forumbeiträge moderieren** - Einzelne Beiträge und Kommentare verstecken oder wieder anzeigen

> **Als Admin wissen:** Diese magischen Links werden vom System generiert. Sie finden den Link am Ende der Bearbeitungsseite. Falls ein Organisator seinen Link verliert, können Sie den Eintrag direkt im Admin-Panel bearbeiten.

---

## 5. Elternaktivitäten: Arbeitsgruppen und Gruppen

### Was Eltern sehen

**URL:** `/elternaktivitaeten`

Die Seite zeigt alle Arbeitsgruppen und Elterninitiativen als Verzeichnis. Oben steht:
> "Neue Helfer immer willkommen! Alle Arbeitsgruppen freuen sich über Verstärkung."

Die gleichen Kategorie-Filter wie bei der Pinnwand sind verfügbar, plus zusätzlich:
- Pädagogik (indigo)
- Kommunikation (türkis)

Jede Aktivität wird als Karte angezeigt:

```
┌──────────────────────────────────────────────────────┐
│ Anlass                                               │
│                                                      │
│ Chor                                                 │
│                                                      │
│ Gemeinsames Singen für alle                          │
│ Interessierten. Notenkenntnisse sind                 │
│ nicht erforderlich.                                  │
│                                                      │
│ 👤 Maria Müller                                      │
│ 🕐 Jeden Dienstag, 20:00 Uhr                        │
│ 📍 Musikzimmer                                       │
│ 💬 5 Forumsbeiträge                                  │
└──────────────────────────────────────────────────────┘
```

### Die Detailseite einer Aktivität

**URL:** `/elternaktivitaeten/{slug}`

Auf der Detailseite sehen Eltern:
- Vollständige Beschreibung der Aktivität
- Treffzeiten und -ort
- Kontaktinformationen (mit "Anzeigen"-Buttons)
- Forum (falls aktiviert) - zum Fragen stellen und Austausch

> **Als Admin wissen:** Aktivitäten werden im Admin-Panel unter "Elternaktivitäten" verwaltet. Wenn Sie eine Aktivität erstellen und als "aktiv" markieren, erscheint sie sofort auf der öffentlichen Seite. Wenn Sie "is_active" deaktivieren, verschwindet sie. Das Forum kann pro Aktivität ein- oder ausgeschaltet werden ("has_forum").

---

## 6. Der Schichtkalender

### Was Eltern sehen

**URL:** `/kalender`

Der Kalender zeigt eine Monatsübersicht aller Helferschichten aus der Pinnwand.

```
                    ◀  Januar 2027  ▶

    Mo    Di    Mi    Do    Fr    Sa    So
  ┌─────┬─────┬─────┬─────┬─────┬─────┬─────┐
  │     │     │     │  1  │  2  │  3  │  4  │
  │     │     │     │     │     │ ██  │     │
  ├─────┼─────┼─────┼─────┼─────┼─────┼─────┤
  │  5  │  6  │  7  │  8  │  9  │ 10  │ 11  │
  │     │     │ ██  │     │     │     │     │
  ├─────┼─────┼─────┼─────┼─────┼─────┼─────┤
  │ ... │     │     │     │     │     │     │
  └─────┴─────┴─────┴─────┴─────┴─────┴─────┘
```

- Farbige Balken zeigen Aktivitäten an verschiedenen Tagen
- Ein Klick auf einen Balken führt zum Pinnwand-Eintrag
- Monatswechsel mit Pfeiltasten (ohne Seitenneuladung)
- Auf dem Handy: Wischgeste nach links/rechts für Monatswechsel

Unter dem Kalender steht eine **Liste aller Termine des Monats** mit Details:
- Datum und Wochentag
- Name der Aktivität (verlinkt)
- Schichtdetails: Aufgabe, Zeit, Anzahl benötigter Helfer
- Mehrtägige Aktivitäten (z.B. Produktion) werden gesondert angezeigt

> **Als Admin wissen:** Im Kalender erscheinen nur Schichten von veröffentlichten Pinnwand-Einträgen, bei denen "Im Kalender anzeigen" aktiviert ist. Die Helferzähler im Kalender zeigen die gleichen Zahlen wie auf der Pinnwand-Detailseite.

---

## 7. Der Schulkalender

### Was Eltern sehen

**URL:** `/schulkalender`

Der Schulkalender zeigt offizielle Schultermine: Feste, Aufführungen, Konferenzen, Ferien.

Die Darstellung ist ähnlich wie beim Schichtkalender, aber mit anderen Farbcodes:
- 🔴 **Fest** (Festival) - Rot
- 🔵 **Treffen** (Meeting) - Blau
- 🟣 **Aufführung** (Performance) - Lila
- ⚪ **Ferien** (Holiday) - Grau
- 🟢 **Sport** - Grün
- 🟡 **Ausflug** (Excursion) - Gelb

Unter dem Kalender steht die Liste aller Termine des Monats mit:
- Veranstaltungstyp (farbig)
- Titel
- Datum (oder Datumsbereich bei mehrtägigen Events)
- Beschreibung (falls vorhanden)

> **Als Admin wissen:** Schulkalender-Einträge werden ausschliesslich von Super Admins verwaltet (Administration → Schulkalender). Einträge können auch per ICS-Datei importiert werden - nützlich, wenn die Schule bereits einen digitalen Kalender führt.

---

## 8. Forum und Diskussionen

### Wo gibt es Foren?

Foren gibt es an zwei Stellen:
1. **Bei Pinnwand-Einträgen** - Für Fragen rund um ein Hilfegesuch
2. **Bei Elternaktivitäten** - Für allgemeinen Austausch innerhalb einer Gruppe

Beide funktionieren gleich, nur der Kontext ist anders.

### Was Eltern im Forum sehen und tun können

#### Ohne Anmeldung
- Forumbeiträge und Kommentare lesen: **Ja**
- Selbst schreiben: **Nein** (gelber Hinweis: "Melden Sie sich an, um an der Diskussion teilzunehmen")

#### Mit Anmeldung

**Einen Beitrag schreiben:**
1. Im Textfeld "Ihre Nachricht..." den Text eingeben (max. 2000 Zeichen)
2. "Beitrag veröffentlichen" klicken
3. Der Beitrag erscheint sofort

**Auf einen Beitrag antworten (Kommentar):**
1. Unter einem Beitrag auf "Kommentieren" klicken
2. Textfeld öffnet sich
3. Kommentar eingeben und absenden
4. Kommentare werden eingerückt unter dem Beitrag angezeigt

**Eigene Beiträge löschen:**
- Beim eigenen Beitrag/Kommentar erscheint ein Lösch-Button
- Admins können alle Beiträge löschen

```
┌──────────────────────────────────────────────────────┐
│ Anna Müller                           vor 2 Stunden  │
│                                                      │
│ Gibt es am Samstag auch eine Kinderbetreuung?        │
│ Ich könnte helfen, aber nur wenn meine Kinder        │
│ beschäftigt sind.                                    │
│                                                      │
│ [Kommentieren]                              [🗑️]     │
│                                                      │
│   ┌──────────────────────────────────────────────┐   │
│   │ Peter Keller                 vor 1 Stunde    │   │
│   │                                              │   │
│   │ Ja, wir haben eine Kinderecke geplant!       │   │
│   └──────────────────────────────────────────────┘   │
└──────────────────────────────────────────────────────┘
```

> **Als Admin wissen:** Forumbeiträge und Kommentare erscheinen im Admin-Panel unter "Kommunikation → Forumbeiträge" bzw. "Kommunikation → Antworten". Dort können Sie:
> - Beiträge löschen (mit Angabe eines Grundes: Spam, Unangemessen, Benutzeranfrage, Duplikat)
> - Gelöschte Beiträge wiederherstellen
> - Super Admins können Beiträge endgültig löschen (nicht wiederherstellbar)
>
> Organisatoren mit einem magischen Bearbeitungslink können Beiträge in ihrem eigenen Pinnwand-Eintrag verstecken und wieder anzeigen - ohne Admin-Rechte.

---

## 9. Profil und Meine Schichten

### Profil bearbeiten

**URL:** `/profile`

Angemeldete Eltern können ihr Profil bearbeiten:

**Persönliche Informationen:**
- **Name** - Kann geändert werden
- **E-Mail-Adresse** - Kann nicht geändert werden (ausgegraut), da sie zur Anmeldung verwendet wird
- **Telefonnummer** - Optional, z.B. "+41 79 123 45 67"
- **Bemerkungen** - Optionales Freitextfeld (max. 200 Zeichen), z.B. "Verfügbar nur am Wochenende" oder "Kann gut kochen"

**Passwort ändern:**
- Aktuelles Passwort eingeben
- Neues Passwort eingeben (mindestens 8 Zeichen)
- Neues Passwort bestätigen

### Öffentliches Profil

**URL:** `/profile/{benutzer}`

Andere angemeldete Nutzer können ein vereinfachtes Profil sehen:
- Name
- Admin-/Super-Admin-Badge (falls zutreffend)
- Kontaktinformationen (E-Mail, Telefon - mit "Anzeigen"-Button)
- Bemerkungen (falls vorhanden)
- Mitglied seit: Datum

### Meine Schichten

**URL:** `/my-shifts`

Hier sehen angemeldete Eltern alle Schichten, für die sie sich angemeldet haben:

**Bevorstehende Schichten:**
- Name der Aktivität (verlinkt zur Pinnwand)
- Aufgabe/Rolle
- Datum und Uhrzeit
- Ort
- "Abmelden"-Button mit Bestätigungsdialog: "Möchten Sie sich wirklich von dieser Schicht abmelden?"

**Vergangene Schichten:**
- Gleiche Informationen, aber ausgegraut
- Kein Abmelde-Button

**Wenn man sich noch nie angemeldet hat:**
- Grosses Uhr-Symbol
- Text: "Sie haben sich noch für keine Schichten angemeldet."
- Button: "Aktivitäten ansehen" (führt zur Pinnwand)

> **Als Admin wissen:** Sie können die Schichtanmeldungen aller Eltern im Admin-Panel einsehen: Pinnwand → Eintrag bearbeiten → Tab "Schichten". Die Spalte "Online-Anmeldungen" zeigt, wie viele sich über die Plattform angemeldet haben. Über den Button "Helfer exportieren" können Sie eine CSV/XLSX-Datei mit allen Helferdaten herunterladen.

---

## 10. Ankündigungen: Nachrichten an alle

### Was Eltern sehen

Ankündigungen erscheinen als farbige Banner oben auf jeder Seite (nach der Navigation). Sie sind das Erste, was Eltern beim Öffnen der Seite sehen.

**Anzeigetypen:**
- **Info** (grau/blau) - Allgemeine Information
- **Ankündigung** (blau, mit Megaphon) - Wichtige Neuigkeit
- **Erinnerung** (gelb, mit Uhr) - Erinnerung an etwas
- **Dringend** (rot, mit Ausrufezeichen) - Sofort beachten

**Verhalten:**
- Normale Ankündigungen können von Eltern mit "X" weggeklickt werden
- Prioritäts-Ankündigungen werden immer angezeigt (können nicht weggeklickt werden)
- Pro Seite werden maximal 3 normale + alle Prioritäts-Ankündigungen angezeigt
- Wegklicken funktioniert ohne Seitenneuladung

> **Als Admin wissen:** Ankündigungen werden von Super Admins erstellt (Administration → Ankündigungen). Wichtige Einstellungen:
> - **Nachricht**: Maximal 300 Zeichen (erzwingt Kürze)
> - **Priorität**: Wenn aktiviert, kann die Ankündigung nicht weggeklickt werden und läuft nie automatisch ab
> - **Ablaufdatum**: Normale Ankündigungen laufen nach 14 Tagen automatisch ab (anpassbar)
> - **Aktiv**: Kann jederzeit ein-/ausgeschaltet werden
>
> Im Admin-Panel sehen Sie auch, wie oft eine Ankündigung weggeklickt wurde (Spalte "X mal").

---

## 11. Die Verbindung: Was Admin tut → Was Eltern sehen

Dieser Abschnitt erklärt die direkten Auswirkungen von Admin-Aktionen auf die Elternseite.

### Pinnwand-Eintrag erstellen

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Eintrag erstellen (Status: Entwurf)   → Nichts (nicht sichtbar)
Status auf "Veröffentlicht" setzen    → Eintrag erscheint auf der Pinnwand
Schichten hinzufügen                  → Schichtanmeldung erscheint im Eintrag
"Im Kalender anzeigen" aktivieren     → Schichten erscheinen im Schichtkalender
Label "Dringend" setzen               → Roter "Dringend"-Badge beim Eintrag
Forum aktivieren                      → "Diskussion"-Tab erscheint
Status auf "Archiviert" setzen        → Eintrag verschwindet von der Pinnwand
```

### Schichten verwalten

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Neue Schicht (Aufbau, 08-10, 3 Helfer)→ "Aufbau" mit "0/3 angemeldet"
"Offline-Zusagen" auf 1 setzen        → "1/3 angemeldet" + "1 Person offline"
Elternteil meldet sich an             → "2/3 angemeldet" + Name in der Liste
"Benötigt" von 3 auf 5 erhöhen        → "2/5 angemeldet" (Zähler aktualisiert)
Schicht löschen                       → Schicht verschwindet (Anmeldungen weg!)
```

### Aktivität erstellen

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Aktivität erstellen (aktiv)           → Karte auf der Übersichtsseite
Forum aktivieren                      → Forum auf der Detailseite
"Aktiv" deaktivieren                  → Aktivität verschwindet von der Seite
Kategorie setzen                      → Farbiger Badge auf der Karte
```

### Benutzer verwalten

```
Admin-Aktion                          → Was Eltern erleben
─────────────────────────────────────────────────────────────
Benutzer deaktivieren                 → Kann sich nicht mehr anmelden
                                        Alle Daten bleiben erhalten
                                        Name erscheint noch bei Schichten
Benutzer wiederherstellen             → Kann sich wieder anmelden
Benutzer anonymisieren (DSGVO)        → Name wird zu "Anonymer Benutzer 42"
                                        E-Mail, Telefon, Bemerkungen gelöscht
                                        Nicht rückgängig machbar!
```

### Ankündigung erstellen

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Ankündigung erstellen (aktiv)         → Banner oben auf jeder Seite
Typ "Dringend" wählen                 → Roter Banner, nicht wegklickbar
Priorität aktivieren                  → Banner bleibt permanent sichtbar
Ablaufdatum setzen                    → Banner verschwindet automatisch
Ankündigung deaktivieren              → Banner verschwindet sofort
```

### Forum moderieren

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Beitrag löschen (mit Grund)           → Beitrag verschwindet
Beitrag wiederherstellen              → Beitrag erscheint wieder
Endgültig löschen (Super Admin)       → Beitrag für immer weg
```

### Schulkalender verwalten

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Termin erstellen                      → Farbiger Eintrag im Schulkalender
Typ "Ferien" setzen                   → Grauer Balken im Kalender
Enddatum setzen                       → Mehrtägiger Balken im Kalender
Termin löschen                        → Eintrag verschwindet
```

### Jahresreset durchführen

```
Admin-Aktion                          → Was Eltern sehen
─────────────────────────────────────────────────────────────
Jahresreset ausführen                 → Pinnwand wird leer (alles archiviert)
                                        Aktivitäten verschwinden (deaktiviert)
                                        Normale Ankündigungen weg
                                        Alle Forumbeiträge verschwinden
                                        Schulkalender bleibt unverändert
                                        Prioritäts-Ankündigungen bleiben
                                        Benutzerkonten bleiben aktiv
```

---

## 12. Häufige Fragen

### Für Eltern

**"Ich habe mein Passwort vergessen."**
→ Auf der Anmeldeseite gibt es "Passwort vergessen?". Ein Link zum Zurücksetzen wird per E-Mail geschickt.

**"Ich kann mich nicht für eine Schicht anmelden."**
→ Mögliche Gründe:
- Sie sind nicht eingeloggt (erst anmelden)
- Die Schicht ist voll besetzt
- Technisches Problem (Seite neu laden)

**"Ich sehe keine Namen bei den Helfern."**
→ Helfernamen werden nur angemeldeten Nutzern angezeigt. Bitte einloggen.

**"Kann ich meine E-Mail-Adresse ändern?"**
→ Nein, die E-Mail-Adresse kann nicht geändert werden, da sie als Anmeldename dient. Kontaktieren Sie einen Admin, falls nötig.

**"Wer sieht meine Telefonnummer?"**
→ Telefonnummer und E-Mail sind hinter "Anzeigen"-Buttons versteckt. Sie werden nicht automatisch angezeigt, müssen aber aktiv angeklickt werden.

### Für Admins

**"Wie erstelle ich einen neuen Pinnwand-Eintrag?"**
→ Admin-Panel → Aktivitäten → Pinnwand → Neuen Eintrag erstellen. Alle Felder ausfüllen, Status auf "Veröffentlicht" setzen, Schichten im Tab "Schichten" hinzufügen.

**"Wie sehe ich, wer sich angemeldet hat?"**
→ Admin-Panel → Pinnwand → Eintrag bearbeiten → Tab "Schichten". Die Spalte "Online-Anmeldungen" und "Offline-Zusagen" zeigen die Zahlen. Für eine vollständige Liste: "Helfer exportieren" klicken.

**"Ein Elternteil möchte sein Konto löschen."**
→ Im Admin-Panel gibt es zwei Optionen:
- **Deaktivieren**: Konto wird gesperrt, Daten bleiben erhalten, kann wiederhergestellt werden
- **Anonymisieren (DSGVO)**: Alle persönlichen Daten werden gelöscht, nicht umkehrbar

**"Wie bereite ich das neue Schuljahr vor?"**
→ Administration → Neues Schuljahr. Dort werden alle aktuellen Inhalte archiviert. Kann nur einmal alle 30 Tage durchgeführt werden. Anleitung im [Admin-Handbuch](ADMIN_HANDBUCH.md), Abschnitt 9.

**"Was ist der Unterschied zwischen Admin und Super Admin?"**

| Aufgabe | Admin | Super Admin |
|---|---|---|
| Pinnwand verwalten | Ja (erstellen/bearbeiten) | Ja (+ löschen + Labels) |
| Aktivitäten verwalten | Ja | Ja |
| Forum moderieren | Ja (löschen/wiederherstellen) | Ja (+ endgültig löschen) |
| Benutzer verwalten | Nein | Ja |
| Ankündigungen | Nein | Ja |
| Schulkalender | Nein | Ja |
| Daten importieren | Nein | Ja |
| Audit-Protokoll | Nein | Ja |
| Jahresreset | Nein | Ja |

**"Wie importiere ich Daten?"**
→ Super Admins können Daten per CSV-Datei importieren. Der Import findet über die jeweilige Ressourcen-Seite statt (Aktionsbutton). Details im [Admin-Handbuch](ADMIN_HANDBUCH.md), Abschnitt 10.

**"Kann ich sehen, was andere Admins gemacht haben?"**
→ Ja, unter Administration → Audit-Protokoll. Dort werden kritische Aktionen protokolliert: Jahresresets, Benutzerdeaktivierungen, Massenimporte, Berechtigungsänderungen. Details im [Admin-Handbuch](ADMIN_HANDBUCH.md), Abschnitt 11.
