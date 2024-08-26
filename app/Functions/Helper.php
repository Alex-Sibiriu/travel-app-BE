<?php

namespace App\Functions;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class Helper
{
    public static function generateSlug($string, $model)
    {
        $slug = Str::slug($string, '-');
        $original_slug = $slug;

        $exist = $model::where('slug', $slug)->first();

        $count = 1;

        while ($exist) {
            $slug = $original_slug . '-' . $count;
            $exist = $model::where('slug', $slug)->first();
            $count++;
        }

        return $slug;
    }
}
