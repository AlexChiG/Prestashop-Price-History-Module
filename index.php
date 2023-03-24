<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class PriceHistoryController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'price_history';
        $this->className = 'PriceHistory';
        $this->lang = false;
        $this->explicitSelect = true;
        $this->allow_export = true;
 
        parent::__construct();
 
        $this->fields_list = array(
            'id_price_history' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 'auto',
            ),
            'id_product' => array(
                'title' => $this->l('Product ID'),
                'width' => 'auto',
            ),
            'price' => array(
                'title' => $this->l('Price'),
                'width' => 'auto',
                'type' => 'price',
            ),
            'old_price' => array(
                'title' => $this->l('Old Price'),
                'width' => 'auto',
                'type' => 'decimal(20,6)',
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'width' => 'auto',
                'type' => 'datetime',
            ),
        );
 
        $this->addRowAction('delete');
    }
}