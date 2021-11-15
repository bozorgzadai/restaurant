<?php

class ProductModel
{
    public static function fetchCartByCartId($cartId)
    {
        $db = Db::getInstance();
        $cart = $db->first("SELECT * FROM pym_cart WHERE cart_id=:cart_id", array(
            'cart_id' => $cartId,
        ));

        return $cart;
    }

    public static function fetchCartByUserIdAndPaid($userId, $paid)
    {
        $db = Db::getInstance();
        $cart = $db->first("SELECT * FROM pym_cart WHERE user_id=:user_id AND paid=:paid", array(
            'user_id' => $userId,
            'paid' => $paid,
        ));

        return $cart;
    }

    public static function fetchCartBySessionIdAndPaid($sessionId, $paid)
    {
        $db = Db::getInstance();
        $cart = $db->first("SELECT * FROM pym_cart WHERE session_id=:session_id AND paid=:paid", array(
            'session_id' => $sessionId,
            'paid' => $paid,
        ));

        return $cart;
    }

    public static function fetchCartBySessionIdAndUserId($sessionId, $userId)
    {
        $db = Db::getInstance();
        $cart = $db->first("SELECT * FROM pym_cart WHERE session_id=:session_id AND user_id=:user_id", array(
            'session_id' => $sessionId,
            'user_id' => $userId,
        ));

        return $cart;
    }

    public static function fetchOrderByCartId($cartId)
    {
        $db = Db::getInstance();
        $order = $db->first("SELECT * FROM pym_order WHERE cart_id=:cart_id", array(
            'cart_id' => $cartId,
        ));

        return $order;
    }

    public static function fetchOrderByProductIdAndCartId($productId, $cartId)
    {
        $db = Db::getInstance();
        $order = $db->first("SELECT * FROM pym_order WHERE product_id=:product_id AND cart_id=:cart_id", array(
            'product_id' => $productId,
            'cart_id' => $cartId,
        ));

        return $order;
    }

    public static function insertCart($sessionId, $userId, $invoiceId, $paid)
    {
        $db = Db::getInstance();
        $cartId = $db->insert("INSERT INTO pym_cart (session_id , user_id , paid , invoice_id) VALUES (:session_id , :user_id , :paid , :invoice_id)", array(
            'session_id' => $sessionId,
            'user_id' => $userId,
            'invoice_id' => $invoiceId,
            'paid' => $paid,
        ));

        return $cartId;
    }

    public static function insertOrder($cartId, $productId, $quantity)
    {
        $db = Db::getInstance();
        $orderId = $db->insert("INSERT INTO pym_order (cart_id , product_id , quantity) VALUES (:cart_id , :product_id , :quantity)", array(
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ));

        return $orderId;
    }

    public static function updateOrderQuantityByProductIdAndCartId($cartId, $productId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE pym_order SET quantity=:quantity WHERE product_id=:product_id AND cart_id=:cart_id", array(
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ));
    }

    public static function updateOrderQuantityByOrderId($orderId, $quantity)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE pym_order SET quantity=:quantity WHERE order_id=:order_id", array(
            'order_id' => $orderId,
            'quantity' => $quantity,
        ));
    }

    public static function deleteOrderByCartId($cartId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM pym_order WHERE cart_id=:cart_id", array(
            'cart_id' => $cartId,
        ));
    }

    public static function deleteCartBySessionIdAndUserId($sessionId, $userId)
    {
        $db = Db::getInstance();
        $db->modify("DELETE FROM pym_cart WHERE session_id=:session_id AND user_id=:user_id", array(
            'session_id' => $sessionId,
            'user_id' => $userId,
        ));
    }

    public static function updateCartSessionIdByCartId($sessionId, $cartId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE pym_cart SET session_id=:session_id WHERE cart_id=:cart_id", array(
            'session_id' => $sessionId,
            'cart_id' => $cartId,
        ));
    }

    public static function updateCartUserIdByCartId($userId, $cartId)
    {
        $db = Db::getInstance();
        $db->modify("UPDATE pym_cart SET user_id=:user_id WHERE cart_id=:cart_id", array(
            'user_id' => $userId,
            'cart_id' => $cartId,
        ));
    }
}