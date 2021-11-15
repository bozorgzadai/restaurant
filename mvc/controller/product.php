<?php

class ProductController
{
    public function fetchProduct()
    {
        $foodTypeSelected = $_POST['foodTypeSelected'];
        $priceSelected = $_POST['priceSelected'];
        $searchText = $_POST['searchText'];
        $wishListFilter = $_POST['wishListFilter'];

        if ($wishListFilter == 'showOnlyWishList') {
            $wishListFilter = "wish_id IS NOT NULL AND";
        } else {
            $wishListFilter = "";
        }

        if (strcmp($foodTypeSelected, "همه") == 0) {
            $foodTypeSelected = '';
        }

        $db = Db::getInstance();
        $products = $db->query("SELECT * FROM food LEFT OUTER JOIN user_wish ON food_id=resource_id AND user_id=:user_id AND resourceType=1 WHERE $wishListFilter foodType LIKE '%$foodTypeSelected%' AND foodName LIKE '%$searchText%' ORDER BY foodPrice $priceSelected", array(
            'user_id' => getUserId(),
        ));

        $data['products'] = $products;
        View::renderPartial("/product/fetchProduct.php", $data);
    }


    private function findLatestCart()
    {
        $user_id = getUserId();
        $session_id = session_id();
        $latestCart = null;

        if ($user_id != 0) {
            $latestCart = ProductModel::fetchCartByUserIdAndPaid($user_id, 0);
        }

        if ($latestCart != null) {
            // when the user had a cart that not paid: right now we want to check that he/she create a new cart before login
            $extraCart = ProductModel::fetchCartBySessionIdAndUserId($session_id, 0);

            if ($extraCart != null) {
                $extraCartOrders = ProductModel::fetchOrderByCartId($extraCart['cart_id']);

                if ($extraCartOrders != null) {
                    foreach ($extraCartOrders as $extraOrder) {
                        // search the new order in previous cart
                        $previousCartOrder = ProductModel::fetchOrderByProductIdAndCartId($extraOrder['product_id'], $latestCart['cart_id']);

                        if ($previousCartOrder == null) {
                            ProductModel::insertOrder($latestCart['cart_id'], $extraOrder['product_id'], $extraOrder['quantity']);
                        } else {
                            $quantity = $previousCartOrder['quantity'] + $extraOrder['quantity'];
                            ProductModel::updateOrderQuantityByProductIdAndCartId($latestCart['cart_id'], $extraOrder['product_id'], $quantity);
                        }
                    }

                    ProductModel::deleteOrderByCartId($extraCart['cart_id']);
                }

                ProductModel::deleteCartBySessionIdAndUserId($session_id, 0);
            }

            // come here when:
            // 1- the user have a not paid cart and right now don't have any cart without login
            // 1- the user have a not paid cart and right now have a cart without login
            ProductModel::updateCartSessionIdByCartId($session_id, $latestCart['cart_id']);
        } else {
            // come here when:
            // 1- there is no user and work with sessionId
            // 2- we have user but don't have cart that not paid
            $latestCart = ProductModel::fetchCartBySessionIdAndPaid($session_id, 0);

            if ($latestCart != null && $user_id != 0) {
                ProductModel::updateCartUserIdByCartId($user_id, $latestCart['cart_id']);
            }
        }

        return $latestCart;
    }

    public function getCurrentCart()
    {
        $latestCart = $this->findLatestCart();
        if ($latestCart != null) {
            return $latestCart;
        }

        $user_id = getUserId();
        $invoice_id = PaymentModel::create_invoice($user_id, 0, getCurrentDateTime(), getCurrentDateTime(), 'سبد خرید');
        $cartId = ProductModel::insertCart(session_id(), $user_id, $invoice_id, 0);

        $cart = ProductModel::fetchCartByCartId($cartId);
        return $cart;
    }

    public function addToCart($product_id)
    {
        $cart = $this->getCurrentCart();

        $currentOrder = ProductModel::fetchOrderByProductIdAndCartId($product_id, $cart['cart_id']);

        if ($currentOrder == null) {
            ProductModel::insertOrder($cart['cart_id'], $product_id, 1);
        } else {
            ProductModel::updateOrderQuantityByOrderId($currentOrder['order_id'], $currentOrder['quantity'] + 1);
        }

        $this->calculateAndUpdateCartPrice($cart);
        $this->refreshCartPreview($cart);
    }

