<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/nav.php';
include __DIR__ . '/../layouts/aside.php';
?>

<div class="content-wrapper">
    <div class="content-header ml-3">
        <h1>Cart Details</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($_SESSION['errors'] as $err) {
                                    echo "<div>$err</div>";
                                }
                                ?>
                            </div>
                        <?php unset($_SESSION['errors']);
                        endif; ?>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success" role="alert">
                                <div><?php echo $_SESSION['success']; ?></div>
                            </div>
                        <?php unset($_SESSION['success']);
                        endif; ?>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="branch" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Quantity</th>
                                            <th>Price(USD)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars(++$index); ?></td>
                                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                                    <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <form method="post" action="/products/purchase">
                                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                    <button type="submit" class="btn btn-primary">Place Order</button>
                                    <a href="/cart/clear" class="btn btn-danger">Clear Cart</a>
                                <?php endif; ?>
                                <a href="/products" class="btn btn-secondary">Back</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>