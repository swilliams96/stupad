    <script>
        $(function () {
            $('.radio-group input[type=radio]').change(function() {
                $(this).parents('.radio-group').children('label').removeClass('selected');
                $(this).parent('label').addClass('selected');
            })
        })

        $(document).ready(function() {
            $('.radio-group input[type=radio]:checked').parent('label').addClass('selected');
        });
    </script>