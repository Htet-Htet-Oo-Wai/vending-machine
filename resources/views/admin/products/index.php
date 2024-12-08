<?php
include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/nav.php';
include __DIR__ . '/../../layouts/aside.php';
?>

<div class="content-wrapper">
    <div class="content-header ml-3">
        <h1>Product Management</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="card-tools mt-2">
                                <a class="btn btn-block btn-primary mr-2" href="/admin/products/create"> Create New Product </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="branch" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name <a href="/admin/products?sort=name&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Quantity Available <a href="/admin/products?sort=quantity_available&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Price(USD) <a href="/admin/products?sort=price&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $index => $product): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(++$index); ?></td>
                                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['quantity_available']); ?></td>
                                                <td>$<?php echo htmlspecialchars($product['price']); ?></td>
                                                <td>
                                                    <a href="/admin/products/<?php echo $product['id']; ?>/edit">Edit</a> |
                                                    <a href="#" onclick="event.preventDefault(); if (confirm('Are you sure?')) { document.getElementById('delete-form-<?php echo $product['id']; ?>').submit(); }">Delete</a>

                                                    <form id="delete-form-<?php echo $product['id']; ?>" action="/admin/products/<?php echo $product['id']; ?>/delete" method="POST" style="display:none;">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-left mt-3">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a class="btn btn-sm <?php echo ($i == $_SESSION['current_page']) ? 'btn-primary' : 'btn-secondary'; ?>"
                                        href="/admin/products?page=<?php echo $i; ?>&sort=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include __DIR__ . '/../../layouts/footer.php';
?>