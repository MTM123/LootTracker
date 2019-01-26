
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./chart');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});


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

$(function() {
    if($("#loot-chart").length !== 0){

        $.getJSON( "/api/getloot", function( data ) {
            console.log(data);
            for (let k in data) {
                //console.log(data[k].name);
                chDonutData1.datasets[0].data.push(data[k].drop_times)
                chDonutData1.labels.push(data[k].name);
            }

            console.log(chDonutData1);

            var chDonut1 = document.getElementById("loot-chart");
            if (chDonut1) {
                lootChart = new Chart(chDonut1, {
                    type: 'pie',
                    data: chDonutData1,
                    options: donutOptions
                });
            }

        });


    }

    $(".item_container .item").click(function(){
        var meta = lootChart.getDatasetMeta(0);
        var id = chDonutData1.labels.indexOf($(this).data("original-title"));
        meta.data[id].hidden = !meta.data[id].hidden;
        $(this).parent().toggleClass("hidden-data");
        lootChart.update();


    });
});