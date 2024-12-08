<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/adminlte.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>

<body class="login-page" cz-shortcut-listen="true" style="min-height: 494.802px;">
    <div class="login-box">
        <div class="login-logo">
            <b>Admin Dashboard</b>
        </div>
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
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form id="loginForm" action="/login" method="POST">
                    <div class="input-group">
                        <input name="username" id="username" type="text" class="form-control" placeholder="User Name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <span class="invalid-feedback" role="alert"></span>
                    <div class="input-group mt-3">
                        <input type="password" id="password" class="form-control" name="password" placeholder="New Password">
                        <div class="login-password input-group-append">
                            <div class="input-group-text">
                                <a style="cursor: pointer;" id="password_toggle">
                                    <i class="fas fa-eye-slash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <span class="invalid-feedback" role="alert">
                    </span>
                    <div class="row mt-3">
                        <div class="col-12 text-right">
                            <button type="submit" class="submit-btn btn btn-primary">
                                Login
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/fontawesome.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on("click", "#password_toggle", function() {
                const icon = $(this).find('i');
                const passwordField = $('#password');
                showHidePassword(passwordField, icon);
            });

            function showHidePassword(passwordField, icon) {
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                if (type === 'password') {
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            }

            function validateField($input, errorMessage) {
                const value = $input.val().trim();
                const errorBox = $input.closest('.input-group').next(".invalid-feedback");
                console.log(errorBox);
                if (value === "") {
                    errorBox.text(errorMessage).show();
                    $input.addClass("is-invalid");
                    return false;
                } else {
                    errorBox.text("");
                    $input.removeClass("is-invalid");
                    return true;
                }
            }
            $("#loginForm").on("submit", function(e) {
                const isUsernameValid = validateField($("#username"), "Username is required.");
                const isPasswordValid = validateField($("#password"), "Password is required.");
                if (!isUsernameValid || !isPasswordValid) {
                    e.preventDefault();
                }
            });
            $("#username, #password").on("input", function() {
                const errorMessage = $(this).attr("id") === "username" ?
                    "Username is required." :
                    "Password is required.";
                validateField($(this), errorMessage);
            });
        });
    </script>
</body>

</html>