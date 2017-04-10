<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$totalQuantity = 0;
$enc = $this->encoder();

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', array() );

$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', array( 'absoluteUri' => 1 ) );


/** client/html/account/download/url/target
 * Destination of the URL where the controller specified in the URL is known
 *
 * The destination can be a page ID like in a content management system or the
 * module of a software development framework. This "target" must contain or know
 * the controller that should be called by the generated URL.
 *
 * @param string Destination of the URL
 * @since 2016.02
 * @category Developer
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/action
 * @see client/html/account/download/url/config
 */
$dlTarget = $this->config( 'client/html/account/download/url/target' );

/** client/html/account/download/url/controller
 * Name of the controller whose action should be called
 *
 * In Model-View-Controller (MVC) applications, the controller contains the methods
 * that create parts of the output displayed in the generated HTML page. Controller
 * names are usually alpha-numeric.
 *
 * @param string Name of the controller
 * @since 2016.02
 * @category Developer
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/action
 * @see client/html/account/download/url/config
 */
$dlController = $this->config( 'client/html/account/download/url/controller', 'account' );

/** client/html/account/download/url/action
 * Name of the action that should create the output
 *
 * In Model-View-Controller (MVC) applications, actions are the methods of a
 * controller that create parts of the output displayed in the generated HTML page.
 * Action names are usually alpha-numeric.
 *
 * @param string Name of the action
 * @since 2016.02
 * @category Developer
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/config
 */
$dlAction = $this->config( 'client/html/account/download/url/action', 'download' );

/** client/html/account/download/url/config
 * Associative list of configuration options used for generating the URL
 *
 * You can specify additional options as key/value pairs used when generating
 * the URLs, like
 *
 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
 *
 * The available key/value pairs depend on the application that embeds the e-commerce
 * framework. This is because the infrastructure of the application is used for
 * generating the URLs. The full list of available config options is referenced
 * in the "see also" section of this page.
 *
 * @param string Associative list of configuration options
 * @since 2016.02
 * @category Developer
 * @see client/html/account/download/url/target
 * @see client/html/account/download/url/controller
 * @see client/html/account/download/url/action
 */
$dlConfig = $this->config( 'client/html/account/download/url/config', array( 'absoluteUri' => 1 ) );

/** client/html/common/summary/detail/product/attribute/types
 * List of attribute type codes that should be displayed in the basket along with their product
 *
 * The products in the basket can store attributes that exactly define an ordered
 * product or which are important for the back office. By default, the product
 * variant attributes are always displayed and the configurable product attributes
 * are displayed separately.
 *
 * Additional attributes for each ordered product can be added by basket plugins.
 * Depending on the attribute types and if they should be shown to the customers,
 * you need to extend the list of displayed attribute types ab adding their codes
 * to the configurable list.
 *
 * @param array List of attribute type codes
 * @category Developer
 * @since 2014.09
 */
$attrTypes = $this->config( 'client/html/common/summary/detail/product/attribute/types', array( 'variant' ) );

$priceTaxvalue = '0.00';

if( isset( $this->summaryBasket ) )
{
	$price = $this->summaryBasket->getPrice();
	$priceValue = $price->getValue();
	$priceService = $price->getCosts();
	$priceRebate = $price->getRebate();
	$priceTaxflag = $price->getTaxFlag();
	$priceCurrency = $this->translate( 'client/currency', $price->getCurrencyId() );
}
else
{
	$priceValue = '0.00';
	$priceRebate = '0.00';
	$priceService = '0.00';
	$priceTaxflag = true;
	$priceCurrency = '';
}

try
{
	$deliveryItem = $this->summaryBasket->getService( 'delivery' );
	$deliveryName = $deliveryItem->getName();
	$deliveryPriceItem = $deliveryItem->getPrice();
	$deliveryPriceService = $deliveryPriceItem->getCosts();
	$deliveryPriceValue = $deliveryPriceItem->getValue();
}
catch( Exception $e )
{
	$deliveryName = '';
	$deliveryPriceValue = '0.00';
	$deliveryPriceService = '0.00';
}

try
{
	$paymentItem = $this->summaryBasket->getService( 'payment' );
	$paymentName = $paymentItem->getName();
	$paymentPriceItem = $paymentItem->getPrice();
	$paymentPriceService = $paymentPriceItem->getCosts();
	$paymentPriceValue = $paymentPriceItem->getValue();
}
catch( Exception $e )
{
	$paymentName = '';
	$paymentPriceValue = '0.00';
	$paymentPriceService = '0.00';
}

/// Price format with price value (%1$s) and currency (%2$s)
$priceFormat = $this->translate( 'client', '%1$s %2$s' );

$unhide = $this->get( 'summaryShowDownloadAttributes', false );
$modify = $this->get( 'summaryEnableModify', false );
$errors = $this->get( 'summaryErrorCodes', array() );
$backParams = $this->get( 'summaryParams', array() );

?>
<?php $this->block()->start( 'common/summary/detail' ); ?>
<div class="common-summary-detail container">
	<div class="header">
