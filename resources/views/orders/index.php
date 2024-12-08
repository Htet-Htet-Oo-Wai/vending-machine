<?php
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/nav.php';
include __DIR__ . '/../layouts/aside.php';
?>

<div class="content-wrapper">
    <div class="content-header ml-3">
        <h1>My Orders</h1>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="branch" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Product Name <a href="/admin/orders?sort=product_name&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Quantity <a href="/admin/orders?sort=quantity&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Total Price <a href="/admin/orders?sort=total_price&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                            <th>Transaction Date <a href="/admin/orders?sort=transaction_date&dir=<?php echo $orderDir === 'asc' ? 'desc' : 'asc'; ?>"><i class="fa-solid fa-sort"></i></a></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $index => $order): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(++$index); ?></td>
                                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                                <td><?php echo htmlspecialchars($order['total_price']); ?></td>
                                                <td><?php echo htmlspecialchars($order['transaction_date']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-left mt-3">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a class="btn btn-sm <?php echo ($i == $_SESSION['current_page']) ? 'btn-primary' : 'btn-secondary'; ?>"
                                        href="/admin/orders?page=<?php echo $i; ?>&sort=<?php echo $orderBy; ?>&dir=<?php echo $orderDir; ?>">
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
include __DIR__ . '/../layouts/footer.php';
?>