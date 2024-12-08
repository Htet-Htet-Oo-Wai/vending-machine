<?php

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\ProductsController;
use App\Models\User;
use App\Providers\DBConnection;

class ProductsControllerTest extends TestCase
{
    private $productsController;
    private $mockDBConnection;

    protected function setUp(): void
    {
        $this->mockDBConnection = $this->createMock(DBConnection::class);
        $this->productsController = new ProductsController(
            $this->mockDBConnection
        );
    }

    public function testIndexRetrievesProducts()
    {
        ob_start();
        $user = User::getUser('admin@gmail.com');
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_name'] = $user['role_name'];
        $this->productsController->index();
        $output = ob_get_clean();
        $this->assertStringContainsString('Product Management', $output);
        if (ob_get_level() > 0) {
            ob_flush();
        }
    }

    public function testProductCreatePage()
    {
        ob_start();
        $user = User::getUser('admin@gmail.com');
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_name'] = $user['role_name'];
        $this->productsController->create();
        $output = ob_get_clean();
        $this->assertStringContainsString('Create New Product', $output);
        if (ob_get_level() > 0) {
            ob_flush();
        }
    }

    public function testStoreRedirectsToCreatePageWhenNameIsEmpty()
    {
        $_POST = ['price' => '30.00', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains('Please enter a product name.', array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsEmpty()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Please enter a price.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsNotNumeric()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => 'Test', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Please enter a valid price.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenPriceIsZero()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '-1', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Price must be greater than $0.01.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsNotSet()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Please enter a stock quantity.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsNotNumeric()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00', 'quantity_available' => 'Test'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Please enter a valid stock quantity.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToCreatePageWhenQuantityIsZero()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00', 'quantity_available' => '-1'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertContains("Stock quantity must be greater than or equal 1.", array_values($_SESSION['errors']));
    }

    public function testStoreCreatesProductAndRedirectsWhenValidDataProvided()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->store();
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Product is Created Successfully.", $_SESSION['success']);
    }

    public function testProductEditPage()
    {
        ob_start();
        $user = User::getUser('admin@gmail.com');
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_name'] = $user['role_name'];
        $this->productsController->edit(1);
        $output = ob_get_clean();
        $this->assertStringContainsString('Edit Product', $output);
        if (ob_get_level() > 0) {
            ob_flush();
        }
    }

    public function testStoreRedirectsToEditPageWhenNameIsEmpty()
    {
        $_POST = ['price' => '30.00', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Please enter a product name.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenPriceIsEmpty()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Please enter a price.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenPriceIsNotNumeric()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => 'Test', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Please enter a valid price.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenPriceIsZero()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '-1', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Price must be greater than $0.01.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenQuantityIsNotSet()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Please enter a stock quantity.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenQuantityIsNotNumeric()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00', 'quantity_available' => 'Test'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Please enter a valid stock quantity.", array_values($_SESSION['errors']));
    }

    public function testStoreRedirectsToEditPageWhenQuantityIsZero()
    {
        $_POST = ['name' => 'Coca Cola (Testing)', 'price' => '30.00', 'quantity_available' => '-1'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertContains("Stock quantity must be greater than or equal 1.", array_values($_SESSION['errors']));
    }

    public function testStoreEditsProductAndRedirectsWhenValidDataProvided()
    {
        $_POST = ['name' => 'Testing Updated', 'price' => '30.00', 'quantity_available' => '10'];
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->update(1);
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Product is updated Successfully.", $_SESSION['success']);
    }

    public function testDestroy()
    {
        $_SESSION = ['role_name' => 'Admin'];
        $this->productsController->destroy(1);
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Product is deleted Successfully.", $_SESSION['success']);
    }

    public function testaddToCart()
    {
        $_POST = ['product_name' => 'Coca Cola (Testing)', 'product_price' => '30.00', 'quantity' => '1'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(19);
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Add to cart successfully.", $_SESSION['success']);
    }

    public function testaddToCartPageWhenNameIsEmpty()
    {
        $_POST = ['product_price' => '30.00', 'quantity' => '10'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(1);
        $this->assertContains("The product name is required.", array_values($_SESSION['errors']));
    }

    public function testaddToCartPageWhenPriceIsEmpty()
    {
        $_POST = ['product_name' => 'Coca Cola (Testing)', 'quantity' => '10'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(1);
        $this->assertContains("The product price is required.", array_values($_SESSION['errors']));
    }

    public function testaddToCartPageWhenPriceIsZero()
    {
        $_POST = ['product_name' => 'Coca Cola (Testing)', 'product_price' => '-1', 'quantity' => '10'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(1);
        $this->assertContains("The product price must be greater than $0.01.", array_values($_SESSION['errors']));
    }

    public function testaddToCartPageWhenQuantityIsNotSet()
    {
        $_POST = ['product_name' => 'Coca Cola (Testing)', 'product_price' => '30.00'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(1);
        $this->assertContains("Please enter a quantity.", array_values($_SESSION['errors']));
    }

    public function testaddToCartPageWhenQuantityIsZero()
    {
        $_POST = ['product_name' => 'Coca Cola (Testing)', 'product_price' => '30.00', 'quantity' => '-1'];
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->addToCart(1);
        $this->assertContains("The quantity must be greater than or equal 1.", array_values($_SESSION['errors']));
    }

    public function testPurchase()
    {
        $_SESSION = ['role_name' => 'User'];
        $this->productsController->purchase();
        $this->assertEmpty($_SESSION['errors'] ?? null);
        $this->assertEquals("Placed order successfully.", $_SESSION['success']);
    }
}
