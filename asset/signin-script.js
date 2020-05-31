/**
 * from https://gist.github.com/uptimizt/34ce8e582e256eb2c3c3b612b23188a0
 */
var SignInAjax = function (elementSelector, ep, args = []) {
  if (document.querySelector(elementSelector)) {
    var form = document.querySelector(elementSelector);
  } else {
    return false;
  }

  var restApiEndpoint = wpApiSettings.root + ep;

  form.addEventListener("submit", function (event) {
    event.preventDefault();

    var formData = new FormData(form);
    var submitBtnText = "";
    var message = "";
    var submitBtn = form.querySelector('input[type="submit"]');
    var request = new XMLHttpRequest();

    submitBtn.disabled = true;
    submitBtnText = submitBtn.value;
    submitBtn.value = "Log in...";

    request.open("POST", restApiEndpoint);
    request.setRequestHeader("X-WP-Nonce", wpApiSettings.nonce);
    request.send(formData);

    request.onload = function () {
      var message = "";

      if (request.status == 200) {
        // analyze HTTP status of the response

        var response = JSON.parse(request.response);

        if (
          args.successCallback &&
          typeof args.successCallback === "function"
        ) {
          args.successCallback(response);
          return;
        }

        if (response.success) {
          message =
            '<div class="alert alert-success" role="alert">' +
            response.data.message +
            "</div>";
          streamerLogin.disply_response(message);
          window.location.replace(response.data.redirect);
        }
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
      } else {
        var response = JSON.parse(request.response);
        message =
          '<div class="alert alert-danger" role="alert">' +
          "Something wrong please try again!</div>";
        details =
          '<div class="alert alert-danger" role="alert">' +
          response.data.details +
          "</div>";
        streamerLogin.disply_response(message + details);
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
      }
    };

    request.onerror = function () {
      submitBtn.disabled = false;
      submitBtn.value = submitBtnText;
    };
  });
};
var signInConfig = window["endpointStreamerSignIn"];
var streamerLogin = {
  get_params: function () {
    var arrayResult = {};
    arrayResult["user_login"] = document.getElementById(
      "streamer_user_login"
    ).value;
    arrayResult["user_password"] = document.getElementById(
      "streamer_user_pass"
    ).value;

    arrayResult["rememberme"] = document.getElementById("rememberme").value;

    return arrayResult;
  },
  disply_response: function (message) {
    var responseBlock = document.querySelector("#streamerSignInResponse");
    responseBlock.innerHTML = message;
  },
};

document.addEventListener("DOMContentLoaded", function () {
  SignInAjax(
    "#streamer_login_form",
    signInConfig["site-url"],
    streamerLogin.get_params()
  );
});
