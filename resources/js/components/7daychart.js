$(function() {

    if($("#loot-statistic").length !== 0){

        am4core.useTheme(am4themes_animated);
        var chart = am4core.create("loot-statistic", am4charts.XYChart);
        chart.data = [];
        $.getJSON( "/api/get7dayloot", function( data ) {
            //var chart = am4core.create("loot-statistic", am4charts.XYChart);
            chart.scrollbarX = new am4core.Scrollbar();

            Object.keys(data).forEach(function(k){

                //Make valueables look good

                var template = `
                <div class="item float-left" data-toggle="tooltip" >
                    <span>{QTY}</span>
                    <img src="http://cdn.kulers.ml/media/{ID}.png" />
                </div>
                `;
                var valuablesHtml = '';
                Object.keys(data[k].valueable).forEach(function(s){
                    valuablesHtml = template.replace("{QTY}", data[k].valueable[s].qty).replace("{ID}", data[k].valueable[s].id) + valuablesHtml;
                });



                chart.data.push({
                    //name: k,
                    date: data[k].date+"",
                    value: data[k].loot,
                    valueable: valuablesHtml
                });
            });



            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.DateAxis());
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 270;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;



            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "value";
            series.dataFields.dateX = "date";
            series.tooltipText = "{value}";
            series.strokeWidth = 0;

            series.columns.template.column.cornerRadiusTopLeft = 2;
            series.columns.template.column.cornerRadiusTopRight = 2;
            series.columns.template.column.fillOpacity = 0.8;

            ///Hover
            //first series */
            series.tooltipHTML = `<center><strong>9 Most valuable drops</strong></center>
            <div style="width:132px;">
            {valueable}
            </div>         
            `;
            series.tooltip.label.interactionsEnabled = true;
            series.tooltip.pointerOrientation = "vertical";

            // Cursor
            chart.cursor = new am4charts.XYCursor();

            series.columns.template.adapter.add("fill", (fill, target)=>{
                return chart.colors.getIndex(target.dataItem.index);
            })

            chart.events.on("beforedatavalidated", function(ev) {
                chart.data.sort(function(a, b) {
                    return (new Date(a.date)) - (new Date(b.date));
                });
            });

            series.columns.template.events.on("hit", function(ev) {

                console.log("clicked on ", ev.target._dataItem.dataContext);
            }, this);



            indicator.hide();
            chart.validateData();

        });



        var indicator;
        var indicatorLabel;
        function showIndicator() {
            indicator = chart.tooltipContainer.createChild(am4core.Container);
            indicator.background.fill = am4core.color("#fff");
            indicator.background.fillOpacity = 0.8;
            indicator.width = am4core.percent(100);
            indicator.height = am4core.percent(100);

            indicatorLabel = indicator.createChild(am4core.Label);
            indicatorLabel.text = "Processing your drops...";
            indicatorLabel.align = "center";
            indicatorLabel.valign = "middle";
            indicatorLabel.fontSize = 20;

        }

        showIndicator();


    }

});