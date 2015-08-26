
// get the width & height from the div
    var w = 500,
        h = 400,
        fetcher, vis, fill, count, background,
        scale;
        //spiral=getSpiral();
        //cur_wc_type = 'professional';
        //initialized = false;

    var layout = d3.layout.cloud()
        .timeInterval(10)
        .size([w, h])
        .fontSize(function(d) { return fontSize(+d.value); })
        .text(function(d) { return d.key; })
        .on("word", progress)
        .on("end", draw);

var wordcloud_img_cmds = {};
var wordcloud_bounds = {};


function create_svg_and_vis() {

    var visDiv = d3.select("#vis");

    if (!d3.select("#svgMain").empty()) {
        d3.select("#svgMain").remove();
    }

    var svg = visDiv.append("svg");

    var wcControls = d3.select("#wcControls");

    w = wcControls.property("clientWidth");
    h = wcControls.property("clientHeight");

    svg.attr("width", w + "px")
        .attr("height", h + "px")
        .attr("id", "svgMain");

    background = svg.append("g");

    // this 'g' is vis, but I don't want to overwrite the existing one
    //var boundingRect = visDiv[0][0].getBoundingClientRect();

    svg.append("g")
        .attr("transform", "translate(" + [w >> 1, h >> 1] + ")") // don't translate to middle - it is top/left
        //.attr("transform", "translate(" + [w >> 1, h >> 1] + ")") // don't translate to middle - it is top/left
        .attr("id","svgG");

    return svg;
}

function initialize_cloud() {

    fill = d3.scale.category20b();

    var words = [],
        max,
        complete = 0,
        keyword = "",
        tags,
        fontSize,
        maxLength = 30,
        //fetcher,
        statusText = d3.select("#status");


    //var viewBoxStr = "0 0 " + w + " " + h;

    create_svg_and_vis();
    vis = d3.select('#svgG');

    scale = d3.scale.linear();

    // sets the count, from and to variables
    if (useProfessional()) {
        count = 2;
        scale.domain([0, count-1]).range([-90, 0]);
    } else {
        count = 5;
        scale.domain([0, count-1]).range([-60, 60]);
    }

    //count = useProfessional() ? 2 : 5;
    //from = useProfessional() ? -90 : -60;
    //to = useProfessional() ? 0 : 60;


    //svg.append("<use xlink:href='#svgG' />");

    /* not hooked up efj 11-Aug-14
    d3.select("#download-svg").on("click", downloadSVG);
    d3.select("#download-png").on("click", downloadPNG);

    d3.select(window).on("hashchange", hashchange);

    var form = d3.select("#form")
        .on("submit", function() {
            load(d3.select("#text").property("value"));
            d3.event.preventDefault();
        });
    form.selectAll("input[type=number]")
        .on("click.refresh", function() {
            if (this.value === this.defaultValue) return;
            generate();
            this.defaultValue = this.value;
        });
    form.selectAll("input[type=radio], #font")
        .on("change", generate);
    */
}

// From Jonathan Feinberg's cue.language, see lib/cue.language/license.txt.
var stopWords = /^(i|me|my|myself|we|us|our|ours|ourselves|you|your|yours|yourself|yourselves|he|him|his|himself|she|her|hers|herself|it|its|itself|they|them|their|theirs|themselves|what|which|who|whom|whose|this|that|these|those|am|is|are|was|were|be|been|being|have|has|had|having|do|does|did|doing|will|would|should|can|could|ought|i'm|you're|he's|she's|it's|we're|they're|i've|you've|we've|they've|i'd|you'd|he'd|she'd|we'd|they'd|i'll|you'll|he'll|she'll|we'll|they'll|isn't|aren't|wasn't|weren't|hasn't|haven't|hadn't|doesn't|don't|didn't|won't|wouldn't|shan't|shouldn't|can't|cannot|couldn't|mustn't|let's|that's|who's|what's|here's|there's|when's|where's|why's|how's|a|an|the|and|but|if|or|because|as|until|while|of|at|by|for|with|about|against|between|into|through|during|before|after|above|below|to|from|up|upon|down|in|out|on|off|over|under|again|further|then|once|here|there|when|where|why|how|all|any|both|each|few|more|most|other|some|such|no|nor|not|only|own|same|so|than|too|very|say|says|said|shall)$/,
    punctuation = new RegExp("[" + unicodePunctuationRe + "]", "g"),
    wordSeparators = /[\s\u3031-\u3035\u309b\u309c\u30a0\u30fc\uff70]+/g,
    discard = /^(@|https?:|\/\/)/,
    htmlTags = /(<[^>]*?>|<script.*?<\/script>|<style.*?<\/style>|<head.*?><\/head>)/g,
    matchTwitter = /^https?:\/\/([^\.]*\.)?twitter\.com/;

