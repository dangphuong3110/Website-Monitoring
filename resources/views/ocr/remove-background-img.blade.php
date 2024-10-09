<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Chuyển đổi các hình ảnh như JPG, PNG, ảnh, SVG và các đồ họa vector khác sang định dạng văn bản text, v.v. Trình chuyển đổi OCR kết hợp AI cho phép bạn chuyển đổi từ hình ảnh sang văn bản miễn phí.">
    <title>Remove Background from Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css?v=1.3') }}">
</head>
<body>
    <div id="dropContainer" style="background-image: url('{{ asset('assets/image/background/background.jpg') }}')">
        <div id="afterContainer" class="hidden">
            <div class="text-center mt-5 mb-3 border-bottom border-dark-subtle">
                <button class="btn btn-outline-info btn-lg rounded-pill" id="buttonInputAfter" type="button">
                    <i class="fa-solid fa-upload"></i>
                    <span class="fw-bold">Upload Image</span>
                </button>
                <div class="mt-2 mb-4 text-white">or drop a file, paste an image
                    <span class="d-inline-block border bg-white px-1 rounded ml-2 text-muted">Ctrl</span>
                    +
                    <span class="d-inline-block border bg-white px-1 rounded text-muted">V</span>
                </div>
            </div>
            <div class="container">
                <div class="upload d-flex flex-column">
                    <div class="row">
                        <div class="col-9">
                            <ul class="nav nav-tabs" style="margin-left: -8px;">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="#" id=original>Original</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white active" href="#" id="removed">Removed Background</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-end">
                            <button type="button" class="btn-close btn-white" id="btnClose"></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="display-image text-center mt-3">
                                <div class="m-4 hidden" id="loading" style="color: #fff">
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    <span role="status">Loading...</span>
                                </div>
                                <div id="result">
                                    <div id="imgOriginal" class="hidden">
                                    </div>
                                    <div id="imgRemoved">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 border-start border-info">
                            <div class="btn-download text-center mt-md-5" id="btnDownload">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="beforeContainer">
            <div class="table-contents pt-4 text-center">
                <div class="d-inline"><b class="fs-1 text-danger"></b></div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="title-container col-md-9">
                    <p class="title text-center m-0 font-monospace fw-bold fs-1">Upload an image to</p>
                    <p class="title text-center font-monospace fw-bold fs-1">remove the background</p>
                </div>
            </div>

            @if($message = Session::get('success'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if($message = Session::get('failure'))
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-7">
                    <div class="hidden text-center" id="buttonInputRps">
                        <button class="btn btn-outline-info btn-lg rounded-pill" id="buttonInputHidden" type="button">
                            <i class="fa-solid fa-upload"></i>
                            <span class="fw-bold">Upload Image</span>
                        </button>
                    </div>
                    <div class="card card-with-shadow card-rounded-max card-without-border mx-auto upload-form" style="max-width: 350px" id="card">
                        <div class="card-body text-center" id="replaceImageInput">
                            <div class="mt-5 mb-4 d-none d-md-block">
                                <i class="fa-solid fa-layer-group fa-3x icon-layer"></i>
                            </div>
                            <form action="{{ route('ocr.removeBg') }}" method="post" enctype="multipart/form-data" id="form-img-input">
                                @csrf
                                <button class="btn btn-outline-info btn-lg rounded-pill" id="buttonInput" type="button">
                                    <i class="fa-solid fa-upload"></i>
                                    <span class="fw-bold">Upload Image</span>
                                </button>
                                <input type="file" name="img-test" id="imageInput" accept=".jpg, .jpeg, .png" class="hidden">
                                <div class="mt-2 mb-4 d-none d-md-block text-white">or drop a file.</div>
                            </form>
                        </div>
                        <div class="card-footer small text-muted text-center d-none d-md-block">
                            Paste image
                            <span class="d-inline-block border bg-white px-1 rounded ml-2">Ctrl</span>
                            +
                            <span class="d-inline-block border bg-white px-1 rounded">V</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        const loadingElement = document.getElementById('loading');
        const resultElement = document.getElementById('result');
        const originalElement = document.getElementById('imgOriginal');
        const removedElement = document.getElementById('imgRemoved');
        const beforeContainer = document.getElementById('beforeContainer');
        const afterContainer = document.getElementById('afterContainer');
        const btnDownload = document.getElementById('btnDownload');

        function submitForm (form) {
            loadingElement.classList.remove('hidden');
            resultElement.classList.add('hidden');
            $.ajax({
                type: 'POST',
                data: new FormData(form),
                contentType: false,
                cache: false,
                processData: false,
                enctype: 'multipart/form-data',
                url: '{{ route('api.ocr.removeBg') }}',
                success: function(randomNumber) {
                    loadingElement.classList.add('hidden');
                    resultElement.classList.remove('hidden');

                    const imgOriginalUrl = `assets/image/removebg/file-${randomNumber}.jpg`;
                    const imgRemovedUrl = `assets/image/removebg/no-bg-${randomNumber}.png`;

                    originalElement.innerHTML = `<img src="${imgOriginalUrl}" alt="" class="img-fluid">`;
                    removedElement.innerHTML = `<img src="${imgRemovedUrl}" alt="" class="transparency-grid img-fluid">`;

                    btnDownload.innerHTML = `<a href="${imgRemovedUrl}" download="removebg.png" class="btn btn-outline-info btn-lg rounded-pill mt-md-5"><i class="fa-solid fa-download"></i> Download</a>`;
                }
            });
        }
        window.addEventListener('paste', e => {
            if (e.clipboardData.files.length > 0) {
                beforeContainer.classList.add('hidden');
                afterContainer.classList.remove('hidden');
                const form = document.getElementById('form-img-input');
                const imageInput = document.getElementById('imageInput');
                imageInput.files = e.clipboardData.files;
                submitForm(form);
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                document.getElementById('card').classList.add('hidden');
                document.getElementById('buttonInputRps').classList.remove('hidden');
            } else {
                document.getElementById('card').classList.remove('hidden');
                document.getElementById('buttonInputRps').classList.add('hidden');
            }
        });


        const dropContainer = document.getElementById('dropContainer');
        const replaceImageInput = document.getElementById('replaceImageInput');
        const buttonInput = document.getElementById('buttonInput');
        const buttonInputHidden = document.getElementById('buttonInputHidden');
        const buttonInputAfter = document.getElementById('buttonInputAfter');
        const imageInput = document.getElementById('imageInput');

        replaceImageInput.addEventListener('click', () => {
            imageInput.click();
        });

        buttonInput.addEventListener('click', () => {
            imageInput.click();
        });

        buttonInputHidden.addEventListener('click', () => {
            imageInput.click();
        });

        buttonInputAfter.addEventListener('click', () => {
            imageInput.click();
        });

        dropContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropContainer.classList.add('active');
        });

        dropContainer.addEventListener('dragleave', () => {
            dropContainer.classList.remove('active');
        });

        dropContainer.addEventListener('drop', e => {
            e.preventDefault();
            beforeContainer.classList.add('hidden');
            afterContainer.classList.remove('hidden');
            dropContainer.classList.remove('active');
            const form = document.getElementById('form-img-input');
            const imageInput = document.getElementById('imageInput');
            imageInput.files = e.dataTransfer.files;
            submitForm(form);
        });

        const original = document.getElementById('original');
        const removed = document.getElementById('removed');
        const imgOriginal = document.getElementById('imgOriginal');
        const imgRemoved = document.getElementById('imgRemoved');

        original.addEventListener('click', function () {
            original.classList.add('active');
            removed.classList.remove('active');
            imgOriginal.classList.remove('hidden');
            imgRemoved.classList.add('hidden');
        });

        removed.addEventListener('click', function () {
            original.classList.remove('active');
            removed.classList.add('active');
            imgOriginal.classList.add('hidden');
            imgRemoved.classList.remove('hidden');
        });

        const btnClose = document.getElementById('btnClose');

        btnClose.addEventListener('click', function () {
            beforeContainer.classList.remove('hidden');
            afterContainer.classList.add('hidden');
        });

        imageInput.addEventListener('change', function () {
            beforeContainer.classList.add('hidden');
            afterContainer.classList.remove('hidden');
            const form = document.getElementById('form-img-input');
            submitForm(form);
        });
    </script>
</body>
</html>

