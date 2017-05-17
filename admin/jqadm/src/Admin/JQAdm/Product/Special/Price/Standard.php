<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Special\Price;

sprintf( 'special' ); // for translation


/**
 * Default implementation of product special priceJQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/special/price/standard/subparts
	 * List of JQAdm sub-clients rendered within the product special pricesection
	 *
	 * The output of the frontend is composed of the code generated by the JQAdm
	 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain JQAdm clients themselves and therefore a
	 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the JQAdm code generated by the parent is printed, then
	 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural JQAdm, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2016.03
	 * @category Developer
	 */
	private $subPartPath = 'admin/jqadm/product/special/price/standard/subparts';

	/** admin/jqadm/product/special/price/name
	 * Name of the special price subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Special\Price\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2017.06
	 * @category Developer
	 */
	private $subPartNames = ['price'];


	/**
	 * Copies a resource
	 *
	 * @return string admin output to display or null for redirecting to the list
	 */
	public function copy()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->specialpriceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->specialpriceBody .= $client->copy();
		}

		/** admin/jqadm/product/special/price/template-item
		 * Relative path to the HTML body template of the special subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2016.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/product/special/price/template-item';
		$default = 'product/item-special-price-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string admin output to display or null for redirecting to the list
	 */
	public function create()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->specialpriceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->specialpriceBody .= $client->create();
		}

		$tplconf = 'admin/jqadm/product/special/price/template-item';
		$default = 'product/item-special-price-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string admin output to display or null for redirecting to the list
	 */
	public function get()
	{
		$view = $this->getView();

		$this->setData( $view );
		$view->specialpriceBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->specialpriceBody .= $client->get();
		}

		$tplconf = 'admin/jqadm/product/special/price/template-item';
		$default = 'product/item-special-price-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null admin output to display or null for redirecting to the list
	 */
	public function save()
	{
		$view = $this->getView();
		$context = $this->getContext();

		$manager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );
		$manager->begin();

		try
		{
			$this->update( $view );
			$view->specialpriceBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->specialpriceBody .= $client->save();
			}

			$manager->commit();
			return;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-special' => $context->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;

			$manager->rollback();
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . ' - ' . $e->getTraceAsString() );
			$error = array( 'product-item-special' => $e->getMessage() );
			$view->errors = $view->get( 'errors', [] ) + $error;

			$manager->rollback();
		}

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** admin/jqadm/product/special/decorators/excludes
		 * Excludes decorators added by the "common" option from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/special/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/special/decorators/global
		 * @see admin/jqadm/product/special/decorators/local
		 */

		/** admin/jqadm/product/special/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/special/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/special/decorators/excludes
		 * @see admin/jqadm/product/special/decorators/local
		 */

		/** admin/jqadm/product/special/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/special/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2016.03
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/special/decorators/excludes
		 * @see admin/jqadm/product/special/decorators/global
		 */
		return $this->createSubClient( 'product/special/' . $type, $name );
	}


	/**
	 * Returns the referenced products for the given product ID
	 *
	 * @param string $prodid Unique product ID
	 * @return array Associative list of bundle product IDs as keys and list items as values
	 */
	protected function getListItems( $prodid )
	{
		$manager = \Aimeos\MShop\Factory::createManager( $this->getContext(), 'product/lists' );

		$search = $manager->createSearch();
		$expr = array(
			$search->compare( '==', 'product.lists.parentid', $prodid ),
			$search->compare( '==', 'product.lists.domain', 'attribute' ),
			$search->compare( '==', 'product.lists.type.domain', 'attribute' ),
			$search->compare( '==', 'product.lists.type.code', 'custom' ),
		);
		$search->setConditions( $search->combine( '&&', $expr ) );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Returns the mapped input parameter or the existing items as expected by the template
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 */
	protected function setData( \Aimeos\MW\View\Iface $view )
	{
		$view->specialpriceData = (array) $view->param( 'specialprice', [] );

		if( !empty( $view->specialpriceData ) ) {
			return;
		}

		$data = [];

		foreach( $view->item->getListItems( 'attribute', 'custom' ) as $listItem )
		{
			if( ( $refItem = $listItem->getRefItem() ) !== null && $refItem->getType() === 'price' ) {
				$data['custom'] = 1;
			}
		}

		$view->specialpriceData = $data;
	}


	/**
	 * Updates product special price option
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with helpers and assigned parameters
	 */
	protected function update( \Aimeos\MW\View\Iface $view )
	{
		$id = $view->item->getId();
		$context = $this->getContext();

		$attrManager = \Aimeos\MShop\Factory::createManager( $context, 'attribute' );
		$listManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists' );

		$attrId = $attrManager->findItem( 'custom', [], 'product', 'price' )->getId();
		$listItems = $this->getListItems( $id );

		if( $view->param( 'specialprice/custom', 0 ) == 1 )
		{
			foreach( $listItems as $listItem )
			{
				if( $listItem->getDomain() === 'attribute'
					&& $listItem->getType() === 'custom'
					&& $listItem->getRefId() == $attrId
				) {
					return;
				}
			}

			$typeManager = \Aimeos\MShop\Factory::createManager( $context, 'product/lists/type' );
			$typeId = $typeManager->findItem( 'custom', [], 'attribute' )->getId();

			$listItem = $listManager->createItem();
			$listItem->setDomain( 'attribute' );
			$listItem->setTypeId( $typeId );
			$listItem->setRefId( $attrId );
			$listItem->setParentId( $id );
			$listItem->setStatus( 1 );

			$listManager->saveItem( $listItem, false );
		}
		else
		{
			foreach( $listItems as $listId => $listItem )
			{
				if( $listItem->getDomain() === 'attribute'
					&& $listItem->getType() === 'custom'
					&& $listItem->getRefId() == $attrId
				) {
					$listManager->deleteItem( $listId );
				}
			}
		}
	}
}