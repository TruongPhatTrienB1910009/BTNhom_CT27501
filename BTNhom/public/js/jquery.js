$(document).ready(function(){
    $("#signupForm").validate({
        rules: {
            name: {required: true, minlength: 2},
            password: {required: true, minlength: 5},
            cpassword: {required: true, minlength: 5, equalTo: "#password"},
            email: {required: true, email: true},
            agree: "required"
        },
        messages: {
            name: {
                required: "Bạn chưa nhập vào tên đăng nhập",
                minlength: "Tên đăng nhập phải có ít nhất 2 ký tự"
            },
            password: {
                required: "Bạn chưa nhập mật khẩu",
                minlength: "Mật khẩu phải có ít nhất 5 ký tự"
            },
            cpassword: {
                required: "Bạn chưa nhập mật khẩu",
                minlength: "Mật khẩu phải có ít nhất 5 ký tự",
                equalTo: "Mật khẩu không trùng khớp với mật khẩu đã nhập"
            },
            email: "Hộp thư điện tử không hợp lệ",
            agree: "Bạn phải đồng ý với các quy định của chúng tôi"
        },
        
        errorElement: "div",
        errorPlacement: function (error, element){
            error.addClass("invalid-feedback");
            if(element.prop("type") === "checkbox"){
                error.insertAfter(element.siblings("label"));
            }else{
                error.insertAfter(element);
            }
        },
        highlight: function(element, errorClass, validClass){
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass){
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });
});