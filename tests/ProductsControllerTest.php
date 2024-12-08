<?php

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\ProductsController;
use App\Models\Product;
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
}
