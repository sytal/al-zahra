<?php

namespace App\Support;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ListSeo
{
    /**
     * @param  iterable<int, array{name: string, url: string}>  $items
     */
    public static function head(string $title, string $description, ?string $image, iterable $items, int $page = 1): HtmlString
    {
        $schema = SeoSchema::collectionPage($title, $description, url()->current(), $items);

        $html = Blade::render('<x-seo :title="$t" :description="$d" :image="$i" :schema="$s" />', [
            't' => $title,
            'd' => $description,
            'i' => $image,
            's' => $schema,
        ]);

        if ($page > 1) {
            $html .= '<meta name="robots" content="noindex,follow">';
        }

        return new HtmlString($html);
    }
}
