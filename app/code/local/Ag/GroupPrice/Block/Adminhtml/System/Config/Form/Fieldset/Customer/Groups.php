<?php

class Ag_GroupPrice_Block_Adminhtml_System_Config_Form_Fieldset_Customer_Groups extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    protected $_varienElement;
    protected $_fieldRenderer;
    protected $_values;

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);
        $groups = Mage::getModel('customer/group')->getCollection();

        foreach ($groups as $group) {
            $html.= $this->_getPercentageFieldHtml($element, $group);
        }

        foreach ($groups as $group) {
            $html.= $this->_getSkuListsHtml($element, $group);
        }
        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    protected function _getVarienElement()
    {
        if (empty($this->_varienElement)) {
            $this->_varienElement = new Varien_Object(array('show_in_default'=>1, 'show_in_website'=>1));
        }
        return $this->_varienElement;
    }

    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        return $this->_fieldRenderer;
    }

    protected function _getValues()
    {
        if (empty($this->_values)) {
            $this->_values = array(
                array('label'=>Mage::helper('adminhtml')->__('No'), 'value'=>0),
                array('label'=>Mage::helper('adminhtml')->__('Yes'), 'value'=>1),
            );
        }
        return $this->_values;
    }

    protected function _getPercentageFieldHtml($fieldset, $group)
    {
        $configData = $this->getConfigData();
        $path = 'groupprice/customergroup/group_'.$group->getId();
        if (isset($configData[$path])) {
            $data = $configData[$path];
            $inherit = false;
        } else {
            $data = (int)(string)$this->getForm()->getConfigRoot()->descend($path);
            $inherit = true;
        }

        $e = $this->_getVarienElement();

        $field = $fieldset->addField($group->getId(), 'text',
            array(
                'name'          => 'groups[customergroup][fields][group_'.$group->getId().'][value]',
                'label'         => $group->getCustomerGroupCode(),
                'value'         => $data,
                'values'        => $this->_getValues(),
                'inherit'       => $inherit,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($e),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($e),
            ))->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }

    protected function _getSkuListsHtml($fieldset, $group)
    {
        $configData = $this->getConfigData();
        $path = 'groupprice/customergroupskulist/group_'.$group->getId();
        if (isset($configData[$path])) {
            $data = $configData[$path];
            $inherit = false;
        } else {
            $data = (int)(string)$this->getForm()->getConfigRoot()->descend($path);
            $inherit = true;
        }

        $e = $this->_getVarienElement();

        $field = $fieldset->addField('skus_'.$group->getId(), 'textarea',
            array(
                'name'          => 'groups[customergroupskulist][fields][group_'.$group->getId().'][value]',
                'label'         => $group->getCustomerGroupCode(),
                'value'         => $data,
                'values'        => $this->_getValues(),
                'inherit'       => $inherit,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($e),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($e),
            ))->setRenderer($this->_getFieldRenderer());
        //TODO: This functionality architecture is not ready yet.
//        return $field->toHtml();
    }
}