<html lang="id">
    <head>
        <title>Compress Upload Image</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>

    <form action="{{ route('upload-file.submit') }}" method="POST" id="upload-file" enctype="multipart/form-data">
        @csrf
        <input type="file" name="images[]" accept="image/*" capture="camera" id="images" multiple="multiple">
        <br>
        <br>
        <input type="submit" value="Upload"/>
    </form>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/screw-filereader@1.4.3/index.min.js"></script>
    <script>
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

        let fileInput = document.getElementById("images")

        fileInput.onchange = async function change() {
            // set max width
            const maxWidth = 500
            // set mx height
            const maxHeight = 500
            // set quality
            const quality = 0.5
            const result = []

            for (const file of this.files) {
                const canvas = document.createElement('canvas')
                const ctx = canvas.getContext('2d')
                const img = await file.image()

                // calculate new size
                const ratio = Math.min(maxWidth / img.width, maxHeight / img.height)
                const width = img.width * ratio + .5 | 0
                const height = img.height * ratio + .5 | 0

                // resize the canvas to the new dimensions
                canvas.width = width
                canvas.height = height

                // scale & draw the image onto the canvas
                ctx.drawImage(img, 0, 0, width, height)

                // just to preview
                // document.body.appendChild(canvas)

                // Get the binary (aka blob)
                const blob = await new Promise(rs => canvas.toBlob(rs, 'image/jpeg', quality))
                const resizedFile = new File([blob], file.name, file)
                result.push(resizedFile)
            }

            const fileList = new FileListItem(result)
            fileInput.onchange = null
            fileInput.files = fileList
            fileInput.onchange = change
        }

        // Used for creating a new FileList in a round-about way
        function FileListItem(a) {
            a = [].slice.call(Array.isArray(a) ? a : arguments)
            for (var c, b = c = a.length, d = !0; b-- && d;) d = a[b] instanceof File
            if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
            for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(a[c])
            return b.files
        }
    </script>
    </body>
</html>