function parseHTML(d) {
    parseText(d.replace(htmlTags, " ").replace(/&#(x?)([\dA-Fa-f]{1,4});/g, function(d, hex, m) {
        return String.fromCharCode(+((hex ? "0x" : "") + m));
    }).replace(/&\w+;/g, " "));
}

function getURL(url, callback) {
    //statusText.text("Fetching… ");

    if (matchTwitter.test(url)) {
        var iframe = d3.select("body").append("iframe").style("display", "none");
        d3.select(window).on("message", function() {
            var json = JSON.parse(d3.event.data);
            callback((Array.isArray(json) ? json : json.results).map(function(d) { return d.text; }).join("\n\n"));
            iframe.remove();
        });
        iframe.attr("src", "http://jsonp.jasondavies.com/?" + encodeURIComponent(url));
        return;
    }

    try {
        d3.text(url, function(text) {
            if (text == null) proxy(url, callback);
            else callback(text);
        });
    } catch(e) {
        proxy(url, callback);
    }
}

function proxy(url, callback) {
    d3.text("//www.jasondavies.com/xhr?url=" + encodeURIComponent(url), callback);
}

function flatten(o, k) {
    if (typeof o === "string") return o;
    var text = [];
    for (k in o) {
        var v = flatten(o[k], k);
        if (v) text.push(v);
    }
    return text.join(" ");
}

function parseText(text) {
    var tags = [];
    var cases = [];

    //if (initialized === false) {
        initialize_cloud();
    //}

    if (!text) {
        text = d3.select("#wordtext").property("value");
    }

    //text.split(d3.select("#per-line").property("checked") ? /\n/g : wordSeparators).forEach(function(word) {
    text.split(wordSeparators).forEach(function(word) {

        if (discard.test(word)) return;

        word = word.replace(punctuation, "");
        var lcWord = word.toLowerCase();

        if (stopWords.test(lcWord)) return;

        //word = word.substr(0, maxLength);

        cases[lcWord] = word;

        // keep count of how many times a word is used
        tags[lcWord] = (tags[lcWord] || 0) + 1;
    });

    tags = d3.entries(tags).sort(function(a, b) { return b.value - a.value; });
    tags.forEach(function(d) { d.key = cases[d.key]; });
    generate(tags);
}

function useProfessional() {
    return $("input[name='wc_type']:checked").val() === 'professional';
}

function getSpiral() {
    // d3.select("input[name=spiral]:checked").property("value")

    return useProfessional() ? 'archimedean' : 'rectangular';
}

function getFont() {
    // d3.select("#fontchoice").property("value")

    return useProfessional() ? 'Impact' : 'Sans Serif';
}

function getScale() {
    // d3.select("input[name=scale]:checked").property("value")

    return useProfessional() ? 'linear' : 'log'; // 'sqrt' or 'linear'
}

function generate(tags) {

    var numWords = Math.min(50, tags.length);

    layout
        .font(getFont())
        .spiral(getSpiral());
    fontSize = d3.scale[getScale()]().range([10, 100]);
    if (tags.length) fontSize.domain([+tags[numWords - 1].value || 1, +tags[0].value]);
    complete = 0;
    //statusText.style("display", null);
    words = [];
    layout.stop().words(tags.slice(0, max = numWords)).start();
    //layout.stop().words(tags.slice(0, max = Math.min(tags.length, +d3.select("#max").property("value")))).start();
}

function progress(d) {
    //statusText.text(++complete + "/" + max);
}

