class FullScreenSearch {

    constructor() {
        this.text = "";
        this.lastKeyStroke = 9999999999999999;
        $("body").append("<div class='searchLayer'><div class='fsDiv'><span class='fsInput'></span><span class=\"blinking-cursor\">|</span></div></div>");

        var that = this;
        $("body").keydown(function( event) {


            if(String.fromCharCode(event.which).match("[A-Z0-9]")){
                that.addLetter(String.fromCharCode(event.which));
                that.lastKeyStroke = that.time();
                event.preventDefault();
            }

            if(event.which === 32){
                that.addLetter(String.fromCharCode(event.which));
                that.lastKeyStroke = that.time();
                event.preventDefault();
            }

            if(event.which === 8){
                that.removeLast()
                that.lastKeyStroke = that.time();
                event.preventDefault();
            }

            if(event.which === 27){
                $(".searchLayer").hide();
                event.preventDefault();
            }
        });

        setInterval(function () {
            if(that.lastKeyStroke + 1 <= that.time()){
                that.mark();
                that.lastKeyStroke = 9999999999999999;
                console.log("update")
            }
        },100);


        this.update();
    }

    addLetter(ch){
        this.text += ch;
        this.update();
    }

    removeLast(){
        this.text = this.text.slice(0,-1)
        this.update();
    }

    mark(){
        var that = this;
        $(".list-group-item").each(function( index ) {
            $(this).hide();
            if($( this ).find(".monster-name").text().toLowerCase().includes(that.text.toLowerCase())){
                $(this).show();
            }
        });
    }

    update(){
        this.text = this.text.replace(/\s\s+/g, ' ');
        if(this.text == ""){
            $(".searchLayer").hide();
            return;
        }
        $(".searchLayer").show();
        $(".searchLayer .fsInput").text(this.text);
    }

    time() {
        //  discuss at: http://locutus.io/php/time/
        // original by: GeekFG (http://geekfg.blogspot.com)
        // improved by: Kevin van Zonneveld (http://kvz.io)
        // improved by: metjay
        // improved by: HKM
        //   example 1: var $timeStamp = time()
        //   example 1: var $result = $timeStamp > 1000000000 && $timeStamp < 2000000000
        //   returns 1: true

        return Math.floor(new Date().getTime() / 1000)
    }
}

window.FullScreenSearch = FullScreenSearch;
