document.addEventListener("DOMContentLoaded", function () {
  var config = window["endpointTeamFinder"];

  /**
   * from https://gist.github.com/uptimizt/34ce8e582e256eb2c3c3b612b23188a0
   */
  var TeamFinderFormAjax = function (elementSelector, ep, args = []) {
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
            teamFinder.disply_response(message);
          }
          //console.log(response);
          submitBtn.disabled = false;
          submitBtn.value = submitBtnText;
          //console.log(responseBlock);
        } else if (request.status == 401) {
          message =
            '<div class="alert alert-danger" role="alert">' +
            "You should login for edit team!</div>";
          teamFinder.disply_response(message);
          submitBtn.disabled = false;
          submitBtn.value = submitBtnText;
        } else {
          message =
            '<div class="alert alert-danger" role="alert">' +
            "Something wrong please try again!</div>";
          teamFinder.disply_response(message);
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

  var teamFinder = {
    get_params: function () {
      var arrayResult = {};
      arrayResult["user-id"] = document.getElementById("user-id").value;
      arrayResult["team-name"] = document.getElementById("team-name").value;

      var teamTypeSelect = document.getElementById("team-type");
      arrayResult["team-type"] =
        teamTypeSelect.options[teamTypeSelect.selectedIndex].value;

      var teamRegionSelect = document.getElementById("team-region");
      arrayResult["team-region"] =
        teamRegionSelect.options[teamRegionSelect.selectedIndex].value;

      var teamRankRequirementsSelect = document.getElementById("team-rank");
      arrayResult["team-rank"] =
        teamRankRequirementsSelect.options[
          teamRankRequirementsSelect.selectedIndex
        ].value;

      var teamAgerRequirementSelect = document.getElementById(
        "team-age-requirement"
      );
      arrayResult["team-age"] =
        teamAgerRequirementSelect.options[
          teamAgerRequirementSelect.selectedIndex
        ].value;

      var teamAgentSelect = document.getElementById("team-agent");
      arrayResult["team-agent"] =
        teamAgentSelect.options[teamAgentSelect.selectedIndex].value;

      return arrayResult;
    },
    disply_response: function (message) {
      var responseBlock = document.querySelector("#teamFinderAddResponse");
      responseBlock.innerHTML = message;
    },
  };

  console.log(teamFinder.get_params());
  TeamFinderFormAjax(
    "#team-finder-add-new",
    "streamers/v1/team/quick_add_new/" + config["user-id"],
    teamFinder.get_params()
  );
});

(function ($) {
  // DataTable
  $(document).ready(function ($) {
    var table = $("#team-finder").DataTable({
      responsive: true,
      sDom: '<"top"i>r<"bottom"tflp><"clear">',
    });
    // Team type filter
    $("#team-type").on("change", function () {
      selectedTeamType = $("#team-type option:selected").html();
      //console.log($("#team-type option:selected").html());
      table.search($("#team-type option:selected").html()).draw();
    });
    // team-region filter
    $("#team-region").on("change", function () {
      selectedTeamRegion = $("#team-region option:selected").html();
      table.search($("#team-region option:selected").html()).draw();
    });
    // team-rank filter
    $("#team-rank").on("change", function () {
      selectedTeamRank = $("#team-rank option:selected").html();
      table.search($("#team-rank option:selected").html()).draw();
    });
    // team-age filter
    $("#team-age-requirement").on("change", function () {
      selectedTeamAgeRequirement = $(
        "#team-age-requirement option:selected"
      ).html();
      table.search($("#team-age-requirement option:selected").html()).draw();
    });
    // team-preferred-agent filter
    $("#team-preferred-agent").on("change", function () {
      selectedTeamPreferredAgent = $(
        "#team-preferred-agent option:selected"
      ).html();
      table.search($("#team-preferred-agent option:selected").html()).draw();
    });
  });
})(window.jQuery);
