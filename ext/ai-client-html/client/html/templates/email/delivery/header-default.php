<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

/// Delivery e-mail subject with order ID
$str = $this->translate( 'client', 'Your order %1$s' );
$this->mail()->setSubject( sprintf( $str, $this->extOrderItem->getId() ) );

?>
<?php echo $this->get( 'deliveryHeader' ); ?>
