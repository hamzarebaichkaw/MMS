<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<?php $this->block()->start( 'checkout/confirm/basic' ); ?>
<div class="checkout-confirm-basic">
	<h2><?php echo $enc->html( $this->translate( 'client', 'Order status' ), $enc::TRUST ); ?></h2>
<?php if( isset( $this->confirmOrderItem ) ) : ?>
	<ul class="attr-list">
		<li class="form-item">
			<span class="name"><?php echo $enc->html( $this->translate( 'client', 'Order ID' ), $enc::TRUST ); ?></span>
			<span class="value"><?php echo $enc->html( $this->confirmOrderItem->getId() ); ?></span>
		</li>
		<li class="form-item">
			<span class="name"><?php echo $enc->html( $this->translate( 'client', 'Payment status' ), $enc::TRUST ); ?></span>
			<span class="value"><?php $code = 'pay:' . $this->confirmOrderItem->getPaymentStatus(); echo $enc->html( $this->translate( 'client/code', $code ) ); ?></span>
		</li>
	</ul>
<?php endif; ?>
<?php echo $this->get( 'basicBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'checkout/confirm/basic' ); ?>
