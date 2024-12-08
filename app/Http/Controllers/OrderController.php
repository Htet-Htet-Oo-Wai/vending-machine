<?php

namespace App\Http\Controllers;

use App\Models\Order;

/**
 * Controller for managing orders.
 */
class OrderController extends Controller
{
    /**
     * Display a paginated list of all orders for administrators.
     *
     * @return void
     */
    public function index(): void
    {
        $this->checkRole(config('constants.admin_role'));
        $limit = 2;
        $page = $_GET['page'] ?? 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = $_GET['sort'] ?? 'id';
        $orderDir = (isset($_GET['dir']) && in_array(strtolower($_GET['dir']), ['asc', 'desc'])) ? strtolower($_GET['dir']) : 'asc';
        $orders = Order::getAll($orderBy, $orderDir, $limit, $offset);
        view('admin/orders/index', $orders);
    }

    /**
     * Display a paginated list of orders for the authenticated user.
     *
     * @return void
     */
    public function getAuthUserOrders(): void
    {
        $this->checkRole(config('constants.user_role'));
        $limit = 2;
        $page = $_GET['page'] ?? 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = $_GET['sort'] ?? 'id';
        $orderDir = (isset($_GET['dir']) && in_array(strtolower($_GET['dir']), ['asc', 'desc'])) ? strtolower($_GET['dir']) : 'asc';
        $orders = Order::getAuthUserOrders($orderBy, $orderDir, $limit, $offset);
        view('orders/index', $orders);
    }
}
