<?php

namespace Aimeos\Client\Html\Checkout\Standard\Summary;


/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $context;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->context = \TestHelperHtml::getContext();

		$paths = \TestHelperHtml::getHtmlTemplatePaths();
		$this->object = new \Aimeos\Client\Html\Checkout\Standard\Summary\Standard( $this->context, $paths );
		$this->object->setView( \TestHelperHtml::getView() );
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
		$controller = \Aimeos\Controller\Frontend\Basket\Factory::createController( $this->context );

		$view = \TestHelperHtml::getView();
		$view->standardStepActive = 'summary';
		$view->standardBasket = $controller->get();
		$this->object->setView( $view );

		$output = $this->object->getHeader();
		$this->assertNotNull( $output );
	}


	public function testGetHeaderOtherStep()
	{
		$output = $this->object->getHeader();
		$this->assertEquals( '', $output );
	}


	public function testGetBody()
	{
		$controller = \Aimeos\Controller\Frontend\Basket\Factory::createController( $this->context );

		$view = \TestHelperHtml::getView();
		$view->standardStepActive = 'summary';
		$view->standardBasket = $controller->get();
		$view->standardSteps = array( 'before', 'summary' );
		$this->object->setView( $view );

		$output = $this->object->getBody();
		$this->assertStringStartsWith( '<section class="checkout-standard-summary common-summary">', $output );
	}


	public function testGetBodyOtherStep()
	{
		$output = $this->object->getBody();
		$this->assertEquals( '', $output );
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
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Param\Standard( $view, array( 'cs_order' => 1 ) );
		$view->addHelper( 'param', $helper );
		$this->object->setView( $view );

		$this->setExpectedException( '\Aimeos\MShop\Order\Exception' );
		$this->object->process();
	}


	public function testProcessSkip()
	{
		$this->object->process();
	}
}
