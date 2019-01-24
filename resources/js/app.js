
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
        if(!$(event.target).hasClass("favme")) {
            $(this).toggleClass("select-filter");
            updateFilter();
        }
    });

    $(".filter-get-drops button").click(function(){
        location.href = $(this).data("url")+"/"+(getaFilterList().join("-"));
    });


});