<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$target = $this->config( 'client/html/checkout/standard/url/target' );
$controller = $this->config( 'client/html/checkout/standard/url/controller', 'checkout' );
$action = $this->config( 'client/html/checkout/standard/url/action', 'index' );
$config = $this->config( 'client/html/checkout/standard/url/config', array() );

$changeUrl = $this->url( $target, $controller, $action, array( 'c_step' => 'payment' ), array(), $config );
$retryUrl = $this->url( $target, $controller, $action, array( 'c_step' => 'order', 'cs_option_terms' => 1, 'cs_option_terms_value' => 1, 'cs_order' => 1 ), array(), $config );

?>
<?php $this->block()->start( 'checkout/confirm/retry' ); ?>
<div class="checkout-confirm-retry">
<?php if( isset( $this->confirmOrderItem ) && $this->confirmOrderItem->getPaymentStatus() < \Aimeos\MShop\Order\Item\Base::PAY_REFUND ) : ?>
	<div class="button-group">
		<a class="standardbutton" href="<?php echo $enc->attr( $changeUrl ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Change payment' ), $enc::TRUST ); ?></a>
		<a class="standardbutton" href="<?php echo $enc->attr( $retryUrl ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Try again' ), $enc::TRUST ); ?></a>
	</div>
<?php endif; ?>
<?php echo $this->get( 'retryBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'checkout/confirm/retry' ); ?>
