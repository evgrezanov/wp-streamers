/**
 * from https://gist.github.com/uptimizt/34ce8e582e256eb2c3c3b612b23188a0
 */
var FormAjax = function (elementSelector, ep, args = []) {
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
    submitBtn.value = "Updating team data...";

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
          teamUpdate.disply_response(message);
        }
        //console.log(response);
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
        //console.log(responseBlock);
      } else if (request.status == 401) {
        message =
          '<div class="alert alert-danger" role="alert">' +
          "You should login for edit team!</div>";
        teamUpdate.disply_response(message);
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
      } else {
        message =
          '<div class="alert alert-danger" role="alert">' +
          "Something wrong please try again!</div>";
        teamUpdate.disply_response(message);
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
var config = window["endpointTeamUpdateProperties"];

var teamUpdate = {
  get_params: function () {
    var arrayResult = {};
    arrayResult["team-id"] = config["team-id"];
    arrayResult["team-author"] = config["team-author"];
    arrayResult["team-name"] = document.getElementById("team-name").value;
    arrayResult["team-type"] = document.getElementById("team-type").value;
    arrayResult["team-region"] = document.getElementById("team-region").value;
    arrayResult["team-rank-requirements"] = document.getElementById(
      "team-rank-requirements"
    ).value;
    arrayResult["team-age-requirement"] = document.getElementById(
      "team-age-requirement"
    ).value;
    arrayResult["team-description"] = document.getElementById(
      "team-description"
    ).value;
    jQuery(document).ready(function ($) {
      arrayResult["team-positions-requered"] = $(
        ".filter-option-inner-inner"
      ).value;
    });
    console.log(arrayResult);
    return arrayResult;
  },
  disply_response: function (message) {
    var responseBlock = document.querySelector("#teamUpdateResponse");
    responseBlock.innerHTML = message;
  },
};

document.addEventListener("DOMContentLoaded", function () {
  FormAjax(
    "#streamer-edit-team",
    "streamers/v1/team/update/" + config["team-id"],
    teamUpdate.get_params()
  );
});

// Multiselect
jQuery(document).ready(function ($) {
  //console.log(config["position_required"]);
  $("#team-positions-requered").selectpicker();
  $("#team-positions-requered").selectpicker(
    "val",
    config["position_required"]
  );
  $("#team-positions-requered").on("changed.bs.select", function (
    e,
    clickedIndex,
    isSelected,
    previousValue
  ) {
    var options = $("#team-positions-requered option:selected");
    var selected = [];
    $(options).each(function () {
      selected.push($(this).val());
    });
    $("#team-positions-requered-arr").val(JSON.stringify(selected));
  });
});
