<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$dlTarget = $this->config( 'client/html/account/download/url/target' );
$dlController = $this->config( 'client/html/account/download/url/controller', 'account' );
$dlAction = $this->config( 'client/html/account/download/url/action', 'download' );
$dlConfig = $this->config( 'client/html/account/download/url/config', array( 'absoluteUri' => 1 ) );

try {
	$products = $this->extOrderBaseItem->getProducts();
} catch( Exception $e ) {
	$products = array();
}

$priceTaxvalue = '0.00';

try
{
	$price = $this->extOrderBaseItem->getPrice();
	$priceValue = $price->getValue();
	$priceService = $price->getCosts();
	$priceRebate = $price->getRebate();
	$priceTaxflag = $price->getTaxFlag();
	$priceCurrency = $this->translate( 'client/currency', $price->getCurrencyId() );
}
catch( Exception $e )
{
	$priceValue = '0.00';
	$priceRebate = '0.00';
	$priceService = '0.00';
	$priceTaxflag = true;
	$priceCurrency = '';
}

try
{
	$deliveryPriceItem = $this->extOrderBaseItem->getService( 'delivery' )->getPrice();
	$deliveryPriceService = $deliveryPriceItem->getCosts();
	$deliveryPriceValue = $deliveryPriceItem->getValue();
}
catch( Exception $e )
{
	$deliveryPriceValue = '0.00';
	$deliveryPriceService = '0.00';
}

try
{
	$paymentPriceItem = $this->extOrderBaseItem->getService( 'payment' )->getPrice();
	$paymentPriceService = $paymentPriceItem->getCosts();
	$paymentPriceValue = $paymentPriceItem->getValue();
}
catch( Exception $e )
{
	$paymentPriceValue = '0.00';
	$paymentPriceService = '0.00';
}

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client', '%1$s %2$s' );
$unhide = $this->get( 'summaryShowDownloadAttributes', false );

?>
<?php $this->block()->start( 'email/common/text/summary/detail' ); ?>


<?php echo strip_tags( $this->translate( 'client', 'Order details' ) ); ?>:
<?php foreach( $products as $product ) : ?>
<?php	$price = $product->getPrice(); ?>

<?php echo strip_tags( $product->getName() ); ?> (<?php echo $product->getProductCode(); ?>)
<?php	foreach( array_merge( $product->getAttributes( 'config' ), $product->getAttributes( 'custom' ) ) as $attribute ) : ?>
- <?php 	echo strip_tags( $this->translate( 'client/code', $attribute->getCode() ) ); ?>: <?php echo strip_tags( ( $attribute->getName() != '' ? $attribute->getName() : $attribute->getValue() ) ); ?>

<?php	endforeach; ?>
<?php	foreach( $product->getAttributes( 'hidden' ) as $attribute ) : ?>
<?php		if( $unhide && $attribute->getCode() === 'download' ) : ?>
- <?php 		echo strip_tags( $attribute->getName()); ?>: <?php echo $this->url( $dlTarget, $dlController, $dlAction, array( 'dl_id' => $attribute->getId() ), array(), $dlConfig ); ?>

<?php		endif; ?>
<?php	endforeach; ?>
<?php echo strip_tags( $this->translate( 'client', 'Quantity' ) ); ?>: <?php echo $product->getQuantity(); ?>

<?php echo strip_tags( $this->translate( 'client', 'Price' ) ); ?>: <?php printf( $priceFormat, $this->number( $price->getValue() ), $priceCurrency ); ?>

<?php echo strip_tags( $this->translate( 'client', 'Sum' ) ); ?>: <?php printf( $priceFormat, $this->number( $price->getValue() * $product->getQuantity() ), $priceCurrency ); ?>

<?php endforeach; ?>

<?php if( ( $serviceValue = $deliveryPriceValue + $paymentPriceValue ) > 0 ) : ?>

<?php	echo strip_tags( $this->translate( 'client', 'Service fees' ) ); ?>: <?php printf( $priceFormat, $this->number( $serviceValue ), $priceCurrency ); ?>


<?php endif; ?>
<?php echo strip_tags( $this->translate( 'client', 'Sub-total' ) ); ?>: <?php printf( $priceFormat, $this->number( $priceValue ), $priceCurrency ); ?>

<?php if( $priceService - $paymentPriceService > 0 ) : ?>
<?php echo strip_tags( $this->translate( 'client', '+ Shipping' ) ); ?>: <?php printf( $priceFormat, $this->number( $priceService - $paymentPriceService ), $priceCurrency ); ?>

<?php endif; ?>
<?php if( $paymentPriceService > 0 ) : ?>
<?php	echo strip_tags( $this->translate( 'client', '+ Payment costs' ) ); ?>: <?php printf( $priceFormat, $this->number( $paymentPriceService ), $priceCurrency ); ?>

<?php endif; ?>
<?php if( $priceTaxflag === true ) : ?>
<?php	echo strip_tags( $this->translate( 'client', 'Total' ) ); ?>: <?php printf( $priceFormat, $this->number( $priceValue + $priceService ), $priceCurrency ); ?>

<?php endif; ?>
<?php foreach( $this->get( 'summaryTaxRates', array() ) as $taxRate => $priceItem ) : $taxValue = $priceItem->getTaxValue(); ?>
<?php	if( $taxRate > '0.00' && $taxValue > '0.00' ) : $priceTaxvalue += $taxValue; ?>
<?php		$taxFormat = ( $priceItem->getTaxFlag() ? $this->translate( 'client', 'Incl. %1$s%% VAT' ) : $this->translate( 'client', '+ %1$s%% VAT' ) ); ?>
<?php		echo strip_tags( sprintf( $taxFormat, $this->number( $taxRate ) ) ); ?>: <?php printf( $priceFormat, $this->number( $taxValue ), $priceCurrency ); ?>

<?php	endif; ?>
<?php endforeach; ?>
<?php if( $priceTaxflag === false ) : ?>
<?php	echo strip_tags( $this->translate( 'client', 'Total' ) ); ?>: <?php printf( $priceFormat, $this->number( $priceValue + $priceService + $priceTaxvalue ), $priceCurrency ); ?>

<?php endif; ?>
<?php if( $priceRebate > '0.00' ) : ?>
<?php echo strip_tags( $this->translate( 'client', 'Included rebates' ) ); ?>: <?php printf( $priceFormat, $this->number( $priceRebate ), $priceCurrency ); ?>

<?php endif; ?>
<?php echo $this->get( 'detailBody' ); ?>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'email/common/text/summary/detail' ); ?>
