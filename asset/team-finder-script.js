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
      //console.log($("#team-region option:selected").html());
      table.search($("#team-region option:selected").html()).draw();
    });
    // team-rank filter
    $("#team-rank").on("change", function () {
      selectedTeamRank = $("#team-rank option:selected").html();
      //console.log($("#team-rank option:selected").html());
      table.search($("#team-rank option:selected").html()).draw();
    });
    // team-age filter
    $("#team-age-requirement").on("change", function () {
      selectedTeamAgeRequirement = $(
        "#team-age-requirement option:selected"
      ).html();
      //console.log($("#team-age-requirement option:selected").html());
      table.search($("#team-age-requirement option:selected").html()).draw();
    });
    // team-preferred-agent filter
    $("#team-preferred-agent").on("change", function () {
      selectedTeamPreferredAgent = $(
        "#team-preferred-agent option:selected"
      ).html();
      //console.log($("#team-preferred-agent option:selected").html());
      table.search($("#team-preferred-agent option:selected").html()).draw();
    });
  });
})(window.jQuery);
