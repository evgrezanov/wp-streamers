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
    submitBtn.value = "Updating...";

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
          teamFinder.add_team_row(response.data);
        }
        submitBtn.disabled = false;
        submitBtn.value = submitBtnText;
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
          "Something wrong please try again!<p>" +
          response.details +
          "</p></div>";
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

var config = window["endpointTeamFinder"];

var teamFinder = {
  get_params: function () {
    var arrayResult = {};
    arrayResult["user-id"] = config["user-id"].value;
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
  add_team_row: function (data) {
    //(function ($) {
    jQuery.noConflict();
    var mytable = jQuery("#team-finder").DataTable();
    mytable.search("").draw();
    mytable.row
      .add([
        data.team_id,
        '<img class="team_finder_team_logo" style="max-width:50px;" src="http://valtzone.local/wp-content/plugins/wp-streamers/img/no_avatar.png">',
        data.team_name,
        data.team_type,
        data.team_region,
        data.team_rank,
        data.team_age,
        data.team_agents,
        data.team_date,
        data.team_status,
        data.team_button,
      ])
      .draw();
    //})(window.jQuery);
  },
  clear_inputs: function () {
    jQuery.noConflict();
    //(function ($) {
    jQuery("select").each(function () {
      this.selectedIndex = 0;
    });
    var dt = jQuery("team-finder").DataTable();
    dt.search("");
    //})(window.jQuery);
    document.getElementById("team-name").value = "";
  },
};

document.addEventListener("DOMContentLoaded", function () {
  TeamFinderFormAjax(
    "#team-finder-add-new",
    "streamers/v1/team/quick_add_new/" + config["user-id"],
    teamFinder.get_params()
  );
});
jQuery.noConflict();
//(function ($) {
// DataTable
jQuery(document).ready(function ($) {
  var table = jQuery("#team-finder").DataTable({
    responsive: true,
    sDom: '<"top"i>r<"bottom"tflp><"clear">',
    order: [[0, "desc"]],
    aoColumnDefs: [
      {
        targets: [0],
        visible: false,
        searchable: false,
      },
    ],
  });

  // Team type filter
  jQuery("#team-type").on("change", function () {
    selectedTeamType = jQuery("#team-type option:selected").html();
    table.search(jQuery("#team-type option:selected").html()).draw();
  });

  // team-region filter
  jQuery("#team-region").on("change", function () {
    selectedTeamRegion = jQuery("#team-region option:selected").html();
    table.search(jQuery("#team-region option:selected").html()).draw();
  });

  // team-rank filter
  jQuery("#team-rank").on("change", function () {
    selectedTeamRank = jQuery("#team-rank option:selected").html();
    table.search(jQuery("#team-rank option:selected").html()).draw();
  });

  // team-age filter
  jQuery("#team-age-requirement").on("change", function () {
    selectedTeamAgeRequirement = jQuery(
      "#team-age-requirement option:selected"
    ).html();
    table.search(jQuery("#team-age-requirement option:selected").html()).draw();
  });

  // team-preferred-agent filter
  jQuery("#team-preferred-agent").on("change", function () {
    selectedTeamPreferredAgent = jQuery(
      "#team-preferred-agent option:selected"
    ).html();
    table.search(jQuery("#team-preferred-agent option:selected").html()).draw();
  });

  // clear filter button
  jQuery("#clear-filter-team-finder").click(function () {
    table.search("").draw();
    jQuery("select").each(function () {
      this.selectedIndex = 0;
    });
    jQuery("#team-name").val("");
  });
});
//})(window.jQuery);
