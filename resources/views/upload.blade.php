<html lang="id">
    <head>
        <title>Compress Upload Image</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>

    <form action="{{ route('upload-file.submit') }}" method="POST" id="upload-file">
        @csrf
        <input type="file" name="image" accept="image/*" capture="camera" id="image">
        <br>
        <br>
        <input type="submit" value="Upload"/>
    </form>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/image-upload-resizer.js') }}"></script>
    <script>
        $('#image').imageUploadResizer({
            max_width: 800, // Defaults 1000
            max_height: 600, // Defaults 1000
            quality: 0.5, // Defaults 1
            do_not_resize: ['gif', 'svg'], // Defaults []
        });

        $('#upload-file').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);
            let url = this.action;

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
            }).done(function (data) {
                console.log(data)

                $('#upload-file')[0].reset();
            });
        })
    </script>
    </body>
</html>
