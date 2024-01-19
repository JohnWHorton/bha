
var upcoming = [];
var fixtures = [];
var races = [];
var enties = [];
var tabledata = [];

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
      fixtures = upcoming["fixtures"];
      races = upcoming["races"];
      enties = upcoming["entries"];
      createtable();
    },
    error: function (xhr, textStatus, error) {
      console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
    },
  });
  function createtable() {
    let fix = 0;
    let fixture = new (Object);
    let trow = new (Object);
    for (let i = 0; i < races.length; i++) {
      for (let j = 0; j < races[i].length; j++) {
        if (races[i][j].fixtureId != fix) {
          fix = races[i][j].fixtureId;
          fixture = getFixture(fix);
        }
        trow.fixtureId = fixture.fixtureId;
        trow.courseName = fixture.courseName;
        trow.fixtureDate = fixture.fixtureDate;
        trow.raceId = races[i][j].raceId;
        trow.raceDate = races[i][j].raceDate;
        trow.raceName = races[i][j].raceName;
        trow.raceTime = races[i][j].raceTime;
        tabledata.push(JSON.parse(JSON.stringify(trow)));

      }
    }
    console.log("tabledata", tabledata);
    showComing();
  }
  function getFixture(f) {
    for (j = 0; j < fixtures.length; j++) {
      if (fixtures[j].fixtureId == f) {
        return fixtures[j];
      }
    }
    return null;
  }
  function showComing() {
    if (tabledata.length > 0) {
      let racestable = "";
      for (let i = 0; i < tabledata.length; i++) {
        racestable += `<tr>`;
        racestable += `<td>${tabledata[i].courseName}</td>`;
        racestable += `<td>${tabledata[i].raceName}</td>`;
        racestable += `<td>${tabledata[i].raceDate}</td>`;
        racestable += `<td>$ ${tabledata[i].raceTime}</td>`;
        racestable += `</tr>`;
        // console.log(racestable);
      }
      console.log(racestable);
      document.getElementById("comingbody").innerHTML = racestable;
      $("#comingbox").show();
    }
  }
}