function find_scale(bounds, w, h) {

    /* this is crap, but it's the original
    var half_w = w >> 1;
    var half_h = h >> 1;

    var scaleTrans = bounds ? Math.min(
        w / Math.abs(bounds[1].x - half_w),
        w / Math.abs(bounds[0].x - half_w),
        h / Math.abs(bounds[1].y - half_h),
        h / Math.abs(bounds[0].y - half_h)) / 2 : 1;

    // if the bounding box is < w and h, then we need to expand the image, not contract it
    if (w > bounds[1].x && h > bounds[1].y) {
        scaleTrans /= 1.0;
    }
    */

    var imgW = bounds[1].x - bounds[0].x;
    var imgH = bounds[1].y - bounds[0].y;
    var scaleTrans = Math.min(w/imgW, h/imgH);

    return scaleTrans;
}

function draw(data, bounds) {
    //statusText.style("display", "none");

    //w  = vis.property("width");
    //h = vis.property("height");

    /*
    var scaleTrans = bounds ? Math.min(
        w / Math.abs(bounds[1].x - w / 2),
        w / Math.abs(bounds[0].x - w / 2),
        h / Math.abs(bounds[1].y - h / 2),
        h / Math.abs(bounds[0].y - h / 2)) / 2 : 1;
    */

    var scaleTrans = find_scale(bounds, w, h);
    words = data;
    var text = vis.selectAll("text")
        .data(words, function(d) { return d.text.toLowerCase(); });
    text.transition()
        .duration(1000)
        .attr("transform", function(d) { return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")"; })
        .style("font-size", function(d) { return d.size + "px"; });
    text.enter().append("text")
        .attr("text-anchor", "middle")
        .attr("transform", function(d) { return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")"; })
        .style("font-size", function(d) { return d.size + "px"; })
        .on("click", function(d) {
            load(d.text);
        })
        //.style("opacity", 1e-6)
        .transition()
        .duration(1000)
        .style("opacity", 1);
    text.style("font-family", function(d) { return d.font; })
        .style("fill", function(d) { return fill(d.text.toLowerCase()); })
        .text(function(d) { return d.text; });
    var exitGroup = background.append("g")
        .attr("transform", vis.attr("transform"));
    var exitGroupNode = exitGroup.node();
    text.exit().each(function() {
        exitGroupNode.appendChild(this);
    });

    var svg = document.getElementById('svgMain');

    exitGroup.transition()
        .duration(1000)
        .style("opacity", 1e-6)
        .remove();
    vis.transition()
        .delay(1000)
        .duration(750)
        //.attr("transform", "translate(" + [svg[0][0].getBoundingClientRect().left>>1, svgp[0][0].getBoundingClientRect().top>>1] + ")scale(" + scaleTrans + ")");
        .attr("transform", "translate(" + [w >> 1, h >> 1] + ")scale(" + scaleTrans + ")");
        //.attr("transform", "scale(" + scaleTrans + ")");

    // hack!

    var svg = document.getElementById('svgMain');
    wordcloud_img_cmds[$('.slide_thumb.active').attr('id')] = $(svg.cloneNode(true));
    wordcloud_bounds[$('.slide_thumb.active').attr('id')] = bounds;
}

// Converts a given word cloud to image/png.
function downloadPNG() {
    var canvas = document.createElement("canvas"),
        c = canvas.getContext("2d");
    canvas.width = w;
    canvas.height = h;
    c.translate(w >> 1, h >> 1);
    c.scale(scale, scale);
    words.forEach(function(word, i) {
        c.save();
        c.translate(word.x, word.y);
        c.rotate(word.rotate * Math.PI / 180);
        c.textAlign = "center";
        c.fillStyle = fill(word.text.toLowerCase());
        c.font = word.size + "px " + word.font;
        c.fillText(word.text, 0, 0);
        c.restore();
    });
    d3.select(this).attr("href", canvas.toDataURL("image/png"));
}

function downloadSVG() {
    d3.select(this).attr("href", "data:image/svg+xml;charset=utf-8;base64," + btoa(unescape(encodeURIComponent(
        svg.attr("version", "1.1")
            .attr("xmlns", "http://www.w3.org/2000/svg")
            .node().parentNode.innerHTML))));
}

function hashchange(fallback) {
    var h = location.hash;
    if (h && h.length > 1) {
        h = decodeURIComponent(h.substr(1));
        if (h !== fetcher) load(h);
    } else if (fallback) load(fallback);
}

