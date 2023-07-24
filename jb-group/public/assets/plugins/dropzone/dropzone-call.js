 Dropzone.autoDiscover = false;

   $(document).ready(function () {
        let token = $('input[name="_token"]').val();
        $("#id_dropzone").dropzone({
            maxFiles: 2000,
            url: "/upload/",
            dictRemoveFile: "Remove",
            success: function (file, response) {
                console.log(response);
            }
        });
   })
