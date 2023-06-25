<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vremea</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .gmap_iframe {
            width: 600px !important;
            height: 400px !important;
        }

        .pattern {
            background-image: url('data:image/svg+xml,%3Csvg width="52" height="26" viewBox="0 0 52 26" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.4"%3E%3Cpath d="M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z" /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 70%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }
    </style>
</head>



<body class="pattern flex flex-col items-center justify-center min-h-screen text-gray-800"
    style="background-color: #121022">

    <div class="container bg-white w-10/12 mx-auto rounded-lg shadow-xl py-10 grid grid-cols-2">
        <div class="max-w-lg mx-auto bg-white rounded-lg">
            <div class="p-6">
                <form action="/" method="GET" class="mb-6 flex">
                    <input type="text" name="city" placeholder="Search by city"
                        class="border border-gray-300 rounded-l-md px-4 py-2 focus:outline-none focus:border-blue-500 transition duration-300 ease-in-out w-full">
                    <button type="submit" id="myButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white rounded-r-md px-6 py-2 focus:outline-none transition duration-300 ease-in-out">
                        Search
                    </button>
                </form>
                <h1 class="text-3xl font-bold">Weather In {{ $city }}</h1>
                <div class="text-lg font-semibold" style="text-transform: capitalize;">
                    {{ $today['weather'][0]['description'] }}
                </div>

                <div class="">
                    <div>
                        <div class="text-6xl font-bold">{{ $temperature }}°C</div>
                    </div>
                </div>

                <hr class="border border-gray-800 my-3">

                <h1 cla>This Week</h1>
                <div class="grid grid-cols-3">
                    @php
                        $sortedWeatherData = collect($weatherData['list'])
                            ->sortByDesc('dt')
                            ->toArray();
                        $numDays = count($sortedWeatherData);
                        $currentDate = strtotime(date('Y-m-d'));
                    @endphp

                    @for ($i = 0; $i < $numDays; $i++)
                        @php
                            $w = $sortedWeatherData[$i];
                            $celsiusTemp = $w['main']['temp'] - 273.15;
                            $formattedTemperature = number_format($celsiusTemp, 1);
                            $description = $w['weather'][0]['description'];
                            $icon = $w['weather'][0]['icon'];
                            $timestamp = $w['dt'];
                            $date = date('Y-m-d', $timestamp);
                            $label = '';
                            
                            if ($date == date('Y-m-d', $currentDate)) {
                                $label = 'Tomorrow';
                            } elseif ($i < $numDays) {
                                $daysAhead = $i + 1;
                                $label = date('l', strtotime("+$daysAhead day", $currentDate));
                            }
                        @endphp

                        <div>
                            <div class="flex items-center">
                                <img src="https://openweathermap.org/img/wn/{{ $icon }}.png" alt="Imagine vreme"
                                    class="">
                                <div>
                                    <div class="text-lg font-bold">{{ $formattedTemperature }}°C</div>
                                    <div class="text-sm text-gray-500">{{ $label }}</div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="map-container animate-fadeIn">
            <div class="gmap_canvas">
                <div id="loadingScreen" class="vertical-center">
                    <div class="block">
                        <p class="text-center">
                            {{-- <i class="fa-solid fa-spinner fa-spin text-gray-300" style="font-size: 40px"></i> --}}
                            <img width="100" src="/images/cloudy.gif">
                        </p>
                        <p class="text-center font-light">Map is Loading</p>
                    </div>
                </div>


                    {{-- <iframe id="mapIframe" class="gmap_iframe rounded-xl" style="filter: grayscale(100%) invert(92%) contrast(83%);" frameborder="0"
                        scrolling="no" marginheight="0" marginwidth="0"
                        src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q={{ $city }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
                         --}}
                         <iframe class="rounded-xl" id="mapIframe" width="600" height="450" src="https://embed.windy.com/embed2.html?lat={{ $todayDet['city']['coord']['lat'] }}&lon={{ $todayDet['city']['coord']['lon'] }}&detailLat={{ $todayDet['city']['coord']['lat'] }}&detailLon={{ $todayDet['city']['coord']['lon'] }}&width=600&height=450&zoom=11&location=coordinates&radarRange=-1" frameborder="0"></iframe>
            </div>
        </div>

    </div>

    <script>
        var button = document.getElementById('myButton');
        button.addEventListener('click', function() {
            button.innerHTML = '<p> <i class="fa-solid fa-spinner fa-spin"></i> </p>';
        });
        document.addEventListener("DOMContentLoaded", function() {
            var loadingScreen = document.getElementById("loadingScreen");
            var mapIframe = document.getElementById("mapIframe");

            mapIframe.addEventListener("load", function() {
                loadingScreen.style.display = "none";
            });
        });
    </script>







    {{-- <div
        class="container mx-auto shadow-xl bg-gray-900 w-10/12 rounded-lg shadow-lg p-8 grid grid-cols-1 md:grid-cols-2 gap-8 animate-fadeIn">
        <div class="flex flex-col items-center">
            <img class="mb-6" src="https://openweathermap.org/img/wn/{{ $icon }}.png" alt="Imagine vreme">
            <h1 class="text-2xl font-bold mb-4">Weather in {{ $city }}</h1>
            <form action="/" method="GET" class="mb-6 flex animate-fadeIn">
                <input type="text" name="city" placeholder="Search by city"
                    class="border border-gray-700 bg-gray-800 text-white px-4 py-2 rounded-l-md focus:outline-none focus:border-blue-500 transition duration-300 ease-in-out w-64">
                    <button type="submit" id="myButton" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-r-md transition duration-300 ease-in-out flex items-center focus:outline-none">
                        <p>Search</p>
                    </button>


            </form>
            <div>
                <p>Caca {{ $isPost }}</p>
            </div>
            <div class="text-6xl font-bold mb-6">
                {{ $temperature }}°C
            </div>
            <div class="text-lg font-bold">
                {{ $description }}
            </div>
        </div>

        <div class="map-container animate-fadeIn">
            <div class="gmap_canvas">
                <iframe class="gmap_iframe" style="filter: invert(100%)" frameborder="0" scrolling="no" marginheight="0"
                    marginwidth="0"
                    src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q={{ $city }}&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
            </div>
        </div>
    </div>


    {{-- <div>
        @foreach ($weatherData['list'] as $w)

        @php
            $celsiusTemp = $w['main']['temp'] - 273.15;
            $formattedTemperature = number_format($celsiusTemp, 1);
        @endphp

        {{ $w['weather'][0]['description'] }} |
        {{ $formattedTemperature }}
        {{ $w['weather'][0]['icon']; }}

        @endforeach
    </div> --}}

</body>

</html>
