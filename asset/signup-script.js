// Validate
//has uppercase
window.Parsley.addValidator("uppercase", {
  requirementType: "number",
  validateString: function (value, requirement) {
    var uppercases = value.match(/[A-Z]/g) || [];
    return uppercases.length >= requirement;
  },
  messages: {
    en: "Your password must contain at least (%s) uppercase letter.",
  },
});

//has lowercase
window.Parsley.addValidator("lowercase", {
  requirementType: "number",
  validateString: function (value, requirement) {
    var lowecases = value.match(/[a-z]/g) || [];
    return lowecases.length >= requirement;
  },
  messages: {
    en: "Your password must contain at least (%s) lowercase letter.",
  },
});

//has number
window.Parsley.addValidator("number", {
  requirementType: "number",
  validateString: function (value, requirement) {
    var numbers = value.match(/[0-9]/g) || [];
    return numbers.length >= requirement;
  },
  messages: {
    en: "Your password must contain at least (%s) number.",
  },
});

(function ($) {
  //$(document).ready(function () {
  $("#streamer-signup")
    .parsley()
    .on("field:validated", function () {
      var ok = $(".parsley-error").length === 0;
      $(".bs-callout-info").toggleClass("hidden", !ok);
      $(".bs-callout-warning").toggleClass("hidden", ok);
    })
    .on("form:submit", function () {
      return false; // Don't submit form for this demo
    });
  //});
})(window.jQuery);
