<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class pricehistory extends Module
{
    public $runUpdate = true;

    public function __construct()
    {
        $this->name = 'pricehistory';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Chitiga Alexandru Gabriel';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();
 
        $this->displayName = $this->l('Price History');
        $this->description = $this->l('Track price history of your products.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('displayAdminProductsExtra') ||
            !$this->registerHook('actionProductUpdate') ||
            !$this->registerHook('actionObjectProductDeleteAfter')
        ) {
            return false;
        }

        

        $tab = new Tab();
        
        $tab->active = 1;
        $tab->class_name = 'Pricehistoryclass';
        $tab->name = array();
            foreach (Language::getLanguages() as $lang) {
                $tab->name[$lang['id_lang']] = $this->l('Price History Module');
    }
        $tab->id_parent = (int) Tab::getIdFromClassName('SELL');
        //$tab->position = 6;
        $tab->module = $this->name;
        if (!$tab->add()) {
            return false;
        }
        
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'price_history` (
            `id_price_history` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_product` int(10) unsigned NOT NULL,
            `price` decimal(20,6) NOT NULL,
            `old_price` decimal(20,6) NOT NULL,
            `date_add` datetime NOT NULL,
            PRIMARY KEY (`id_price_history`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
    
        if (!Db::getInstance()->execute($sql)) {
            return false;
        }
    
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->unregisterHook('displayAdminProductsExtra') ||
            !$this->unregisterHook('actionProductUpdate') ||
            !$this->unregisterHook('actionObjectProductDeleteAfter')
        ) {
            return false;
        }

        $id_tab = (int) Tab::getIdFromClassName('Pricehistoryclass');

    // Delete the tab from the back office dashboard
    $tab = new Tab($id_tab);
    $tab->delete();

        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'price_history`';
    if (!Db::getInstance()->execute($sql)) {
        return false;
    }

        return true;
    }

    public function hookDisplayAdminProductsExtra($params)
    {

        $productId = (int) Tools::getValue('id_product');
        $priceHistory = Db::getInstance()->execute('SELECT * FROM `' . _DB_PREFIX_ . 'price_history` WHERE `id_product` = ' . (int) $productId . ' ORDER BY `date_add` DESC');
 
        $this->context->smarty->assign(array(
            'price_history' => $priceHistory,
            'module_dir' => $this->_path,
        ));
 
        return $this->display(__FILE__, 'views/templates/admin/price_history.tpl');
        
    }

    public function hookActionProductUpdate($params)
    {
        if ($this->runUpdate) {
            $productId = (int) Tools::getValue('id_product');
            // $pricee = (int) Tools::getValue('price');
            // $sql2 = Db::getInstance()->update('price_history', array(
            // 'price' => pSQL($pricee),
            // 'date_add' => date('Y-m-d H:i:s'),
            // ), 'id_product =' . (int) $productId, 1, true);
    //->execute('UPDATE `'._DB_PREFIX_.'price_history` SET `price` = '.$pricee' , date_add = '.date('Y-m-d H:i:s')' WHERE `id_product` = ' . (int) $productId);
            
                $priceHistory = "";
            $priceHistory = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'price_history` WHERE `id_product` = ' . (int) $productId);
            
    
            $productIdCurr = (int) $params['id_product'];
            $product = new Product($productIdCurr);
     
            if (!Validate::isLoadedObject($product)) {
                return;
            }
    
            $price = Product::getPriceStatic($productId);
    
            // if(!$oldPrice)
                
            $price = $product->getPrice();
    
            if ($priceHistory)
            {
                $request = 'SELECT price FROM `' . _DB_PREFIX_ . 'price_history` WHERE `id_product` = ' . (int) $productId;
                $oldPricee = Db::getInstance()->getValue($request);
                Db::getInstance()->update('price_history', array(
                    'price' => pSQL($price),
                    'old_price' => pSQL($oldPricee),
                    'date_add' => date('Y-m-d H:i:s'),
                    ), 'id_product =' . (int) $productId, 1, true);
                    $priceHistory = "";
            } else {
                    $oldPricee = 0;
                    $data = array(
                        'id_product' => $productId,
                        'price' => $price,
                        'old_price' => $oldPricee,
                        'date_add' => date('Y-m-d H:i:s'),
                    );
                    var_dump($data);
                    Db::getInstance()->insert('price_history', $data);
                    var_dump("test");
                    exit;
            }
            // $oldPrice = $price;

            $this->runUpdate = false;
        }

       
        
    }

    public function hookActionObjectProductDeleteAfter($params)
    {
        $productId = (int) $params['object']->id;
        Db::getInstance()->delete('price_history', '`id_product` = ' . (int) $productId);
    }
}