<?php
class Ag_GroupPrice_Adminhtml_GrouppricebackendController extends Mage_Adminhtml_Controller_Action
{

	protected function _isAllowed()
	{
		//return Mage::getSingleton('admin/session')->isAllowed('groupprice/grouppricebackend');
		return true;
	}

	public function indexAction()
    {
        $this->_title($this->__("Group Price"));
        $this->loadLayout();
        $block = $this->getLayout()
            ->createBlock('groupprice/catalog_product_grid');

        Mage::dispatchEvent('ag_groupprice_addbutton',array('block'=> $block));

        $this->_addContent($block);
        $this->renderLayout();
    }

    public function configAction()
    {
        $this->loadLayout();
        $this->_title($this->__("Configuration"));
        $this->renderLayout();
    }

    public function massAction()
    {
        $prod = Mage::app()->getRequest()->getParam('product');
        $storeId= Mage::app()->getStore()->getStoreId();
        $customer_gruop = Mage::app()->getRequest()->getParam('customer_group');
        $prod_model = Mage::getModel('catalog/product');

        foreach ($prod as $item) {
            $product = $prod_model->load($item);
            $discount = Mage::getStoreConfig('groupprice/customergroup/group_'.$customer_gruop);
            $prices = $product->getData('group_price');
            $prices = $this->_priceAlreadyExist($prices,$customer_gruop);
            $final_group_price = $product->getPrice() *($discount/100);

            $new_price = array (
                "website_id" => $storeId, "cust_group" => $customer_gruop, "price" => $final_group_price
            );
            array_push($prices,$new_price);

            $product->setGroupPrice($prices)
                ->getResource()
                ->getAttribute('group_price')
                ->getBackend()
                ->afterSave($product);
        }
        return $this->_redirect('admin_groupprice/adminhtml_grouppricebackend');
    }



    protected function _priceAlreadyExist($param = array(), $customer_gruop)
    {
        foreach ($param as $item) {

            $key = array_search($customer_gruop,$item);
            if ($key == 'cust_group')
            {
                unset($param);
            }
        }
        return $param;
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('groupprice/catalog_product_grid')->toHtml()
        );
    }

}
