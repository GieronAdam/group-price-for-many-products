<?php

class Ag_GroupPrice_Model_AgGroupprice
{

    /**
     * @param $prices = array()
     * @param $product
     */
    public function insertProductPrice($prices, $product)
    {
        $product->setGroupPrice($prices)
            ->getResource()
            ->getAttribute('group_price')
            ->getBackend()
            ->afterSave($product);
    }

}