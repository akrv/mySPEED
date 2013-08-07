<?php
/**
* @package joomadvertisement
* @version 1.5
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import MODEL object class
jimport('joomla.application.component.model');


class qrcode_detailModelqrcode_detail extends JModel
{
	/**
	 * joomadvertisement_detail id
	 *
	 * @var int
	 */
	var $_id = null;
	/**
	 * joomadvertisement_detail data
	 *
	 * @var array
	 */
	var $_data = null;
	/**
	 * table_prefix - table prefix for all component table
	 * 
	 * @var string
	 */
	var $_table_prefix = null;
	/**
	 * Constructor
	 *
	 *	set id of joomadvertisement detail 
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();

		//initialize class property
	  $this->_table_prefix = 'jos_ijoomer_';		
	  
		$array = JRequest::getVar('id',  0, '', 'array');
		$this->setId((int)$array[0]);

	}

	/**
	 * Method to set the joomadvertisement_detail identifier
	 *
	 * @access	public
	 * @param	int joomadvertisement_detail identifier
	 */
	function setId($id)
	{
		// Set joomadvertisement_detail id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}

	
	/**
	 * Method to get a joomadvertisement data
	 *
	 * this method is called from the owner VIEW by VIEW->get('Data');
	 * -  get detail of joomadvertisement.
	 * - 	if detail exists then load else int a new detail 
	 * -  check if the country is published if not raise exception.
	 * @since 1.5
	 */
	function &getData()
	{
		//DEVNOTE:  Load the joomadvertisement_detail data
		if ($this->_loadData())
		{}
		//DEVNOTE: init a new detail
		else  $this->_initData();

   	return $this->_data;
	}
	
	/**
	 * Method to checkout/lock the joomadvertisement_detail
	 *
	 * @access	public
	 * @param	int	$uid	User ID of the user checking the helloworl detail out
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkout($uid = null)
	{
		if ($this->_id)
		{
			// Make sure we have a user id to checkout the article with
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			// Lets get to it and checkout the thing...
			$helloworld_detail = & $this->getTable();
			
			
			if(!$helloworld_detail->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}
	/**
	 * Method to checkin/unlock the joomadvertisement_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function checkin()
	{
		if ($this->_id)
		{
			$helloworld_detail = & $this->getTable();
			if(! $helloworld_detail->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}	
	/**
	 * Tests if joomadvertisement_detail is checked out
	 *
	 * @access	public
	 * @param	int	A user id
	 * @return	boolean	True if checked out
	 * @since	1.5
	 */
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}	
		
	/**
	 * Method to load content joomadvertisement_detail data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM '.$this->_table_prefix.'qrcode_detail'.
 			' WHERE id = '. $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	/**
	 * Method to initialise the joomadvertisement_detail data
	 *
	 * @access	private
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$detail = new stdClass();
			$detail->id							= 0;
			$detail->url						= null;
			$detail->qrcode_image				= null;
			$detail->published					= 1;
			$this->_data				 		= $detail;
			return (boolean) $this->_data;
		}
		return true;
	}
  	

	/**
	 * Method to store the helloword text
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function store($data)
	{
	 	//DEVNOTE: give me JTable object			 	
		$row =& $this->getTable();
		
		//DEVNOTE: Bind the form fields to the joomadvertisement table
		
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		//DEVNOTE: Create the timestamp for the date field
		//$row->date = gmdate('Y-m-d H:i:s');
		
		//DEVNOTE: Make sure the joomadvertisement table is valid
		//JTable return always true but there is space to put
		//our custom check method
		if (!$row->check()) {
			
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		//DEVNOTE: Store the joomadvertisement detail record into the database
		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		return true;
	}
	/**
	 * Method to remove a joomadvertisement_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function delete($cid = array())
	{
		$result = false;
		if (count( $cid ))
		{
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM '.$this->_table_prefix.'qrcode_detail WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	
		/**
	 * Method to (un)publish a joomadvertisement_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE '.$this->_table_prefix.'qrcode_detail'
				. ' SET published = ' . intval( $publish )
				. ' WHERE id IN ( '.$cids.' )'
				. ' AND ( checked_out = 0 OR ( checked_out = ' .$user->get('id'). ' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}
	

	/**
	* Select list of active categories for components
	*/
	/*
	 * Function is used in the site/administrator : duplicate in JElementCategory
	 */
	/*function getCountry( $order='descript')
	{
		$query = 'SELECT id_country AS value, descript AS text'
		. ' FROM '.$this->_table_prefix.'country WHERE published > 0 '
		. ' ORDER BY '. $order;
		
		$this->_db->setQuery( $query );

		return $this->_db->loadObjectList();
	}*/	
	
	/**
	 * Method to move a joomadvertisement_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function move($direction)
	{
	//DEVNOTE: Load table class from com_joomadvertisement/tables/joomadvertisement_detail.php 	
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

//	echo $direction;
	
//	exit;	
  //DEVNOTE: call move method of JTABLE. 
  //first parameter: direction [up/down]
  //second parameter: condition
		if (!$row->move( $direction, ' id = '.$row->id.' AND published >= 0 ' )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	/**
	 * Method to savejoomadvertisementlloworld_detail
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();
		$groupings = array();

		// update ordering values
		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );
			// track categories
			$groupings[] = $row->catid;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		// execute updateOrder for each parent group
		$groupings = array_unique( $groupings );
		foreach ($groupings as $group){
		//DEVNOTE: reorder for each group(category)
			$row->reorder('catid = '.$group);
		}

		return true;
	}
		
}

?>
