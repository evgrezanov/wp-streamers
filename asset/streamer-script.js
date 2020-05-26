var config = window["endpointStreamerUpdateSettings"];

// Multiselect
jQuery(document).ready(function ($) {
  $("#streamer-preferred-agent").selectpicker();
  $("#streamer-preferred-agent").selectpicker(
    "val",
    config["position_required"]
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
});

// validation IGN number
(function ($) {
  $(document).ready(function () {
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
