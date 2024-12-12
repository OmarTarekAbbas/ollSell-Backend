<?php

namespace App\Providers;

use App\Providers\LanguageTranslationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Basic\Entities\Translation;

class LanguageTranslationListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * param  \App\Providers\LanguageTranslationEvent  $event
     * return void
     */
    public function handle(LanguageTranslationEvent $event)
    {
       $translations = Translation::where('language_id',languageId())->get();
       foreach($translations as $translation){
       $new_translation =$translation->replicate();
       $new_translation->language_id = $event->lang_id;
       $new_translation->save();
       }
    }
}
