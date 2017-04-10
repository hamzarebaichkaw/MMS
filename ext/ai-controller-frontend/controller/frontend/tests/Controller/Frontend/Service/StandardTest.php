<?php

namespace Aimeos\Controller\Frontend\Service;


/**
 * @copyright Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private static $basket;


	protected function setUp()
	{
		$this->object = new \Aimeos\Controller\Frontend\Service\Standard( \TestHelperFrontend::getContext() );
	}


	public static function setUpBeforeClass()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$orderBaseMgr = $orderManager->getSubManager( 'base' );
		self::$basket = $orderBaseMgr->createItem();
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetServices()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$services = $this->object->getServices( 'delivery', $basket );
		$this->assertGreaterThan( 0, count( $services ) );

		foreach( $services as $service ) {
			$this->assertInstanceOf( '\\Aimeos\\MShop\\Service\\Item\\Iface', $service );
		}
	}


	public function testGetServicesCache()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$this->object->getServices( 'delivery', $basket );
		$services = $this->object->getServices( 'delivery', $basket );

		$this->assertGreaterThan( 0, count( $services ) );
	}


	public function testGetServiceAttributes()
	{
		$service = $this->getServiceItem();
		$attributes = $this->object->getServiceAttributes( 'delivery', $service->getId(), self::$basket );

		$this->assertEquals( 0, count( $attributes ) );
	}


	public function testGetServiceAttributesCache()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$services = $this->object->getServices( 'delivery', $basket );

		if( ( $service = reset( $services ) ) === false ) {
			throw new \Exception( 'No service item found' );
		}

		$attributes = $this->object->getServiceAttributes( 'delivery', $service->getId(), self::$basket );

		$this->assertEquals( 0, count( $attributes ) );
	}


	public function testGetServiceAttributesNoItems()
	{
		$this->setExpectedException( '\\Aimeos\\Controller\\Frontend\\Service\\Exception' );
		$this->object->getServiceAttributes( 'invalid', -1, self::$basket );
	}


	public function testGetServicePrice()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$service = $this->getServiceItem();
		$price = $this->object->getServicePrice( 'delivery', $service->getId(), $basket );

		$this->assertEquals( '12.95', $price->getValue() );
		$this->assertEquals( '1.99', $price->getCosts() );
	}


	public function testGetServicePriceCache()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$services = $this->object->getServices( 'delivery', $basket );

		if( ( $service = reset( $services ) ) === false ) {
			throw new \Exception( 'No service item found' );
		}

		$price = $this->object->getServicePrice( 'delivery', $service->getId(), $basket );

		$this->assertEquals( '12.95', $price->getValue() );
		$this->assertEquals( '1.99', $price->getCosts() );
	}


	public function testGetServicePriceNoItems()
	{
		$orderManager = \Aimeos\MShop\Order\Manager\Factory::createManager( \TestHelperFrontend::getContext() );
		$basket = $orderManager->getSubManager( 'base' )->createItem();

		$this->setExpectedException( '\\Aimeos\\Controller\\Frontend\\Service\\Exception' );
		$this->object->getServicePrice( 'invalid', -1, $basket );
	}


	public function testCheckServiceAttributes()
	{
		$service = $this->getServiceItem();
		$attributes = $this->object->checkServiceAttributes( 'delivery', $service->getId(), array() );

		$this->assertEquals( array(), $attributes );
	}


	/**
	 * @return \Aimeos\MShop\Order\Item\Base\Iface
	 */
	protected function getServiceItem()
	{
		$serviceManager = \Aimeos\MShop\Service\Manager\Factory::createManager( \TestHelperFrontend::getContext() );

		$search = $serviceManager->createSearch( true );
		$expr = array(
			$search->getConditions(),
			$search->compare( '==', 'service.provider', 'Standard' ),
			$search->compare( '==', 'service.type.domain', 'service' ),
			$search->compare( '==', 'service.type.code', 'delivery' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		$services = $serviceManager->searchItems( $search );

		if( ( $service = reset( $services ) ) === false ) {
			throw new \Exception( 'No service item found' );
		}

		return $service;
	}
}
