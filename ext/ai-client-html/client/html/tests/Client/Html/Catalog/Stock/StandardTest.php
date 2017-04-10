<?php

namespace Aimeos\Client\Html\Catalog\Stock;


/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $context;


	protected function setUp()
	{
		$this->context = \TestHelperHtml::getContext();

		$paths = \TestHelperHtml::getHtmlTemplatePaths();
		$this->object = new \Aimeos\Client\Html\Catalog\Stock\Standard( $this->context, $paths );
		$this->object->setView( \TestHelperHtml::getView() );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetHeader()
	{
		$output = $this->object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetHeaderException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Stock\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Exception() ) );

		$object->setView( \TestHelperHtml::getView() );

		$this->assertEquals( null, $object->getHeader() );
	}


	public function testGetBody()
	{
		$productId = $this->getProductItem()->getId();

		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 's_prodid' => $productId ) );
		$view->addHelper( 'param', $helper );

		$output = $this->object->getBody();
		$this->assertRegExp( '/"' . $productId . '".*stock-high/', $output );
	}


	public function testGetBodyStockUnlimited()
	{
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 's_prodid' => -1 ) );
		$view->addHelper( 'param', $helper );

		$output = $this->object->getBody();
		$this->assertRegExp( '/"-1".*stock-unlimited/', $output );
	}


	public function testGetBodyException()
	{
		$object = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Stock\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$object->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Exception() ) );

		$object->setView( \TestHelperHtml::getView() );

		$this->assertEquals( null, $object->getBody() );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( '$$$', '$$$' );
	}


	protected function getProductItem()
	{
		$manager = \Aimeos\MShop\Product\Manager\Factory::createManager( $this->context );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', 'CNC' ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( 'No product item with code "CNC" found' );
		}

		return $item;
	}
}
