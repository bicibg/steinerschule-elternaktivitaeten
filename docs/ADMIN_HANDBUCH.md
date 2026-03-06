# Admin-Handbuch: Elternaktivitäten-Plattform

Steinerschule Langnau - Verwaltungspanel

**Letzte Aktualisierung:** März 2026
**Zugang:** `/admin` (z.B. `https://elternaktivitaeten.steinerschule-langnau.ch/admin`)

---

## Inhaltsverzeichnis

1. [Anmeldung und Rollen](#1-anmeldung-und-rollen)
2. [Navigation im Admin-Panel](#2-navigation-im-admin-panel)
3. [Pinnwand verwalten (Hilfegesuche)](#3-pinnwand-verwalten)
4. [Elternaktivitäten verwalten](#4-elternaktivitäten-verwalten)
5. [Schulkalender verwalten](#5-schulkalender-verwalten)
6. [Forum-Moderation](#6-forum-moderation)
7. [Benutzerverwaltung](#7-benutzerverwaltung)
8. [Ankündigungen](#8-ankündigungen)
9. [Neues Schuljahr vorbereiten](#9-neues-schuljahr-vorbereiten)
10. [Datenexport und -import](#10-datenexport-und--import)
11. [Audit-Protokoll](#11-audit-protokoll)
12. [Berechtigungsübersicht](#12-berechtigungsübersicht)

---

## 1. Anmeldung und Rollen

### Admin-Login

1. Gehe zu `/admin`
2. Melde dich mit deiner E-Mail-Adresse und deinem Passwort an
3. Falls du dein Passwort vergessen hast: Klicke auf "Passwort vergessen" auf der Anmeldeseite

### Zwei Admin-Rollen

| Funktion | Admin | Super Admin |
|---|---|---|
| Pinnwand-Einträge verwalten | Ja | Ja |
| Elternaktivitäten verwalten | Ja | Ja |
| Forumbeiträge moderieren | Ja | Ja |
| Forumbeiträge endgültig löschen | Nein | Ja |
| Pinnwand-Einträge löschen | Nein | Ja |
| Markierungen setzen (Dringend etc.) | Nein | Ja |
| Schulkalender verwalten | Nein | Ja |
| Benutzer verwalten | Nein | Ja |
| Ankündigungen erstellen | Nein | Ja |
| Neues Schuljahr vorbereiten | Nein | Ja |
| Audit-Protokoll einsehen | Nein | Ja |

Super-Admin-Bereiche sind im Menü mit einem Schloss-Symbol gekennzeichnet.

**Wichtig:** Es gibt kein Genehmigungs-System für neue Benutzer. Wer sich registriert, kann sofort die Plattform nutzen. Admin-Rechte werden nur von einem Super Admin vergeben.

---

## 2. Navigation im Admin-Panel

Das Seitenmenü ist in drei Gruppen unterteilt:

### Aktivitäten
- **Pinnwand** - Hilfegesuche und Schichtplanung
- **Elternaktivitäten** - Elterngruppen und Arbeitskreise

### Kommunikation
- **Forumbeiträge** - Beiträge im Pinnwand-Forum
- **Antworten** - Kommentare auf Forumbeiträge

### Administration (Super Admin)
- **Schulkalender** - Schulveranstaltungen verwalten
- **Benutzer** - Benutzerverwaltung und DSGVO
- **Ankündigungen** - Plattform-weite Mitteilungen
- **Audit-Protokoll** - Systemaktionen nachverfolgen
- **Neues Schuljahr** - Jahresreset durchführen

Zusätzlich im Menü:
- **Zur Webseite** (im Benutzermenü oben rechts) - Zurück zur öffentlichen Seite

---

## 3. Pinnwand verwalten

Die Pinnwand ist das Herzstück der Plattform: Hier werden Hilfegesuche für Schulanlässe veröffentlicht.

### Neuen Eintrag erstellen

Klicke auf **"Neuer Eintrag"** oben rechts auf der Pinnwand-Übersichtsseite.

#### Pflichtfelder

| Feld | Beschreibung |
|---|---|
| **Titel** | Name der Aktivität (z.B. "Helfende Hände für den Osterstand") |
| **Beschreibung** | Detaillierte Informationen zur Aktivität |
| **Kontaktperson - Name** | Wer ist verantwortlich? |
| **Status** | Entwurf, Veröffentlicht oder Archiviert |

#### Optionale Felder

| Feld | Beschreibung |
|---|---|
| **Beginnt am** | Datum und Uhrzeit (Format: TT.MM.JJJJ HH:MM) |
| **Endet am** | Enddatum und -uhrzeit |
| **Ort** | Veranstaltungsort |
| **Kontakt Telefon** | Telefonnummer der Kontaktperson |
| **Kontakt E-Mail** | E-Mail der Kontaktperson |
| **Kategorie** | Anlass, Haus/Umgebung/Taskforces, Produktion, Organisation, Verkauf |
| **Markierung** | Dringend, Wichtig, Hervorgehoben, Last Minute (nur Super Admin) |

#### Einstellungen (Schalter)

| Schalter | Standard | Beschreibung |
|---|---|---|
| **Diskussionsforum aktivieren** | - | Eltern können Fragen stellen und diskutieren |
| **Schichtplanung aktivieren** | - | Helferschichten mit Kapazitäten verwalten |
| **Im Kalender anzeigen** | An | Eintrag erscheint im öffentlichen Schichtkalender |

#### Status-Werte

- **Entwurf**: Nicht öffentlich sichtbar, nur im Admin-Panel
- **Veröffentlicht**: Für alle Besucher auf der Pinnwand sichtbar
- **Archiviert**: Nicht mehr aktiv, aber Daten bleiben erhalten

### Schichten verwalten

Schichten werden erst nach dem Speichern des Eintrags sichtbar. Öffne den Eintrag zum Bearbeiten und wechsle zum Tab **"Schichten"**.

#### Neue Schicht erstellen

Klicke auf **"Neue Schicht"** und fülle folgende Felder aus:

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Rolle/Aufgabe** | Ja | z.B. "Aufbau", "Cafeteria", "Kinderbetreuung" |
| **Datum** | Ja | Datum der Schicht (TT.MM.JJJJ) |
| **Von** | Ja | Startzeit (HH:MM) |
| **Bis** | Ja | Endzeit (HH:MM) |
| **Benötigt** | Ja | Wie viele Helfer werden gebraucht? (Mindestens 1) |
| **Besetzt** | Nein | Bereits offline zugesagte Personen (Standard: 0) |

Die Zeitanzeige wird automatisch formatiert, z.B.: "Montag, 15.03.2026, 09:00 - 12:00 Uhr"

#### Schicht-Tabelle verstehen

| Spalte | Bedeutung |
|---|---|
| **Rolle/Aufgabe** | Beschreibung der Schicht |
| **Zeit** | Formatierter Zeitraum |
| **Benötigt** | Gesamtanzahl benötigter Helfer |
| **Offline-Zusagen** | Manuell eingetragene Helfer |
| **Online-Anmeldungen** | Über die Webseite angemeldete Helfer |
| **Total** | Summe aus Offline + Online |

Farben der Total-Spalte:
- **Grün**: Schicht vollständig besetzt
- **Orange**: Teilweise besetzt
- **Rot**: Noch niemand angemeldet

### Magischer Bearbeitungslink

Jeder Pinnwand-Eintrag erhält automatisch einen geheimen Bearbeitungslink. Damit können Organisatoren ihren Eintrag bearbeiten, auch ohne eigenes Konto. Der Link wird beim Erstellen des Eintrags generiert und hat folgendes Format:

`/pinnwand/{slug}/edit?token={64-stelliger-code}`

Diesen Link kannst du dem Organisator per E-Mail schicken.

### Übersichtstabelle

Die Pinnwand-Übersicht zeigt folgende Spalten:
- Titel (durchsuchbar, sortierbar)
- Kontakt (durchsuchbar, sortierbar)
- Kategorie (farbig)
- Beginnt / Endet (sortierbar)
- Status (Entwurf/Veröffentlicht/Archiviert)
- Markierung (nur für Super Admin sichtbar)
- Link zur öffentlichen Seite (Pfeil-Symbol)

---

## 4. Elternaktivitäten verwalten

Elternaktivitäten sind die dauerhaften Elterngruppen und Arbeitskreise (z.B. Elternrat, Filzgruppe, Putzorganisation).

### Neue Aktivität erstellen

Klicke auf **"Neue Aktivität"** oben rechts.

#### Aktivitätsinformationen

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Titel** | Ja | Name der Gruppe (z.B. "Filzgruppe") |
| **Beschreibung** | Nein | Was macht diese Gruppe? |
| **Kategorie** | Ja | Auswahl aus 5 Kategorien (siehe unten) |
| **Treffzeiten** | Nein | z.B. "Jeden Dienstag, 20:00 Uhr" |
| **Treffpunkt** | Nein | z.B. "Musikzimmer" |

#### Kontaktperson

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Name** | Ja | Ansprechperson für diese Aktivität |
| **E-Mail** | Nein | E-Mail-Adresse |
| **Telefon** | Nein | Telefonnummer |

#### Einstellungen

| Schalter | Standard | Beschreibung |
|---|---|---|
| **Diskussionsforum aktivieren** | An | Forum unter der Aktivitätsseite |
| **Aktiv** | An | Aktivität ist sichtbar auf der Webseite |

#### 5 Kategorien

| Kategorie | Beispiele |
|---|---|
| **Anlass** | Osterstand, Sponsorenlauf, Märit-Events |
| **Haus, Umgebung und Taskforces** | Putzorganisation, Mittagstisch, Läuseteam |
| **Produktion** | Filzgruppe, Kerzenziehen, Puppen-Nähen, Backgruppe |
| **Organisation** | Elternrat, Qualitätsgruppe, Budget, IT |
| **Verkauf** | ProBon-Aktion, Lachsverkauf, WELEDA-Bestellung |

### Übersichtstabelle

Spalten:
- Titel (durchsuchbar, sortierbar)
- Kontakt (durchsuchbar, sortierbar)
- Kategorie (farbige Markierung)
- Forum (Ja/Nein-Symbol)
- Aktiv (Ja/Nein-Symbol)
- Anzahl Forumsbeiträge
- Link zur öffentlichen Seite (Pfeil-Symbol)

### Filter

- **Kategorie**: Eine der 5 Kategorien auswählen
- **Aktiv**: Ja/Nein/Alle
- **Forum**: Ja/Nein/Alle

---

## 5. Schulkalender verwalten

Der Schulkalender zeigt offizielle Schultermine (Ferien, Aufführungen, Sportwochen etc.). **Nur Super Admins** haben Zugriff.

### Neues Ereignis erstellen

Klicke auf **"Neue Schulveranstaltung"** oben rechts.

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Titel** | Ja | Name des Events (max. 255 Zeichen) |
| **Beschreibung** | Nein | Zusatzinformationen |
| **Startdatum** | Ja | Format: TT.MM.JJJJ |
| **Enddatum** | Nein | Bei mehrtägigen Events (muss nach Startdatum liegen) |
| **Uhrzeit** | Nein | z.B. "19:00 Uhr" (Freitext) |
| **Ort** | Nein | Veranstaltungsort (max. 255 Zeichen) |
| **Veranstaltungstyp** | Nein | Auswahl (Standard: Sonstiges) |
| **Ganztägig** | Ja | Standard: An |

#### Veranstaltungstypen

| Typ | Farbe in Tabelle |
|---|---|
| Fest | Rot |
| Treffen | Blau |
| Aufführung | Grau |
| Ferien | Grau |
| Sport | Grün |
| Ausflug | Orange |
| Sonstiges | - |

### Übersichtstabelle

Sortiert nach Startdatum (aufsteigend). Spalten:
- Titel (durchsuchbar, sortierbar)
- Startdatum (sortierbar)
- Enddatum (sortierbar)
- Ort (durchsuchbar, ein-/ausblendbar)
- Typ (farbige Markierung)
- Ganztägig (Ja/Nein, ein-/ausblendbar)

Filter: **Veranstaltungstyp** auswählen

Aktionen pro Zeile: Bearbeiten, Löschen (mit Bestätigung)

---

## 6. Forum-Moderation

Die Plattform hat zwei getrennte Foren:
1. **Pinnwand-Forum** (Forumbeiträge + Antworten) - in der Navigation unter "Kommunikation"
2. **Aktivitäten-Forum** - direkt auf den Aktivitätsseiten (kein eigener Admin-Bereich)

### Forumbeiträge (Pinnwand-Forum)

Unter **Kommunikation > Forumbeiträge** siehst du alle Beiträge aus den Pinnwand-Foren.

#### Tabellen-Spalten
- Pinnwand-Eintrag (zu welchem Hilfegesuch gehört der Beitrag)
- Autor (wer hat geschrieben)
- Nachricht (gekürzt auf 50 Zeichen, Maus-Hover zeigt vollen Text)
- Erstellt am

#### Filter
- **Gelöschte Einträge**: "Nur aktive" / "Nur gelöschte" / "Mit gelöschten"

#### Beiträge löschen (Soft Delete)

1. Wähle einen oder mehrere Beiträge über die Checkbox aus
2. Klicke auf **"Löschen"** in der Massenaktionsleiste
3. Wähle einen **Löschgrund** (Pflichtfeld):
   - Jahresarchivierung
   - Spam
   - Unangemessen
   - Auf Anfrage des Benutzers
   - Duplikat
4. Bestätige die Löschung

Gelöschte Beiträge werden **nicht endgültig entfernt** - sie werden als "soft deleted" markiert und können wiederhergestellt werden.

#### Beiträge wiederherstellen

1. Setze den Filter auf "Mit gelöschten" oder "Nur gelöschte"
2. Wähle die gelöschten Beiträge aus
3. Klicke auf **"Wiederherstellen"**

#### Endgültig löschen (nur Super Admin)

Gelöschte Beiträge können von Super Admins mit **"Endgültig löschen"** permanent entfernt werden. Diese Aktion ist nicht rückgängig zu machen.

### Antworten (Kommentare)

Unter **Kommunikation > Antworten** werden alle Kommentare auf Forumbeiträge angezeigt. Die Moderation funktioniert identisch zu den Forumbeiträgen (Löschen mit Grund, Wiederherstellen, Endgültig löschen).

### Organisatoren-Moderation (via Bearbeitungslink)

Organisatoren, die einen magischen Bearbeitungslink für ihren Pinnwand-Eintrag haben, können selbst Beiträge und Kommentare in ihrem Forum ein-/ausblenden. Das funktioniert ohne Admin-Login - der Bearbeitungslink reicht. Ausgeblendete Beiträge erhalten den Löschgrund "Unangemessen" und können vom Organisator wieder eingeblendet werden.

### Aktivitäten-Forum

Beiträge und Kommentare im Aktivitäten-Forum verwenden ein anderes System: Sie werden über ein `is_hidden`-Feld ausgeblendet statt gelöscht. Es gibt derzeit **keinen eigenen Admin-Bereich** für die Moderation des Aktivitäten-Forums. Ausblendungen müssen direkt über die Datenbank oder zukünftige Erweiterungen erfolgen.

### IP-Hash für Moderation

Jeder Forumbeitrag und Kommentar speichert einen gehashten IP-Wert. Damit lässt sich erkennen, ob mehrere problematische Beiträge von derselben Person stammen, ohne die tatsächliche IP-Adresse zu speichern.

---

## 7. Benutzerverwaltung

**Nur Super Admins** haben Zugriff auf die Benutzerverwaltung.

### Benutzer-Tabelle

Spalten:
- Name (durchsuchbar) - mit Hinweis "(Anonymisiert)" oder "(Deaktiviert)"
- E-Mail (durchsuchbar)
- Admin (Ja/Nein-Symbol)
- Super Admin (Ja/Nein-Symbol)
- Deaktiviert am (versteckt, einblendbar)
- Anonymisiert am (versteckt, einblendbar)
- Erstellt am (versteckt, einblendbar)

Filter: **Deaktivierte Benutzer** ein-/ausblenden

### Neuen Benutzer erstellen

Klicke auf **"Neuer Benutzer"**.

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Name** | Ja | Vor- und Nachname |
| **E-Mail** | Ja | E-Mail-Adresse |
| **Passwort** | Ja (nur beim Erstellen) | Mindestens 8 Zeichen |
| **Administrator** | Nein | Zugang zum Admin-Panel |
| **Super Administrator** | Nein | Alle Rechte inkl. Benutzerverwaltung |

Beim Bearbeiten ist das Passwort-Feld optional - leer lassen behält das bestehende Passwort.

### Benutzer deaktivieren (Soft Delete)

1. Klicke auf das **"Deaktivieren"**-Symbol (Kreis mit Strich) neben dem Benutzer
2. Bestätige im Dialog: "Sind Sie sicher, dass Sie diesen Benutzer deaktivieren möchten?"
3. Klicke auf **"Ja, deaktivieren"**

Auswirkung: Der Benutzer kann sich nicht mehr anmelden, aber alle Daten (Name, E-Mail, Beiträge) bleiben erhalten. Diese Aktion kann rückgängig gemacht werden.

### Benutzer wiederherstellen

1. Aktiviere den Filter "Deaktivierte Benutzer"
2. Klicke auf **"Wiederherstellen"** neben dem deaktivierten Benutzer
3. Bestätige: "Möchten Sie diesen Benutzer wiederherstellen?"

### Benutzer anonymisieren (DSGVO)

**WARNUNG: Diese Aktion ist NICHT rückgängig zu machen!**

1. Klicke auf **"Anonymisieren (DSGVO)"** (rotes Schild-Symbol)
2. Lies die Warnung: "Alle persönlichen Daten werden dauerhaft anonymisiert."
3. Klicke auf **"Unwiderruflich anonymisieren"**

Was passiert:
- Name wird zu "Anonymer Benutzer {ID}"
- E-Mail wird zu "deleted-{ID}@anonymous.local"
- Telefon und Bemerkungen werden gelöscht
- Die Änderung wird im Löschprotokoll dokumentiert

Verwende dies nur auf ausdrücklichen Wunsch einer Person (DSGVO Recht auf Löschung) oder wenn rechtlich notwendig.

### Admin-Rechte vergeben

1. Öffne den Benutzer zum Bearbeiten
2. Schalte **"Administrator"** ein für Admin-Panel-Zugang
3. Schalte **"Super Administrator"** ein für volle Rechte (optional)
4. Speichern

Hinweis: Du kannst dir selbst nicht die Super-Admin-Rechte entziehen (Selbstschutz).

---

## 8. Ankündigungen

**Nur Super Admins** können Ankündigungen erstellen und verwalten.

Ankündigungen erscheinen als Banner oben auf der Webseite für alle eingeloggten Benutzer.

### Neue Ankündigung erstellen

| Feld | Pflicht | Beschreibung |
|---|---|---|
| **Titel** | Ja | Kurzer Titel (max. 255 Zeichen) |
| **Nachricht** | Ja | Nachrichtentext (max. 300 Zeichen, Zähler wird angezeigt) |
| **Typ** | Ja | Information, Ankündigung, Erinnerung oder Dringend |
| **Aktiv** | - | Standard: An |
| **Priorität** | - | Standard: Aus |
| **Startet am** | Nein | Ab wann soll die Ankündigung erscheinen? |
| **Läuft ab am** | Nein | Standard: 14 Tage ab jetzt |

#### Typen

| Typ | Farbe | Symbol |
|---|---|---|
| Information | Blau | Info-Kreis |
| Ankündigung | Lila | Megafon |
| Erinnerung | Orange | Uhr |
| Dringend | Rot | Warndreieck |

#### Priorität

- **Aus**: Normale Ankündigung - läuft nach 14 Tagen ab (anpassbar), Benutzer können sie wegklicken
- **An**: Wird immer angezeigt, kein automatisches Ablaufdatum

### Übersichtstabelle

Zeigt an, wie oft eine Ankündigung von Benutzern weggeklickt ("abgewiesen") wurde.

Filter: Nach Typ oder Aktiv-Status filtern.

---

## 9. Neues Schuljahr vorbereiten

**Nur Super Admins** - unter **Administration > Neues Schuljahr**

Diese Funktion bereitet die Plattform für ein neues Schuljahr vor. Sie räumt alte Inhalte auf, damit die Plattform frisch startet.

### Was passiert beim Jahresreset?

| Aktion | Details |
|---|---|
| Alle aktiven **Aktivitäten** werden deaktiviert | `is_active` wird auf `false` gesetzt |
| Alle veröffentlichten **Pinnwand-Einträge** werden archiviert | Status wird auf "Archiviert" gesetzt |
| Nicht-prioritäre **Ankündigungen** werden deaktiviert | Prioritäts-Ankündigungen bleiben aktiv |
| Alle **Forumbeiträge** werden archiviert | Soft Delete mit Grund "Jahresarchivierung" |
| Alle **Kommentare** werden archiviert | Soft Delete mit Grund "Jahresarchivierung" |

**Was NICHT gelöscht wird:**
- Benutzerkonten bleiben erhalten
- Schulkalender-Einträge bleiben erhalten
- Prioritäts-Ankündigungen bleiben aktiv
- Schicht-Anmeldungen bleiben erhalten (für Nachvollziehbarkeit)

### Sicherheitsmassnahmen

Der Jahresreset ist mehrfach abgesichert:

1. Nur Super Admins sehen die Seite
2. Anzeige, wie viele Einträge betroffen sind (vor dem Reset)
3. Eingabe des neuen Schuljahres (z.B. "2026/2027")
4. Exakte Eingabe der Bestätigungsphrase: **NEUES SCHULJAHR STARTEN**
5. Eingabe des eigenen Passworts
6. Sperre: Kann nur einmal alle 30 Tage ausgeführt werden
7. Alles wird in einer Transaktion ausgeführt (Fehler = kein Datenverlust)

### Letzter Reset

Die Seite zeigt Informationen zum letzten durchgeführten Reset: Datum, wer hat es gemacht, welches Schuljahr, und wie viele Einträge betroffen waren.

---

## 10. Datenexport und -import

### Verfügbare Exporte

Die Plattform unterstützt CSV/XLSX-Export für:

| Datentyp | Exportierte Felder |
|---|---|
| **Benutzer** | Name, E-Mail, Kontaktdaten |
| **Aktivitäten** | Titel, Kategorie, Kontakt, Treffzeiten |
| **Pinnwand-Einträge** | Titel, Beschreibung, Datum, Kontakt, Status |
| **Schulveranstaltungen** | Titel, Datum, Ort, Typ |
| **Schicht-Anmeldungen** | Aktivität, Schicht, Zeit, Name, E-Mail, Anmeldedatum |

### Verfügbare Importe

| Datentyp | Beschreibung |
|---|---|
| **Benutzer** | Massenanlage von Benutzerkonten |
| **Aktivitäten** | Massenanlage von Elterngruppen |
| **Pinnwand-Einträge** | Massenanlage von Hilfegesuchen |
| **Schulveranstaltungen** | Massenanlage von Schulkalenterterminen |

Exporte und Importe sind über die jeweilige Ressourcen-Seite erreichbar (Aktionsbuttons oben auf der Tabelle).

---

## 11. Audit-Protokoll

**Nur Super Admins** - unter **Administration > Audit-Protokoll**

Das Audit-Protokoll dokumentiert automatisch alle kritischen Systemaktionen.

### Protokollierte Aktionen

| Aktionstyp | Beschreibung |
|---|---|
| **Neues Schuljahr** | Jahresreset durchgeführt |
| **Benutzer gelöscht** | Deaktivierung oder Anonymisierung |
| **Massenimport** | Datenimport über Admin-Panel |
| **Berechtigung geändert** | Admin-Rechte vergeben/entzogen |

### Schweregrade

| Stufe | Farbe | Bedeutung |
|---|---|---|
| Information | Grün | Normale Aktion |
| Warnung | Orange | Beachtenswert |
| Kritisch | Rot | Wichtige Systemänderung |

### Filter

- Nach Aktionstyp filtern
- Nach Schweregrad filtern
- Nur kritische Aktionen anzeigen
- Letzte 30 Tage (Standardfilter)

Audit-Einträge können **nicht bearbeitet, erstellt oder gelöscht** werden. Sie sind reine Protokolleinträge.

---

## 12. Berechtigungsübersicht

### Zusammenfassung aller Admin-Panel-Bereiche

| Bereich | Menügruppe | Admin | Super Admin |
|---|---|---|---|
| Pinnwand | Aktivitäten | Erstellen, Bearbeiten | + Löschen, Markierungen |
| Elternaktivitäten | Aktivitäten | Erstellen, Bearbeiten, Löschen | Erstellen, Bearbeiten, Löschen |
| Forumbeiträge | Kommunikation | Löschen, Wiederherstellen | + Endgültig löschen |
| Antworten | Kommunikation | Löschen, Wiederherstellen | + Endgültig löschen |
| Schulkalender | Administration | - | Erstellen, Bearbeiten, Löschen |
| Benutzer | Administration | - | Erstellen, Bearbeiten, Deaktivieren, Anonymisieren |
| Ankündigungen | Administration | - | Erstellen, Bearbeiten, Löschen |
| Audit-Protokoll | Administration | - | Nur lesen |
| Neues Schuljahr | Administration | - | Ausführen |
| Exporte | - | Ja | Ja |
| Importe | - | Ja | Ja |

### Hinweise zur tatsächlichen Implementierung

1. **Kein Genehmigungs-Workflow**: Neue Benutzer sind sofort aktiv. Es gibt kein Genehmigungssystem.
2. **Kein Aktivitäten-Forum-Admin**: Das Aktivitäten-Forum (ActivityPost/ActivityComment) hat keinen eigenen Moderationsbereich im Admin-Panel. Nur das Pinnwand-Forum (Post/Comment) kann im Admin moderiert werden.
3. **Schichtplanung nur über Pinnwand**: Schichten (Helferschichten) gehören immer zu einem Pinnwand-Eintrag. Es gibt keine eigenständige Schichtverwaltung.
4. **Magische Bearbeitungslinks**: Organisatoren ohne Konto können ihre Pinnwand-Einträge über einen geheimen Link bearbeiten. Diese Links werden automatisch beim Erstellen generiert.
5. **Passwort-Regeln**: Minimum 8 Zeichen, keine Komplexitätsanforderungen.
6. **Export/Import**: Laufen als Hintergrundjobs über die Laravel-Queue. Status ist im Admin-Panel einsehbar.
