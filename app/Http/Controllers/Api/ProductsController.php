<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{
    /**
     * Get all products.
     */
    public function index()
    {
        $this->verifyToken();
        $limit = 2;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $orderDir = isset($_GET['dir']) && in_array($_GET['dir'], ['asc', 'desc']) ? $_GET['dir'] : 'asc';
        $products = Product::getAll($orderBy, $orderDir, $limit, $offset);
        echo json_encode(['status' => 'success', 'data' => $products]);
    }
}
