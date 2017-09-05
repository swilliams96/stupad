@extends('layouts.app')

@section('content')

    <section id="search">
        <div class="container">
            <div class="row">
                <!-- FILTERS -->
                <div class="col-lg-12">
                    <form class="filters" method="POST" action="{{ route('results') }}">
                        <h2>Search</h2>
                        <hr class="filters-hr">

                        {{ csrf_field() }}

                        <input type="text" id="location" name="location" placeholder="Where are you searching?"/>

                        <h3><i class="fa fa-gbp fa-pad-5 sr-icons"></i>Rent (per week)</h3>
                        <div id="rent-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="rent-min-label" class="min-label"></span>
                            <span id="rent-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="rent-min" name="rent_min" value="0">
                        <input type="hidden" id="rent-max" name="rent_max" value="0">

                        <h3><i class="fa fa-bed fa-pad-5 sr-icons"></i>Bedrooms</h3>
                        <div id="bedrooms-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="bedrooms-min-label" class="min-label"></span>
                            <span id="bedrooms-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="bedrooms-min" name="bedrooms_min" value="0">
                        <input type="hidden" id="bedrooms-max" name="bedrooms_max" value="0">

                        <h3><i class="fa fa-bath fa-1 fa-pad-5 sr-icons"></i>Bathrooms</h3>
                        <div id="bathrooms-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="bathrooms-min-label" class="min-label"></span>
                            <span id="bathrooms-max-label" class="max-label"></span>
                        </div>
                        <input type="hidden" id="bathrooms-min" name="bathrooms_min" value="0">
                        <input type="hidden" id="bathrooms-max" name="bathrooms_max" value="0">

                        <h3 style="width: 100%;">
                            <span><i class="fa fa-map-marker fa-1 fa-pad-5 sr-icons"></i>Dist. from</span>
                            <select id="place" name="place">
                                <option value="campus">Campus</option>
                                <option value="town">Town Centre</option>
                            </select>
                            <!--
                            <span style="padding-left: 4px;">by</span>
                            <select id="transport" name="transport">
                                <option value="any">Any method</option>
                                <option value="bus">Bus</option>
                                <option value="walk">Walking</option>
                                <option value="cycle">Cycling</option>
                            </select>
                            -->
                        </h3>
                        <div id="distance-slider" class="slider"></div>
                        <div class="slider-labels">
                            <span id="distance-label" class="min-label"></span>
                        </div>
                        <input type="hidden" id="distance" name="distance" value="0">

                        <button class="btn btn-primary btn-xl" type="submit">Search</button>

                        <!--
                            <h3>
                                <i class="fa fa-map-marker fa-1 fa-pad-5 sr-icons"></i>Method of transport
                                <select id="transport" name="transport">
                                    <option value="bus">Bus</option>
                                    <option value="walk">Walking</option>
                                    <option value="cycle">Cycling</option>
                                </select>
                            </h3>


                            <h3><i class="fa fa-bed fa-pad-5 sr-icons"></i>No. Bedrooms</h3>
                            <label><input type="checkbox" name="beds_0" value="beds_0">Studio</label>
                            <label><input type="checkbox" name="beds_1" value="beds_1">1 bedroom</label>
                            <label><input type="checkbox" name="beds_2" value="beds_2">2 bedrooms</label>
                            <label><input type="checkbox" name="beds_3" value="beds_3">3 bedrooms</label>
                            <label><input type="checkbox" name="beds_4" value="beds_4">4 bedrooms</label>
                            <label><input type="checkbox" name="beds_5+" value="beds_5+">5+ bedrooms</label>

                            <h3><i class="fa fa-bath fa-1 fa-pad-5 sr-icons"></i>No. Bathrooms</h3>
                            <label><input type="checkbox" name="beds_1" value="beds_1">1 bathroom</label>
                            <label><input type="checkbox" name="beds_2" value="beds_2">2 bathrooms</label>
                            <label><input type="checkbox" name="beds_3+" value="beds_3+">3+ bathrooms</label>
                        -->

                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- SLIDERS -->
    <script>
        // TODO: SET DEFAULTS TO THE SESSION DATA WHERE SESSION DATA IS SET
        $(function() {
            var rent_defaults = [100, 250];
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
            var bedroom_defaults = [2, 4];
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
            var bathroom_defaults = [1, 4];
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

            var distance_default = 45;
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

    <!-- TODO: IMPLEMENT LOCATION SUGGESTIONS -->

@endsection