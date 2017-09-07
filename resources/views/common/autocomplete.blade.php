    <!-- jQuery Autocomplete -->
    <script>
        $( function() {
            var availableLocations = [
                @php
                    if (isset($all_locations)) {
                        foreach ($all_locations as $location_name) {
                            echo '"' . $location_name . '"' . ($location_name !== end($all_locations) ? ', ' : '');
                        }
                    }
                @endphp

            ];

            $( ".location-autocomplete" ).autocomplete({
                source: availableLocations
            });
        } );
    </script>