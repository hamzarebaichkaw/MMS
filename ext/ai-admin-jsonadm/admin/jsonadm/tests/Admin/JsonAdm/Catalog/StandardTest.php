<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\Admin\JsonAdm\Catalog;


class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $context;
	private $object;
	private $view;


	protected function setUp()
	{
		$this->context = \TestHelperJadm::getContext();
		$templatePaths = \TestHelperJadm::getJsonadmPaths();
		$this->view = $this->context->getView();

		$this->object = new \Aimeos\Admin\JsonAdm\Catalog\Standard( $this->context, $this->view, $templatePaths, 'catalog' );
	}


	public function testGetSearch()
	{
		$params = array(
			'filter' => array(
				'==' => array( 'catalog.code' => 'cafe' )
			),
			'include' => 'text'
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertEquals( 1, count( $result['data'] ) );
		$this->assertEquals( 'catalog', $result['data'][0]['type'] );
		$this->assertEquals( 6, count( $result['data'][0]['relationships']['text'] ) );
		$this->assertEquals( 6, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testGetTree()
	{
		$params = array(
			'id' => $this->getCatalogItem( 'group' )->getId(),
			'filter' => array(
				'==' => array( 'catalog.status' => 1 )
			),
			'include' => 'catalog,text'
		);
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$header = array();
		$status = 500;

		$result = json_decode( $this->object->get( '', $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertEquals( 'catalog', $result['data']['type'] );
		$this->assertEquals( 2, count( $result['data']['relationships']['catalog'] ) );
		$this->assertEquals( 2, count( $result['included'] ) );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPatch()
	{
		$stub = $this->getCatalogMock( array( 'getItem', 'moveItem', 'saveItem' ) );

		$stub->expects( $this->once() )->method( 'moveItem' );
		$stub->expects( $this->once() )->method( 'saveItem' );
		$stub->expects( $this->exactly( 2 ) )->method( 'getItem' ) // 2x due to decorator
			->will( $this->returnValue( $stub->createItem() ) );


		$params = array( 'id' => '-1' );
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $this->view, $params );
		$this->view->addHelper( 'param', $helper );

		$body = '{"data": {"parentid": "1", "targetid": 2, "type": "catalog", "attributes": {"catalog.label": "test"}}}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->patch( $body, $header, $status ), true );

		$this->assertEquals( 200, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( 'catalog', $result['data']['type'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	public function testPost()
	{
		$stub = $this->getCatalogMock( array( 'getItem', 'insertItem' ) );

		$stub->expects( $this->any() )->method( 'getItem' )
			->will( $this->returnValue( $stub->createItem() ) );
		$stub->expects( $this->once() )->method( 'insertItem' );


		$body = '{"data": {"type": "catalog", "attributes": {"catalog.code": "test", "catalog.label": "Test catalog"}}}';
		$header = array();
		$status = 500;

		$result = json_decode( $this->object->post( $body, $header, $status ), true );

		$this->assertEquals( 201, $status );
		$this->assertEquals( 1, count( $header ) );
		$this->assertEquals( 1, $result['meta']['total'] );
		$this->assertArrayHasKey( 'data', $result );
		$this->assertEquals( 'catalog', $result['data']['type'] );
		$this->assertArrayNotHasKey( 'included', $result );
		$this->assertArrayNotHasKey( 'errors', $result );
	}


	protected function getCatalogItem( $code )
	{
		$manager = \Aimeos\MShop\Catalog\Manager\Factory::createManager( $this->context );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'catalog.code', $code ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( sprintf( 'No catalog item with code "%1$s" found', $code ) );
		}

		return $item;
	}


	protected function getCatalogMock( array $methods )
	{
		$name = 'ClientJsonAdmStandard';
		$this->context->getConfig()->set( 'mshop/catalog/manager/name', $name );

		$stub = $this->getMockBuilder( '\\Aimeos\\MShop\\Catalog\\Manager\\Standard' )
			->setConstructorArgs( array( $this->context ) )
			->setMethods( $methods )
			->getMock();

		\Aimeos\MShop\Product\Manager\Factory::injectManager( '\\Aimeos\\MShop\\Catalog\\Manager\\' . $name, $stub );

		return $stub;
	}
}