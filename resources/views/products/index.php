<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/nav.php';
include __DIR__ . '/../layouts/aside.php';
?>

<div class="content-wrapper">
    <div class="content-header ml-3">
        <h1>Products</h1>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div>
                <div class="card-tools mt-2 mb-3">
                    <a class="btn btn-primary mr-2" href="/cart"> Go To Cart </a>
                </div>
                <div class="row">
                    <?php foreach ($products as $index => $product) {
                    ?>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="product-container">
                                <div class="mt-3">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>
                                                <h6><?php echo htmlspecialchars($product['name']); ?></h6>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Price:</td>
                                            <td><?php echo htmlspecialchars($product['price']); ?> USD</td>
                                        </tr>
                                        <form method="post" action="/products/<?php echo $product['id']; ?>/addtocart">
                                            <tr>
                                                <td>Qty:</td>
                                                <th>
                                                    <input name="quantity" id="quantity" type="number" class="form-control" min="0" placeholder="0">
                                                </th>
                                            </tr>

                                    </table>
                                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                                    <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                                    <button type="submit" class="btn btn-block btn-primary" name="add_to_cart">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
                <div class="float-left mt-3">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a class="btn btn-sm <?php echo ($i == $_SESSION['current_page']) ? 'btn-primary' : 'btn-secondary'; ?>"
                            href="/products?page=<?php echo $i; ?>&sort=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>