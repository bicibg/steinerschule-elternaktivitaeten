<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    private function userId(string $name): ?int
    {
        return User::where('email', Str::slug($name, '.') . '@example.com')->first()?->id;
    }

    public function run(): void
    {
        // ANLÄSSE
        Activity::create([
            'title' => 'Osterstand',
            'description' => 'Organisation und Durchführung des Osterstandes mit Verkauf von Osterdekoration und selbstgemachten Produkten.',
            'category' => 'anlass',
            'contact_name' => 'Julia Winkler',
            'contact_email' => 'osterstand@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Julia Winkler'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Sponsorenlauf',
            'description' => null,
            'category' => 'anlass',
            'contact_name' => 'Julia Eisenhut, Matthias Rytz',
            'contact_email' => 'sponsorenlauf@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Julia Eisenhut'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Stand an der Trubschachen-Woche',
            'description' => 'Präsentation der Schule und Verkaufsstand während der Trubschachen-Woche.',
            'category' => 'anlass',
            'contact_name' => 'Maria Mani, Selina Lüchiger',
            'contact_email' => 'trubschachen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Maria Mani'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Kaffeestube an der Trubschachen-Woche',
            'description' => 'Bewirtung der Kaffeestube während der Trubschachen-Woche. Gemütlicher Treffpunkt für Besucher.',
            'category' => 'anlass',
            'contact_name' => 'Bylie Beese, Anna Stalder',
            'contact_email' => 'kaffeestube@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Bylie Beese'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Pflanzenmärit',
            'description' => 'Verkauf von Setzlingen, Pflanzen und Gartenzubehör. HELFER GESUCHT!',
            'category' => 'anlass',
            'contact_name' => 'Helfer gesucht',
            'contact_email' => 'pflanzenmarit@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Spielzeug- und Kinderkleiderbörse',
            'description' => 'Zweimal jährlich stattfindende Börse für gebrauchte Spielsachen und Kinderkleidung. Im Umbruch - neue Organisatoren willkommen!',
            'category' => 'anlass',
            'contact_name' => 'Linda Denissen, Yael Stanca',
            'contact_email' => 'boerse@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Linda Denissen'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Märit-OK',
            'description' => 'Organisationskomitee für alle Märkte der Schule. Koordination, Planung und Durchführung der verschiedenen Märkte.',
            'category' => 'anlass',
            'contact_name' => 'Swenja Heyers, Yves Bönzli',
            'contact_email' => 'marit@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Swenja Heyers'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        // HAUS, UMGEBUNG UND TASKFORCES
        Activity::create([
            'title' => 'Putzorganisation',
            'description' => null,
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Susann Glättli, Hans Baumgartner',
            'contact_email' => 'putz@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Susann Glättli'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Mittagstisch',
            'description' => 'Organisation und Durchführung des Mittagstisches für Schülerinnen und Schüler. Planung, Administration, Reinigung und Wäscheverwaltung.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Anna Stalder',
            'contact_email' => 'mittagstisch@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Anna Stalder'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Läuseteam',
            'description' => 'Prävention und Behandlung bei Läusebefall. Regelmässige Kontrollen und Beratung für betroffene Familien.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Céline Zaugg',
            'contact_email' => 'laeuseteam@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Céline Zaugg'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Wäsche',
            'description' => 'Verwaltung und Pflege der Schulwäsche (Handtücher, Geschirrtücher, etc.).',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Katharina Baumgartner',
            'contact_email' => 'waesche@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Katharina Baumgartner'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Hausgruppe',
            'description' => 'Unterhalt und kleine Reparaturen am Schulhaus. Handwerkliche Arbeiten und Instandhaltung.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Hans Baumgartner',
            'contact_email' => 'hausgruppe@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Hans Baumgartner'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Erneuerung Pausenplatzareal',
            'description' => 'Projektgruppe zur Neugestaltung und Erneuerung des Pausenplatzes. Planung und Umsetzung von Spielgeräten und Gestaltungselementen.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Julia Eisenhut, Sami Eisenhut',
            'contact_email' => 'pausenplatz@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Julia Eisenhut'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        // PRODUKTION
        Activity::create([
            'title' => 'Filzgruppe',
            'description' => 'Herstellung von gefilzten Produkten für Märkte und Anlässe. Filzkurse und gemeinsames Filzen.',
            'category' => 'produktion',
            'contact_name' => 'Maria Mani',
            'contact_email' => 'filzen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Maria Mani'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Kerzenziehen',
            'description' => null,
            'category' => 'produktion',
            'contact_name' => 'Rene Winkler',
            'contact_email' => 'kerzen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Rene Winkler'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Puppen-Nähen',
            'description' => 'Herstellung von Waldorfpuppen und anderen genähten Spielsachen für Märkte.',
            'category' => 'produktion',
            'contact_name' => 'Manila Dür',
            'contact_email' => 'puppen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Manila Dür'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Kranzgruppe',
            'description' => 'Herstellung von Adventskränzen und anderen jahreszeitlichen Kränzen.',
            'category' => 'produktion',
            'contact_name' => 'Elsa Zürcher Ledermann',
            'contact_email' => 'kranz@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Elsa Zürcher Ledermann'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Seifenherstellung',
            'description' => 'Produktion von handgemachten Seifen für Märkte und Anlässe.',
            'category' => 'produktion',
            'contact_name' => 'Claudia Pereira',
            'contact_email' => 'seife@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Claudia Pereira'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Backgruppe',
            'description' => 'Backen von Brot, Kuchen und Gebäck für Schulanlässe und Märkte.',
            'category' => 'produktion',
            'contact_name' => 'Swenja Heyers, Matthias Frey',
            'contact_email' => 'backen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Swenja Heyers'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Tee-Produktion',
            'description' => 'Herstellung und Verpackung von Kräutertees für den Verkauf.',
            'category' => 'produktion',
            'contact_name' => 'Anna Stalder',
            'contact_email' => 'tee@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Anna Stalder'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Päcklifischen',
            'description' => 'Organisation des traditionellen Päcklifischens am Weihnachtsmarkt.',
            'category' => 'produktion',
            'contact_name' => 'Manuela Tschanz',
            'contact_email' => 'paecklifischen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Manuela Tschanz'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Lebkuchenverzieren',
            'description' => 'Lebkuchen backen und verzieren für den Weihnachtsmarkt.',
            'category' => 'produktion',
            'contact_name' => 'Tom Schick',
            'contact_email' => 'lebkuchen@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Tom Schick'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        // ORGANISATION
        Activity::create([
            'title' => 'Liegenschaftsverein der RSS Langnau',
            'description' => 'Verwaltung der Schulliegenschaften.',
            'category' => 'organisation',
            'contact_name' => 'Christian Konopka',
            'contact_email' => 'liegenschaft@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Christian Konopka'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Elternrat',
            'description' => null,
            'category' => 'organisation',
            'contact_name' => 'Tatjana Baumgartner, Maria Mani',
            'contact_email' => 'elternrat@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Tatjana Baumgartner'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Plakate',
            'description' => 'Gestaltung und Aushang von Plakaten für Schulanlässe.',
            'category' => 'organisation',
            'contact_name' => 'Rebekka Schaerer',
            'contact_email' => 'plakate@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Rebekka Schaerer'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Qualitätsgruppe (WzQ)',
            'description' => 'Qualitätsentwicklung und -sicherung an der Schule. Wege zur Qualität.',
            'category' => 'organisation',
            'contact_name' => 'Marianne Wey',
            'contact_email' => 'wzq@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Marianne Wey'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Öffentlichkeitsarbeit',
            'description' => 'Kommunikation nach aussen, Pressemitteilungen, Website und Social Media.',
            'category' => 'organisation',
            'contact_name' => 'Yves Bönzli',
            'contact_email' => 'pr@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Yves Bönzli'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Elterngesprächsgruppe (Erstgespräche)',
            'description' => 'Führung von Erstgesprächen mit interessierten Eltern.',
            'category' => 'organisation',
            'contact_name' => 'Sandra Lanz, Heinz Ledermann, Tamás Mokos',
            'contact_email' => 'erstgespraeche@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Sandra Lanz'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Forum',
            'description' => 'Organisation und Moderation des Schulforums.',
            'category' => 'organisation',
            'contact_name' => 'Marisa Frey, Susanne Marienfeld',
            'contact_email' => 'forum@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Marisa Frey'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Budgetkommission',
            'description' => 'Budgetplanung und -kontrolle für die Schule.',
            'category' => 'organisation',
            'contact_name' => 'Marianne Wey',
            'contact_email' => 'budget@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Marianne Wey'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'IT Langnau / RSS Cloud',
            'description' => 'IT-Support und Verwaltung der digitalen Infrastruktur.',
            'category' => 'organisation',
            'contact_name' => 'Matthias Hartmann',
            'contact_email' => 'it@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Matthias Hartmann'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Mediengruppe',
            'description' => 'Medienpädagogik und Medienkonzept der Schule.',
            'category' => 'organisation',
            'contact_name' => 'Christa Aeschlimann, Christian Brendle',
            'contact_email' => 'medien@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Christa Aeschlimann'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        // VERKAUF
        Activity::create([
            'title' => 'ProBon-Aktion',
            'description' => 'Organisation der ProBon-Verkaufsaktion zur Unterstützung der Schule.',
            'category' => 'verkauf',
            'contact_name' => 'Daniela Wüthrich',
            'contact_email' => 'probon@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Daniela Wüthrich'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'Lachsverkauf',
            'description' => 'Jährlicher Verkauf von Räucherlachs zur Weihnachtszeit.',
            'category' => 'verkauf',
            'contact_name' => 'Gisela Wyss',
            'contact_email' => 'lachs@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Gisela Wyss'),
            'has_forum' => true,
            'is_active' => true,
        ]);

        Activity::create([
            'title' => 'WELEDA-Bestellaktion',
            'description' => 'Sammelbestellung von WELEDA-Produkten mit Rabatt für die Schulgemeinschaft.',
            'category' => 'verkauf',
            'contact_name' => 'Rémy Reist',
            'contact_email' => 'weleda@steinerschule-langnau.ch',
            'contact_user_id' => $this->userId('Rémy Reist'),
            'has_forum' => true,
            'is_active' => true,
        ]);
    }
}
