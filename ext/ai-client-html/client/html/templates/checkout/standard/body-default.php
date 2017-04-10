<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', array() );

$checkoutTarget = $this->config( 'client/html/checkout/standard/url/target' );
$checkoutController = $this->config( 'client/html/checkout/standard/url/controller', 'checkout' );
$checkoutAction = $this->config( 'client/html/checkout/standard/url/action', 'index' );
$checkoutConfig = $this->config( 'client/html/checkout/standard/url/config', array() );

$link = true;
$stepActive = $this->get( 'standardStepActive', false );

?>
<?php $this->block()->start( 'checkout/standard' ); ?>
<section class="aimeos checkout-standard">
	<nav>
		<ol class="steps">
			<li class="step basket active"><a href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array(), array(), $basketConfig ) ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Basket' ), $enc::TRUST ); ?></a></li>
<?php foreach( $this->get( 'standardSteps', array() ) as $name ) :

		$class = '';

		if( $stepActive )
		{
			if( $name === $stepActive )
			{
				$class .= ' current';
				$link = false;
			}

			if( $link === true ) {
				$class .= ' active';
			}
		}
?>
			<li class="step <?php echo $name . $class; ?>">
<?php	if( $stepActive && $link ) : ?>
				<a href="<?php echo $enc->attr( $this->url( $checkoutTarget, $checkoutController, $checkoutAction, array( 'c_step' => $name ), array(), $checkoutConfig ) ); ?>">
<?php	endif; ?>
					<?php echo $enc->html( $this->translate( 'client', $name ) ); ?>
<?php	if( $stepActive && $link ) : ?>
				</a>
<?php	endif; ?>
			</li>
<?php endforeach; ?>
		</ol>
	</nav>
<?php if( isset( $this->standardErrorList ) ) : ?>
	<ul class="error-list">
<?php foreach( (array) $this->standardErrorList as $errmsg ) : ?>
		<li class="error-item"><?php echo $enc->html( $errmsg ); ?></li>
<?php endforeach; ?>
	</ul>
<?php endif; ?>
	<form method="<?php echo $enc->attr( $this->get( 'standardMethod', 'POST' ) ); ?>" action="<?php echo $enc->attr( $this->get( 'standardUrlNext' ) ); ?>">
<?php echo $this->csrf()->formfield(); ?>
<?php echo $this->get( 'standardBody' ); ?>
	</form>
</section>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'checkout/standard' ); ?>
