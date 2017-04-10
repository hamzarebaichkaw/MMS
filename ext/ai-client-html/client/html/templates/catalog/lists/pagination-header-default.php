<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();
$params = $this->get( 'listParams', array() );
$total = $this->get( 'listProductTotal', 1 ) / $this->get( 'listPageSize', 1 );
$current = $this->get( 'listPageCurr', 1 );

$listTarget = $this->config( 'client/html/catalog/lists/url/target' );
$listController = $this->config( 'client/html/catalog/lists/url/controller', 'catalog' );
$listAction = $this->config( 'client/html/catalog/lists/url/action', 'list' );
$listConfig = $this->config( 'client/html/catalog/lists/url/config', array() );

?>
<?php if( $current > 1 ) : ?>
<link rel="prev" href="<?php echo $enc->attr( $this->url( $listTarget, $listController, $listAction, array( 'l_page' => $this->pagiPagePrev ) + $params, array(), $listConfig ) ); ?>" />
<?php endif; ?>
<?php if( $current > 1 && $current < $total ) : // Optimization to avoid loading next page while the user is still filtering ?>
<link rel="next prefetch" href="<?php echo $enc->attr( $this->url( $listTarget, $listController, $listAction, array( 'l_page' => $this->pagiPageNext ) + $params, array(), $listConfig ) ); ?>" />
<?php endif; ?>
<?php echo $this->get( 'pagiHeader' ); ?>
