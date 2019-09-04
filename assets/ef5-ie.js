/**
 * @team: the EF5 Team
 * @since: 1.0.0
 * @author: the EF5 Team
 */
(function ($) {
    function download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', text);
        element.setAttribute('download', filename);
        element.style.display = 'none';
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
    $(document).on('click', '.button-primary.create-demo', function (e) {
        e.preventDefault();
        if ($('#ef5-ie-id').val() === '') {
            $('#ef5-ie-id').focus();
        } else {
            $('.ef5-export-contents').submit();
        }
    });
    $(document).on('click', '.ef5-import-btn.ef5-import-submit', function (e) {
        e.preventDefault();
        var _form = $(this).parents('form.ef5-ie-demo-item');
        if (confirm('Are you sure you want to install this demo data?')) {
            _form.find(".ef5-loading").css('display', 'block');
            _form.submit();
        } else {
            return;
        }
    });
    $(document).on('click', '.ef5-delete-demo', function (e) {
        e.preventDefault();
        var _this = $(this);
        var _validate = prompt("Type \"reset\" in the confirmation field to confirm the reset and then click the OK button");
        if (_validate === "reset") {
            if (confirm('Are you sure you want to reset site?')) {
                _this.parents('form.ef5-ie-demo-item').find('input[name="action"]').val('ef5-reset');
                _this.parents('form.ef5-ie-demo-item').submit();
            } else {
                return;
            }
        } else {
            if (_validate !== null) {
                alert('Invalid confirmation. Please type \'reset\' in the confirmation field.');
            } else {
                return;
            }
        }
    });
    $(document).on('click', 'li.ef5-advance-reset', function (e) {
        e.preventDefault();
        var _form = $(document).find('form.ef5-reset-form-advance');
        var _validate = prompt("Type \"reset\" in the confirmation field to confirm the reset and then click the OK button");
        if (_validate === "reset") {
            if (confirm('Are you sure you want to reset site?')) {
                _form.submit();
            } else {
                return false;
            }
        } else {
            if (_validate !== null) {
                alert('Invalid confirmation. Please type \'reset\' in the confirmation field.');
            } else {
                return false;
            }
        }
    });
    $(document).on('click', 'li.ef5-show-regenerate-thumbnail', function (e) {
        e.preventDefault();
        var _form = $(document).find('form.ef5-regenerate-thumbnail-sm');
        if (confirm('Are you sure you want to Regenerate Thumbnail?')) {
            _form.submit();
        } else {
            return false;
        }
    });
    $(document).on('click', '.ef5-show-manual-import', function (e) {
        e.preventDefault();
        $(document).find(".ef5-manual-import-layout").css('display','block');
        setTimeout(function () {
            $(document).find(".tabs-contents.ef5-mi-demo-list").addClass("active");
            $(document).find(".ef5-manual-import-layout").removeClass("ef5-m-hidden");
        },10);
    });
    $(document).on('click', '.ef5-contain .dashicons.dashicons-dismiss', function (e) {
        e.preventDefault();
        $(document).find(".ef5-manual-import-layout").addClass("ef5-m-hidden");
        setTimeout(function () {
            $(document).find(".ef5-manual-import-layout").css('display','none');
        },600);
    });

    $(document).on('click', '.ef5-mi-select', function (e) {
        e.preventDefault();
        $(document).find(".ef5-mi-image.ef5-selected").removeClass("ef5-selected");
        $(document).find(".tabs-contents.active").removeClass("active");
        $(document).find("#attachments").addClass("active");
        $(document).find(".tabs-demos[data-id=select-demo]").addClass("ef5-mi-done");
        $(document).find(".tabs-demos[data-id=select-demo]").removeClass("ef5-mi-active");
        $(document).find(".tabs-demos[data-id=attachments]").addClass("ef5-mi-active");
        var _this = $(this),
            _img = _this.parents(".ef5-mi-image");
        _img.addClass("ef5-selected");
        $(".ef5-mi-image-selected img").attr("src",_img.find("img").attr("src"));
        $("#ef5-download-attachment-btn").attr("data-attachment",_this.attr("data-attachment"));
        $(".ef5-mi-demo-title-selected").html(_img.find(".ef5-mi-demo-title").html());
        $("input[name=ef5-ie-id]").val(_this.attr("data-demo"));
        setTimeout(function () {
            $(document).find("#select-demo").css('display','none');
        },300);
    });
    $(document).on('click', '.tabs-demos.ef5-mi-done', function (e) {
        e.preventDefault();
        var _this = $(this);
        $(document).find(".tabs-demos").removeClass("ef5-mi-done");
        $(document).find(".tabs-demos").removeClass("ef5-mi-active");
        var _data_id = _this.attr("data-id");
        switch (_data_id) {
            case "attachments":
                $(document).find(".tabs-demos").removeClass("ef5-mi-done");
                $(document).find(".tabs-demos").removeClass("ef5-mi-active");
                $(document).find(".tabs-contents.active").removeClass("active");
                $(document).find("#attachments").addClass("active");
                $(document).find(".tabs-demos[data-id=select-demo]").addClass("ef5-mi-done");
                $(document).find(".tabs-demos[data-id=select-demo]").removeClass("ef5-mi-active");
                $(document).find(".tabs-demos[data-id=attachments]").addClass("ef5-mi-active");
                break;
            case "select-demo":
                $(document).find("#select-demo").css('display','block');
                $(document).find(".tabs-demos").removeClass("ef5-mi-done");
                $(document).find(".tabs-demos").removeClass("ef5-mi-active");
                setTimeout(function () {
                    $(document).find(".tabs-contents.active").removeClass("active");
                    $(document).find("#select-demo").addClass("active");
                    $(document).find(".tabs-demos[data-id=attachments]").removeClass("ef5-mi-active");
                    $(document).find(".tabs-demos[data-id=select-demo]").addClass("ef5-mi-active");
                },10);
                break;
            default:
                break;
        }
    });
    $(document).on('click','#ef5-download-attachment-btn',function (e) {
        e.preventDefault();
        var _this = $(this);
        download("ef5-attachments.zip",_this.attr("data-attachment"));
    });
    $(document).on('change','#ef5-accept-unzip-done',function (e) {
        e.preventDefault();
        var _checked = $("input#ef5-accept-unzip-done:checked").length;
        if(_checked === 1){
            $(document).find(".ef5-mi-dl-step.step-4 button").addClass("active");
        }else{
            $(document).find(".ef5-mi-dl-step.step-4 button").removeClass("active");
        }
    });
    $(document).on('click','.ef5-mi-dl-step.step-4 button.active',function (e) {
        e.preventDefault();
        var _this = $(this);
        var _checked = $("input#ef5-accept-unzip-done:checked").length;
        if(_checked === 1){
            if (confirm('Are you sure you want to install this demo data?')) {
                _this.next().submit();
            } else {
                return false;
            }
        }else{
            alert("Please accept \"I uploaded and unzipped file\"");
        }
    });
})(jQuery);
