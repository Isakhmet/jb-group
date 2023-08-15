Dropzone.autoDiscover = false;

$(document).ready(function () {
    $('.dropzone').dropzone({
        maxFiles:       20,
        addRemoveLinks: true,
        dictRemoveFile: "Удалить",
        removedfile:    function (file) {
            $.ajax({
                type:    'get',
                url:     '/remove/' + file.name,
                data: {'album': $('#album').val()},
                success: function (response) {
                    console.log(response)
                }
            })
            let _ref;

            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },
        init:           function () {
            var _this = this;

            document.querySelector("button#clear-dropzone").addEventListener("click", function () {
                _this.removeAllFiles();
            });
        }
    })
})
