<!DOCTYPE html>
<html>
<head>
    <title>Import Cities</title>
</head>
<body>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <form action="{{ route('import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Import</button>
    </form>
</body>
</html>
