<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body>
    <div id="app" data-page="{{ json_encode($page) }}"></div>
    <script src="{{ mix('/js/app.js') }}" defer></script>
</body>
</html>
