<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

global $infernoshout;

$output = str_replace('<!--SHOUTBOX-->', $infernoshout->box, $output);

unset($infernoshout);
?>