<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$items = $this->get( 'quoteItems', array() );

?>
<?php $this->block()->start( 'catalog/lists/quote' ); ?>
<div class="catalog-list-quote">
<?php if( count( $items ) > 0 ) : ?>
	<div class="content">
<?php 	foreach( $items as $quoteItem ) : ?>
		<article><?php echo $enc->html( $quoteItem->getContent() ); ?></article>
<?php 	endforeach; ?>
		<a href="#"><?php echo $enc->html( $this->translate( 'client', 'Show all quotes' ), $enc::TRUST ); ?></a>
	</div>
<?php endif; ?>
<?php echo $this->get( 'quoteBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/lists/quote' ); ?>
