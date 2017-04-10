<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<?php $this->block()->start( 'catalog/detail/basic' ); ?>
<?php if( isset( $this->detailProductItem ) ) : ?>
<div class="catalog-detail-basic">
	<h1 class="name" itemprop="name"><?php echo $enc->html( $this->detailProductItem->getName(), $enc::TRUST ); ?></h1>
	<p class="code">
		<span class="name"><?php echo $enc->html( $this->translate( 'client', 'Article no.:' ), $enc::TRUST ); ?></span>
		<span class="value" itemprop="sku"><?php echo $enc->html( $this->detailProductItem->getCode() ); ?></span>
	</p>
<?php foreach( $this->detailProductItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
	<p class="short" itemprop="description"><?php echo $enc->html( $textItem->getContent(), $enc::TRUST ); ?></p>
<?php endforeach; ?>
<?php echo $this->basicBody; ?>
</div>
<?php endif; ?>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/detail/basic' ); ?>
