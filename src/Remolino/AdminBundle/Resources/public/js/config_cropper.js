$(function () {
    if ($('#production_services_name_EN')[0]) {
        $('.cropper-local button.img').click(function () {
            $.fn.cropper.setDefaults({
                aspectRatio: 16 / 9,
                zoomable: true,
                cropBoxResizable: true,
                movable: false,
                dragCrop: true,
                dragMode: "none",
                ready: function (event) {
                    var name64 = $(".cropper-preview").children("img").attr("src");
                    var tipo = base64Extension(name64);
                    if (tipo == "gif") {
                        $(this).addClass("imgOpen");
                        $(this).cropper("clear");
                        $(this).cropper("disable");
                    }
                    $(this).cropper('setData', {
                        width: 1280,
                        height: 720
                    });
                },
            });
        });

        $('.cropper-local button.imgFull').click(function () {
            $.fn.cropper.setDefaults({
                aspectRatio: 16 / 9,
                zoomable: true,
                cropBoxResizable: true,
                movable: false,
                dragCrop: true,
                dragMode: "none",
                ready: function (event) {
                    var name64 = $(this).attr("src");
                    var tipo = base64Extension(name64);

                    if (tipo == "gif") {
                        $(this).addClass("imgFullOpen");
                        $(this).cropper("clear");
                        $(this).cropper("disable");
                    }
                    $(this).cropper('setData', {
                        width: 1280,
                        height: 720
                    });
                },
            });
        });

        $(".cropper .btn.btn-primary").click(function () {
            var name64 = $(this).parent().parent().children(".modal-body").children().children().children().find(".cropper-hidden").attr("src");

            var tipo = base64Extension(name64);
            if (tipo == "gif") {
                if ($(".imgFullOpen")[0]) {
                    setTimeout(function () {
                        $("#production_services_fullImageFile_base64").val(name64);
                        $("#production_services_fullImageFile_base64").parent().children(".cropper-canvas-container").html("");
                        $("#production_services_fullImageFile_base64").parent().children(".cropper-canvas-container").html("<img src='" + name64 + "' height=400/> ");
                    }, 1000);
                } else {
                    setTimeout(function () {
                        $("#production_services_imageFile_base64").val(name64);
                        $("#production_services_imageFile_base64").parent().children(".cropper-canvas-container").html("");
                        $("#production_services_imageFile_base64").parent().children(".cropper-canvas-container").html("<img src='" + name64 + "' height=400/> ");
                    }, 1000);
                }
            }
        });
    }
    if ($('#team_members_name_EN')[0]) {
        $('.cropper-local button.img').click(function () {
            $.fn.cropper.setDefaults({
                aspectRatio: 1,
                zoomable: true,
                cropBoxResizable: true,
                movable: false,
                dragCrop: true,
                dragMode: "none",
                ready: function (event) {
                    var name64 = $(".cropper-preview").children("img").attr("src");
                    var tipo = base64Extension(name64);
                    if (tipo == "gif") {
                        $(this).addClass("imgOpen");
                        $(this).cropper("clear");
                        $(this).cropper("disable");
                    }
                    $(this).cropper('setData', {
                        width: 767,
                        height: 767
                    });
                },
            });
        });

        $('.cropper-local button.imgFull').click(function () {
            $.fn.cropper.setDefaults({
                aspectRatio: 16 / 9,
                zoomable: true,
                cropBoxResizable: true,
                movable: false,
                dragCrop: true,
                dragMode: "none",
                ready: function (event) {
                    var name64 = $(this).attr("src");
                    var tipo = base64Extension(name64);

                    if (tipo == "gif") {
                        $(this).addClass("imgFullOpen");
                        $(this).cropper("clear");
                        $(this).cropper("disable");
                    }
                    $(this).cropper('setData', {
                        width: 1280,
                        height: 720
                    });
                },
            });
        });

        $(".cropper .btn.btn-primary").click(function () {
            var name64 = $(this).parent().parent().children(".modal-body").children().children().children().find(".cropper-hidden").attr("src");

            var tipo = base64Extension(name64);
            if (tipo == "gif") {
                if ($(".imgFullOpen")[0]) {
                    setTimeout(function () {
                        $("#team_members_fullImageFile_base64").val(name64);
                        $("#team_members_fullImageFile_base64").parent().children(".cropper-canvas-container").html("");
                        $("#team_members_fullImageFile_base64").parent().children(".cropper-canvas-container").html("<img src='" + name64 + "' height=400/> ");
                    }, 1000);
                } else {
                    setTimeout(function () {
                        $("#team_members_imageFile_base64").val(name64);
                        $("#team_members_imageFile_base64").parent().children(".cropper-canvas-container").html("");
                        $("#team_members_imageFile_base64").parent().children(".cropper-canvas-container").html("<img src='" + name64 + "' height=400/> ");
                    }, 1000);
                }
            }
        });
    }
});
function base64Extension(name64) {
    var type = name64.split(';')[0].split('/')[1];
    return type;
}