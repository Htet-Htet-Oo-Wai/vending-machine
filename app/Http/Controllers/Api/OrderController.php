<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Add a product to the cart.
     *
     * @param int $id
     */
    public function addToCart($id)
    {
        $this->verifyToken();
        $request = new AddToCartRequest($_POST);
        if (!$request->validate()) {
            http_response_code(422);
            echo json_encode(['errors' => $request->errors()]);
            return;
        }
        $data = $request->sanitized();
        $productName = $data['product_name'];
        $productPrice = $data['product_price'];
        $quantity = $data['quantity'];
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $productFound = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] += $quantity;
                $productFound = true;
                break;
            }
        }
        if (!$productFound) {
            $_SESSION['cart'][] = [
                'id' => $id,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity,
            ];
        }
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart.']);
    }

    /**
     * View the current cart.
     */
    public function viewCart()
    {
        $this->verifyToken();
        $cart = $_SESSION['cart'] ?? [];
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $cart]);
    }

    /**
     * Purchase the items in the cart.
     */
    public function purchase()
    {
        $user = $this->verifyToken();
        $_SESSION['user_id'] = $user->sub;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method.']);
            return;
        }
        if (empty($_SESSION['cart'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Cart is empty.']);
            return;
        }
        Product::purchase();
        unset($_SESSION['cart']);
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully.']);
    }

    /**
     * Get the authenticated user's orders.
     */
    public function getAuthUserOrders()
    {
        $limit = 2;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $orderBy = $_GET['sort'] ?? 'id';
        $orderDir = (isset($_GET['dir']) && in_array(strtolower($_GET['dir']), ['asc', 'desc'])) ? $_GET['dir'] : 'asc';
        $user = $this->verifyToken();
        $orders = Order::getAuthUserOrders($user->sub, $orderBy, $orderDir, $limit, $offset);
        http_response_code(200);
        echo json_encode(['status' => 'success', 'data' => $orders]);
    }
}
