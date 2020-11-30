var errors = {};
		$(document).ready(function() {
			$("#email").keyup(function() {
				var email = $("#email").val();
				var object = {
					"field": "email",
					"value": email
				};
				$.ajax({
					url: "readJson.php",
					method: "post",
					data: object,
					success: function(res) {
						$("#email-error").html(res);
						errors["email"] = res;
					}
				})
			});
			$("#username").keyup(function() {
				var username = $("#username").val();
				var object = {
					"field": "username",
					"value": username
				};
				$.ajax({
					url: "readJson.php",
					method: "post",
					data: object,
					success: function(res) {
						$("#username-error").html(res);
						errors["username"] = res;
					}
				})
			});
			$("#password").keyup(function() {
				var password = $("#password").val();
				var confirmPassword = $("#confirmPassword").val();
				if(password !== confirmPassword && confirmPassword !== "") {
					$("#password-mismatch-error").html("Passwords do not match");
					errors["password"] = true;
				} else {
					$("#password-mismatch-error").html("");
					errors["password"] = false;
				}
			});
			$("#confirmPassword").keyup(function() {
				var password = $("#password").val();
				var confirmPassword = $("#confirmPassword").val();
				if(password !== confirmPassword) {
					$("#password-mismatch-error").html("Passwords do not match");
					errors["password"] = true;
				} else {
					$("#password-mismatch-error").html("");
					errors["password"] = false;
				}
			});
			$("form").submit(function(event) {
				event.preventDefault();
				var email = $("#email").val();
				var username = $("#username").val();
				var password = $("#password").val();
				var object = {
					"field": "submit",
					"email": email,
					"username": username,
					"password": password
				}
				if(errors["email"] != "" || errors["username"] != "" || errors["password"] !== false) {
					window.alert("Form contains invalid credentials");
				} else {
					$.ajax({
						url: "readJson.php",
						method: "post",
						data: object,
						success: function(res) {
							if(res) {
								window.alert("Creating account success");
								window.location.href = "login.php";
							} else {
								window.alert("Creating account failure");
								window.location.href = "signUp.php";
							}
						}
					});
				}
			});
		});


(function ($) {
    "use strict"; 

    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (
            location.pathname.replace(/^\//, "") ==
                this.pathname.replace(/^\//, "") &&
            location.hostname == this.hostname
        ) {
            var target = $(this.hash);
            target = target.length
                ? target
                : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top - 70,
                    },
                    1000,
                    "easeInOutExpo"
                );
                return false;
            }
        }
    });
    // Closes responsive menu when a
    $(".js-scroll-trigger").click(function () {
        $(".navbar-collapse").collapse("hide");
    });

    // Activate scrollspy to add active class to navbar items on scroll
    $("body").scrollspy({
        target: "#mainNav",
        offset: 95,
    });

    // Collapse Navbar
    var navbarCollapse = function () {
        if ($("#mainNav").offset().top > 100) {
            $("#mainNav").addClass("navbar-shrink");
        } else {
            $("#mainNav").removeClass("navbar-shrink");
        }
    };
    // Collapse now if page is not at top
    navbarCollapse();
    // Collapse the navbar when page is scrolled
    $(window).scroll(navbarCollapse);
})(jQuery);