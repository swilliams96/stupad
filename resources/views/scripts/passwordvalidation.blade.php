    <!-- Client side password validation -->
    <script type="text/javascript">
        function EquatePasswordFields() {
            if ($("#password").val() != "" && $("#password-confirm").val() != "") {
                if ($("#password-confirm").val() == $("#password").val()) {
                    $("#password-confirm").css("border", "1px solid #ccc");
                } else {
                    $("#password-confirm").css("border", "1px solid #A11");
                }
            }
        }

        $(function() {
            $('#password').keyup(function() {
                if ($("#password").val().length >= 8 || $("#password").val().length == 0) {
                    $("#password").css("border", "1px solid #ccc");
                    $("#password-helper-text").css("display", "none");
                } else {
                    $("#password-helper-text").css("display", "inline");
                    $("#password").css("border", "1px solid #A11");
                }
                EquatePasswordFields();
            });

            $('#password-confirm').keyup(function() {
                EquatePasswordFields();
            });
        });
    </script>