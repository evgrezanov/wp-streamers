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
            teamFinder.clear_inputs();
            //teamFinder.add_new_row(args);
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

    clear_inputs: function () {
      (function ($) {
        $("select").each(function () {
          this.selectedIndex = 0;
        });
        var dt = $("team-finder").DataTable();
        dt.search("");
      })(window.jQuery);
      document.getElementById("team-name").value = "";
    },

    /*add_new_row: function (data) {
      // https://stackoverflow.com/questions/29563160/add-a-new-row-to-the-top-of-a-jquery-datatable
      // https://stackoverflow.com/questions/4731622/insert-a-new-row-into-datatable
      //var teamFinderTable = document.getElementById("#team-finder");
      TeamNewRow = dt.NewRow();
      (TeamNewRow[0] =
        '<img class="team_finder_team_logo" style="max-width:50px;" src="http://valtzone.local/wp-content/plugins/wp-streamers/img/no_avatar.png">'),
        (TeamNewRow[1] = data["team-name"]),
        (TeamNewRow[2] = data["team-type"]),
        (TeamNewRow[3] = data["team-region"]),
        (TeamNewRow[4] = data["team-rank"]),
        (TeamNewRow[5] = data["team-age"]),
        (TeamNewRow[6] = data["team-agent"]),
        (TeamNewRow[7] = ""),
        (TeamNewRow[8] = '<span class="badge badge-secondary">draft</span>'),
        (TeamNewRow[9] =
          '<button type="button" class="btn btn-danger btn-sm">Send invite</button><a type="button" class="btn btn-info btn-sm" href="http://valtzone.local/teams/team2/">More info</a>'),
        td.TeamNewRow.InsertAt(TeamNewRow, 0);
    },*/
  };

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

    // clear filter button
    $("#clear-filter-team-finder").click(function () {
      table.search("").draw();
      $("select").each(function () {
        this.selectedIndex = 0;
      });
      $("#team-name").val("");
    });
  });
})(window.jQuery);
