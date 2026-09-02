<?php

namespace App\Observers;

use App\Enums\SlideGroupEnum;
use App\Models\Slide;
use Illuminate\Support\Facades\Cache;

class SlideObserver
{
    public function saved(Slide $slide): void
    {
        $this->clearCache($slide);
    }

    public function deleted(Slide $slide): void
    {
        $this->clearCache($slide);
    }

    private function clearCache(Slide $slide): void
    {
        $groups = [$slide->group];

        if ($slide->wasChanged('group')) {
            $groups[] = SlideGroupEnum::tryFrom((int) $slide->getRawOriginal('group'));
        }

        foreach (array_filter(array_unique($groups, SORT_REGULAR)) as $group) {
            Cache::store('api')->forget('home_widgets_sliders' . $group->value);
        }
    }
}
