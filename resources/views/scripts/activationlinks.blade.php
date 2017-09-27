    <script>
        $('a.activation-link').click(function() {
            var url = '/listings/' + $(this).attr('listing-id') + '/activate';
            var form = $('<form action="' + url + '" method="POST">{{ csrf_field() }}</form>');
            form.appendTo($('body')).submit();
            return false;
        });

        $('a.deactivation-link').click(function() {
            var url = '/listings/' + $(this).attr('listing-id') + '/deactivate';
            var form = $('<form action="' + url + '" method="POST">{{ csrf_field() }}</form>');
            form.appendTo($('body')).submit();
            return false;
        });
    </script>