<?php if( isset( $this->summaryUrlBasket ) ) : ?>
		<a class="modify" href="<?php echo $enc->attr( $this->summaryUrlBasket ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?></a>
<?php endif; ?>
		<h2><?php echo $enc->html( $this->translate( 'client', 'Details' ), $enc::TRUST ); ?></h2>
	</div>
	<div class="basket">
		<table>
			<thead>
				<tr>
					<th class="details"></th>
					<th class="quantity"><?php echo $enc->html( $this->translate( 'client', 'Quantity' ), $enc::TRUST ); ?></th>
					<th class="unitprice"><?php echo $enc->html( $this->translate( 'client', 'Price' ), $enc::TRUST ); ?></th>
					<th class="price"><?php echo $enc->html( $this->translate( 'client', 'Sum' ), $enc::TRUST ); ?></th>
<?php if( $modify ) : ?>
					<th class="action"></th>
<?php endif; ?>
				</tr>
			</thead>
			<tbody>
<?php if( isset( $this->summaryBasket ) ) : ?>

<?php 	foreach( $this->summaryBasket->getProducts() as $position => $product ) : $totalQuantity += $product->getQuantity(); ?>
				<tr class="product <?php echo ( isset( $errors['product'][$position] ) ? 'error' : '' ); ?>">
					<td class="details">
<?php		if( ( $url = $product->getMediaUrl() ) != '' ) : ?>
						<img src="<?php echo $enc->attr( $this->content( $url ) ); ?>" />
<?php		endif; ?>
<?php		$params = array( 'd_prodid' => $product->getProductId(), 'd_name' => $product->getName( 'url' ) ); ?>
						<a class="product-name" href="<?php echo $enc->attr( $this->url( $detailTarget, $detailController, $detailAction, $params, array(), $detailConfig ) ); ?>">
<?php		echo $enc->html( $product->getName(), $enc::TRUST ); ?>
						</a>
						<p class="code">
							<span class="name"><?php echo $enc->html( $this->translate( 'client', 'Article no.:' ), $enc::TRUST ); ?></span>
							<span class="value"><?php echo $product->getProductCode(); ?></span>
						</p>
<?php		foreach( $attrTypes as $attrType ) : ?>
						<ul class="attr-list <?php echo $enc->attr( 'attr-list-' . $attrType ); ?>">
<?php			foreach( $product->getAttributes( $attrType ) as $attribute ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><?php echo $enc->html( ( $attribute->getName() != '' ? $attribute->getName() : $attribute->getValue() ) ); ?></span>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endforeach; ?>
<?php		if( ( $attributes = $product->getAttributes( 'config' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-config">
<?php			foreach( $attributes as $attribute ) : ?>
							<li class="attr-item">
<?php					if( $modify ) : ?>
<?php						$params = array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity(), 'b_attrconfcode' => $attribute->getCode() ); ?>
								<a class="change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, $params, array(), $basketConfig ) ); ?>">
<?php					endif; ?>
									<span class="sign">−</span>
									<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
									<span class="value"><?php echo $enc->html( ( $attribute->getName() != '' ? $attribute->getName() : $attribute->getValue() ) ); ?></span>
<?php					if( $modify ) : ?>
								</a>
<?php					endif; ?>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
<?php		if( ( $attributes = $product->getAttributes( 'custom' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-custom">
<?php			foreach( $attributes as $attribute ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><?php echo $enc->html( $attribute->getValue() ); ?></span>
							</li>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
<?php		if( $unhide && ( $attributes = $product->getAttributes( 'hidden' ) ) !== array() ) : ?>
						<ul class="attr-list attr-list-hidden">
<?php			foreach( $attributes as $attribute ) : ?>
<?php				if( $attribute->getCode() === 'download' ) : ?>
							<li class="attr-item">
								<span class="name"><?php echo $enc->html( $this->translate( 'client/code', $attribute->getCode() ) ); ?></span>
								<span class="value"><a href="<?php echo $enc->attr( $this->url( $dlTarget, $dlController, $dlAction, array( 'dl_id' => $attribute->getId() ), array(), $dlConfig ) ); ?>" ><?php echo $enc->html( $attribute->getName() ); ?></a></span>
							</li>
<?php				endif; ?>
<?php			endforeach; ?>
						</ul>
<?php		endif; ?>
					</td>
<?php		$prodPrice = $product->getPrice()->getValue(); ?>
					<td class="quantity">
<?php		if( $modify && ( $product->getFlags() & \Aimeos\MShop\Order\Item\Base\Product\Base::FLAG_IMMUTABLE ) == 0 ) : ?>
<?php			if( $product->getQuantity() > 1 ) : ?>
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity() - 1 ) + $backParams, array(), $basketConfig ) ); ?>">−</a>
<?php			else : ?>
						&nbsp;
<?php			endif; ?>
						<input class="value" type="text" name="<?php echo $enc->attr( $this->formparam( array( 'b_prod', $position, 'quantity' ) ) ); ?>" value="<?php echo $enc->attr( $product->getQuantity() ); ?>" maxlength="10" required="required" />
						<input type="hidden" name="<?php echo $enc->attr( $this->formparam( array( 'b_prod', $position, 'position' ) ) ); ?>" value="<?php echo $enc->attr( $position ); ?>" />
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'edit', 'b_position' => $position, 'b_quantity' => $product->getQuantity() + 1 ) + $backParams, array(), $basketConfig ) ); ?>">+</a>
<?php		else : ?>
<?php 			echo $enc->html( $product->getQuantity() ); ?>
<?php		endif; ?>
					</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $prodPrice ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $prodPrice * $product->getQuantity() ), $priceCurrency ) ); ?></td>
