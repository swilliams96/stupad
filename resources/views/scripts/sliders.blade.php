    <!-- SLIDERS -->
    <script>
        // TODO: SET DEFAULTS TO THE SESSION DATA IF SESSION DATA IS SET
        $(function() {
            var rent_defaults = [
                {{ Cookie::get('lastsearch_rent_min', 100) }},
                {{ Cookie::get('lastsearch_rent_max', 250) }}
            ];
            var rent_max = 500;
            var currency = "£";
            $("#rent-min-label").html(currency + rent_defaults[0]);
            $("#rent-max-label").html(currency + rent_defaults[1]);
            $("#rent-slider").slider({
                min: 5,
                max: rent_max,
                step: 5,
                range: true,
                values: rent_defaults,
                slide: function(event, ui) {
                    $("#rent-min-label").html(currency + ui.values[0]);
                    $("#rent-max-label").html(currency + ui.values[1]);
                    $("#rent-min").val(ui.values[0]);
                    $("#rent-max").val(ui.values[1]);

                    if (ui.values[0] == rent_max) {
                        $("#rent-min-label").html(currency + rent_max + "+");
                    }
                    if (ui.values[1] == rent_max) {
                        $("#rent-max-label").html(currency + rent_max + "+");
                    }

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 5) {
                            $("#rent-min-label").html("Any");
                            $("#rent-max-label").html("");
                        } else if (ui.values[0] == rent_max) {
                            $("#rent-min-label").html("");
                            $("#rent-max-label").html(currency + rent_max + "+");
                        } else {
                            $("#rent-min-label").html(currency + ui.values[0]);
                            $("#rent-max-label").html("");
                        }
                    } else if (ui.values[0] == 5 && ui.values[1] == rent_max) {
                        $("#rent-min-label").html("Any");
                        $("#rent-max-label").html("");
                    }
                }
            });

            var options_bedrooms = ["Studio", "1 bedroom", "2 bedrooms", "3 bedrooms", "4 bedrooms", "5+ bedrooms"];
            var bedroom_defaults = [
                {{ Cookie::get('lastsearch_bedrooms_min', 2) }},
                {{ Cookie::get('lastsearch_bedrooms_max', 4) }}
            ];
            $("#bedrooms-min-label").html(options_bedrooms[bedroom_defaults[0]]);
            $("#bedrooms-max-label").html(options_bedrooms[bedroom_defaults[1]]);
            $("#bedrooms-slider").slider({
                min: 0,
                max: 5,
                range: true,
                values: bedroom_defaults,
                slide: function(event, ui) {
                    $("#bedrooms-min-label").html(options_bedrooms[ui.values[0]]);
                    $("#bedrooms-max-label").html(options_bedrooms[ui.values[1]]);
                    $("#bedrooms-min").val(ui.values[0]);
                    $("#bedrooms-max").val(ui.values[1]);

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 0) {
                            $("#bedrooms-min-label").html(options_bedrooms[0]);
                            $("#bedrooms-max-label").html("");
                        } else if (ui.values[0] == 5) {
                            $("#bedrooms-min-label").html("");
                            $("#bedrooms-max-label").html(options_bedrooms[5]);
                        } else {
                            $("#bedrooms-min-label").html(options_bedrooms[ui.values[0]]);
                            $("#bedrooms-max-label").html("");
                        }
                    } else if (ui.values[0] == 0 && ui.values[1] == 5) {
                        $("#bedrooms-min-label").html("Any");
                        $("#bedrooms-max-label").html("");
                    }
                }
            });

            var options_bathrooms = ["1 bathroom", "2 bathrooms", "3 bathrooms", "4 bathrooms", "5+ bathrooms"];
            var bathroom_defaults = [
                {{ Cookie::get('lastsearch_bathrooms_min', 1) }},
                {{ Cookie::get('lastsearch_bathrooms_max', 4) }}
            ];
            $("#bathrooms-min-label").html(options_bathrooms[bathroom_defaults[0]-1]);
            $("#bathrooms-max-label").html(options_bathrooms[bathroom_defaults[1]-1]);
            $("#bathrooms-slider").slider({
                min: 1,
                max: 5,
                range: true,
                values: bathroom_defaults,
                slide: function(event, ui) {
                    $("#bathrooms-min-label").html(options_bathrooms[ui.values[0]-1]);
                    $("#bathrooms-max-label").html(options_bathrooms[ui.values[1]-1]);
                    $("#bathrooms-min").val(ui.values[0]);
                    $("#bathrooms-max").val(ui.values[1]);

                    if (ui.values[0] == ui.values[1]) {
                        if (ui.values[1] == 1) {
                            $("#bathrooms-min-label").html(options_bathrooms[1-1]);
                            $("#bathrooms-max-label").html("");
                        } else if (ui.values[0] == 5) {
                            $("#bathrooms-min-label").html("");
                            $("#bathrooms-max-label").html(options_bathrooms[5-1]);
                        } else {
                            $("#bathrooms-min-label").html(options_bathrooms[ui.values[0]-1]);
                            $("#bathrooms-max-label").html("");
                        }
                    } else if (ui.values[0] == 1 && ui.values[1] == 5) {
                        $("#bathrooms-min-label").html("Any");
                        $("#bathrooms-max-label").html("");
                    }
                }
            });

            var distance_default = {{ Cookie::get('lastsearch_distance', 45) }};
            var distance_max = 60;
            var measurement = " mins";
            $("#distance-label").html("<" + distance_default + measurement);
            $("#distance-slider").slider({
                min: 10,
                max: distance_max,
                step: 5,
                range: "min",
                value: distance_default,
                slide: function(event, ui) {
                    $("#distance-label").html("<" + ui.value + measurement);
                    $("#distance").val(ui.value);

                    if (ui.value == distance_max) {
                        $("#distance-label").html("Any distance");
                    }
                }
            });

            // SET DEFAULTS FOR HIDDEN INPUT FIELDS
            $("#rent-min").val(rent_defaults[0]);
            $("#rent-max").val(rent_defaults[1]);
            $("#bedrooms-min").val(bedroom_defaults[0]);
            $("#bedrooms-max").val(bedroom_defaults[1]);
            $("#bathrooms-min").val(bathroom_defaults[0]);
            $("#bathrooms-max").val(bathroom_defaults[1]);
            $("#distance").val(distance_default);

        });
    </script>