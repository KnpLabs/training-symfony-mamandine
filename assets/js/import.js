const progressbar = document.getElementById("progressbar-import");
let isInProgress = false;

function importUsersAjax() {
  isInProgress = true;
  computeProgressbarPercentage();
  $.ajax({
    type: "GET",
    url: "/import/users",
    async: true,
    cache: false,
    dataType: 'html',
    success: function (data) { },
    error: function () { },
    complete: function () {
      isInProgress = false;
      progressbar.style.width = "100%";
    }
  });
}

function computeProgressbarPercentage() {
  if (isInProgress != false) {
    $.ajax({
      type: "GET",
      url: "/compute-progressbar-percentage",
      async: true,
      cache: false,
      dataType: 'html',
      success: function (data) {
        progressbar.style.width = data + "%";
      },
      error: function () { },
      complete: function () {
        setTimeout(() => {
          computeProgressbarPercentage();
        }, 1000);
      }
    });
  } else { }
}

window.importUsersAjax = importUsersAjax;
