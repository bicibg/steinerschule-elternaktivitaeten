# Admin-Leitfaden

Steinerschule Langnau - Elternaktivitäten-Plattform

**Aufgabenbasierte Anleitung für Administratoren**
Letzte Aktualisierung: März 2026

---

## Inhalt

1. [Einstieg: Anmeldung und Rollen](#1-einstieg-anmeldung-und-rollen)
2. [Das Admin-Panel im Überblick](#2-das-admin-panel-im-überblick)
3. [Pinnwand-Einträge verwalten](#3-pinnwand-einträge-verwalten)
4. [Schichten und Helfer verwalten](#4-schichten-und-helfer-verwalten)
5. [Elternaktivitäten verwalten](#5-elternaktivitäten-verwalten)
6. [Schulkalender verwalten](#6-schulkalender-verwalten)
7. [Forum moderieren](#7-forum-moderieren)
8. [Benutzer verwalten](#8-benutzer-verwalten)
9. [Ankündigungen erstellen](#9-ankündigungen-erstellen)
10. [Daten exportieren und importieren](#10-daten-exportieren-und-importieren)
11. [Neues Schuljahr vorbereiten](#11-neues-schuljahr-vorbereiten)
12. [Audit-Protokoll](#12-audit-protokoll)
13. [Was Admin tut → Was Eltern sehen](#13-was-admin-tut--was-eltern-sehen)
14. [Berechtigungsübersicht](#14-berechtigungsübersicht)
15. [Häufige Aufgaben: Schritt für Schritt](#15-häufige-aufgaben-schritt-für-schritt)

---

## 1. Einstieg: Anmeldung und Rollen

### So meldest du dich an

1. Gehe zu `/admin` (z.B. `https://elternaktivitaeten.steinerschule-langnau.ch/admin`)
2. E-Mail und Passwort eingeben
3. Falls Passwort vergessen: "Passwort vergessen" auf der Anmeldeseite

### Zwei Rollen

| Was kann ich? | Admin | Super Admin |
|---|---|---|
| Pinnwand erstellen und bearbeiten | Ja | Ja |
| Pinnwand löschen und Labels setzen | Nein | Ja |
| Elternaktivitäten verwalten | Ja | Ja |
| Forum moderieren | Ja | Ja |
| Forum endgültig löschen | Nein | Ja |
| Schulkalender | Nein | Ja |
| Benutzer verwalten | Nein | Ja |
| Ankündigungen | Nein | Ja |
| Neues Schuljahr | Nein | Ja |
| Audit-Protokoll | Nein | Ja |

Super-Admin-Bereiche sind im Menü mit einem Schloss-Symbol gekennzeichnet.

**Wichtig:** Neue Benutzer, die sich registrieren, sind sofort aktiv. Es gibt kein Genehmigungssystem. Admin-Rechte werden nur von einem Super Admin vergeben.

---

## 2. Das Admin-Panel im Überblick

### Seitenmenü

Das Menü ist in drei Gruppen unterteilt:

**Aktivitäten:**
- Pinnwand - Hilfegesuche und Schichtplanung
- Elternaktivitäten - Elterngruppen und Arbeitskreise

**Kommunikation:**
- Forumbeiträge - Beiträge im Pinnwand-Forum
- Antworten - Kommentare auf Forumbeiträge

**Administration (Super Admin):**
- Schulkalender - Schulveranstaltungen
- Benutzer - Benutzerverwaltung und DSGVO
- Ankündigungen - Plattform-weite Mitteilungen
- Exporte - Abgeschlossene Datenexporte (auch für Admins sichtbar)
- Importe - Massenimporte (nur Super Admin)
- Audit-Protokoll - Systemaktionen nachverfolgen
- Neues Schuljahr - Jahresreset

### Zurück zur Webseite

Oben rechts im Benutzermenü findest du **"Zur Webseite"**, um zur öffentlichen Seite zurückzukehren.

---

## 3. Pinnwand-Einträge verwalten

Die Pinnwand ist das Herzstück der Plattform. Hier werden Hilfegesuche veröffentlicht.

### Neuen Eintrag erstellen

1. Gehe zu **Aktivitäten → Pinnwand**
2. Klicke oben rechts auf **"Neuer Eintrag"**
3. Fülle die Felder aus:

**Pflichtfelder:**

| Feld | Beschreibung |
|---|---|
| Titel | Name der Aktivität, z.B. "Helfer für den Osterstand" |
| Beschreibung | Detaillierte Informationen |
| Kontaktperson - Name | Wer ist verantwortlich? |
| Status | Entwurf, Veröffentlicht oder Archiviert |

**Optionale Felder:**

| Feld | Beschreibung |
|---|---|
| Beginnt am / Endet am | Datum und Uhrzeit (TT.MM.JJJJ HH:MM) |
| Ort | Veranstaltungsort |
| Kontakt Telefon / E-Mail | Erreichbarkeit der Kontaktperson |
| Kategorie | Anlass, Haus/Umgebung/Taskforces, Produktion, Organisation, Verkauf |
| Markierung | Dringend, Wichtig, Hervorgehoben, Last Minute (nur Super Admin) |

**Schalter:**

| Schalter | Was passiert? |
|---|---|
| Diskussionsforum aktivieren | Eltern können Fragen stellen und diskutieren |
| Schichtplanung aktivieren | Helferschichten mit Kapazitäten verwalten |
| Im Kalender anzeigen | Schichten erscheinen im öffentlichen Schichtkalender |

### Status-Werte verstehen

- **Entwurf** - Nicht sichtbar für Eltern, nur im Admin-Panel
- **Veröffentlicht** - Für alle auf der Pinnwand sichtbar
- **Archiviert** - Nicht mehr aktiv, Daten bleiben erhalten

### Übersichtstabelle

Die Pinnwand-Übersicht zeigt: Titel, Kontakt, Kategorie (farbig), Datum, Status, Markierung (nur Super Admin) und einen Link zur öffentlichen Seite.

### Magischer Bearbeitungslink

Jeder Eintrag bekommt automatisch einen geheimen Bearbeitungslink. Damit können Organisatoren ohne Admin-Konto ihren Eintrag bearbeiten.

**Format:** `/pinnwand/{slug}/edit?token={64-stelliger-code}`

Du findest den Link am Ende der Bearbeitungsseite. Schicke ihn dem Organisator per E-Mail. Damit kann der Organisator:
- Titel, Beschreibung, Datum und Ort ändern
- Status ändern
- Forum und Schichten ein-/ausschalten
- Forumbeiträge in seinem Eintrag ausblenden/einblenden

Falls jemand den Link verliert: Du kannst den Eintrag jederzeit direkt im Admin-Panel bearbeiten.

---

## 4. Schichten und Helfer verwalten

Schichten werden innerhalb eines Pinnwand-Eintrags verwaltet.

### Schichten erstellen

1. Öffne einen Pinnwand-Eintrag zum Bearbeiten
2. Wechsle zum Tab **"Schichten"** (erscheint erst nach dem ersten Speichern)
3. Klicke auf **"Neue Schicht"**

| Feld | Pflicht | Beschreibung |
|---|---|---|
| Rolle/Aufgabe | Ja | z.B. "Aufbau", "Cafeteria", "Kinderbetreuung" |
| Datum | Ja | Format: TT.MM.JJJJ |
| Von / Bis | Ja | Start- und Endzeit (HH:MM) |
| Benötigt | Ja | Wie viele Helfer? (mindestens 1) |
| Besetzt | Nein | Bereits offline zugesagte Personen (Standard: 0) |

### Die Schicht-Tabelle lesen

| Spalte | Bedeutung |
|---|---|
| Rolle/Aufgabe | Beschreibung der Schicht |
| Zeit | Formatierter Zeitraum |
| Benötigt | Gesamtzahl benötigter Helfer |
| Offline-Zusagen | Manuell eingetragene Helfer (z.B. telefonische Zusagen) |
| Online-Anmeldungen | Über die Webseite angemeldete Helfer |
| Total | Summe aus Offline + Online |

**Farbcode der Total-Spalte:**
- **Grün** - Schicht vollständig besetzt
- **Orange** - Teilweise besetzt
- **Rot** - Noch niemand angemeldet

### Offline-Zusagen eintragen

Wenn jemand z.B. per Telefon zusagt: Erhöhe den Wert bei **"Besetzt"** in der jeweiligen Schicht. Diese Person erscheint als "offline angemeldet" für andere Nutzer.

### Helfer exportieren

Über den Button **"Helfer exportieren"** kannst du eine CSV/XLSX-Datei mit allen Helferdaten herunterladen (Name, E-Mail, Telefon, Schicht, Anmeldedatum).

---

## 5. Elternaktivitäten verwalten

Elternaktivitäten sind die dauerhaften Gruppen und Arbeitskreise (z.B. Elternrat, Filzgruppe, Putzorganisation).

### Neue Aktivität erstellen

1. Gehe zu **Aktivitäten → Elternaktivitäten**
2. Klicke auf **"Neue Aktivität"**

**Aktivitätsinformationen:**

| Feld | Pflicht | Beschreibung |
|---|---|---|
| Titel | Ja | Name der Gruppe, z.B. "Filzgruppe" |
| Beschreibung | Nein | Was macht diese Gruppe? |
| Kategorie | Ja | Auswahl aus 5 Kategorien |
| Treffzeiten | Nein | z.B. "Jeden Dienstag, 20:00 Uhr" |
| Treffpunkt | Nein | z.B. "Musikzimmer" |

**Kontaktperson:**

| Feld | Pflicht |
|---|---|
| Name | Ja |
| E-Mail | Nein |
| Telefon | Nein |

**Schalter:**

| Schalter | Standard | Beschreibung |
|---|---|---|
| Diskussionsforum aktivieren | An | Forum unter der Aktivitätsseite |
| Aktiv | An | Aktivität ist auf der Webseite sichtbar |

### Die 5 Kategorien

| Kategorie | Beispiele |
|---|---|
| Anlass | Osterstand, Sponsorenlauf, Märit-Events |
| Haus, Umgebung und Taskforces | Putzorganisation, Mittagstisch, Läuseteam |
| Produktion | Filzgruppe, Kerzenziehen, Puppen-Nähen, Backgruppe |
| Organisation | Elternrat, Qualitätsgruppe, Budget, IT |
| Verkauf | ProBon-Aktion, Lachsverkauf, WELEDA-Bestellung |

### Übersicht und Filter

Die Tabelle zeigt: Titel, Kontakt, Kategorie, Forum (Ja/Nein), Aktiv (Ja/Nein), Anzahl Forumsbeiträge und Link zur Seite.

Filter: Kategorie, Aktiv-Status, Forum-Status.

---

## 6. Schulkalender verwalten

**Nur Super Admins.** Unter **Administration → Schulkalender**.

### Neues Ereignis erstellen

1. Klicke auf **"Neue Schulveranstaltung"**

| Feld | Pflicht | Beschreibung |
|---|---|---|
| Titel | Ja | Name des Events (max. 255 Zeichen) |
| Beschreibung | Nein | Zusatzinformationen |
| Startdatum | Ja | Format: TT.MM.JJJJ |
| Enddatum | Nein | Bei mehrtägigen Events (muss nach Startdatum liegen) |
| Uhrzeit | Nein | Freitext, z.B. "19:00 Uhr" |
| Ort | Nein | Veranstaltungsort |
| Veranstaltungstyp | Nein | Fest, Treffen, Aufführung, Ferien, Sport, Ausflug, Sonstiges |
| Ganztägig | Ja | Standard: An |

### Veranstaltungstypen und Farben

| Typ | Farbe im Kalender |
|---|---|
| Fest | Rot |
| Treffen | Blau |
| Aufführung | Grau |
| Ferien | Grau |
| Sport | Grün |
| Ausflug | Orange |

### ICS-Import

Schulkalender-Einträge können auch per ICS-Datei (iCalendar) importiert werden - nützlich, wenn die Schule bereits einen digitalen Kalender führt.

---

## 7. Forum moderieren

### Wo finde ich Forumbeiträge?

Im Admin-Panel unter **Kommunikation**:
- **Forumbeiträge** - Die Hauptbeiträge
- **Antworten** - Kommentare auf Beiträge

Beide Bereiche funktionieren identisch.

### Beiträge löschen (Soft Delete)

1. Wähle einen oder mehrere Beiträge über die Checkbox
2. Klicke auf **"Löschen"**
3. Wähle einen Löschgrund (Pflichtfeld):
   - Jahresarchivierung
   - Spam
   - Unangemessen
   - Auf Anfrage des Benutzers
   - Duplikat
4. Bestätige

Gelöschte Beiträge sind nicht endgültig weg - sie können wiederhergestellt werden.

### Beiträge wiederherstellen

1. Filter auf **"Mit gelöschten"** oder **"Nur gelöschte"** setzen
2. Gelöschte Beiträge auswählen
3. Auf **"Wiederherstellen"** klicken

### Endgültig löschen (nur Super Admin)

Gelöschte Beiträge können mit **"Endgültig löschen"** permanent entfernt werden. Diese Aktion ist nicht rückgängig zu machen.

### Organisatoren-Moderation

Organisatoren mit einem magischen Bearbeitungslink können Beiträge und Kommentare in ihrem eigenen Pinnwand-Forum ein- und ausblenden - ohne Admin-Rechte.

### Hinweis zum Aktivitäten-Forum

Das Aktivitäten-Forum (bei Elternaktivitäten) hat derzeit keinen eigenen Moderationsbereich im Admin-Panel. Nur das Pinnwand-Forum wird hier verwaltet.

---

## 8. Benutzer verwalten

**Nur Super Admins.** Unter **Administration → Benutzer**.

### Benutzer-Tabelle

Spalten: Name, E-Mail, Admin-Status, Super-Admin-Status. Zusätzlich einblendbar: Deaktiviert am, Anonymisiert am, Erstellt am.

Filter: Deaktivierte Benutzer ein-/ausblenden.

### Neuen Benutzer erstellen

1. Klicke auf **"Neuer Benutzer"**
2. Fülle aus: Name, E-Mail, Passwort (min. 8 Zeichen)
3. Optional: Administrator und/oder Super Administrator aktivieren
4. Speichern

Beim Bearbeiten: Passwort-Feld leer lassen behält das bestehende Passwort.

### Benutzer deaktivieren

1. Klicke auf das **Deaktivieren**-Symbol (Kreis mit Strich) neben dem Benutzer
2. Bestätige: "Ja, deaktivieren"

**Auswirkung:** Der Benutzer kann sich nicht mehr anmelden. Alle Daten bleiben erhalten. Name erscheint weiterhin bei Schichten. Diese Aktion ist rückgängig machbar.

### Benutzer wiederherstellen

1. Filter "Deaktivierte Benutzer" aktivieren
2. Auf **"Wiederherstellen"** klicken
3. Bestätigen

### Benutzer anonymisieren (DSGVO)

**WARNUNG: Nicht rückgängig machbar!**

1. Klicke auf **"Anonymisieren (DSGVO)"** (rotes Schild-Symbol)
2. Lies die Warnung
3. Klicke auf **"Unwiderruflich anonymisieren"**

Was passiert:
- Name → "Anonymer Benutzer {ID}"
- E-Mail → "deleted-{ID}@anonymous.local"
- Telefon und Bemerkungen → gelöscht
- Wird im Löschprotokoll dokumentiert

Verwende dies nur auf ausdrücklichen Wunsch einer Person oder wenn rechtlich notwendig.

### Admin-Rechte vergeben

1. Benutzer zum Bearbeiten öffnen
2. **"Administrator"** einschalten → Admin-Panel-Zugang
3. Optional: **"Super Administrator"** einschalten → volle Rechte
4. Speichern

Hinweis: Du kannst dir selbst nicht die Super-Admin-Rechte entziehen.

---

## 9. Ankündigungen erstellen

**Nur Super Admins.** Unter **Administration → Ankündigungen**.

Ankündigungen erscheinen als Banner oben auf jeder Seite.

### Neue Ankündigung erstellen

| Feld | Pflicht | Beschreibung |
|---|---|---|
| Titel | Ja | Kurzer Titel (max. 255 Zeichen) |
| Nachricht | Ja | Text (max. 300 Zeichen, Zähler wird angezeigt) |
| Typ | Ja | Information, Ankündigung, Erinnerung oder Dringend |
| Aktiv | - | Standard: An |
| Priorität | - | Standard: Aus |
| Startet am | Nein | Ab wann soll sie erscheinen? |
| Läuft ab am | Nein | Standard: 14 Tage ab jetzt |

### Typen

| Typ | Farbe | Symbol |
|---|---|---|
| Information | Blau | Info-Kreis |
| Ankündigung | Lila | Megafon |
| Erinnerung | Orange | Uhr |
| Dringend | Rot | Warndreieck |

### Priorität verstehen

- **Aus (Standard):** Normale Ankündigung. Läuft nach 14 Tagen ab (anpassbar). Eltern können sie wegklicken.
- **An:** Wird immer angezeigt. Kein automatisches Ablaufdatum. Eltern können sie nicht wegklicken.

### Tipps

- Maximal 3 normale Ankündigungen werden gleichzeitig angezeigt (plus alle Prioritäts-Ankündigungen)
- In der Übersichtstabelle siehst du, wie oft eine Ankündigung weggeklickt wurde

---

## 10. Daten exportieren und importieren

### Exporte starten

Exporte werden über den Aktionsbutton auf der jeweiligen Ressourcen-Seite gestartet. Alle Admins können aus ihren sichtbaren Bereichen exportieren.

**Verfügbare Exporte:**

| Datentyp | Was wird exportiert? |
|---|---|
| Benutzer | ID, Name, E-Mail, Admin-Status, Erstellt am (ohne gelöschte/anonymisierte) |
| Aktivitäten | Titel, Beschreibung, Kategorie, Kontakt, Treffzeiten/-ort (nur aktive) |
| Pinnwand-Einträge | Titel, Beschreibung, Datum, Ort, Kontakt, Status, Kategorie (nur veröffentlichte) |
| Schulveranstaltungen | Titel, Datum, Ort, Typ (alle) |
| Schicht-Anmeldungen | Aktivität, Schicht, Zeit, Name, E-Mail, Telefon, Anmeldedatum (alle) |

Exporte laufen im Hintergrund. Den Status siehst du unter **Administration → Exporte**. Download als CSV oder XLSX.

### Importe durchführen (nur Super Admin)

Importe ebenfalls über den Aktionsbutton auf der jeweiligen Ressourcen-Seite.

**Verfügbare Importe:**

| Datentyp | Pflichtfelder | Duplikat-Erkennung |
|---|---|---|
| Benutzer | Name, E-Mail | Per E-Mail, stellt gelöschte wieder her |
| Aktivitäten | Titel, Kategorie, Kontaktperson | Per Titel |
| Pinnwand-Einträge | Titel, Beschreibung, Kontaktperson | Per Titel |
| Schulveranstaltungen | Titel, Startdatum, Veranstaltungstyp | Per Titel + Startdatum |

**Beim Benutzer-Import:** Wird kein Passwort angegeben, wird automatisch "12345678" gesetzt. Benutzer sollten beim ersten Login ihr Passwort ändern.

Fehlgeschlagene Zeilen können als Datei heruntergeladen werden. Status unter **Administration → Importe**.

---

## 11. Neues Schuljahr vorbereiten

**Nur Super Admins.** Unter **Administration → Neues Schuljahr**.

### Was passiert beim Jahresreset?

| Aktion | Details |
|---|---|
| Aktivitäten deaktiviert | `is_active` wird auf `false` gesetzt |
| Pinnwand-Einträge archiviert | Status wird auf "Archiviert" gesetzt |
| Normale Ankündigungen deaktiviert | Prioritäts-Ankündigungen bleiben aktiv |
| Forumbeiträge archiviert | Soft Delete mit Grund "Jahresarchivierung" |
| Kommentare archiviert | Soft Delete mit Grund "Jahresarchivierung" |

**Was NICHT betroffen ist:**
- Benutzerkonten bleiben aktiv
- Schulkalender-Einträge bleiben erhalten
- Prioritäts-Ankündigungen bleiben aktiv
- Schicht-Anmeldungen bleiben erhalten (für Nachvollziehbarkeit)

### Ablauf Schritt für Schritt

1. Gehe zu **Administration → Neues Schuljahr**
2. Die Seite zeigt, wie viele Einträge betroffen sind
3. Gib das neue Schuljahr ein (z.B. "2026/2027")
4. Gib die Bestätigungsphrase ein: **NEUES SCHULJAHR STARTEN**
5. Gib dein eigenes Passwort ein
6. Bestätige

### Sicherheitsmassnahmen

- Nur Super Admins sehen die Seite
- Vorschau der betroffenen Einträge
- Bestätigungsphrase und Passwort erforderlich
- Kann nur einmal alle 30 Tage ausgeführt werden
- Alles läuft in einer Transaktion (bei Fehler: kein Datenverlust)

### Letzter Reset

Die Seite zeigt Informationen zum letzten Reset: Datum, wer hat ihn durchgeführt, welches Schuljahr, wie viele Einträge betroffen waren.

---

## 12. Audit-Protokoll

**Nur Super Admins.** Unter **Administration → Audit-Protokoll**.

Das Protokoll dokumentiert automatisch alle kritischen Systemaktionen.

### Was wird protokolliert?

| Aktionstyp | Beschreibung |
|---|---|
| Neues Schuljahr | Jahresreset durchgeführt |
| Benutzer gelöscht | Deaktivierung oder Anonymisierung |
| Massenimport | Datenimport über Admin-Panel |
| Berechtigung geändert | Admin-Rechte vergeben/entzogen |

### Schweregrade

| Stufe | Farbe | Bedeutung |
|---|---|---|
| Information | Grün | Normale Aktion |
| Warnung | Orange | Beachtenswert |
| Kritisch | Rot | Wichtige Systemänderung |

### Filter

- Nach Aktionstyp
- Nach Schweregrad
- Nur kritische Aktionen
- Letzte 30 Tage (Standardfilter)

Audit-Einträge können nicht bearbeitet, erstellt oder gelöscht werden.

---

## 13. Was Admin tut → Was Eltern sehen

### Pinnwand-Eintrag

| Du machst... | Eltern sehen... |
|---|---|
| Eintrag erstellen (Status: Entwurf) | Nichts (nicht sichtbar) |
| Status auf "Veröffentlicht" setzen | Eintrag erscheint auf der Pinnwand |
| Schichten hinzufügen | Schichtanmeldung im Eintrag |
| "Im Kalender anzeigen" aktivieren | Schichten im Schichtkalender |
| Label "Dringend" setzen | Roter "Dringend"-Badge |
| Forum aktivieren | "Diskussion"-Tab erscheint |
| Status auf "Archiviert" setzen | Eintrag verschwindet |

### Schichten

| Du machst... | Eltern sehen... |
|---|---|
| Neue Schicht (Aufbau, 08-10, 3 Helfer) | "Aufbau" mit "0/3 angemeldet" |
| "Offline-Zusagen" auf 1 setzen | "1/3 angemeldet" + "1 Person offline" |
| "Benötigt" von 3 auf 5 erhöhen | Zähler aktualisiert sich |
| Schicht löschen | Schicht verschwindet (Anmeldungen weg!) |

### Aktivitäten

| Du machst... | Eltern sehen... |
|---|---|
| Aktivität erstellen (aktiv) | Karte auf der Übersichtsseite |
| Forum aktivieren | Forum auf der Detailseite |
| "Aktiv" deaktivieren | Aktivität verschwindet |

### Benutzer

| Du machst... | Eltern erleben... |
|---|---|
| Benutzer deaktivieren | Kann sich nicht mehr anmelden, Daten bleiben |
| Benutzer wiederherstellen | Kann sich wieder anmelden |
| Benutzer anonymisieren (DSGVO) | Name wird "Anonymer Benutzer", Daten gelöscht, nicht rückgängig! |

### Ankündigungen

| Du machst... | Eltern sehen... |
|---|---|
| Ankündigung erstellen (aktiv) | Banner oben auf jeder Seite |
| Typ "Dringend" wählen | Roter Banner |
| Priorität aktivieren | Banner bleibt permanent sichtbar |
| Ankündigung deaktivieren | Banner verschwindet sofort |

### Jahresreset

| Du machst... | Eltern sehen... |
|---|---|
| Jahresreset ausführen | Pinnwand leer, Aktivitäten weg, normale Ankündigungen weg, Forum leer. Schulkalender, Prioritäts-Ankündigungen und Konten bleiben. |

---

## 14. Berechtigungsübersicht

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

---

## 15. Häufige Aufgaben: Schritt für Schritt

### Ein Hilfegesuch veröffentlichen

1. **Aktivitäten → Pinnwand → Neuer Eintrag**
2. Titel, Beschreibung und Kontaktperson ausfüllen
3. Datum, Ort und Kategorie setzen
4. "Schichtplanung aktivieren" und "Diskussionsforum aktivieren" einschalten
5. Status auf **"Veröffentlicht"** setzen
6. **Speichern**
7. Eintrag erneut öffnen → Tab **"Schichten"** → Schichten hinzufügen
8. Optional: Magischen Bearbeitungslink an den Organisator schicken

### Sehen, wer sich angemeldet hat

1. **Aktivitäten → Pinnwand** → Eintrag öffnen
2. Tab **"Schichten"**
3. Spalten "Online-Anmeldungen" und "Offline-Zusagen" zeigen die Zahlen
4. Für eine vollständige Helferliste: **"Helfer exportieren"**

### Einen dringenden Aufruf machen

1. Pinnwand-Eintrag erstellen oder bearbeiten
2. Markierung auf **"Dringend"** setzen (nur Super Admin)
3. Zusätzlich: Ankündigung erstellen mit Typ "Dringend" und Priorität "An"

### Ein Elternteil will sein Konto löschen

1. **Administration → Benutzer** → Person suchen
2. Entscheidung:
   - **Deaktivieren** → Konto gesperrt, Daten bleiben, kann wiederhergestellt werden
   - **Anonymisieren (DSGVO)** → Alle Daten unwiderruflich gelöscht
3. Bei DSGVO-Anfrage: Anonymisieren wählen und bestätigen

### Neues Schuljahr starten

1. **Administration → Neues Schuljahr**
2. Prüfe, wie viele Einträge betroffen sind
3. Schuljahr eingeben (z.B. "2026/2027")
4. Bestätigungsphrase eingeben: **NEUES SCHULJAHR STARTEN**
5. Eigenes Passwort eingeben
6. Bestätigen
7. Danach: Neue Aktivitäten und Pinnwand-Einträge erstellen

### Jemandem Admin-Rechte geben

1. **Administration → Benutzer** → Person suchen und öffnen
2. **"Administrator"** einschalten
3. Optional: **"Super Administrator"** einschalten
4. Speichern

### Einen Schultermin eintragen

1. **Administration → Schulkalender → Neue Schulveranstaltung**
2. Titel und Startdatum eingeben
3. Bei mehrtägigen Events: Enddatum setzen
4. Veranstaltungstyp auswählen
5. Speichern

---

*Entwickelt für die Elterngemeinschaft der Steinerschule Langnau von Buğra Ergin.*
