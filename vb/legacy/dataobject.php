<?php if (!defined('VB_ENTRY')) die('Access denied.');

/*======================================================================*\
|| #################################################################### ||
|| # vBulletin 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright �2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/

/**
 * @package vBulletin
 * @subpackage Legacy
 * @author Kevin Sours, vBulletin Development Team
 * @version $Revision: 92140 $
 * @since NulleD By - vBSupport.org
 * @copyright vBulletin Solutions Inc.
 */

/**
 * Base class for legacy wrapper objects.
 *
 */
class vB_Legacy_Dataobject
{

	/**
	 * Get the value of a post field.
	 *
	 * @param string $field Name of field
	 */
	public function get_field($field)
	{
		if (!array_key_exists($field, $this->record))
		{
			//todo figure out exception handling.
			throw new Exception(get_class($this) . " does not have a field '$field'");
		}

		return $this->record [$field];
	}

	/**
	*	Alias of get_field to facilitate the transition of naming schemes on the
	* child objects (allows a particular child object to be referenced consistantly).
	*/
	public function getField($field)
	{
		return $this->get_field($field);
	}

	/**
	*	Is this field defined on the object
	*
	* @param string $field Name of field
	*/
	public function has_field($field)
	{
		return (isset($this->record[$field]));
	}

	public function hasField($field)
	{
		return $this->has_field($field);
	}

	/**
	 * Get the names of the fields set for this object
	 *
	 * @return array(string) Names of defined fields
	 */
	public function get_fieldnames()
	{
		return array_keys($this->record);
	}

	public function getFieldnames()
	{
		return $this->get_fieldnames();
	}

	/**
	 *	For when we need to handle the array directly.
	 *	Use sparingly -- mostly intended for interacting with legacy code.
	 *
	 *	return array(mixed) base record for the object
	 */
	public function get_record()
	{
		return $this->record;
	}

	public function getRecord()
	{
		return $this->get_record();
	}

	/**
	 *	Set the record for this object
	 *
	 *	for use by initilizer functions.
	 *
	 *	@param array(mixed) $record
	 */
	protected function set_record($record)
	{
		$this->record = $record;
	}

	protected function set_field($name, $value)
	{
		$this->record[$name] = $value;
	}


	/**
	 * @var array_mixed
	 */
	protected $record;
}

/*======================================================================*\
|| ####################################################################
|| # $Revision: 92140 $
|| # NulleD By - vBSupport.org
|| ####################################################################
\*======================================================================*/
