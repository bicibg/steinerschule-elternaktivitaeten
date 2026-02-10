<?php

namespace App\Services;

use App\Models\SchoolEvent;
use Illuminate\Support\Str;
use Sabre\VObject\Reader;

class IcsImportService
{
    protected int $created = 0;

    protected int $updated = 0;

    protected int $skipped = 0;

    public function import(string $filePath): array
    {
        $this->created = 0;
        $this->updated = 0;
        $this->skipped = 0;

        $vcalendar = Reader::read(
            fopen($filePath, 'r'),
            Reader::OPTION_FORGIVING | Reader::OPTION_IGNORE_INVALID_LINES
        );

        if (! isset($vcalendar->VEVENT)) {
            return $this->results();
        }

        foreach ($vcalendar->VEVENT as $vevent) {
            $this->importEvent($vevent);
        }

        $vcalendar->destroy();

        return $this->results();
    }

    protected function importEvent($vevent): void
    {
        $title = trim((string) ($vevent->SUMMARY ?? ''));
        if ($title === '') {
            $this->skipped++;
            return;
        }

        $dtstart = $vevent->DTSTART;
        if (! $dtstart) {
            $this->skipped++;
            return;
        }

        $allDay = $this->isAllDay($dtstart);
        $startDate = $dtstart->getDateTime();
        $endDate = null;

        if (isset($vevent->DTEND)) {
            $endDate = $vevent->DTEND->getDateTime();
            // iCal DTEND for all-day events is exclusive (day after last day)
            if ($allDay) {
                $endDate = $endDate->modify('-1 day');
            }
            // If end equals start after adjustment, no separate end needed
            if ($endDate->format('Y-m-d') === $startDate->format('Y-m-d')) {
                $endDate = null;
            }
        }

        $description = trim((string) ($vevent->DESCRIPTION ?? ''));
        $location = trim((string) ($vevent->LOCATION ?? ''));
        $eventType = $this->guessEventType($title);

        $eventTime = null;
        if (! $allDay) {
            $eventTime = $startDate->format('H:i') . ' Uhr';
        }

        $event = SchoolEvent::firstOrNew([
            'title' => $title,
            'start_date' => $startDate->format('Y-m-d'),
        ]);

        $isNew = ! $event->exists;

        $event->fill([
            'description' => $description ?: $event->description,
            'end_date' => $endDate?->format('Y-m-d') ?? $event->end_date,
            'event_time' => $eventTime ?? $event->event_time,
            'location' => $location ?: $event->location,
            'event_type' => $isNew ? $eventType : $event->event_type,
            'all_day' => $allDay,
        ]);

        $event->save();

        if ($isNew) {
            $this->created++;
        } else {
            $this->updated++;
        }
    }

    protected function isAllDay($dtstart): bool
    {
        // VALUE=DATE means all-day event (no time component)
        $valueParam = $dtstart->offsetGet('VALUE');

        return $valueParam && strtoupper((string) $valueParam) === 'DATE';
    }

    protected function guessEventType(string $title): string
    {
        $lower = mb_strtolower($title);

        $keywords = [
            'holiday' => ['ferien', 'feiertag', 'schulferien', 'herbstferien', 'winterferien', 'frühlingsferien', 'sommerferien', 'sportferien', 'auffahrt', 'pfingst'],
            'festival' => ['fest', 'basar', 'markt', 'flohmarkt', 'sommerfest', 'winterfest', 'herbstfest', 'frühlingsfest', 'jubiläum', 'weihnacht', 'advent', 'laterne', 'johanni', 'michael', 'erntedank', 'ostern'],
            'performance' => ['aufführung', 'theater', 'konzert', 'eurythmie', 'klassenspiel', 'monatsfeier', 'vorführung', 'chor'],
            'meeting' => ['versammlung', 'elternabend', 'sitzung', 'konferenz', 'gespräch', 'info', 'vortrag'],
            'sports' => ['sport', 'olympiade', 'turnier', 'lauf', 'wanderung', 'spieltag'],
            'excursion' => ['ausflug', 'exkursion', 'reise', 'lager', 'camp', 'waldorf'],
        ];

        foreach ($keywords as $type => $words) {
            foreach ($words as $word) {
                if (str_contains($lower, $word)) {
                    return $type;
                }
            }
        }

        return 'other';
    }

    protected function results(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'skipped' => $this->skipped,
        ];
    }
}
