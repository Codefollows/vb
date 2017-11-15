<?php

/*
 * Inferno SB
 * Version 3.2.7
 * thunderbolt-tech.com
 */

global $infernoshout;

if (!$infernoshout->deploy)
{
	$infernoshout->load_editor_settings();
	$infernoshout->build_editor_select('colours', 'color');
	$infernoshout->build_editor_select('fonts', 'font-family');
	$iShout = array();
	$iShout = $infernoshout->editor_settings;
	$iShout['script'] = $infernoshout->script;
	$editor['colors'] = $infernoshout->editor_selects['colours'];
	$editor['fonts'] = $infernoshout->editor_selects['fonts'];
//	$template = 'inferno_shoutbox_editor';
	$templater = vB_Template::create('inferno_shoutbox_editor');
	$templater->register('editor',$editor);
	$editor = $templater->render();
//	$template = 'inferno_shoutbox_box';
	$templater = vB_Template::create('inferno_shoutbox_box');
	$templater->register('editor',$editor);
	$templater->register('iShout',$iShout);
	$infernoshout->box = $templater->render();

	if ($infernoshout->vbulletin->options['ishout_largertext'])
	{
		$infernoshout->box = str_replace('smallfont', '', $infernoshout->box);
	}
}
?>