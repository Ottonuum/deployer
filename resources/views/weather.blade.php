<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .weather-card {
            background-color: #f5f5f5;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .temperature {
            font-size: 2em;
            font-weight: bold;
        }
        .description {
            text-transform: capitalize;
            margin-top: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        input {
            padding: 10px;
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Weather App</h1>
    @isset($weather)
        <div class="weather-card">
            <h2>Weather in {{ $weather['name'] }}</h2>
            <div class="temperature">{{ $weather['main']['temp'] }}°C</div>
            <div class="description">{{ $weather['weather'][0]['description'] }}</div>
            <p>Humidity: {{ $weather['main']['humidity'] }}%</p>
            <p>Wind: {{ $weather['wind']['speed'] }} m/s</p>
        </div>
    @endisset
</body>
</html> 