<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ActivitySeeder extends Seeder
{
    private function userIds(array $names): array
    {
        return User::whereIn('email', array_map(
            fn ($name) => Str::slug($name, '.').'@example.com',
            $names
        ))->pluck('id')->toArray();
    }

    private function createWithContacts(array $data, array $contactNames = []): Activity
    {
        $activity = Activity::create($data);
        if (! empty($contactNames)) {
            $activity->contactUsers()->attach($this->userIds($contactNames));
        }

        return $activity;
    }

    public function run(): void
    {
        // ANLÄSSE
        $this->createWithContacts([
            'title' => 'Osterstand',
            'description' => 'Organisation und Durchführung des Osterstandes mit Verkauf von Osterdekoration und selbstgemachten Produkten.',
            'category' => 'anlass',
            'contact_name' => 'Julia Winkler',
            'contact_email' => 'osterstand@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Julia Winkler']);

        $this->createWithContacts([
            'title' => 'Sponsorenlauf',
            'description' => null,
            'category' => 'anlass',
            'contact_name' => 'Julia Eisenhut, Matthias Rytz',
            'contact_email' => 'sponsorenlauf@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Julia Eisenhut', 'Matthias Rytz']);

        $this->createWithContacts([
            'title' => 'Stand an der Trubschachen-Woche',
            'description' => 'Präsentation der Schule und Verkaufsstand während der Trubschachen-Woche.',
            'category' => 'anlass',
            'contact_name' => 'Maria Mani, Selina Lüchiger',
            'contact_email' => 'trubschachen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Maria Mani', 'Selina Lüchiger']);

        $this->createWithContacts([
            'title' => 'Kaffeestube an der Trubschachen-Woche',
            'description' => 'Bewirtung der Kaffeestube während der Trubschachen-Woche. Gemütlicher Treffpunkt für Besucher.',
            'category' => 'anlass',
            'contact_name' => 'Bylie Beese, Anna Stalder',
            'contact_email' => 'kaffeestube@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Bylie Beese', 'Anna Stalder']);

        Activity::create([
            'title' => 'Pflanzenmärit',
            'description' => 'Verkauf von Setzlingen, Pflanzen und Gartenzubehör. HELFER GESUCHT!',
            'category' => 'anlass',
            'contact_name' => 'Helfer gesucht',
            'contact_email' => 'pflanzenmarit@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ]);

        $this->createWithContacts([
            'title' => 'Spielzeug- und Kinderkleiderbörse',
            'description' => 'Zweimal jährlich stattfindende Börse für gebrauchte Spielsachen und Kinderkleidung. Im Umbruch - neue Organisatoren willkommen!',
            'category' => 'anlass',
            'contact_name' => 'Linda Denissen, Yael Stanca',
            'contact_email' => 'boerse@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Linda Denissen', 'Yael Stanca']);

        $this->createWithContacts([
            'title' => 'Märit-OK',
            'description' => 'Organisationskomitee für alle Märkte der Schule. Koordination, Planung und Durchführung der verschiedenen Märkte.',
            'category' => 'anlass',
            'contact_name' => 'Swenja Heyers, Yves Bönzli',
            'contact_email' => 'marit@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Swenja Heyers', 'Yves Bönzli']);

        // HAUS, UMGEBUNG UND TASKFORCES
        $this->createWithContacts([
            'title' => 'Putzorganisation',
            'description' => null,
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Susann Glättli, Hans Baumgartner',
            'contact_email' => 'putz@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Susann Glättli', 'Hans Baumgartner']);

        $this->createWithContacts([
            'title' => 'Mittagstisch',
            'description' => 'Organisation und Durchführung des Mittagstisches für Schülerinnen und Schüler. Planung, Administration, Reinigung und Wäscheverwaltung.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Anna Stalder',
            'contact_email' => 'mittagstisch@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Anna Stalder']);

        $this->createWithContacts([
            'title' => 'Läuseteam',
            'description' => 'Prävention und Behandlung bei Läusebefall. Regelmässige Kontrollen und Beratung für betroffene Familien.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Céline Zaugg',
            'contact_email' => 'laeuseteam@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Céline Zaugg']);

        $this->createWithContacts([
            'title' => 'Wäsche',
            'description' => 'Verwaltung und Pflege der Schulwäsche (Handtücher, Geschirrtücher, etc.).',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Katharina Baumgartner',
            'contact_email' => 'waesche@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Katharina Baumgartner']);

        $this->createWithContacts([
            'title' => 'Hausgruppe',
            'description' => 'Unterhalt und kleine Reparaturen am Schulhaus. Handwerkliche Arbeiten und Instandhaltung.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Hans Baumgartner',
            'contact_email' => 'hausgruppe@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Hans Baumgartner']);

        $this->createWithContacts([
            'title' => 'Erneuerung Pausenplatzareal',
            'description' => 'Projektgruppe zur Neugestaltung und Erneuerung des Pausenplatzes. Planung und Umsetzung von Spielgeräten und Gestaltungselementen.',
            'category' => 'haus_umgebung_taskforces',
            'contact_name' => 'Julia Eisenhut, Sami Eisenhut',
            'contact_email' => 'pausenplatz@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Julia Eisenhut', 'Sami Eisenhut']);

        // PRODUKTION
        $this->createWithContacts([
            'title' => 'Filzgruppe',
            'description' => 'Herstellung von gefilzten Produkten für Märkte und Anlässe. Filzkurse und gemeinsames Filzen.',
            'category' => 'produktion',
            'contact_name' => 'Maria Mani',
            'contact_email' => 'filzen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Maria Mani']);

        $this->createWithContacts([
            'title' => 'Kerzenziehen',
            'description' => null,
            'category' => 'produktion',
            'contact_name' => 'Rene Winkler',
            'contact_email' => 'kerzen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Rene Winkler']);

        $this->createWithContacts([
            'title' => 'Puppen-Nähen',
            'description' => 'Herstellung von Waldorfpuppen und anderen genähten Spielsachen für Märkte.',
            'category' => 'produktion',
            'contact_name' => 'Manila Dür',
            'contact_email' => 'puppen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Manila Dür']);

        $this->createWithContacts([
            'title' => 'Kranzgruppe',
            'description' => 'Herstellung von Adventskränzen und anderen jahreszeitlichen Kränzen.',
            'category' => 'produktion',
            'contact_name' => 'Elsa Zürcher Ledermann',
            'contact_email' => 'kranz@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Elsa Zürcher Ledermann']);

        $this->createWithContacts([
            'title' => 'Seifenherstellung',
            'description' => 'Produktion von handgemachten Seifen für Märkte und Anlässe.',
            'category' => 'produktion',
            'contact_name' => 'Claudia Pereira',
            'contact_email' => 'seife@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Claudia Pereira']);

        $this->createWithContacts([
            'title' => 'Backgruppe',
            'description' => 'Backen von Brot, Kuchen und Gebäck für Schulanlässe und Märkte.',
            'category' => 'produktion',
            'contact_name' => 'Swenja Heyers, Matthias Frey',
            'contact_email' => 'backen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Swenja Heyers', 'Matthias Frey']);

        $this->createWithContacts([
            'title' => 'Tee-Produktion',
            'description' => 'Herstellung und Verpackung von Kräutertees für den Verkauf.',
            'category' => 'produktion',
            'contact_name' => 'Anna Stalder',
            'contact_email' => 'tee@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Anna Stalder']);

        $this->createWithContacts([
            'title' => 'Päcklifischen',
            'description' => 'Organisation des traditionellen Päcklifischens am Weihnachtsmarkt.',
            'category' => 'produktion',
            'contact_name' => 'Manuela Tschanz',
            'contact_email' => 'paecklifischen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Manuela Tschanz']);

        $this->createWithContacts([
            'title' => 'Lebkuchenverzieren',
            'description' => 'Lebkuchen backen und verzieren für den Weihnachtsmarkt.',
            'category' => 'produktion',
            'contact_name' => 'Tom Schick',
            'contact_email' => 'lebkuchen@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Tom Schick']);

        // ORGANISATION
        $this->createWithContacts([
            'title' => 'Liegenschaftsverein der RSS Langnau',
            'description' => 'Verwaltung der Schulliegenschaften.',
            'category' => 'organisation',
            'contact_name' => 'Christian Konopka',
            'contact_email' => 'liegenschaft@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Christian Konopka']);

        $this->createWithContacts([
            'title' => 'Elternrat',
            'description' => null,
            'category' => 'organisation',
            'contact_name' => 'Tatjana Baumgartner, Maria Mani',
            'contact_email' => 'elternrat@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Tatjana Baumgartner', 'Maria Mani']);

        $this->createWithContacts([
            'title' => 'Plakate',
            'description' => 'Gestaltung und Aushang von Plakaten für Schulanlässe.',
            'category' => 'organisation',
            'contact_name' => 'Rebekka Schaerer',
            'contact_email' => 'plakate@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Rebekka Schaerer']);

        $this->createWithContacts([
            'title' => 'Qualitätsgruppe (WzQ)',
            'description' => 'Qualitätsentwicklung und -sicherung an der Schule. Wege zur Qualität.',
            'category' => 'organisation',
            'contact_name' => 'Marianne Wey',
            'contact_email' => 'wzq@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Marianne Wey']);

        $this->createWithContacts([
            'title' => 'Öffentlichkeitsarbeit',
            'description' => 'Kommunikation nach aussen, Pressemitteilungen, Website und Social Media.',
            'category' => 'organisation',
            'contact_name' => 'Yves Bönzli',
            'contact_email' => 'pr@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Yves Bönzli']);

        $this->createWithContacts([
            'title' => 'Elterngesprächsgruppe (Erstgespräche)',
            'description' => 'Führung von Erstgesprächen mit interessierten Eltern.',
            'category' => 'organisation',
            'contact_name' => 'Sandra Lanz, Heinz Ledermann, Tamás Mokos',
            'contact_email' => 'erstgespraeche@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Sandra Lanz', 'Heinz Ledermann', 'Tamás Mokos']);

        $this->createWithContacts([
            'title' => 'Forum',
            'description' => 'Organisation und Moderation des Schulforums.',
            'category' => 'organisation',
            'contact_name' => 'Marisa Frey, Susanne Marienfeld',
            'contact_email' => 'forum@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Marisa Frey', 'Susanne Marienfeld']);

        $this->createWithContacts([
            'title' => 'Budgetkommission',
            'description' => 'Budgetplanung und -kontrolle für die Schule.',
            'category' => 'organisation',
            'contact_name' => 'Marianne Wey',
            'contact_email' => 'budget@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Marianne Wey']);

        $this->createWithContacts([
            'title' => 'IT Langnau / RSS Cloud',
            'description' => 'IT-Support und Verwaltung der digitalen Infrastruktur.',
            'category' => 'organisation',
            'contact_name' => 'Matthias Hartmann',
            'contact_email' => 'it@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Matthias Hartmann']);

        $this->createWithContacts([
            'title' => 'Mediengruppe',
            'description' => 'Medienpädagogik und Medienkonzept der Schule.',
            'category' => 'organisation',
            'contact_name' => 'Christa Aeschlimann, Christian Brendle',
            'contact_email' => 'medien@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Christa Aeschlimann', 'Christian Brendle']);

        // VERKAUF
        $this->createWithContacts([
            'title' => 'ProBon-Aktion',
            'description' => 'Organisation der ProBon-Verkaufsaktion zur Unterstützung der Schule.',
            'category' => 'verkauf',
            'contact_name' => 'Daniela Wüthrich',
            'contact_email' => 'probon@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Daniela Wüthrich']);

        $this->createWithContacts([
            'title' => 'Lachsverkauf',
            'description' => 'Jährlicher Verkauf von Räucherlachs zur Weihnachtszeit.',
            'category' => 'verkauf',
            'contact_name' => 'Gisela Wyss',
            'contact_email' => 'lachs@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Gisela Wyss']);

        $this->createWithContacts([
            'title' => 'WELEDA-Bestellaktion',
            'description' => 'Sammelbestellung von WELEDA-Produkten mit Rabatt für die Schulgemeinschaft.',
            'category' => 'verkauf',
            'contact_name' => 'Rémy Reist',
            'contact_email' => 'weleda@steinerschule-langnau.ch',
            'has_forum' => true,
            'is_active' => true,
        ], ['Rémy Reist']);
    }
}
