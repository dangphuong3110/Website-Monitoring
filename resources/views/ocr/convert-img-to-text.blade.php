<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Chuyển đổi các hình ảnh như JPG, PNG, ảnh, SVG và các đồ họa vector khác sang định dạng văn bản text, v.v. Trình chuyển đổi OCR kết hợp AI cho phép bạn chuyển đổi từ hình ảnh sang văn bản miễn phí.">
    <title>Chuyển đổi hình ảnh thành văn bản</title>
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
                <div class="d-inline"><b class="fs-1 text-danger">Chuyển đổi hình ảnh thành văn bản</b></div>
            </div>
            <div class="wrapper">
                <div class="row d-flex justify-content-center">
                    <div class="title-container col-md-9">
                        <p class="title text-center m-0">Nếu bạn muốn tìm hiểu cách chuyển hình ảnh thành tài liệu văn bản thì bạn đã đến đúng nơi.</p>
                        <p class="title text-center">Công cụ trực tuyến miễn phí này cho phép bạn chuyển đổi từ hình ảnh sang văn bản.</p>
                    </div>
                </div>
                <div class="img-test">
                    <form action="{{ route('api.ocr.processImage') }}" method="post" enctype="multipart/form-data" id="form-img-input">
                        @csrf
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="overlay text-center rounded p-2 w-auto">
                                        <div class="custom-file-upload rounded w-100 h-100 d-flex flex-column justify-content-center" id="replaceImageInput">
                                            <i class="fa-solid fa-cloud-arrow-up fa-3x mb-2"></i>
                                            <label id="labelForInputImg" for="imageInput">Chọn tải lên tập tin hoặc kéo thả nó vào đây. Dán ảnh
                                                <span class="d-inline-block border bg-white px-1 rounded ml-2 text-muted">Ctrl</span>
                                                +
                                                <span class="d-inline-block border bg-white px-1 rounded text-muted">V</span></label>
                                        </div>
                                    </div>
                                    <input type="file" accept=".jpg, .jpeg, .png, .gif" name="img[]" id="imageInput" class="form-control fs-6 mt-2 hidden" multiple>
                                    <div id="selectedImagesContainer" class="mt-3" style="color: #fff"></div>
                                    <select class="form-select mt-2 mb-1" name="language" required>
                                        <option value="1" selected>Vietnamese</option>
                                        <option value="2">English</option>
                                    </select>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" class="btn btn-success mt-2" id="btnConvert">Chuyển</button>
                                        <a href="#" class="btn btn-outline-info mt-2 ms-2 hidden" id="btnDownload">Tải xuống kết quả</a>
                                    </div>
                                </div>
                                <div class="col-md-1 mb-3">
                                    <div class="d-none d-md-block dis-button"></div>
                                </div>
                                <div class="text col-md-5">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-danger fw-bold text-decoration-underline">Kết quả:</h5>
                                        <a href="#" class="copy" id="btn-copy-para">
                                            <span class="before-copy" id="before-copy-para"><i class="fa-regular fa-copy"></i> Sao chép đoạn văn</span>
                                            <span class="after-copy hidden" id="after-copy-para"><i class="fa-solid fa-check"></i> Đã sao chép</span>
                                        </a>
                                        <a href="#" class="copy" id="btn-copy-text">
                                            <span class="before-copy" id="before-copy"><i class="fa-regular fa-copy"></i> Sao chép các hàng</span>
                                            <span class="after-copy hidden" id="after-copy"><i class="fa-solid fa-check"></i> Đã sao chép</span>
                                        </a>
                                    </div>
                                    <div class="border border-dark-subtle rounded-3 mb-3" id="wrapper-result">
                                        <div class="hidden m-4" id="loading" style="color: #fff">
                                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                            <span role="status">Loading...</span>
                                        </div>
                                        <div class="ms-3 me-3" id="result" style="color: #fff">
                                            @foreach($results as $result)
                                                <p>{{ $result }}</p>
                                            @endforeach
                                        </div>
                                        <div class="hidden" id="resultCopyParagraph">
                                        </div>
                                        <div class="hidden" id="resultCopy">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        const loadingElement = document.getElementById('loading');
        const resultCopyElement = document.getElementById('resultCopy');
        const resultCopyParaElement = document.getElementById('resultCopyParagraph')
        const resultElement = document.getElementById('result');
        const btnDownload = document.getElementById('btnDownload');

        const imageInput = document.getElementById('imageInput');
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
                url: '{{ route('api.ocr.processImage') }}',
                success: function(results) {
                    console.log(results);
                    loadingElement.classList.add('hidden');
                    resultElement.classList.remove('hidden');
                    if (results.length === 1) {
                        resultElement.innerHTML = `<p class="mt-3">Vui lòng chọn ảnh trước khi chuyển đổi.</p>`;
                        btnDownload.classList.add('hidden');
                    }
                    else if (results.length === 2 && results[1] === 'Không tìm thấy văn bản trong hình ảnh.') {
                        resultElement.innerHTML = `<p class="mt-3">Không tìm thấy văn bản trong hình ảnh.</p>`;
                        btnDownload.classList.add('hidden');
                    }
                    else {
                        let resultHtml = '';
                        let resultCopy = '';
                        let resultCopyParagraph = '';
                        results.forEach(result => {
                            resultHtml += `<p>${result}</p>`;
                            resultCopy += `${result}\n`;
                            resultCopyParagraph += `${result} `;
                        });
                        resultElement.innerHTML = resultHtml;

                        resultCopyElement.textContent = resultCopy;

                        resultCopyParaElement.textContent = resultCopyParagraph;

                        const blobTextDownload = new Blob([resultCopy], { type: 'text/plain' });
                        btnDownload.href = window.URL.createObjectURL(blobTextDownload);
                        btnDownload.download = 'ket-qua.txt';
                        btnDownload.classList.remove('hidden');
                    }
                }
            });
        }

        const btnConvert = document.getElementById('btnConvert');
        btnConvert.addEventListener('click', function (e) {
            e.preventDefault();
            const form = document.getElementById('form-img-input');
            submitForm(form);
        });

        window.addEventListener('paste', e => {
            if (e.clipboardData.files.length > 0) {
                const form = document.getElementById('form-img-input');
                const imageInput = document.getElementById('imageInput');
                imageInput.files = e.clipboardData.files;
                loadingElement.classList.remove('hidden');
                resultElement.classList.add('hidden');
                submitForm(form);
            }
        });

        const btnCopyText = document.getElementById('btn-copy-text');
        btnCopyText.addEventListener('click', function () {
            const textArea = document.createElement('textarea');
            textArea.value = resultCopyElement.innerText;

            document.body.appendChild(textArea);

            textArea.select();
            navigator.clipboard.writeText(textArea.value);

            document.body.removeChild(textArea);

            const beforeCopy = document.getElementById('before-copy');
            const afterCopy = document.getElementById('after-copy');

            beforeCopy.classList.add('hidden');
            afterCopy.classList.remove('hidden');

            setTimeout(function () {
                beforeCopy.classList.remove('hidden');
                afterCopy.classList.add('hidden');
            }, 1500);
        });

        const btnCopyPara = document.getElementById('btn-copy-para');
        btnCopyPara.addEventListener('click', function () {
            const textArea = document.createElement('textarea');
            textArea.value = resultCopyParaElement.innerText;

            document.body.appendChild(textArea);

            textArea.select();
            navigator.clipboard.writeText(textArea.value);

            document.body.removeChild(textArea);

            const beforeCopy = document.getElementById('before-copy-para');
            const afterCopy = document.getElementById('after-copy-para');

            beforeCopy.classList.add('hidden');
            afterCopy.classList.remove('hidden');

            setTimeout(function () {
                beforeCopy.classList.remove('hidden');
                afterCopy.classList.add('hidden');
            }, 1500);
        });

        const dropContainer = document.getElementById('dropContainer');
        const replaceImageInput = document.getElementById('replaceImageInput');

        replaceImageInput.addEventListener('click', () => {
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
            dropContainer.classList.remove('active');
            const form = document.getElementById('form-img-input');
            const imageInput = document.getElementById('imageInput');
            imageInput.files = e.dataTransfer.files;
            submitForm(form);
        });

        function addOneMoreInput() {
            $('#imageInput').change(function() {
                const selectedImagesContainer = $('#selectedImagesContainer');
                const files = this.files;
                let totalSize = 0;
                for (let i = 0; i < files.length; i++) {
                    totalSize += files[i].size;
                }
                if (totalSize > 52428800){
                    alert("Kích cỡ ảnh quá lớn!");
                    this.value = "";
                    selectedImagesContainer.empty();
                    return;
                }
                const selectedFiles = this.files;

                selectedImagesContainer.empty();

                let text = 'Ảnh đã chọn: ';
                for (let i = 0; i < selectedFiles.length; i++) {
                    const fileName = selectedFiles[i].name;
                    if (i !== selectedFiles.length - 1)
                        text += fileName + ', ';
                    else
                        text += fileName + '.';
                }
                selectedImagesContainer.append('<p>' + text + '</p>');

                $(this).off('change');
                addOneMoreInput();
            });
        }
        addOneMoreInput();
    </script>
</body>
</html>