    public function removeOrder($order_id)
    {
        $cart = $this->getCurrentCart();
        $db = Db::getInstance();
        $db->modify("DELETE FROM pym_order WHERE order_id=:order_id", array(
            'order_id' => $order_id,
        ));
        $this->calculateAndUpdateCartPrice($cart);
        $this->refreshCartPreview($cart);
    }

    public function refreshCartPreview($cart = null)
    {
        if ($cart == null) {
            $cart = $this->getCurrentCart();
        }
        $db = Db::getInstance();
        $orderItems = $db->first("SELECT COUNT(*) AS total FROM pym_order WHERE cart_id=:cart_id", array(
            'cart_id' => $cart['cart_id'],
        ), 'total');

        $orders = $db->query("SELECT * FROM pym_order LEFT OUTER JOIN food ON pym_order.product_id=food.food_id WHERE cart_id=:cart_id", array(
            'cart_id' => $cart['cart_id'],
        ));
        $invoice_hash = $db->first("SELECT * FROM x_invoice WHERE invoice_id=:invoice_id", array(
            'invoice_id' => $cart['invoice_id'],
        ), 'hash');

        $dataCartPreview['orders'] = $orders;
        $dataCartPreview['invoice_hash'] = $invoice_hash;
        ob_start();
        View::renderPartial("/product/cartPreview.php", $dataCartPreview);
        $cartPreviewOrders = ob_get_clean();

        $data['orderItems'] = $orderItems;
        $data['cartPreviewOrders'] = $cartPreviewOrders;
        echo json_encode($data);
    }

    private function calculateAndUpdateCartPrice($cart)
    {
        $db = Db::getInstance();

        $orders = $db->query("SELECT * FROM pym_order INNER JOIN food ON product_id=food_id WHERE cart_id=:cart_id", array(
            'cart_id' => $cart['cart_id'],
        ));

        $finalPrice = 0;
        foreach ($orders as $order) {
            $priceWithDiscount = $order['foodPrice'] - $order['foodPrice'] * $order['foodDiscount'] / 100;
            $finalPrice += $order['quantity'] * $priceWithDiscount;
        }

        $db->modify("UPDATE pym_cart SET price=:price WHERE cart_id=:cart_id", array(
            'cart_id' => $cart['cart_id'],
            'price' => $finalPrice,
        ));

        $db->modify("UPDATE x_invoice SET price=:price WHERE invoice_id=:invoice_id", array(
            'invoice_id' => $cart['invoice_id'],
            'price' => $finalPrice,
        ));
    }

    public function cart()
    {
        $cart = $this->getCurrentCart();
        $db = Db::getInstance();

        $orders = $db->query("SELECT * FROM pym_order LEFT OUTER JOIN food ON pym_order.product_id=food.food_id WHERE cart_id=:cart_id", array(
            'cart_id' => $cart['cart_id'],
        ));

        $invoice_hash = $db->first("SELECT * FROM x_invoice WHERE invoice_id=:invoice_id", array(
            'invoice_id' => $cart['invoice_id'],
        ), 'hash');


        $dataCartPreview['orders'] = $orders;
        $dataCartPreview['invoice_hash'] = $invoice_hash;

        $dataCartPreview['activePage'] = '';
        $dataCartPreview['pageId'] = '';
        $dataCartPreview['topTitle'] = 'مدیریت سبد خرید ';
        $dataCartPreview['pageTitle'] = 'مدیریت سبد خرید';

        View::render('default', "/product/cart.php", $dataCartPreview);
    }

    public function updateQuantity()
    {
        $orderId = $_POST['orderId'];
        $quantity = $_POST['quantity'];

        $db = Db::getInstance();

        $db->modify("UPDATE pym_order SET quantity=:quantity WHERE order_id=:orderId", array(
            'quantity' => $quantity,
            'orderId' => $orderId,
        ));
    }
}