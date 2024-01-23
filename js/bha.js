
var upcoming = [];
var fixtures = [];
var races = [];
var entries = [];
var results = [];
var tabledata = [];

$(document).ready(function () {
  getUpcoming();
});

function getUpcoming() {

  $.getJSON('upcoming.json', function (upcoming) {
    console.log(upcoming);
    fixtures = upcoming["fixtures"];
    races = upcoming["races"];
    entries = upcoming["entries"];
    results = upcoming["results"];
    createtable();
    $("#spinner").hide();
  });
}

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
    let cname = "";
    for (let i = 0; i < tabledata.length; i++) {
      racestable += `<tr>`;
      if (cname != tabledata[i].courseName) {
        cname = tabledata[i].courseName;
        racestable += `<td>${tabledata[i].courseName}</td>`;
        racestable += `<td>${tabledata[i].raceDate}</td>`;
      } else {
        racestable += `<td></td><td></td>`;
      }
      racestable += `<td>${tabledata[i].raceTime}</td>`;
      racestable += `<td class="maxwidth">
      <span class="compare" onclick="doCompare(${tabledata[i].raceId})">${tabledata[i].raceName}</span>
      </td>`;
      racestable += `</tr>`;
      // console.log(racestable);
    }
    // console.log(racestable);
    document.getElementById("comingbody").innerHTML = racestable;
    $("#comingbox").show();
  }
}
function doCompare(r) {
  console.log(r);
  let runners = [];
  // get runners for specific race
  for (let i = 0; i < entries.length; i++) {
    let tmp = entries[i];
    for (let j = 0; j < tmp.length; j++) {
      if (entries[i][j].raceId == r) {
        runners.push(entries[i][j].racehorseName);
      }
    }
  }
  let uniq = [...new Set(runners)];
  console.log(uniq);
}


