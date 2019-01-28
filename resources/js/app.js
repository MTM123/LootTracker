
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
//require('./chart');

// Favorite Button - Heart
jQuery('.favme').click(function() {
    jQuery(this).toggleClass('active');
    saveFavoriteMonsters();
});

/* when a user clicks, toggle the 'is-animating' class */
jQuery(".favme").on('click touchstart', function(){
    jQuery(this).toggleClass('is_animating');
    saveFavoriteMonsters();
});

/*when the animation is over, remove the class*/
jQuery(".favme").on('animationend', function(){
    jQuery(this).toggleClass('is_animating');
});

function loadFavoriteMonsters() {
    var stars = JSON.parse(window.localStorage.getItem('favorite_monsters'));
    for(var k in stars) {
        if(stars[k].active){
            jQuery('.favme[data-mid="'+stars[k].id+'"]').addClass("active");
        }
    }
}


function saveFavoriteMonsters() {
    var stars = [];
    jQuery('.favme[data-mid]').each(function( index ) {
        stars.push({id:$( this ).data("mid"), active:$( this ).hasClass("active")});
    });
    window.localStorage.setItem('favorite_monsters', JSON.stringify(stars));
}

function updateFilter() {
    $(".filter-selected-list").html("");
    $(".select-filter").each(function( index ) {
        $(".filter-selected-list").append("<li class=\"list-group-item\">"+$( this ).find(".monster-name").text()+"</li>")
    });
}

function getaFilterList() {
    var list = [];
    $(".select-filter").each(function( index ) {
        list.push($(this).data("monsterid"));
    });
    return list;
}

$(function() {
    loadFavoriteMonsters();
    $('[data-toggle="tooltip"]').tooltip();

    $(".user-monster-kill .list-group-item").click(function(event){
        var selectedMonsters = $(".select-filter").length;

        if(selectedMonsters >= 5 && !$(event.target).hasClass("select-filter")){
            $.notify('You can\'t select more than 5', { allow_dismiss: false,timer: 1000,placement: {from: "bottom",align: "right"} });
            return;
        }

        if(!$(event.target).hasClass("favme")) {
            $(this).toggleClass("select-filter");
            updateFilter();
        }

        if($(".select-filter").length === 0){
            $(".btn-clear-filter").hide();
        }else{
            $(".btn-clear-filter").show();
        }
    });

    $(".btn-go-to-drops").click(function(){
        location.href = $(this).data("url")+"/"+(getaFilterList().join("-"));
    });

    $(".btn-clear-filter").click(function(){
        $(this).hide();
        $(".select-filter").removeClass("select-filter");
        updateFilter();
    });


});
function rgb2hex(r, g, b) {
    if (r > 255 || g > 255 || b > 255)
        throw "Invalid color component";
    return ((r << 16) | (g << 8) | b).toString(16);
}


let colors = "#3366CC #DC3912 #FF9900 #109618 #990099 #3B3EAC #0099C6 #DD4477 #66AA00 #B82E2E #316395 #994499 #22AA99 #AAAA11 #6633CC #E67300 #8B0707 #329262 #5574A6 #3B3EAC #3366CC #DC3912 #FF9900 #109618 #990099 #3B3EAC #0099C6 #DD4477 #66AA00 #B82E2E #316395 #994499 #22AA99 #AAAA11 #6633CC #E67300 #8B0707 #329262 #5574A6 #3B3EAC #3366CC #DC3912 #FF9900 #109618 #990099 #3B3EAC #0099C6 #DD4477 #66AA00 #B82E2E #316395 #994499 #22AA99 #AAAA11 #6633CC #E67300 #8B0707 #329262 #5574A6 #3B3EAC".split(" ");



let donutOptions = {
    cutoutPercentage: 0,
    legend: {position: 'none', padding: 5, labels: {pointStyle: 'circle', usePointStyle: true}}
};

// donut 1
let chDonutData1 = {
    labels: [],
    datasets: [
        {
            backgroundColor: colors,
            borderWidth: 0,
            data: []
        }
    ]
};

var lootChart;

// $(function() {
//     if($("#loot-chart").length !== 0){
//
//         $.getJSON( "/api/getloot", function( data ) {
//             console.log(data);
//             for (let k in data) {
//                 //console.log(data[k].name);
//                 //chDonutData1.datasets[0].data.push(data[k].total_price)
//                 //chDonutData1.datasets[0].data.push(data[k].drop_times);
//                 chDonutData1.datasets.push({data:[Math.random()*100],backgroundColor: colors, borderWidth: 0});
//                 chDonutData1.labels.push(data[k].name);
//             }
//
//             console.log(chDonutData1);
//
//             var chDonut1 = document.getElementById("loot-chart");
//             if (chDonut1) {
//                 lootChart = new Chart(chDonut1, {
//                     type: 'bar',
//                     data: chDonutData1,
//                     options: donutOptions
//                 });
//             }
//
//         });
//
//
//     }
//
//     $(".item_container .item").click(function(){
//         var meta = lootChart.getDatasetMeta(0);
//         var id = chDonutData1.labels.indexOf($(this).data("original-title"));
//         meta.data[id].hidden = !meta.data[id].hidden;
//         $(this).parent().toggleClass("hidden-data");
//         lootChart.update();
//
//
//     });
// });

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end




$(function() {
    if($("#chartdiv").length !== 0){

        $.getJSON( "/api/getloot", function( data ) {
            var chart = am4core.create("chartdiv", am4charts.XYChart);
            chart.scrollbarX = new am4core.Scrollbar();
            chart.data = data;

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

// Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.sequencedInterpolation = true;
            series.dataFields.valueY = graph_sort;
            series.dataFields.categoryX = "name";
            series.tooltipText = "[{nameX}: bold]{valueY}[/]";
            series.columns.template.strokeWidth = 0;

            series.tooltip.pointerOrientation = "vertical";

            series.columns.template.column.cornerRadiusTopLeft = 10;
            series.columns.template.column.cornerRadiusTopRight = 10;
            series.columns.template.column.fillOpacity = 0.8;

// on hover, make corner radiuses bigger
            let hoverState = series.columns.template.column.states.create("hover");
            hoverState.properties.cornerRadiusTopLeft = 0;
            hoverState.properties.cornerRadiusTopRight = 0;
            hoverState.properties.fillOpacity = 1;

            series.columns.template.adapter.add("fill", (fill, target)=>{
                return chart.colors.getIndex(target.dataItem.index);
            })

// Cursor
            chart.cursor = new am4charts.XYCursor();

        });


    }

    $(".item_container .item").click(function(){

        $(this).parent().toggleClass("hidden-data");


    });
});