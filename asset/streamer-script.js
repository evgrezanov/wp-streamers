/**
 * from https://gist.github.com/uptimizt/34ce8e582e256eb2c3c3b612b23188a0
 */
var StreamerUpdateSettingsAjax = function (elementSelector, ep, args = []) {
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
    submitBtn.value = "Update...";

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
          streamerUpdateSettings.disply_response(message);
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
        streamerUpdateSettings.disply_response(message + details);
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

var streamerSettingsConfig = window["endpointStreamerUpdateSettings"];
var streamerUpdateSettings = {
  get_params: function () {
    var arrayResult = {};
    arrayResult["user-id"] = streamerSettingsConfig["user-id"];
    arrayResult["first_name"] = document.getElementById("first_name").value;
    arrayResult["last_name"] = document.getElementById("last_name").value;
    arrayResult["user_login"] = document.getElementById("user_login").value;
    arrayResult["user_email"] = document.getElementById("user_email").value;
    arrayResult["description"] = document.getElementById("description").value;

    arrayResult["passw1"] = document.getElementById("passw1").value;
    arrayResult["passw2"] = document.getElementById("passw2").value;

    arrayResult["user_birthday_dd"] = document.getElementById(
      "user_birthday_dd"
    ).value;
    arrayResult["user_birthday_mm"] = document.getElementById(
      "user_birthday_mm"
    ).value;
    arrayResult["user_birthday_yy"] = document.getElementById(
      "user_birthday_yy"
    ).value;

    arrayResult["streamer-ign"] = document.getElementById("streamer-ign").value;
    arrayResult["streamer-ign-number"] = document.getElementById(
      "streamer-ign-number"
    ).value;
    arrayResult["streamer-preferred-agent-arr"] = document.getElementById(
      "streamer-preferred-agent-arr"
    ).value;

    var streamerValorantServerSelect = document.getElementById(
      "streamer_valorant_server"
    );
    arrayResult["streamer_valorant_server"] =
      streamerValorantServerSelect.options[
        streamerValorantServerSelect.selectedIndex
      ].value;

    var streamerRankRequirementsSelect = document.getElementById(
      "streamer-rank"
    );
    arrayResult["streamer-rank"] =
      streamerRankRequirementsSelect.options[
        streamerRankRequirementsSelect.selectedIndex
      ].value;

    arrayResult["streamer-availability"] = document.getElementById(
      "streamer-availability"
    ).value;

    return arrayResult;
  },
  disply_response: function (message) {
    var responseBlock = document.querySelector("#streamerSettingsResponse");
    responseBlock.innerHTML = message;
  },
};

document.addEventListener("DOMContentLoaded", function () {
  StreamerUpdateSettingsAjax(
    "#streamer-edit-profile",
    "streamers/v1/streamer/update/" + streamerSettingsConfig["user-id"],
    streamerUpdateSettings.get_params()
  );
});

(function ($) {
  $(document).ready(function () {
    // Multiselect
    $("#streamer-preferred-agent").selectpicker();
    $("#streamer-preferred-agent").selectpicker(
      "val",
      streamerSettingsConfig["position_required"]
    );
    $("#streamer-preferred-agent").selectpicker("refresh");

    $("#streamer-preferred-agent").on("changed.bs.select", function (
      e,
      clickedIndex,
      isSelected,
      previousValue
    ) {
      var options = $("#streamer-preferred-agent option:selected");
      var selected = [];
      $(options).each(function () {
        selected.push($(this).val());
      });
      $("#streamer-preferred-agent-arr").val(JSON.stringify(selected));
    });

    // validation IGN number
    $("#streamer-ign-number").keydown(function (e) {
      // Allow: backspace, delete, tab, escape, enter and .
      if (
        $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: home, end, left, right, down, up
        (e.keyCode >= 35 && e.keyCode <= 40)
      ) {
        // let it happen, don't do anything
        return;
      }
      // Ensure that it is a number and stop the keypress
      if (
        (e.shiftKey || e.keyCode < 48 || e.keyCode > 57) &&
        (e.keyCode < 96 || e.keyCode > 105)
      ) {
        e.preventDefault();
      }
    });
  });
})(window.jQuery);
