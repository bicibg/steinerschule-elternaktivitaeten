<?php

namespace App\Filament\Resources\BulletinPostResource\Pages;

use App\Filament\Resources\BulletinPostResource;
use App\Models\Activity;
use Filament\Resources\Pages\CreateRecord;

class CreateBulletinPost extends CreateRecord
{
    protected static string $resource = BulletinPostResource::class;

    protected static ?string $title = 'Eintrag erstellen';

    public function mount(): void
    {
        parent::mount();

        $activityId = request()->query('activity_id');

        if ($activityId && $activity = Activity::with('contactUsers')->find($activityId)) {
            $this->form->fill([
                'activity_id' => $activity->id,
                'contactUsers' => $activity->contactUsers->pluck('id')->toArray(),
                'contact_name' => $activity->contact_name,
                'contact_email' => $activity->contact_email,
                'contact_phone' => $activity->contact_phone,
                'category' => $activity->category,
                'location' => $activity->meeting_location,
            ]);
        }
    }
}
