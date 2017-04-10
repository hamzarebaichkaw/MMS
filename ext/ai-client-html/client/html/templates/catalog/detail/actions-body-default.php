<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2014
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();
$prodid = $this->param( 'd_prodid' );
$params = $this->get( 'actionsParams', array() );
$login = ( $this->get( 'actionsUserId', '' ) ? 0 : 1 );

$pinTarget = $this->config( 'client/html/catalog/session/pinned/url/target' );
$pinController = $this->config( 'client/html/catalog/session/pinned/url/controller', 'catalog' );
$pinAction = $this->config( 'client/html/catalog/session/pinned/url/action', 'detail' );
$pinConfig = $this->config( 'client/html/catalog/session/pinned/url/config', array() );

$watchTarget = $this->config( 'client/html/account/watch/url/target' );
$watchController = $this->config( 'client/html/account/watch/url/controller', 'account' );
$watchAction = $this->config( 'client/html/account/watch/url/action', 'watch' );
$watchConfig = $this->config( 'client/html/account/watch/url/config', array() );

$favTarget = $this->config( 'client/html/account/favorite/url/target' );
$favController = $this->config( 'client/html/account/favorite/url/controller', 'account' );
$favAction = $this->config( 'client/html/account/favorite/url/action', 'favorite' );
$favConfig = $this->config( 'client/html/account/favorite/url/config', array() );


/** client/html/catalog/detail/actions/list
 * List of user action names that should be displayed in the catalog detail view
 *
 * Users can add products to several personal lists that are either only
 * available during the session or permanently if the user is logged in. The list
 * of pinned products is session based while the watch list and the favorite
 * products are durable. For the later two lists, the user has to be logged in
 * so the products can be associated to the user account.
 *
 * The order of the action names in the configuration determines the order of
 * the actions on the catalog detail page.
 *
 * @param array List of user action names
 * @since 2014.09
 * @category User
 * @category Developer
 */
$list = $this->config( 'client/html/catalog/detail/actions/list', array( 'pin', 'watch', 'favorite' ) );

$urls = array(
	'pin' => $this->url( $pinTarget, $pinController, $pinAction, array( 'pin_action' => 'add', 'pin_id' => $prodid ) + $params, $pinConfig ),
	'watch' => $this->url( $watchTarget, $watchController, $watchAction, array( 'wat_action' => 'add', 'wat_id' => $prodid ) + $params, $watchConfig ),
	'favorite' => $this->url( $favTarget, $favController, $favAction, array( 'fav_action' => 'add', 'fav_id' => $prodid ) + $params, $favConfig ),
);

?>
<?php $this->block()->start( 'catalog/detail/actions' ); ?>
<!-- catalog.detail.actions -->
<div class="catalog-detail-actions">
<?php foreach( $list as $entry ) : ?>
<?php	if( isset( $urls[$entry] ) ) : ?>
	<a class="actions-button actions-button-<?php echo $enc->attr( $entry ); ?>" data-login="<?php echo $login; ?>" href="<?php echo $enc->attr( $urls[$entry] ); ?>" title="<?php echo $enc->attr( $this->translate( 'client/code', $entry ) ); ?>"></a>
<?php	endif; ?>
<?php endforeach; ?>
<?php echo $this->actionsBody; ?>
</div>
<!-- catalog.detail.actions -->
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'catalog/detail/actions' ); ?>
