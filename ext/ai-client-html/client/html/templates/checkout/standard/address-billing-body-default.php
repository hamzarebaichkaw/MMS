<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

/** client/html/common/address/billing/disable-new
 * Disables the billing address form for a new address
 *
 * Normally, customers are allowed to enter a different billing address in the
 * checkout process that is only stored along with the current order. Registered
 * customers also have the possibility to change their current billing address
 * but this updates the existing one in their profile.
 *
 * You can disable the address form for the new billing address by this setting
 * if it shouldn't be allowed to enter a different billing address.
 *
 * @param boolean True to disable the "new billing address" form, false to allow a new address
 * @since 2014.03
 * @category Developer
 * @category User
 * @see client/html/common/address/delivery/disable-new
 */
$disablenew = (bool) $this->config( 'client/html/common/address/billing/disable-new', false );


try {
	$addrArray = $this->standardBasket->getAddress( \Aimeos\MShop\Order\Item\Base\Address\Base::TYPE_PAYMENT )->toArray();
} catch( Exception $e ) {
	$addrArray = array();
}

$billingDefault = ( isset( $this->addressCustomerItem ) ? $this->addressCustomerItem->getId() : 'null' );
$billingOption = $this->param( 'ca_billingoption', ( isset( $addrArray['order.base.address.addressid'] ) && $addrArray['order.base.address.addressid'] != '' ? $addrArray['order.base.address.addressid'] : $billingDefault ) );

$billingSalutations = $this->get( 'billingSalutations', array() );
$billingCountries = $this->get( 'addressCountries', array() );
$billingStates = $this->get( 'addressStates', array() );
$billingLanguages = $this->get( 'addressLanguages', array() );

$paymentCssAll = array();

foreach( $this->get( 'billingMandatory', array() ) as $name ) {
	$paymentCssAll[$name][] = 'mandatory';
}

foreach( $this->get( 'billingOptional', array() ) as $name ) {
	$paymentCssAll[$name][] = 'optional';
}

foreach( $this->get( 'billingHidden', array() ) as $name ) {
	$paymentCssAll[$name][] = 'hidden';
}

?>
<?php $this->block()->start( 'checkout/standard/address/billing' ); ?>
<div class="checkout-standard-address-billing">
	<h2><?php echo $enc->html( $this->translate( 'client', 'Billing address' ), $enc::TRUST ); ?></h2>
<?php if( isset( $this->addressPaymentItem )  ) : ?>
	<div class="item-address">
		<div class="header">
			<input type="radio" name="<?php echo $enc->attr( $this->formparam( array( 'ca_billingoption' ) ) ); ?>" value="<?php echo $enc->attr( $this->addressPaymentItem->getAddressId() ); ?>" <?php echo ( $billingOption == $this->addressPaymentItem->getAddressId() ? 'checked="checked"' : '' ); ?> />
			<div class="values">
<?php
		$addr = $this->addressPaymentItem;
		$id = $this->addressPaymentItem->getAddressId();

		echo preg_replace( "/\n+/m", "<br/>", trim( $enc->html( sprintf(
			/// Address format with company (%1$s), salutation (%2$s), title (%3$s), first name (%4$s), last name (%5$s),
			/// address part one (%6$s, e.g street), address part two (%7$s, e.g house number), address part three (%8$s, e.g additional information),
			/// postal/zip code (%9$s), city (%10$s), state (%11$s), country (%12$s), language (%13$s),
			/// e-mail (%14$s), phone (%15$s), facsimile/telefax (%16$s), web site (%17$s), vatid (%18$s)
			$this->translate( 'client', '%1$s
%2$s %3$s %4$s %5$s
%6$s %7$s
%8$s
%9$s %10$s
%11$s
%12$s
%13$s
%14$s
%15$s
%16$s
%17$s
%18$s
'
			),
			$addr->getCompany(),
			( !in_array( $addr->getSalutation(), array( 'company' ) ) ? $this->translate( 'client/code', $addr->getSalutation() ) : '' ),
			$addr->getTitle(),
			$addr->getFirstName(),
			$addr->getLastName(),
			$addr->getAddress1(),
			$addr->getAddress2(),
			$addr->getAddress3(),
			$addr->getPostal(),
			$addr->getCity(),
			$addr->getState(),
			$this->translate( 'client/country', $addr->getCountryId() ),
			$this->translate( 'client/language', $addr->getLanguageId() ),
			$addr->getEmail(),
			$addr->getTelephone(),
			$addr->getTelefax(),
			$addr->getWebsite(),
			$addr->getVatID()
		) ) ) );
