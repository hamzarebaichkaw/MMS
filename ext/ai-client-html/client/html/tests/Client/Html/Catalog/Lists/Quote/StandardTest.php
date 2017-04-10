<?php

namespace Aimeos\Client\Html\Catalog\Lists\Quote;


/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$context = \TestHelperHtml::getContext();
		$paths = \TestHelperHtml::getHtmlTemplatePaths();
		$this->object = new \Aimeos\Client\Html\Catalog\Lists\Quote\Standard( $context, $paths );

		$catalogManager = \Aimeos\MShop\Catalog\Manager\Factory::createManager( $context );
		$search = $catalogManager->createSearch();
		$search->setConditions( $search->compare( '==', 'catalog.code', 'cafe' ) );
		$catItems = $catalogManager->searchItems( $search, array( 'text' ) );

		if( ( $catItem = reset( $catItems ) ) === false ) {
			throw new \Exception( 'No catalog item found' );
		}

		$view = \TestHelperHtml::getView();

		$view->listCatPath = array( $catalogManager->createItem(), $catItem );

		$this->object->setView( $view );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetHeader()
	{
		$output = $this->object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetBody()
	{
		$output = $this->object->getBody();
		$quoteItems = $this->object->getView()->get( 'quoteItems', array() );

		if( ( $quoteItem = reset( $quoteItems ) ) === false ) {
			throw new \Exception( 'No quote item found' );
		}

		$this->assertEquals( 'Kaffee Bewertungen', $quoteItem->getContent() );
		$this->assertStringStartsWith( '<div class="catalog-list-quote">', $output );
	}


	public function testGetSubClient()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testProcess()
	{
		$this->object->process();
	}
}