<?php		if( $modify && ( $product->getFlags() & \Aimeos\MShop\Order\Item\Base\Product\Base::FLAG_IMMUTABLE ) == 0 ) : ?>
					<td class="action">
						<a class="minibutton change" href="<?php echo $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, array( 'b_action' => 'delete', 'b_position' => $position ), array(), $basketConfig ) ); ?>"><?php echo $this->translate( 'client', 'X' ); ?></a>
					</td>
<?php		endif; ?>
				</tr>
<?php 	endforeach; ?>

<?php	if( $deliveryPriceValue > 0 ) : ?>
				<tr class="delivery">
					<td class="details">
<?php		if( isset( $this->summaryUrlServiceDelivery ) ) : ?>
						<a href="<?php echo $enc->attr( $this->summaryUrlServiceDelivery ); ?>">
							<?php echo $enc->html( $deliveryName ); ?>
						</a>
<?php		else : ?>
						<?php echo $enc->html( $deliveryName ); ?>
<?php		endif; ?>
					</td>
					<td class="quantity">1</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $deliveryPriceValue ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $deliveryPriceValue ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>

<?php	if( $paymentPriceValue > 0 ) : ?>
				<tr class="payment">
					<td class="details">
<?php		if( isset( $this->summaryUrlServicePayment ) ) : ?>
						<a href="<?php echo $enc->attr( $this->summaryUrlServicePayment ); ?>">
							<?php echo $enc->html( $paymentName ); ?>
						</a>
<?php		else : ?>
						<?php echo $enc->html( $paymentName ); ?>
<?php		endif; ?>
					</td>
					<td class="quantity">1</td>
					<td class="unitprice"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceValue ), $priceCurrency ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceValue ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>

<?php endif; ?>
			</tbody>
			<tfoot>
				<tr class="subtotal">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Sub-total' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue ), $priceCurrency ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>

<?php if( $priceService - $paymentPriceService > 0 ) : ?>
				<tr class="delivery">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Shipping' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceService - $paymentPriceService ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>

<?php if( $paymentPriceService > 0 ) : ?>
				<tr class="payment">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Payment costs' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $paymentPriceService ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>

<?php if( $priceTaxflag === true ) : ?>
				<tr class="total">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Total' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue + $priceService ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>

<?php foreach( $this->get( 'summaryTaxRates', array() ) as $taxRate => $priceItem ) : $taxValue = $priceItem->getTaxValue(); ?>
<?php	if( $taxRate > '0.00' && $taxValue > '0.00' ) : $priceTaxvalue += $taxValue; ?>
				<tr class="tax">
<?php		if( $priceItem->getTaxFlag() ) : ?>
					<td colspan="3"><?php echo $enc->html( sprintf( $this->translate( 'client', 'Incl. %1$s%% VAT' ), $this->number( $taxRate ) ) ); ?></td>
<?php		else : ?>
					<td colspan="3"><?php echo $enc->html( sprintf( $this->translate( 'client', '+ %1$s%% VAT' ), $this->number( $taxRate ) ) ); ?></td>
<?php		endif; ?>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $taxValue ), $priceCurrency ) ); ?></td>
<?php		if( $modify ) : ?>
					<td class="action"></td>
<?php		endif; ?>
				</tr>
<?php	endif; ?>
<?php endforeach; ?>

<?php if( $priceTaxflag === false ) : ?>
				<tr class="total">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Total' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceValue + $priceService + $priceTaxvalue ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>

<?php if( $priceRebate > '0.00' ) : ?>
				<tr class="rebate">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Included rebates' ) ); ?></td>
					<td class="price"><?php echo $enc->html( sprintf( $priceFormat, $this->number( $priceRebate ), $priceCurrency ) ); ?></td>
<?php	if( $modify ) : ?>
					<td class="action"></td>
<?php	endif; ?>
				</tr>
<?php endif; ?>

				<tr class="quantity">
					<td colspan="3"><?php echo $enc->html( $this->translate( 'client', 'Total quantity' ) ); ?></td>
					<td class="value"><?php echo $enc->html( sprintf( $this->translate( 'client', '%1$d article', '%1$d articles', $totalQuantity ), $totalQuantity ) ); ?></td>
<?php if( $modify ) : ?>
					<td class="action"></td>
<?php endif; ?>
				</tr>
			</tfoot>
		</table>
	</div>
<?php echo $this->get( 'detailBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'common/summary/detail' ); ?>
