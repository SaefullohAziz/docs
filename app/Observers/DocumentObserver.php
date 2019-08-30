<?php

namespace App\Observers;

use App\Document;
use App\Status;

class DocumentObserver
{
    /**
     * Handle the document "created" event.
     *
     * @param  \App\Document  $document
     * @return void
     */
    public function created(Document $document)
    {
        //
    }

    /**
     * Handle the document "updated" event.
     *
     * @param  \App\Document  $document
     * @return void
     */
    public function updated(Document $document)
    {
        //
    }

    /**
     * Handle the document "deleted" event.
     *
     * @param  \App\Document  $document
     * @return void
     */
    public function deleted(Document $document)
    {
        //
    }

    /**
     * Handle the document "restored" event.
     *
     * @param  \App\Document  $document
     * @return void
     */
    public function restored(Document $document)
    {
        //
    }

    /**
     * Handle the document "force deleted" event.
     *
     * @param  \App\Document  $document
     * @return void
     */
    public function forceDeleted(Document $document)
    {
        //
    }
}
