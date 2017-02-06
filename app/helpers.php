<?php
/**
 * Created by PhpStorm.
 * User: asnike
 * Date: 2017-01-03
 * Time: 오후 10:55
 */


if (!function_exists('icon')) {
    function icon($class, $addition = 'icon', $inline = null) {
        $icon   = config('icons.' . $class);
        $inline = $inline ? 'style="' . $inline . '"' : null;

        return sprintf('<i class="%s %s" %s></i>', $icon, $addition, $inline);
    }
}
