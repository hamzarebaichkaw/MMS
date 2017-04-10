<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$detailTarget = $this->config( 'client/html/catalog/detail/url/target' );
$detailController = $this->config( 'client/html/catalog/detail/url/controller', 'catalog' );
$detailAction = $this->config( 'client/html/catalog/detail/url/action', 'detail' );
$detailConfig = $this->config( 'client/html/catalog/detail/url/config', array( 'absoluteUri' => 1 ) );

?>
<?php $this->block()->start( 'email/watch/html/detail' ); ?>
<div class="common-summary-detail common-summary container content-block">
	<div class="header">
		<h2><?php echo $enc->html( $this->translate( 'client', 'Details' ), $enc::TRUST ); ?></h2>
	</div>
	<div class="basket">
		<table>
			<thead>
				<tr>
					<th class="details"></th>
				</tr>
			</thead>
			<tbody>
<?php foreach( $this->extProducts as $entry ) : $product = $entry['item']; ?>
				<tr class="product">
					<td class="details">
<?php	$media = $product->getRefItems( 'media', 'default', 'default' ); ?>
<?php	if( ( $image = reset( $media ) ) !== false && ( $url = $image->getPreview() ) != '' ) : ?>
						<img src="<?php echo $enc->attr( $this->content( $url ) ); ?>" />
<?php	endif; ?>
<?php	$params = array( 'd_prodid' => $product->getId(), 'd_name' => $product->getName( 'url' ) ); ?>
						<a class="product-name" href="<?php echo $enc->attr( $this->url( $detailTarget, $detailController, $detailAction, $params, array(), $detailConfig ) ); ?>">
<?php	echo $enc->html( $product->getName(), $enc::TRUST ); ?>
						</a>
						<div class="price-list">
<?php	echo $this->partial( $this->config( 'client/html/common/partials/price', 'common/partials/price-default.php' ), array( 'prices' => array( $entry['price'] ) ) ); ?>
						</div>
					</td>
				</tr>
<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php echo $this->get( 'detailBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'email/watch/html/detail' ); ?>
