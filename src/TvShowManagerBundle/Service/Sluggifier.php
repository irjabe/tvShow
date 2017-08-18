<?php

namespace TvShowManagerBundle\Service;

class Sluggifier
{

    public function slug($name)
    {
        // replace non letter or digits by -
        $name = preg_replace('#[^\\pL\d]+#u', '-', $name);
        // trim
        $name = trim($name, '-');
        // transliterate
        if (function_exists('iconv'))
        {
            $name = iconv('utf-8', 'us-ascii//TRANSLIT', $name);
        }
        // lowercase
        $name = strtolower($name);
        // remove unwanted characters
        $slug = preg_replace('#[^-\w]+#', '', $name);
        if (empty($name))
        {
            return 'n-a';
        }
        return $slug;
    }
}