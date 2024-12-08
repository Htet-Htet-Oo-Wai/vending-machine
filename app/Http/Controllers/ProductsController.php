<?php

namespace App\Http\Controllers;

use App\Utilities\Response;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

/**
 * Controller for managing products.
 */
class ProductsController extends Controller
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Display a paginated list of all products for administrators.
     *
     * @return void
     */
    public function index()
    {
        $this->checkRole(config('constants.admin_role'));
        $limit = 2;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $orderDir = isset($_GET['dir']) && in_array($_GET['dir'], ['asc', 'desc']) ? $_GET['dir'] : 'asc';

        $products = Product::getAll($orderBy, $orderDir, $limit, $offset);
        view('admin/products/index', $products);
    }

    /**
     * Display a paginated list of all products for users.
     *
     * @return void
     */
    public function getAllProducts()
    {
        $this->checkRole(config('constants.user_role'));
        $limit = 2;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $_SESSION['current_page'] = $page;
        $offset = ($page - 1) * $limit;
        $orderBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
        $orderDir = isset($_GET['dir']) && in_array($_GET['dir'], ['asc', 'desc']) ? $_GET['dir'] : 'asc';

        $products = Product::getAll($orderBy, $orderDir, $limit, $offset);
        view('products/index', $products);
    }

    /**
     * Handle the purchase of products in the cart.
     *
     * @return void
     */
    public function purchase()
    {
        $this->checkRole(config('constants.user_role'));
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
            $result = Product::purchase();
            if($result['status'] == true) {
                $_SESSION['success'] = 'Placed order successfully.';
                Response::redirect('/products');
            } else {
                $_SESSION['errors'] = $result['errors'];
                header('Location: /cart');
            }
        }
    }

    /**
     * Show the create product form.
     *
     * @return void
     */
    public function create()
    {
        $this->checkRole(config('constants.admin_role'));
        view('admin/products/create');
    }

    /**
     * Store a newly created product.
     *
     * @return void
     */
    public function store()
    {
        $this->checkRole(config('constants.admin_role'));
        $request = new ProductRequest($_POST);
        if ($request->validate()) {
            $data = $request->sanitized();
            if (php_sapi_name() === 'cli') {
                $data['name'] = $_POST['name'] ?? null;
                $data['price'] = $_POST['price']  ?? null;
                $data['quantity_available'] = $_POST['quantity_available']  ?? null;
            }
            $result = Product::create($data);
            if ($result['status'] == 'success') {
                $_SESSION['success'] = 'Product is Created Successfully.';
                Response::redirect('/admin/products');
            }
        } else {
            Response::withErrors($request->errors(), '/admin/products/create');
        }
    }

    /**
     * Show the edit product form.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        $this->checkRole(config('constants.admin_role'));
        $product = Product::find($id);
        view('admin/products/edit', compact('product'));
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @return void
     */
    public function update($id)
    {
        $this->checkRole(config('constants.admin_role'));
        $request = new ProductRequest($_POST);
        if ($request->validate()) {
            $data = $request->sanitized();
            Product::update($id, $data['name'], $data['price'], $data['quantity']);
            $_SESSION['success'] = 'Product is updated Successfully.';
            Response::redirect('/admin/products');
        } else {
            Response::withErrors($request->errors(), '/admin/products/' . $id . '/edit');
        }
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $this->checkRole(config('constants.admin_role'));
        Product::delete($id);
        $_SESSION['success'] = 'Product is deleted Successfully.';
        Response::redirect('/admin/products');
    }

    /**
     * Add a product to the cart.
     *
     * @param int $id
     * @return void
     */
    public function addToCart($id)
    {
        $this->checkRole(config('constants.user_role'));
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $productId = $id;
        $request = new AddToCartRequest($_POST);
        if (!$request->validate()) {
            Response::withErrors($request->errors(), '/admin/products');
        }
        $data = $request->sanitized();
        $productName = $data['product_name'];
        $productPrice = $data['product_price'];
        $quantity = $data['quantity'];
        $productFound = false;

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $productFound = true;
                break;
            }
        }
        if (!$productFound) {
            $_SESSION['cart'][] = [
                'id' => $productId,
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => $quantity,
            ];
        }
        $_SESSION['success'] = 'Add to cart successfully.';
        Response::redirect('/products');
    }

    /**
     * View the shopping cart.
     *
     * @return void
     */
    public function viewCart()
    {
        $this->checkRole(config('constants.user_role'));
        view('products/cart');
    }

    /**
     * Clear the shopping cart.
     *
     * @return void
     */
    public function clearCart()
    {
        $this->checkRole(config('constants.user_role'));
        $_SESSION['cart'] = array();
        header('Location: /products');
    }
}
