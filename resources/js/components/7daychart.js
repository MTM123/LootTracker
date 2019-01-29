$(function() {

    if($("#loot-statistic").length !== 0){

        am4core.useTheme(am4themes_animated);

        $.getJSON( "/api/get7dayloot", function( data ) {
            var chart = am4core.create("loot-statistic", am4charts.XYChart);
            chart.scrollbarX = new am4core.Scrollbar();


            console.log(data);

            chart.data = [];
            Object.keys(data).forEach(function(k){
                chart.data.push({
                    //name: k,
                    date: data[k].date+"",
                    value: data[k].loot
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

        });


    }

});