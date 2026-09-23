<?php

namespace App\Filament\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Add to a Filament Create/Edit page. Call syncMediaLibraryUploads() from
 * afterCreate()/afterSave(), passing a map of [form field => media collection name].
 *
 * The paired FileUpload form fields must NOT correspond to real database
 * columns (they are stripped harmlessly by Eloquent's mass-assignment
 * since they are absent from $fillable), and should disable Filament's
 * own file deletion-on-save handling being relied upon since media is
 * copied into Spatie MediaLibrary immediately after save.
 */
trait SavesMediaLibraryUploads
{
    /**
     * @param  array<string, string>  $map  ['form_field' => 'media_collection_name']
     */
    protected function syncMediaLibraryUploads(array $map): void
    {
        $data = $this->form->getState();

        foreach ($map as $field => $collection) {
            $path = data_get($data, $field);

            if (blank($path) || ! is_string($path)) {
                continue;
            }

            if (! Storage::disk('public')->exists($path)) {
                continue;
            }

            $this->record
                ->addMediaFromDisk($path, 'public')
                ->toMediaCollection($collection);
        }
    }
}
