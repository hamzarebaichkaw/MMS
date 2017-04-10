<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */


namespace Aimeos\Admin\JsonAdm\Common\Factory;


class BaseTest extends \PHPUnit_Framework_TestCase
{
	private $context;


	protected function setUp()
	{
		$this->context = \TestHelperJadm::getContext();
		$config = $this->context->getConfig();

		$config->set( 'admin/jsonadm/common/decorators/default', array() );
		$config->set( 'admin/jsonadm/decorators/global', array() );
		$config->set( 'admin/jsonadm/decorators/local', array() );

	}


	public function testInjectClient()
	{
		$cntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );
		\Aimeos\Admin\JsonAdm\Factory::injectClient( '\\Aimeos\\Admin\\JsonAdm\\Standard', $cntl );

		$iCntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );

		$this->assertSame( $cntl, $iCntl );
	}


	public function testInjectClientReset()
	{
		$cntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );
		\Aimeos\Admin\JsonAdm\Factory::injectClient( '\\Aimeos\\Admin\\JsonAdm\\Standard', $cntl );
		\Aimeos\Admin\JsonAdm\Factory::injectClient( '\\Aimeos\\Admin\\JsonAdm\\Standard', null );

		$new = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );

		$this->assertNotSame( $cntl, $new );
	}


	public function testAddDecoratorsInvalidName()
	{
		$decorators = array( '$' );
		$view = $this->context->getView();
		$cntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );

		$this->setExpectedException( '\\Aimeos\\Admin\\JsonAdm\\Exception' );
		\Aimeos\Admin\JsonAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $cntl, $decorators, 'Test', $this->context, $view, array(), 'attribute' );
	}


	public function testAddDecoratorsInvalidClass()
	{
		$decorators = array( 'Test' );
		$view = $this->context->getView();
		$cntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );

		$this->setExpectedException( '\\Aimeos\\Admin\\JsonAdm\\Exception' );
		\Aimeos\Admin\JsonAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $cntl, $decorators, 'TestDecorator', $this->context, $view, array(), 'attribute' );
	}


	public function testAddDecoratorsInvalidInterface()
	{
		$decorators = array( 'Test' );
		$view = $this->context->getView();
		$cntl = \Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );

		$this->setExpectedException( '\\Aimeos\\Admin\\JsonAdm\\Exception' );
		\Aimeos\Admin\JsonAdm\Common\Factory\TestAbstract::addDecoratorsPublic( $cntl, $decorators,
			'\\Aimeos\\Admin\\Jsonadm\\Common\\Decorator\\', $this->context, $view, array(), 'attribute' );
	}


	public function testAddClientDecoratorsExcludes()
	{
		$this->context->getConfig()->set( 'admin/jsonadm/decorators/excludes', array( 'TestDecorator' ) );
		$this->context->getConfig()->set( 'admin/jsonadm/common/decorators/default', array( 'TestDecorator' ) );

		$this->setExpectedException( '\\Aimeos\\Admin\\JsonAdm\\Exception' );
		\Aimeos\Admin\JsonAdm\Factory::createClient( $this->context, array(), 'attribute', 'Standard' );
	}
}


class TestAbstract
	extends \Aimeos\Admin\JsonAdm\Common\Factory\Base
{
	/**
	 * @param string $classprefix
	 * @param string $path
	 */
	public static function addDecoratorsPublic( \Aimeos\Admin\JsonAdm\Iface $client, array $decorators, $classprefix,
		\Aimeos\MShop\Context\Item\Iface $context, \Aimeos\MW\View\Iface $view, $templatePaths, $path )
	{
		self::addDecorators( $client, $decorators, $classprefix, $context, $view, $templatePaths, $path );
	}

	public static function addClientDecoratorsPublic( \Aimeos\Admin\JsonAdm\Iface $client,
		\Aimeos\MShop\Context\Item\Iface $context, \Aimeos\MW\View\Iface $view, $templatePaths, $path )
	{
		self::addClientDecorators( $client, $context, $view, $templatePaths, $path );
	}
}


class TestDecorator
{
}
