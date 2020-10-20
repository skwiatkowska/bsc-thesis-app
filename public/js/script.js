function confirmDeletion() {
  confirm("Czy na pewno chcesz usunąć na stałe?");
}
function goBack() {
  window.history.back()
}

$(document).ready(function () {
  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;

  $(".next").click(function () {
    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            display: "none",
            position: "relative",
          });
          next_fs.css({ opacity: opacity });
        },
        duration: 600,
      }
    );
  });

  $(".previous").click(function () {
    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#progressbar li")
      .eq($("fieldset").index(current_fs))
      .removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            display: "none",
            position: "relative",
          });
          previous_fs.css({ opacity: opacity });
        },
        duration: 600,
      }
    );
  });

  $(".sorted-list li").sort(asc_sort).appendTo(".sorted-list");

  $(".sorted span ").sort(asc_sort).appendTo(".sorted");

  function asc_sort(a, b) {
    return $(b).text() < $(a).text() ? 1 : -1;
  }

  $(".delete").on("click", function () {
    return confirm("Czy na pewno chcesz usunąć?");
  });

  $(".noSuchInfo").hide();

  $(".listSearch").on("keyup", function () {
    $(".emptyDBInfo").hide();
    var value = $(this).val().toLowerCase();

    $(".item-list li").filter(function () {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);

      var numOfVisibleRows = $(".item-list li:visible").length;

      if (numOfVisibleRows == 0) {
        $(".noSuchInfo").show();
      }
      if (value.length == 0) {
        $(".noSuchInfo").hide();
      }
    });
  });
});
