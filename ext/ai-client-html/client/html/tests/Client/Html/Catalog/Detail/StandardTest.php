<?php

namespace Aimeos\Client\Html\Catalog\Detail;


/**
 * @copyright Metaways Infosystems GmbH, 2012
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

		$this->object = new \Aimeos\Client\Html\Catalog\Detail\Standard( $this->context, $paths );
		$this->object->setView( \TestHelperHtml::getView() );
	}


	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetHeader()
	{
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 'd_prodid' => $this->getProductItem()->getId() ) );
		$view->addHelper( 'param', $helper );

		$tags = array();
		$expire = null;
		$output = $this->object->getHeader( 1, $tags, $expire );

		$this->assertStringStartsWith( '	<title>Cafe Noire Cappuccino</title>', $output );
		$this->assertEquals( '2022-01-01 00:00:00', $expire );
		$this->assertEquals( 6, count( $tags ) );
	}


	public function testGetHeaderException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperHtml::getHtmlTemplatePaths() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Exception() ) );

		$mock->getHeader();
	}


	public function testGetBody()
	{
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 'd_prodid' => $this->getProductItem()->getId() ) );
		$view->addHelper( 'param', $helper );

		$tags = array();
		$expire = null;
		$output = $this->object->getBody( 1, $tags, $expire );

		$this->assertStringStartsWith( '<section class="aimeos catalog-detail"', $output );
		$this->assertEquals( '2022-01-01 00:00:00', $expire );
		$this->assertEquals( 6, count( $tags ) );
	}


	public function testGetBodyDefaultId()
	{
		$context = clone $this->context;
		$context->getConfig()->set( 'client/html/catalog/detail/prodid-default', $this->getProductItem()->getId() );

		$paths = \TestHelperHtml::getHtmlTemplatePaths();
		$this->object = new \Aimeos\Client\Html\Catalog\Detail\Standard( $context, $paths );
		$this->object->setView( \TestHelperHtml::getView() );

		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array() );
		$view->addHelper( 'param', $helper );

		$output = $this->object->getBody();

		$this->assertContains( '<span class="value" itemprop="sku">CNC</span>', $output );
	}


	public function testGetBodyClientHtmlException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperHtml::getHtmlTemplatePaths() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Aimeos\Client\Html\Exception() ) );

		$mock->getBody();
	}


	public function testGetBodyControllerFrontendException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperHtml::getHtmlTemplatePaths() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Aimeos\Controller\Frontend\Exception() ) );

		$mock->getBody();
	}


	public function testGetBodyMShopException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperHtml::getHtmlTemplatePaths() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$mock->getBody();
	}


	public function testGetBodyException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, \TestHelperHtml::getHtmlTemplatePaths() ) )
			->setMethods( array( 'setViewParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'setViewParams' )
			->will( $this->throwException( new \Exception() ) );

		$mock->getBody();
	}


	public function testGetSubClient()
	{
		$client = $this->object->getSubClient( 'basic', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Client\\HTML\\Iface', $client );
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


	public function testProcess()
	{
		$this->object->process();
	}


	public function testProcessClientHtmlException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'getClientParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'getClientParams' )
			->will( $this->throwException( new \Aimeos\Client\Html\Exception() ) );

		$mock->process();
	}


	public function testProcessControllerFrontendException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'getClientParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'getClientParams' )
			->will( $this->throwException( new \Aimeos\Controller\Frontend\Exception() ) );

		$mock->process();
	}


	public function testProcessMShopException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'getClientParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'getClientParams' )
			->will( $this->throwException( new \Aimeos\MShop\Exception() ) );

		$mock->process();
	}


	public function testProcessException()
	{
		$mock = $this->getMockBuilder( '\Aimeos\Client\Html\Catalog\Detail\Standard' )
			->setConstructorArgs( array( $this->context, array() ) )
			->setMethods( array( 'getClientParams' ) )
			->getMock();

		$mock->setView( \TestHelperHtml::getView() );

		$mock->expects( $this->once() )->method( 'getClientParams' )
			->will( $this->throwException( new \Exception() ) );

		$mock->process();
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
