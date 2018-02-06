<?php

class Ag_GroupPrice_Model_AgGroupprice extends Mage_Catalog_Model_Resource_Product_Attribute_Backend_Groupprice
{
    public function __construct()
    {
        parent::_construct();
    }

    /**
     * @param array $data
     * @return Mage_Catalog_Model_Resource_Product_Attribute_Backend_Tierprice
     */
    public function insertProductPrice($product, $data)
    {
        $priceObject = new Varien_Object($data);
        $priceObject->setEntityId($product->getId());

        return $this->savePriceData($priceObject);
    }
}