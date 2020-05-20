/**
 * upload streamer avatar by uppy lib, 2019 02 26
 * evgrezanov@gmail.com
 */

(function ($) {
  var btn = document.querySelector("#uppyModalOpener");
  userid = btn.getAttribute("data-user");
  url = "/wp-json/streamers/v1/avatar/upload/" + userid;

  const uppy = Uppy.Core({
    allowMultipleUploads: false,
    debug: false,
    autoProceed: false,
    restrictions: {
      maxFileSize: 5000000,
      maxNumberOfFiles: 1,
      minNumberOfFiles: 1,
      allowedFileTypes: ["image/*"],
    },
  })

    .use(Uppy.Dashboard, {
      id: "Dashboard",
      target: "body",
      trigger: "#uppyModalOpener",
      hideProgressAfterFinish: true,
      closeModalOnClickOutside: true,
      closeAfterFinish: true,
      proudlyDisplayPoweredByUppy: false,
    })

    .use(Uppy.Webcam, {
      target: Uppy.Dashboard,
      countdown: false,
      modes: ["picture"],
      mirror: false,
      facingMode: "user",
    })

    .use(Uppy.XHRUpload, {
      endpoint: url,
      fieldName: "user_avatar",
    });

  uppy.run();

  uppy.on("upload-success", (file, body) => {
    var thumbnail = body.body.thumbnail;
    $("#streamer_img").attr("src", thumbnail);
  });

  // rank verify
  var rankVerifyBtn = document.querySelector("#uppyModalOpenerRankVerify");
  userid = rankVerifyBtn.getAttribute("data-user");
  url = "/wp-json/streamers/v1/rank_verify/upload/" + userid;

  const uppyRank = Uppy.Core({
    allowMultipleUploads: false,
    debug: false,
    autoProceed: false,
    restrictions: {
      maxFileSize: 2000000,
      maxNumberOfFiles: 1,
      minNumberOfFiles: 1,
      allowedFileTypes: ["image/*"],
    },
  })

    .use(Uppy.Dashboard, {
      id: "Dashboard",
      target: "body",
      trigger: "#uppyModalOpenerRankVerify",
      hideProgressAfterFinish: true,
      closeModalOnClickOutside: true,
      closeAfterFinish: true,
      proudlyDisplayPoweredByUppy: false,
    })

    .use(Uppy.Webcam, {
      target: Uppy.Dashboard,
      countdown: false,
      modes: ["picture"],
      mirror: false,
      facingMode: "user",
    })

    .use(Uppy.XHRUpload, {
      endpoint: url,
      fieldName: "user_rank_verify",
    });

  uppyRank.run();

  uppyRank.on("upload-success", (file, body) => {
    var thumbnail = body.body.thumbnail;
    $("#rank_verification_img").attr("src", thumbnail);
  });
})(window.jQuery);
