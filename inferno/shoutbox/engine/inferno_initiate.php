<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

$script_allow = explode(',', preg_replace('#\s#', '', $this->vbulletin->options['ishout_pages']));

//if ((@in_array(THIS_SCRIPT, $script_allow) || $this->vbulletin->options['ishout_globaldeploy']) && $this->vbulletin->options['ishout_online'] && !$this->is_in_ug_list($this->vbulletin->options['ishout_hideshoutbox']))
{
	$this->setup_deployment();
}
?>