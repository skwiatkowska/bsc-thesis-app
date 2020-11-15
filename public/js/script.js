function goBack() {
  window.history.back();
}

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
