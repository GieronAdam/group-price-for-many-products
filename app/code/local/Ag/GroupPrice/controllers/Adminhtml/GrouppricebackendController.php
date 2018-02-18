<?php

class Ag_GroupPrice_Adminhtml_GrouppricebackendController extends Mage_Adminhtml_Controller_Action
{

    /**
     * @var saveModel
     */
    public $saveModel;
    /**
     * @var Product Model
     */
    public $productModel;

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('groupprice/grouppricebackend');
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
        $this->saveModel = Mage::getModel('groupprice/aggroupprice');
        $this->productModel = Mage::getModel('catalog/product');
        $products = $this->productModel->getCollection()
            ->addFieldToFilter('entity_id',array('in'=> $prod));
        $products->load();

        Mage::getSingleton('core/resource_iterator')
            ->walk(
                $products->getSelect(),
                array(array($this, 'productsCallback')));

        return $this->_redirect('admin_groupprice/adminhtml_grouppricebackend');
    }

    public function productsCallback($args)
    {
        $storeId = Mage::app()->getStore()->getStoreId();
        $customer_group = Mage::app()->getRequest()->getParam('customer_group');
        $product = $this->productModel->load($args['row']['entity_id']);
        $discount = Mage::getStoreConfig('groupprice/customergroup/group_'.$customer_group);
        $prices = $product->getData('group_price');
        $final_group_price = $product->getPrice() - ( number_format(($discount/100) * $product->getPrice(),2));
        $new_price = array (
            "website_id" => $storeId, "cust_group" => $customer_group, "price" => $final_group_price
        );
        array_push($prices,$new_price);
        $this->saveModel->insertProductPrice($prices,$product);
        $this->productModel->reset();
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('groupprice/catalog_product_grid')->toHtml()
        );
    }
}
