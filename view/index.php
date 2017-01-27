<?php
require_once("../code/PrintHelper.php");
require_once("../code/SessionHelper.php");
require_once("../code/Util.php");

// SessionHelper::logOut();
SessionHelper::doActivity();

$user_oid = SessionHelper::getCurrentUserOid();
if ($user_oid !== null) {
    Util::redirect("Dashboard.php");
}


$login_valid = true;
$email = Util::parsePost("email");
$password = Util::parsePost("password");

if (!Util::isEmpty($email) && !Util::isEmpty($password)) {
    $login_valid = SessionHelper::logIn($email, $password);
    if ($login_valid) {
        Util::redirect("Dashboard.php");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> WG Wolke </title>
</head>

<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <img src="../images/wg_wolke.png" class="img-responsive text-center" alt="WG Wolke Logo">
            </div>

        </div>
        <div class="row">
            <div class="col-md-6">
            <form role="form" method="POST"> <!-- onsubmit="return checkLoginForm()"-->
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Log in</h3>
                    </div>
                    <div class="panel-body panel-height">
                        <div>
                            <p class="lead text-muted"> Hey there, welcome back! </p>
                        </div>
                            <fieldset>
                                <div class="form-group input-group form-group-login">
                                    <span class="input-group-addon">@</span>
                                    <input class="form-control" id="login-email" placeholder="E-Mail" name="email" type="email" autofocus>
                                </div>
                                <div class="form-group form-group-login">
                                    <input class="form-control" id="login-password" placeholder="Password" name="password" type="password" value="">
                                </div>

                                <h6 class="text-right text-muted text-success"><a class="link-no-decoration">Forgot password?</a></h6>                                
                            </fieldset>
                        <?php
                            if (!$login_valid) { ?>
                                <div class="alert alert-danger">
                                    E-mail or password is incorrect.
                                </div>
                            <?php } ?>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-lg btn-success btn-block" id="button-login">Login</button>
                    </div>
                </div>
                
                    </form>
            </div>

            <div class="col-md-6">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body panel-height">
                        <p class="lead text-muted"> Don't have an account yet? </p>
                        <p class="text-muted"> Get access to convenient community features. </p>                        
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#register-dialog">Register</button>                        
                    </div>
                </div>
            </div>

            
        </div>
    </div>

    <div class="modal fade" id="register-dialog" tabindex="-1" role="dialog" aria-labelledby="registerTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="registerTitle">Register</h4>
                </div>
                <div class="modal-body">
                    <p class="lead text-muted"> Community </p>
                    <div class="form-group" id="register-community-name-form-group">
                        <input class="form-control" placeholder="Community-Name" id="register-community-name" name="community-name" type="text" autofocus maxlength="50">
                    </div>
                    <div class="form-group" id="register-community-description-form-group">
                        <textarea class="form-control" rows="3" placeholder="Description" maxlength="500"></textarea>
                    </div>
                    <p class="lead text-muted"> About you </p>

                    <div class="form-group" id="register-firstname-form-group">
                        <input type="text" id="register-firstname" class="form-control" placeholder="First Name" maxlength="50">
                    </div>
                    <div class="form-group" id="register-lastname-form-group">
                        <input type="text" id="register-lastname" class="form-control" placeholder="Last Name" maxlength="50">
                    </div>
                    <div class="form-group input-group" id="register-email-form-group">
                        <span class="input-group-addon">@</span>
                        <input type="text" id="register-email" class="form-control" placeholder="E-Mail" maxlength="200">
                    </div>
                    <div class="form-group" id="register-password-form-group">
                        <input type="password" id="register-password" class="form-control" placeholder="Password" maxlength="32">
                    </div>
                    <div class="form-group" id="register-password-confirm-form-group">
                        <input type="password" id="register-password-confirm" class="form-control" placeholder="Confirm Password" maxlength="32">
                    </div>
                    <div class="alert alert-danger" id="alert-incorrect-data">
                        We are sorry, some fields contain incorrect information.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-register">Register</button>
                </div>
            </div>
        </div>
    </div>

    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">

        $(document).ready(function() {
            $("#alert-incorrect-data").hide();
            $("#register-community-name").popover({content: "Community name must not be empty", trigger: "focus", placement:"bottom"});
            $("#register-password-confirm").popover({content: "Repeat your password", trigger: "focus", placement: "bottom"});
            $("#register-password").popover({content: "Passwords must be at least 8 characters long", trigger: "focus", placement: "bottom"});
            $("#button-register").click(validateRegisterForm);

            $("#register-community-name").focusout(validateCommunityName);
            $("#register-firstname").focusout(validateFirstName);
            $("#register-lastname").focusout(validateLastName);
            $("#register-email").focusout(validateEmail);
            $("#register-password").focusout(validatePassword);
            $("#register-password-confirm").focusout(validatePasswordConfirm);

            <?php if (!$login_valid) { ?>
                $(".form-group-login").addClass("has-error");
            <?php } ?>
        });

        function validateCommunityName() {
            var communityName = $("#register-community-name");
            if (communityName.val().length === 0) {
                $("#register-community-name-form-group").addClass("has-error");
            } else {
                $("#register-community-name-form-group").removeClass("has-error");
            }
            return communityName.val().length !== 0;
        }

        function validateFirstName() {
            var firstName = $("#register-firstname");
            if (!isValidName(firstName.val())) {
                $("#register-firstname-form-group").addClass("has-error");
            } else {
                $("#register-firstname-form-group").removeClass("has-error");
            }
            return isValidName(firstName.val());
        }

        function validateLastName() {
            var lastName = $("#register-lastname");
            if (!isValidName(lastName.val())) {
                $("#register-lastname-form-group").addClass("has-error");
            } else {
                $("#register-lastname-form-group").removeClass("has-error");
            }
            return isValidName(lastName.val());
        }

        function validateEmail() {
            var email = $("#register-email");
            if (!isValidEmailAddress(email.val())) {
                $("#register-email-form-group").addClass("has-error");
            } else {
                $("#register-email-form-group").removeClass("has-error");
            }
            return isValidEmailAddress(email.val());
        }

        function validatePassword(){
            var password = $("#register-password");
            if (password.val().length < 8) {
                $("#register-password-form-group").addClass("has-error");
            } else {
                $("#register-password-form-group").removeClass("has-error");
            }
            return password.val().length >= 8;
        }

        function validatePasswordConfirm() {
            var passwordConfirm = $("#register-password-confirm");
            var password = $("#register-password");
            if (password.val() !== passwordConfirm.val() || passwordConfirm.val().length === 0) {
                $("#register-password-confirm-form-group").addClass("has-error");
            } else {
                $("#register-password-confirm-form-group").removeClass("has-error");
            }
            return password.val() === passwordConfirm.val();
        }

        function validateRegisterForm() {
            var communityName = $("#register-community-name");
            var communityDescription = $("#register-community-description");
            var firstName = $("#register-firstname");
            var lastName = $("#register-lastname");
            var email = $("#register-email");
            var password = $("#register-password");
            var passwordConfirm = $("#register-password-confirm");

            validateCommunityName();
            validateFirstName();
            validateLastName();
            validateEmail();
            validatePassword();
            validatePasswordConfirm();

            var isValid = validateCommunityName()
                && validateFirstName()
                && validateLastName()
                && validateEmail()
                && validatePassword()
                && validatePasswordConfirm();
            
            if (isValid || true) {
                $("#alert-incorrect-data").hide();
                $.ajax({
                    url: "../code/Registration.php",
                    type: "POST",
                    data: {
                        community_name: communityName.val(),
                        community_description: communityDescription.val(),
                        user_first_name: firstName.val(),
                        user_last_name: lastName.val(),
                        user_email: email.val(),
                        user_password: password.val(),
                        user_password_confirm: passwordConfirm.val()
                    },
                    dataType: "json"
                }).done(function() {
                    // Util::redirect("Dashboard.php");
                    window.location.replace("Dashboard.php");
                }).fail(function(jqXhr, status, error) {
                    debugger;
                    console.log(jqXhr, status, error);
                });
            } else {
                $("#alert-incorrect-data").show();
            }
        }

        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
        };

        function isValidName(name) {
            var pattern = /^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\\/<>?:;|=.,]{1,20}$/;
            return pattern.test(name);
        };

    </script>
</body>
</html>
