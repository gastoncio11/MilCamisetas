<?php

namespace CodeIgniterCart;

use Config\Services;

/**
 * The Cart class is a basic port of the CodeIgniter 3 cart module for 
 * CodeIgniter 4.
 *
 * @package    App\Libraries
 * @link       https://github.com/jason-napolitano/CodeIgniter4-Cart-Module
 */
class Cart
{
    public $productIdRules = '\.a-z0-9_-';
    public $productNameRules = '\w \-\.\:';
    public $productNameSafe = true;

    protected $cartContents = [];
    protected $session;

    public function __construct()
    {
        $this->session = session();

        $this->cartContents = $this->session->get('cart_contents');
        if ($this->cartContents === null) {
            $this->cartContents = ['cart_total' => 0, 'total_items' => 0];
        }

        log_message('info', 'Cart Class Initialized');
    }

    public function insert($items = []): bool
    {
        if (!is_array($items) || count($items) === 0) {
            return false;
        }

        $save_cart = false;
        if (isset($items['id'])) {
            if (($rowid = $this->_insert($items))) {
                $save_cart = true;
            }
        } else {
            foreach ($items as $val) {
                if (is_array($val) && isset($val['id']) && $this->_insert($val)) {
                    $save_cart = true;
                }
            }
        }

        if ($save_cart === true) {
            $this->saveCart();
            return $rowid ?? true;
        }

        return false;
    }

    protected function _insert($items = [])
    {
        if (!is_array($items) || count($items) === 0) {
            return false;
        }

        if (!isset($items['id'], $items['qty'], $items['price'], $items['name'])) {
            return false;
        }

        $items['qty'] = (float)$items['qty'];
        if ($items['qty'] === 0) {
            return false;
        }

        if (!preg_match('/^[' . $this->productIdRules . ']+$/i', $items['id'])) {
            return false;
        }

        if ($this->productNameSafe && !preg_match('/^[' . $this->productNameRules . ']+$/iu', $items['name'])) {
            return false;
        }

        $items['price'] = (float)$items['price'];

        $rowid = isset($items['options']) && count($items['options']) > 0
            ? md5($items['id'] . serialize($items['options']))
            : md5($items['id']);

        $old_quantity = isset($this->cartContents[$rowid]['qty']) ? (int)$this->cartContents[$rowid]['qty'] : 0;

        $items['rowid'] = $rowid;
        $items['qty'] += $old_quantity;
        $this->cartContents[$rowid] = $items;

        return $rowid;
    }

    public function update($items = []): bool
    {
        if (!is_array($items) || count($items) === 0) {
            return false;
        }

        $save_cart = false;
        if (isset($items['rowid'])) {
            if ($this->_update($items) === true) {
                $save_cart = true;
            }
        } else {
            foreach ($items as $val) {
                if (is_array($val) && isset($val['rowid']) && $this->_update($val) === true) {
                    $save_cart = true;
                }
            }
        }

        if ($save_cart === true) {
            $this->saveCart();
            return true;
        }

        return false;
    }

    protected function _update($items = []): bool
    {
        if (!isset($items['rowid'], $this->cartContents[$items['rowid']])) {
            return false;
        }

        if (isset($items['qty'])) {
            $items['qty'] = (float)$items['qty'];
            if ($items['qty'] === 0) {
                unset($this->cartContents[$items['rowid']]);
                return true;
            }
        }

        $keys = array_intersect(array_keys($this->cartContents[$items['rowid']]), array_keys($items));

        if (isset($items['price'])) {
            $items['price'] = (float)$items['price'];
        }

        foreach (array_diff($keys, ['id', 'name']) as $key) {
            $this->cartContents[$items['rowid']][$key] = $items[$key];
        }

        return true;
    }

    protected function saveCart(): bool
    {
        $this->cartContents['total_items'] = $this->cartContents['cart_total'] = 0;

        foreach ($this->cartContents as $key => $val) {
            if (!is_array($val) || !isset($val['price'], $val['qty'])) {
                continue;
            }

            $this->cartContents['cart_total'] += ($val['price'] * $val['qty']);
            $this->cartContents['total_items'] += $val['qty'];
            $this->cartContents[$key]['subtotal'] = ($val['price'] * $val['qty']);
        }

        if (count($this->cartContents) <= 2) {
            $this->session->remove('cart_contents');
            return false;
        }

        $this->session->set('cart_contents', $this->cartContents);
        return true;
    }

    public function total()
    {
        return $this->cartContents['cart_total'];
    }

    public function remove($rowid): bool
    {
        unset($this->cartContents[$rowid]);
        $this->saveCart();
        return true;
    }

    public function totalItems()
    {
        return $this->cartContents['total_items'];
    }

    public function contents($newest_first = false): array
    {
        $cart = ($newest_first) ? array_reverse($this->cartContents) : $this->cartContents;
        unset($cart['total_items'], $cart['cart_total']);
        return $cart;
    }

    public function getItem($row_id)
    {
        return (in_array($row_id, ['total_items', 'cart_total'], true) || !isset($this->cartContents[$row_id]))
            ? false
            : $this->cartContents[$row_id];
    }

    public function hasOptions($row_id = ''): bool
    {
        return (isset($this->cartContents[$row_id]['options']) && count($this->cartContents[$row_id]['options']) !== 0);
    }

    public function productOptions($row_id = '')
    {
        return $this->cartContents[$row_id]['options'] ?? [];
    }

    public function formatNumber($n = ''): string
    {
        return ($n === '') ? '' : number_format((float)$n, 2);
    }

    public function destroy(): void
    {
        $this->cartContents = ['cart_total' => 0, 'total_items' => 0];
        $this->session->remove('cart_contents');
    }
}
