<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    <!DOCTYPE html>
    <html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/js/app.js')
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.9.0/maps/maps-web.min.js"></script>

    
    


</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Calcolo della Distanza</h1>
                <form id="form" method="post" action="/calculate-distance">
                    @csrf
                    <div class="form-group">
                        <label for="origin">Indirizzo di partenza</label>
                        <input type="text" class="form-control" id="origin" name="origin" placeholder="Indirizzo di partenza" value="{{ old('origin') }}">
                    </div>
                    <div class="form-group mt-3">
                        <label for="destination">Indirizzo di destinazione</label>
                        <input type="text" class="form-control" id="destination" name="destination" placeholder="Indirizzo di destinazione" value="{{ old('destination') }}">
                    </div>
                    <div class="dropdown">
                        <ul id="dropdown" class="dropdown-menu">
                            <li class="dropdown-item"></li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Calcola Distanza</button>
                </form>
    
                @if(isset($distance))
                    <br>
                    <br>
                    <h2>Risultati del Calcolo:</h2>
                    <p>Distanza tra le coordinate geografiche: {{ $distance }} metri</p>
                @endif
            </div>
        </div>
    </div>
    <script>
        tomtom.setProductInfo('YourAppName', '1.0');
        var tomtomKey = 'PWX9HGsOx1sGv84PlpxzgXIbaElOjVMF';
    
        tomtom.searchKey(tomtomKey);
    </script>
    <script>
        function enableTomTomAutocomplete(inputId) {
            tomtom.searchBox({
                element: inputId,
                useDeviceLocation: false,
            });
        }
    
        // Attiva l'autocompletamento per gli input desiderati
        enableTomTomAutocomplete('origin');
        enableTomTomAutocomplete('destination');
    </script>
    
</body>
</html>
