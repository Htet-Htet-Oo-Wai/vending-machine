<?php
include __DIR__ . '/../../layouts/header.php';
include __DIR__ . '/../../layouts/nav.php';
include __DIR__ . '/../../layouts/aside.php';
?>
<div class="content-wrapper">
    <div class="content-header">
        <h1 class="ml-3">Create New User</h1>
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
                        <form id="userForm" action="/admin/users" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Name <span class="danger">*</span></label>
                                            <input name="name" id="name" type="text" class="form-control" placeholder="User Name">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Email <span class="danger">*</span></label>
                                            <input name="email" id="email" type="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Password <span class="danger">*</span></label>
                                            <input name="password" id="password" type="password" class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Role <span class="danger">*</span></label>
                                            <select class="custom-select" name="role_id" id="role_id">
                                                <?php foreach ($roles as $role) {
                                                    $id = $role['id'];
                                                    $name = $role['name'];
                                                    echo "<option value='$id'>$name</option>";
                                                } ?>
                                            </select>
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
                                        <a href="/admin/users" class="btn btn-block btn-secondary">Back</a>
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
        $("#userForm").validate({
            rules: {
                name: "required",
                password: "required",
                role_id: "required",
                email: {
                    required: true,
                    email: true // This built-in method checks if the email is in the correct format
                },
            },
            messages: {
                name: "Please enter user name",
                password: "Please enter password",
                role_id: "Please choose role",
                email: {
                    required: "Please enter email",
                    email: "Please enter email format"
                },
            }
        });
    });
</script>
<?php
include __DIR__ . '/../../layouts/footer.php';
?>