?>
			</div>
		</div>
<?php
		$paymentCss = $paymentCssAll;
		if( $billingOption == $id )
		{
			foreach( $this->get( 'billingError', array() ) as $name => $msg ) {
				$paymentCss[$name][] = 'error';
			}
		}

		$addrValues = $addr->toArray();

		if( !isset( $addrValues['order.base.address.languageid'] ) || $addrValues['order.base.address.languageid'] == '' ) {
			$addrValues['order.base.address.languageid'] = $this->get( 'billingLanguage', 'en' );
		}

		$values = array(
			'address' => $addrValues,
			'salutations' => $billingSalutations,
			'languages' => $billingLanguages,
			'countries' => $billingCountries,
			'states' => $billingStates,
			'type' => 'billing',
			'css' => $paymentCss,
			'id' => $id,
		);

		/** client/html/common/partials/address
		 * Relative path to the address partial template file
		 *
		 * Partials are templates which are reused in other templates and generate
		 * reoccuring blocks filled with data from the assigned values. The address
		 * partial creates an HTML block with input fields for address forms.
		 *
		 * The partial template files are usually stored in the templates/partials/ folder
		 * of the core or the extensions. The configured path to the partial file must
		 * be relative to the templates/ folder, e.g. "common/partials/address-default.php".
		 *
		 * @param string Relative path to the template file
		 * @since 2015.04
		 * @category Developer
		 * @category User
		 */
?>
		<ul class="form-list">
<?php	echo $this->partial( $this->config( 'client/html/common/partials/address', 'common/partials/address-default.php' ), $values ); ?>
		</ul>
	</div>
<?php endif; ?>
<?php if( $disablenew === false ) : ?>
	<div class="item-address item-new" data-option="<?php echo $enc->attr( $billingOption ); ?>">
		<div class="header">
			<input type="radio" name="<?php echo $enc->attr( $this->formparam( array( 'ca_billingoption' ) ) ); ?>" value="null" <?php echo ( $billingOption == 'null' ? 'checked="checked"' : '' ); ?> />
			<div class="values"><span class="value value-new"><?php echo $enc->html( $this->translate( 'client', 'new address' ), $enc::TRUST ); ?></span></div>
		</div>
<?php
		$paymentCss = $paymentCssAll;
		if( $billingOption == 'null' )
		{
			foreach( $this->get( 'billingError', array() ) as $name => $msg ) {
				$paymentCss[$name][] = 'error';
			}
		}

		$addrValues = array_merge( $addrArray, $this->param( 'ca_billing', array() ) );

		if( !isset( $addrValues['order.base.address.languageid'] ) || $addrValues['order.base.address.languageid'] == '' ) {
			$addrValues['order.base.address.languageid'] = $this->get( 'billingLanguage', 'en' );
		}

		$values = array(
			'address' => $addrValues,
			'salutations' => $billingSalutations,
			'languages' => $billingLanguages,
			'countries' => $billingCountries,
			'states' => $billingStates,
			'type' => 'billing',
			'css' => $paymentCss,
		);
?>
		<ul class="form-list">

<?php	echo $this->partial( $this->config( 'client/html/common/partials/address', 'common/partials/address-default.php' ), $values ); ?>

			<li class="form-item birthday">
				<label for="customer-birthday">
					<?php echo $enc->html( $this->translate( 'client', 'Birthday' ), $enc::TRUST ); ?>
				</label><!--
				--><input type="date" class="birthday"
					id="customer-birthday"
					name="<?php echo $enc->attr( $this->formparam( array( 'ca_extra', 'customer.birthday' ) ) ); ?>"
					value="<?php echo $enc->attr( $this->get( 'addressExtra/customer.birthday' ) ); ?>"
				/>
			</li>
		</ul>
	</div>
<?php endif; ?>
<?php echo $this->get( 'billingBody' ); ?>
</div>
<?php $this->block()->stop(); ?>
<?php echo $this->block()->get( 'checkout/standard/address/billing' ); ?>
