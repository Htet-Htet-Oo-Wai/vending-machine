<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
        <span class="brand-text font-weight-light ml-3">Admin Dashboard</span>
    </a>
    <div class="sidebar">
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sm form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($_SESSION['role_name'] === config('constants.admin_role')): ?>
                    <li class="nav-item">
                        <a href="/admin/products" class="nav-link">
                            <i class="fa-solid fa-tag nav-icon"></i>
                            <p>
                                Products
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/users" class="nav-link">
                            <i class="fas fa-user-cog nav-icon"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/admin/orders" class="nav-link">
                            <i class="fas fa-shopping-basket  nav-icon"></i>
                            <p>
                                Orders
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($_SESSION['role_name'] === config('constants.user_role')): ?>
                    <li class="nav-item">
                        <a href="/products" class="nav-link">
                            <i class="fa-solid fa-tag nav-icon"></i>
                            <p>
                                Products
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/orders" class="nav-link">
                            <i class="fas fa-shopping-basket  nav-icon"></i>
                            <p>
                                My Orders
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <form method="POST" action="/logout" class="nav-link">
                        <a hrefs="#" class="logout-btn" onclick="event.preventDefault(); this.closest('form').submit();" style="cursor: pointer;">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>