// Generated by CoffeeScript 1.10.0
window.onload = function() {
  var i, len, region, regions, results;
  regions = document.querySelectorAll('.edit-me');
  results = [];
  for (i = 0, len = regions.length; i < len; i++) {
    region = regions[i];
    results.push(new ContentEdit.Region(region));
  }
  return results;
};