<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Controller
 * @subpackage ExtJS
 */


namespace Aimeos\Controller\ExtJS\Customer\Lists;


/**
 * ExtJS customer list controller for admin interfaces.
 *
 * @package Controller
 * @subpackage ExtJS
 */
class Standard
	extends \Aimeos\Controller\ExtJS\Base
	implements \Aimeos\Controller\ExtJS\Common\Iface
{
	private $manager = null;


	/**
	 * Initializes the customer list controller.
	 *
	 * @param \Aimeos\MShop\Context\Item\Iface $context MShop context object
	 */
	public function __construct( \Aimeos\MShop\Context\Item\Iface $context )
	{
		parent::__construct( $context, 'Customer_Lists' );
	}


	/**
	 * Retrieves all items matching the given criteria.
	 *
	 * @param \stdClass $params Associative array containing the parameters
	 * @return array List of associative arrays with item properties, total number of items and success property
	 */
	public function searchItems( \stdClass $params )
	{
		$this->checkParams( $params, array( 'site' ) );
		$this->setLocale( $params->site );

		$totalList = 0;
		$search = $this->initCriteria( $this->getManager()->createSearch(), $params );
		$result = $this->getManager()->searchItems( $search, array(), $totalList );

		$idLists = array();
		$listItems = array();

		foreach( $result as $item )
		{
			if( ( $domain = $item->getDomain() ) != '' ) {
				$idLists[$domain][] = $item->getRefId();
			}
			$listItems[] = (object) $item->toArray();
		}

		return array(
			'items' => $listItems,
			'total' => $totalList,
			'graph' => $this->getDomainItems( $idLists ),
			'success' => true,
		);
	}


	/**
	 * Returns the schema of the item.
	 *
	 * @return array Associative list of "name" and "properties" list (including "description", "type" and "optional")
	 */
	public function getItemSchema()
	{
		$attributes = $this->getManager()->getSearchAttributes( false );
		$properties = $this->getAttributeSchema( $attributes );

		$properties['customer.lists.type'] = array(
			'description' => 'Customer list type code',
			'optional' => false,
			'type' => 'string',
		);
		$properties['customer.lists.typename'] = array(
			'description' => 'Customer list type name',
			'optional' => false,
			'type' => 'string',
		);

		return array(
			'name' => 'Customer_Lists',
			'properties' => $properties,
		);
	}


	/**
	 * Returns the manager the controller is using.
	 *
	 * @return \Aimeos\MShop\Common\Manager\Iface Manager object
	 */
	protected function getManager()
	{
		if( $this->manager === null ) {
			$this->manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'customer/lists' );
		}

		return $this->manager;
	}


	/**
	 * Returns the prefix for searching items
	 *
	 * @return string MShop search key prefix
	 */
	protected function getPrefix()
	{
		return 'customer.lists';
	}


	/**
	 * Transforms ExtJS values to be suitable for storing them
	 *
	 * @param \stdClass $entry Entry object from ExtJS
	 * @return \stdClass Modified object
	 */
	protected function transformValues( \stdClass $entry )
	{
		if( isset( $entry->{'customer.lists.datestart'} ) && $entry->{'customer.lists.datestart'} != '' ) {
			$entry->{'customer.lists.datestart'} = str_replace( 'T', ' ', $entry->{'customer.lists.datestart'} );
		} else {
			$entry->{'customer.lists.datestart'} = null;
		}

		if( isset( $entry->{'customer.lists.dateend'} ) && $entry->{'customer.lists.dateend'} != '' ) {
			$entry->{'customer.lists.dateend'} = str_replace( 'T', ' ', $entry->{'customer.lists.dateend'} );
		} else {
			$entry->{'customer.lists.dateend'} = null;
		}

		if( isset( $entry->{'customer.lists.config'} ) ) {
			$entry->{'customer.lists.config'} = (array) $entry->{'customer.lists.config'};
		}

		return $entry;
	}
}
