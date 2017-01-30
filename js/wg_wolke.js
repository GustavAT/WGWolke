/*
* Field validation
*/

function validateField(id) {
    var field = $("#" + id);
    if (field.val().length === 0) {
        $("#" + id + "-form-group").addClass("has-error");
    } else {
        $("#" + id + "-form-group").removeClass("has-error");
    }
    return field.val().length !== 0;
}

function validateEmailField(id) {
    var field = $("#" + id);
    if (!isValidEmailAdress(field.val())) {
        $("#" + id + "-form-group").addClass("has-error");
    } else {
        $("#" + id + "-form-group").removeClass("has-error");
    }
    return isValidEmailAdress(field.val());
}

function validatePasswordField(id) {
    var field = $("#" + id);
    if (field.val().length < 8) {
        $("#" + id + "-form-group").addClass("has-error");
    } else {
        $("#" + id + "-form-group").removeClass("has-error");
    }
    return field.val().length >= 8;
}

function validatePasswordEquals(id, id2) {
    var field1 = $("#" + id2);
    var field2 = $("#" + id);
    if (field2.val() !== field1.val() || field1.val().length === 0) {
        $("#" + id2 + "-form-group").addClass("has-error");
    } else {
        $("#" + id2 + "-form-group").removeClass("has-error");
    }
    return field2.val() === field1.val();
}

function isValidEmailAdress(email) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(email);
}

function isValidName(name) {
    var pattern = /^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\\/<>?:;|=.,]{1,20}$/;
    return pattern.test(name);
}

/*
* Nav bar
*/ 

function initNavBar() {
    $("#alert-incorrect-data").hide();
    $("#bb-title").popover({content: "Title must not be empty", trigger: "focus", placement:"bottom"});
    $("#bb-title").focusout(function(){ validateField("bb-title") });
    $("#button-create").click(validateBlackboardForm);
    $("#button-google-it").click(searchGoogle);
    $("#input-google-it").keyup(function(e) {
        if (e.which === 13) {
            searchGoogle();
        }
    });
}

function searchGoogle() {
    var input = $("#input-google-it").val();
    window.open("https://www.google.com/search?q=" + input, "_blank");
    $("#input-google-it").val("");
}

function validateBlackboardForm() {
    var title = $("#bb-title");
    var message = $("#bb-message");

    var isValid = validateField("bb-title");
    if (isValid) {
        $("#alert-incorrect-data").hide();
        $.ajax({
            url: "../code/CreateNewsFeedItem.php",
            type: "POST",
            data: {
                title: title.val(),
                message: message.val(),
                community_oid: window.phpVars.communityOid,                        
                user_oid: window.phpVars.userOid
            },
            dataType: "json"
        }).done(function(data) {
            var errorDiv = $("#alert-incorrect-data");
            var message;
            if (!data) {
                message = window.phpVars.unknownError;
            } else if (!data.success) {
                message = data.result;
            } else {
                window.location.replace("Dashboard.php");
                return;
            }
            errorDiv.text(message).show();
        }).fail(function(jqXhr, status, error) {
            $("#alert-incorrect-data").text(window.phpVars.unknownError).show();
        });
    } else {
        $("#alert-incorrect-data").show();
    }
}
