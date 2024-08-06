
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @foreach ($aDetails as $row)
        <img
            src="{{$row['img']}}"
            width="{{ $row['width'] }}"
            height="{{ $row['height'] }}"
            style="position: absolute; top: {{ $row['top'] }}px; left: {{ $row['left'] }}px;"
            alt="pdf img"
        />
    @endforeach
</body>
</html>