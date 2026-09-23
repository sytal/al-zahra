<?php

namespace App\Modules\Resource\Services;

use App\Modules\Resource\Models\Resource;

class ResourceService
{
    public function recordDownload(Resource $resource): void
    {
        $resource->increment('download_count');
    }
}
