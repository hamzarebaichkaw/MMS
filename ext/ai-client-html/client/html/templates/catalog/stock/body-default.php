<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$result = array();
$enc = $this->encoder();

$stockProductIds = $this->get( 'stockProductIds', array() );
$stockItemsByProducts = $this->get( 'stockItemsByProducts', array() );
$warehouseItems = $this->get( 'stockWarehouseItems', array() );

/** client/html/catalog/stock/level/low
 * The number of products in stock below it's considered a low stock level
 *
 * There are four stock levels available:
 * * unlimited
 * * high
 * * low
 * * out
 *
 * If no stock information is available, the number of products is considered
 * unlimited, which is useful for digital products. Zero or less products in
 * stock means out of stock while a quantity of products above the option value
 * represents a high stock value.
 *
 * There can be the case that a stock level is sometimes negative even if only
 * products that are in stock can be bought. This is due to the time difference
 * the product is actually ordered and the stock level is decreased. If you've
 * configured the stock level update every minute, within this minute another
 * customer can buy the same product that is considered to be still in stock at
 * this time.
 *
 * @param integer Number of products in stock
 * @since 2014.03
 * @category User
 * @category Developer
 * @see client/html/catalog/stock/sort
 */
$stockLow = $this->config( 'client/html/catalog/stock/level/low', 5 );

/// Stock string composition with warehouse name (%1$s, normally left out) and stock level string (%2$s)
$textStockIn = $this->translate( 'client', 'Stock: %1$s, %2$s' );
/// Stock string composition with warehouse name (%1$s, normally left out), stock level string (%2$s) and back in stock date (%3$s)
$textStockOut = $this->translate( 'client', 'Stock: %1$s, %2$s, back on %3$s' );
$dateFormat = $this->translate( 'client', 'Y-m-d' );

$textStock = array(
	/// code for "product is out of stock"
	'stock-out' => nl2br( $enc->html( $this->translate( 'client', 'stock-out' ), $enc::TRUST ) ),
	/// code for "only a few products are available"
	'stock-low' => nl2br( $enc->html( $this->translate( 'client', 'stock-low' ), $enc::TRUST ) ),
	/// code for "product is in stock"
	'stock-high' => nl2br( $enc->html( $this->translate( 'client', 'stock-high' ), $enc::TRUST ) ),
	/// code for "product is available (without stock limit)"
	'stock-unlimited' => nl2br( $enc->html( $this->translate( 'client', 'stock-unlimited' ), $enc::TRUST ) ),
);


foreach( $stockProductIds as $prodId )
{
	if( !isset( $stockItemsByProducts[$prodId] ) )
	{
		$result[$prodId] = '<div class="stockitem stock-prodid-' . $enc->attr( $prodId ) . ' stock-unlimited" title="' . $enc->attr( $textStock['stock-unlimited'] ) . '"><div class="stocklevel"></div><span class="stocktext">' . $textStock['stock-unlimited'] . '</span></div>';
		continue;
	}

	$stocks = array( 'stock-unlimited' => '', 'stock-high' => '', 'stock-low' => '', 'stock-out' => '' );

	foreach( (array) $stockItemsByProducts[$prodId] as $item )
	{
		$whId = $item->getWarehouseId();
		$whCode = ( isset( $warehouseItems[$whId] ) ? 'warehouse:' . $warehouseItems[$whId]->getCode() : 'default' );

		if( !isset( $whText[$whCode] ) ) {
			$whText[$whCode] = $this->translate( 'client/code', $whCode );
		}

		$stocklevel = $item->getStockLevel();

		if( $stocklevel === null ) {
			$level = 'stock-unlimited'; $link = 'http://schema.org/InStock';
		} elseif( $stocklevel <= 0 ) {
			$level = 'stock-out'; $link = 'http://schema.org/OutOfStock';
		} elseif( $stocklevel <= $stockLow ) {
			$level = 'stock-low'; $link = 'http://schema.org/LimitedAvailability';
		} else {
			$level = 'stock-high'; $link = 'http://schema.org/InStock';
		}

		if( $stocklevel <= 0 && ( $date = $item->getDateBack() ) != '' )
		{
			$text = sprintf( $textStockOut,
				$whText[$whCode],
				$textStock[$level],
				date_create( $date )->format( $dateFormat )
			);
		}
		else
		{
			$text = sprintf( $textStockIn,
				$whText[$whCode],
				$textStock[$level]
			);
		}

		$text = nl2br( $enc->html( $text, $enc::TRUST ) );
		$stocks[$level] .= '<div class="stockitem stock-prodid-' . $enc->attr( $prodId ) . ' ' . $level . '" title="' . $enc->attr( $textStock[$level] ) . '">
	<link itemprop="availability" href="' . $link . '" />
	<div class="stocklevel"></div>
	<span class="stocktext">' . $text . '</span>
</div>';
	}

	$result[$prodId] = implode( '', $stocks );
}

?>
<?php $this->block()->start( 'catalog/stock' ); ?>
// <!--
var aimeosStockHtml = <?php echo json_encode( $result, JSON_FORCE_OBJECT ); ?>;

$(".aimeos .product .stock").each(function() {

	var html = "";
	var elem = $(this);
	var productIds = String( elem.data("prodid") ).split(" ");

	for( var i=0; i<productIds.length; i++ ) {
		if( aimeosStockHtml.hasOwnProperty( productIds[i] ) ) {
			html += aimeosStockHtml[productIds[i]];
		}
	}

	if( html !== '' ) {
		elem.html( elem.html() + html );
		$(".stockitem:first-child", elem).addClass("stock-actual");
	}
});

$(".aimeos .catalog-detail-basket").each(function() {

	var elem = $(this);

	if( elem.data("reqstock") && $(".stockitem:first-child", elem).hasClass("stock-out") ) {
		$(".addbasket .btn-action", elem).addClass("btn-disabled").attr("disabled", "disabled");
	}
});
// -->
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/stock' ); ?>
