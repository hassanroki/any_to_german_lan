<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>English to German Translator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4 text-primary">📝 Translate English to German</h1>

    <div class="card mb-5">
        <div class="card-body">
            <form id="translateForm" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="text" class="form-label">Enter English Text:</label>
                    <textarea name="text" id="text" rows="5" class="form-control" placeholder="Write something in English..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="chassi" class="form-label">Or Upload a PDF/DOCX File:</label>
                    <input type="file" name="chassi" id="chassi" class="form-control" accept=".pdf,.docx">
                </div>
                <button type="submit" class="btn btn-primary">Translate & Generate German PDF</button>
            </form>
        </div>
    </div>

    <h2 class="mb-3">📚 All Translations</h2>
    <div id="translationsList">
        @include('partials.translations', ['translations' => $translations])
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
document.getElementById("translateForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    axios.post("{{ route('translate') }}", formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    })
    .then(res => {
        Toastify({ text: res.data.message, duration: 3000, gravity: "top", position: "right", backgroundColor: "green" }).showToast();
        document.getElementById("translationsList").innerHTML = res.data.html;
        document.getElementById("translateForm").reset();
    })
    .catch(err => {
        Toastify({ text: err.response?.data?.message || "Something went wrong", duration: 3000, gravity: "top", position: "right", backgroundColor: "red" }).showToast();
    });
});
</script>
</body>
</html>
