<?php

namespace App\Observers;

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
        Cache::store('api')->forget('home_widgets_sliders' . $slide->group->name);
    }
}
