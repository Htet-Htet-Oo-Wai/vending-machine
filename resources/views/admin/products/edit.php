<?php
include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/nav.php';
include __DIR__ . '/../../layouts/aside.php';
?>
<div class="content-wrapper">
    <div class="content-header">
        <h1 class="ml-3">Edit Product</h1>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="card">
                        <?php if (isset($_SESSION['errors'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($_SESSION['errors'] as $err) {
                                    echo "<div>$err</div>";
                                }
                                ?>
                            </div>
                        <?php unset($_SESSION['errors']);
                        endif; ?>
                        <form action="/admin/products/<?= $product['id'] ?>/update" method="POST" id="productForm">  
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Name <span class="danger">*</span></label>
                                            <input name="name" id="name" type="text" class="form-control" placeholder="Product Name" value="<?= $product['name'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Price <span class="danger">*</span></label>
                                            <input name="price" id="price" type="number" class="form-control" placeholder="Product Price" value="<?= $product['price'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Stock Available <span class="danger">*</span></label>
                                            <input name="quantity_available" id="quantity_available" type="number" min="1" value="<?= $product['quantity_available'] ?>" class="form-control" placeholder="Stock Available">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row mt-2 mb-2">
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <button type="submit" class="btn btn-block btn-primary">Submit</button>
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2">
                                        <a href="/admin/products" class="btn btn-block btn-secondary">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> <br>
    </section>
</div>
<script>
    $(document).ready(function() {
        $("#productForm").validate({
            rules: {
                name: "required",
                price: {
                    required: true,
                    number: true,
                    min: 0.01
                },
                quantity_available: {
                    required: true,
                    number: true,
                    min: 0.01
                }
            },
            messages: {
                name: "Please enter a product name",
                price: {
                    required: "Please enter a price",
                    number: "Please enter a valid price",
                    min: "Price must be greater than 0"
                },
                quantity_available: {
                    required: "Please enter a stock available",
                    number: "Please enter a valid stock available",
                    min: "stock available must be greater than 0"
                }
            }
        });
    });
</script>
<?php
include __DIR__ . '/../../layouts/footer.php';
?>