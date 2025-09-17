# Demo-Anleitung für Elternaktivitäten-Plattform

## Vorbereitung (vor der Präsentation)

### Technisch
- [ ] Browser öffnen (Chrome/Firefox)
- [ ] Zwei Browser-Tabs vorbereiten:
  - Tab 1: Normale Nutzeransicht (nicht eingeloggt)
  - Tab 2: Admin-Panel (eingeloggt als Super Admin)
- [ ] Demo-Account bereithalten: demo@example.com / demo123456
- [ ] Admin-Account bereithalten: bugraergin@gmail.com / 123456789

### Inhalt prüfen
- [ ] Sicherstellen, dass aktuelle Aktivitäten auf der Pinnwand sind
- [ ] Mindestens eine Aktivität mit offenen Schichten haben
- [ ] Schulkalender hat sichtbare Events

## Demo-Ablauf (15-20 Minuten)

### 1. Einleitung (2 Min)
**Start auf der Hauptseite**
- Kurz die Ausgangslage erklären
- "Ich zeige Ihnen jetzt eine Lösung, die speziell für unsere Schule entwickelt wurde"
- Kosten erwähnen: CHF 240/Jahr vs. CHF 1000+/Jahr bei SignupNow

### 2. Öffentliche Ansicht - Pinnwand (3 Min)
**Route: /pinnwand**
- Zeigen: Aktivitäten die Hilfe suchen
- Klick auf eine Aktivität mit Schichten
- Zeigen:
  - Detailinfos zur Aktivität
  - Organisator-Kontakt
  - Schichtanmeldung OHNE Account möglich
  - Forum/Diskussion unten

**Demo-Aktion:**
- Als Helfer für eine Schicht anmelden (Name + Email eingeben)
- Zeigen wie einfach es ist

### 3. Elternaktivitäten-Seite (2 Min)
**Route: /aktivitaeten**
- "Hier sind ALLE Arbeitsgruppen der Schule"
- Kategorien zeigen (Anlass, Produktion, etc.)
- Eine Aktivität öffnen
- Kontaktpersonen und Treffzeiten zeigen

### 4. Kalender-System (3 Min)
**Route: /kalender**
- Zwei Kalender nebeneinander zeigen
- Links: Aktivitäten-Kalender (Helfereinsätze)
- Rechts: Schulkalender (Ferien, Feste)
- Klick auf ein Event → Detailseite
- Back-Button Funktionalität demonstrieren

### 5. Benutzer-Perspektive (3 Min)
**Login als Demo-User**
- Profil zeigen (/profile/edit)
  - Telefonnummer und Bemerkungen möglich
  - Eigene Schichten-Übersicht
- Benachrichtigung oben zeigen (falls vorhanden)
- Schliessen-Funktion demonstrieren

### 6. Admin-Perspektive (5 Min)
**Login als Admin: /admin**
- Übersicht Dashboard
- **Pinnwand verwalten:**
  - Neue Aktivität erstellen (schnell durchklicken)
  - Schichten hinzufügen
  - Status ändern (published/draft/ended)
- **Benachrichtigungen (nur Super Admin):**
  - Neue Benachrichtigung erstellen
  - Priorität setzen für wichtige Mitteilungen
  - Zeichen-Limit demonstrieren (200 Zeichen)
- **Benutzer & Datenschutz:**
  - Übersicht aller registrierten Helfer
  - **DSGVO-Funktionen demonstrieren:**
    - Benutzer deaktivieren (reversibel)
    - Benutzer anonymisieren (permanent, DSGVO Art. 17)
    - Audit-Log zeigt alle Aktionen
  - Kontaktdaten exportierbar

### 7. Mobile-Ansicht (2 Min)
**Browser-Fenster verkleinern oder Entwicklertools nutzen**
- Responsive Design zeigen
- Hamburger-Menü demonstrieren
- Touch-freundliche Schaltflächen

### 8. Abschluss & Fragen (3 Min)
**Zurück zur Hauptseite**
- Kernvorteile zusammenfassen:
  - Keine Login-Pflicht für Helfer
  - Alles auf einer Plattform
  - Massgeschneidert für unsere Bedürfnisse
  - Minimale Kosten
  - Von Eltern für Eltern
- "Das System ist bereit für eine Testphase"
- Fragen beantworten

## Wichtige Talking Points

### Bei Kostenfragen:
- "Einmalige Entwicklung bereits erfolgt - keine Kosten"
- "Laufend nur CHF 20/Monat für Domain"
- "Hosting vorerst kostenfrei über bestehende Infrastruktur"

### Bei Technik-Fragen:
- "Moderne, sichere Technologie (Laravel)"
- "Läuft auf jedem Smartphone und Computer"
- "Backups und Sicherheit gewährleistet"
- "DSGVO-konform mit Recht auf Vergessenwerden"
- "Keine Tracking-Cookies oder Analytics"

### Bei Anpassungswünschen:
- "System ist flexibel erweiterbar"
- "Feedback wird gerne aufgenommen"
- "Schritt für Schritt einführbar"

## Häufige Fragen & Antworten

**F: Müssen sich alle Eltern registrieren?**
A: Nein! Für Schichtanmeldungen reicht Name und Email. Nur wer diskutieren möchte, braucht einen Account.

**F: Wer pflegt die Inhalte?**
A: Organisatoren können ihre eigenen Aktivitäten verwalten. Die Schulverwaltung hat Zugriff auf alles.

**F: Was passiert mit den Daten?**
A: Alle Daten bleiben bei uns. Kein Verkauf, keine Weitergabe. Vollständig DSGVO-konform mit:
- Recht auf Vergessenwerden (Anonymisierung möglich)
- Datenminimierung (nur notwendige Daten)
- Transparente Datenschutzerklärung
- Keine Suchmaschinen-Indexierung

**F: Können wir das Design anpassen?**
A: Ja, Farben und Logo können an Schuldesign angepasst werden.

**F: Was ist mit SignupNow?**
A: SignupNow ist gut für Mensen, aber zu teuer und nicht auf alle unsere Bedürfnisse zugeschnitten.

## Notfall-Plan

### Falls etwas nicht funktioniert:
1. "Das zeige ich Ihnen gleich im Detail" → weitermachen
2. Auf Screenshots ausweichen (vorbereiten!)
3. Fokus auf Konzept statt Live-Demo

### Falls Internet ausfällt:
- KONZEPT.md Dokument zeigen
- Über Vorteile und Konzept sprechen
- Screenshots verwenden

## Nach der Demo

- [ ] Feedback notieren
- [ ] Konkrete nächste Schritte besprechen
- [ ] Timeline für Testphase festlegen
- [ ] Ansprechpartner definieren

---

**Tipp:** Enthusiastisch aber professionell bleiben. Es ist UNSERE Lösung für UNSERE Schule!