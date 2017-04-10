<?php

namespace Aimeos\Controller\Frontend\Basket\Decorator;


class CategoryTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $context;


	protected function setUp()
	{
		$this->context = \TestHelperFrontend::getContext();
		$object = new \Aimeos\Controller\Frontend\Basket\Standard( $this->context );
		$this->object = new \Aimeos\Controller\Frontend\Basket\Decorator\Category( $object, $this->context );
	}


	protected function tearDown()
	{
		$this->object->clear();
		$this->context->getSession()->set( 'aimeos', array() );

		unset( $this->object );
	}


	public function testAddProductWithCategory()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$item = $manager->findItem( 'CNE' );

		$this->object->addProduct( $item->getId(), 5 );
	}


	public function testAddProductNoCategory()
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->context, 'product' );
		$item = $manager->findItem( 'ABCD' );

		$this->setExpectedException( '\Aimeos\Controller\Frontend\Basket\Exception' );
		$this->object->addProduct( $item->getId(), 5 );
	}
}
