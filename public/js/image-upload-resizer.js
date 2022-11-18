(function( $ ) {

    $.fn.imageUploadResizer = function(options) {
        let settings = $.extend({
            max_width: 1000,
            max_height: 1000,
            quality: 1,
            do_not_resize: [],
        }, options );

        this.filter('input[type="file"]').each(function () {
            this.onchange = function() {
                const that = this; // input node
                const originalFile = this.files[0];

                if (!originalFile || !originalFile.type.startsWith('image')) {
                    return;
                }

                // Don't resize if doNotResize is set
                if (settings.do_not_resize.includes('*') || settings.do_not_resize.includes( originalFile.type.split('/')[1])) {
                    return;
                }

                let reader = new FileReader();

                reader.onload = function (e) {
                    let img = document.createElement('img');
                    let canvas = document.createElement('canvas');

                    img.src = e.target.result
                    img.onload = function () {
                        let ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);

                        if (img.width < settings.max_width && img.height < settings.max_height) {
                            // Resize not required
                            return;
                        }

                        const ratio = Math.min(settings.max_width / img.width, settings.max_height / img.height);
                        const width = Math.round(img.width * ratio);
                        const height = Math.round(img.height * ratio);

                        canvas.width = width;
                        canvas.height = height;

                        let ctx2 = canvas.getContext('2d');
                        ctx2.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(function (blob) {
                            let resizedFile = new File([blob], 'resized_'+originalFile.name, originalFile);

                            let dataTransfer = new DataTransfer();
                            dataTransfer.items.add(resizedFile);

                            // temporary remove event listener, change and restore
                            let currentOnChange = that.onchange;

                            that.onchange = null;
                            that.files = dataTransfer.files;
                            that.onchange = currentOnChange;

                        }, 'image/jpeg', settings.quality);
                    }
                }

                reader.readAsDataURL(originalFile);
            }
        });

        return this;
    };

}(jQuery));
