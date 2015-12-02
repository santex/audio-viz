var width = 400,
    height = 430
    num_axes = 8,
    tick_axis = 1,
    start = 0
    end = 2;

var theta = function(r) {
  return -2*Math.PI*r;
};

var arc = d3.svg.arc()
  .startAngle(0)
  .endAngle(2*Math.PI);

var radius = d3.scale.linear()
  .domain([start, end])
  .range([0, d3.min([width,height])/2-20]);

var angle = d3.scale.linear()
  .domain([0,num_axes])
  .range([0,360])

var svg = d3.select("#chart").append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width/2 + "," + (height/2+8) +")");

var pieces = d3.range(start, end+0.001, (end-start)/1000);

var spiral = d3.svg.line.radial()
  .interpolate("cardinal")
  .angle(theta)
  .radius(radius);

svg.append("text")
  .text("And there was much rejoicing!")
  .attr("class", "title")
  .attr("x", 0)
  .attr("y", -height/2+16)
  .attr("text-anchor", "middle")

svg.selectAll("circle.tick")
    .data(d3.range(end,start,(start-end)/4))
  .enter().append("circle")
    .attr("class", "tick")
    .attr("cx", 0)
    .attr("cy", 0)
    .attr("r", function(d) { return radius(d); })

svg.selectAll(".axis")
    .data(d3.range(num_axes))
  .enter().append("g")
    .attr("class", "axis")
    .attr("transform", function(d) { return "rotate(" + -angle(d) + ")"; })
  .call(radial_tick)
  .append("text")
    .attr("y", radius(end)+13)
    .text(function(d) { return angle(d) + "°"; })
    .attr("text-anchor", "middle")
    .attr("transform", function(d) { return "rotate(" + -90 + ")" })

svg.selectAll(".spiral")
    .data([pieces])
  .enter().append("path")
    .attr("class", "spiral")
    .attr("d", spiral)
    .attr("transform", function(d) { return "rotate(" + 90 + ")" });

function radial_tick(selection) {
  selection.each(function(axis_num) {
    d3.svg.axis()
      .scale(radius)
      .ticks(5)
      .tickValues( axis_num == tick_axis ? null : [])
      .orient("bottom")(d3.select(this))

    d3.select(this)
      .selectAll("text")
      .attr("text-anchor", "bottom")
      .attr("transform", "rotate(" + angle(axis_num) + ")")
  });
}

function awesome_transition(t) {
  var k = t/2000*Math.sin(t/1000) + 1;

  var theta = function(r) {
      return k*-2*Math.PI*r;
  };

  spiral.angle(theta);

  svg.select(".spiral")
    .attr("d", spiral)
    .style("stroke", "rgba(" + Math.round(255*Math.sin(k)) + "," + Math.round(255*Math.cos(k)) + ",0,1)");

  svg.attr("transform", "translate(200,233) rotate(" + (50*k) + ")");
};

d3.timer( awesome_transition );
