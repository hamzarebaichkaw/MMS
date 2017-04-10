<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

$listTarget = $this->config( 'client/html/catalog/lists/url/target' );
$listController = $this->config( 'client/html/catalog/lists/url/controller', 'catalog' );
$listAction = $this->config( 'client/html/catalog/lists/url/action', 'list' );
$listConfig = $this->config( 'client/html/catalog/lists/url/config', array() );

$params = $this->get( 'stageParams', array() );

?>
<?php $this->block()->start( 'catalog/stage/breadcrumb' ); ?>
<div class="catalog-stage-breadcrumb">
	<nav class="breadcrumb">
		<span class="title"><?php echo $enc->html( $this->translate( 'client', 'You are here:' ), $enc::TRUST ); ?></span>
		<ol>
<?php if( isset( $this->stageCatPath ) ) : ?>
<?php	foreach( (array) $this->stageCatPath as $cat ) : ?>
<?php		$params['f_catid'] = $cat->getId(); ?>
			<li><a href="<?php echo $enc->attr( $this->url( $listTarget, $listController, $listAction, $params, array( $cat->getName() ), $listConfig ) ); ?>"><?php echo $enc->html( $cat->getName() ); ?></a></li>
<?php	endforeach; ?>
<?php else : ?>
			<li><a href="<?php echo $enc->attr( $this->url( $listTarget, $listController, $listAction, $params, array(), $listConfig ) ); ?>"><?php echo $enc->html( $this->translate( 'client', 'Your search result' ), $enc::TRUST ); ?></a></li>
<?php endif; ?>
		</ol>
	</nav>
<?php echo $this->get( 'breadcrumbBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/stage/breadcrumb' ); ?>
