<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Chuyển đổi các hình ảnh như JPG, PNG, ảnh, SVG và các đồ họa vector khác sang định dạng văn bản text, v.v. Trình chuyển đổi OCR kết hợp AI cho phép bạn chuyển đổi từ hình ảnh sang văn bản miễn phí.">
    <title>@yield('title-tab')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css?v=1.3') }}">
</head>
<body>

<div id="dropContainer" style="background-image: url('{{ asset('assets/image/background/background.jpg') }}')">
    <div id="afterContainer" class="hidden">
        @yield('afterContainer')
    </div>
    <div class="container" id="beforeContainer">
        <div class="table-contents pt-4 text-center">
            <div class="d-inline"><b class="fs-1 text-danger">@yield('title')</b></div>
        </div>

        @yield('content')

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
