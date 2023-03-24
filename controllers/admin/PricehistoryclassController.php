<?php

Class PricehistoryclassController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'price_history';
        $this->className = 'PricehistoryclassController';
        $this->lang = false;
        // $this->addRowAction('edit');
        $this->addRowAction('delete');

        parent::__construct();
    }

    public function renderList()
    {
    $this->fields_list = array(
        'id_product' => array(
            'title' => $this->l('Product ID'),
            'align' => 'center',
            'width' => 30,
        ),
        'price' => array(
            'title' => $this->l('Price'),
            'align' => 'center',
            'width' => 50,
            'type' => 'price',
            'currency' => true,
        ),
        'old_price' => array(
            'title' => $this->l('Old Price'),
            'align' => 'center',
            'width' => 50,
            'type' => 'price',
            'currency' => true,
        ),
        'date_add' => array(
            'title' => $this->l('Date Added'),
            'align' => 'center',
            'type' => 'datetime',
            'search' => false,
        ),
    );

        return parent::renderList();
    }
}