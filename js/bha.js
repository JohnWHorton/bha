
var upcoming = [];
var fixtures = [];
var races = [];
var enties = [];

$(document).ready(function () {
 
  getUpcoming();
});
function getUpcoming() {
  rounds = [];
  var parms = { operation: "upcoming" };

  $.ajax({
    type: "POST",
    async: false,
    url: "./getData.php",
    contentType: "application/json; charset=UTF-8",
    dataType: "json",
    data: JSON.stringify(parms),
    success: function (response) {
      upcoming = response;
      console.log(upcoming);
    },
    error: function (xhr, textStatus, error) {
      console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
    },
  });
  // console.log("rounds", rounds);
}