function load(f) {
    fetcher = f;
    var h = /^(https?:)?\/\//.test(fetcher)
        ? "#" + encodeURIComponent(fetcher)
        : "";
    if (fetcher != null) d3.select("#text").property("value", fetcher);
    if (location.hash !== h) location.hash = h;
    if (h) getURL(fetcher, parseHTML);
    else if (fetcher) parseText(fetcher);
}

d3.select("#random-palette").on("click", function() {
    paletteJSON("http://www.colourlovers.com/api/palettes/random", {}, function(d) {
        fill.range(d[0].colors);
        vis.selectAll("text")
            .style("fill", function(d) { return fill(d.text.toLowerCase()); });
    });
    d3.event.preventDefault();
});

//(function() {

/*
function draw_angles() {
    var r = 40.5,
        px = 35,
        py = 20;

    var angles = d3.select("#angles").append("svg")
        .attr("width", 2 * (r + px))
        .attr("height", r + 1.5 * py)
        .append("g")
        .attr("transform", "translate(" + [r + px, r + py] +")");

    angles.append("path")
        .style("fill", "none")
        .attr("d", ["M", -r, 0, "A", r, r, 0, 0, 1, r, 0].join(" "));

    angles.append("line")
        .attr("x1", -r - 7)
        .attr("x2", r + 7);

    angles.append("line")
        .attr("y2", -r - 7);

    angles.selectAll("text")
        .data([-90, 0, 90])
        .enter().append("text")
        .attr("dy", function(d, i) { return i === 1 ? null : ".3em"; })
        .attr("text-anchor", function(d, i) { return ["end", "middle", "start"][i]; })
        .attr("transform", function(d) {
            d += 90;
            return "rotate(" + d + ")translate(" + -(r + 10) + ")rotate(" + -d + ")translate(2)";
        })
        .text(function(d) { return d + "°"; });

    var radians = Math.PI / 180,
        from,
        to,
        count,
        scale = d3.scale.linear(),
        arc = d3.svg.arc()
            .innerRadius(0)
            .outerRadius(r);


    d3.selectAll("#angle-count, #angle-from, #angle-to")
        .on("change", getAngles)
        .on("mouseup", getAngles);

    getAngles();
    }



    function update() {

        scale.domain([0, count - 1]).range([from, to]);


        var step = (to - from) / count;

        var path = angles.selectAll("path.angle")
            .data([{startAngle: from * radians, endAngle: to * radians}]);
        path.enter().insert("path", "circle")
            .attr("class", "angle")
            .style("fill", "#fc0");
        path.attr("d", arc);

        var line = angles.selectAll("line.angle")
            .data(d3.range(count).map(scale));
        line.enter().append("line")
            .attr("class", "angle");
        line.exit().remove();
        line.attr("transform", function(d) { return "rotate(" + (90 + d) + ")"; })
            .attr("x2", function(d, i) { return !i || i === count - 1 ? -r - 5 : -r; });

        var drag = angles.selectAll("path.drag")
            .data([from, to]);
        drag.enter().append("path")
            .attr("class", "drag")
            .attr("d", "M-9.5,0L-3,3.5L-3,-3.5Z")
            .call(d3.behavior.drag()
                .on("drag", function(d, i) {
                    d = (i ? to : from) + 90;
                    var start = [-r * Math.cos(d * radians), -r * Math.sin(d * radians)],
                        m = [d3.event.x, d3.event.y],
                        delta = ~~(Math.atan2(cross(start, m), dot(start, m)) / radians);
                    d = Math.max(-90, Math.min(90, d + delta - 90)); // remove this for 360°
                    delta = to - from;
                    if (i) {
                        to = d;
                        if (delta > 360) from += delta - 360;
                        else if (delta < 0) from = to;
                    } else {
                        from = d;
                        if (delta > 360) to += 360 - delta;
                        else if (delta < 0) to = from;
                    }
                    update();
                })
                .on("dragend", generate));
        drag.attr("transform", function(d) { return "rotate(" + (d + 90) + ")translate(-" + r + ")"; });
        }
*/

        layout.rotate(function() {
            return scale(~~(Math.random() * count));
        });

/*
        d3.select("#angle-count").property("value", count);
        d3.select("#angle-from").property("value", from);
        d3.select("#angle-to").property("value", to);
*/
    //}

    function cross(a, b) { return a[0] * b[1] - a[1] * b[0]; }
    function dot(a, b) { return a[0] * b[0] + a[1] * b[1]; }

//}
//)();
