$(function() {

    if($("#loot-statistic").length !== 0){

        am4core.useTheme(am4themes_animated);

        $.getJSON( "/api/get7dayloot", function( data ) {
            var chart = am4core.create("loot-statistic", am4charts.XYChart);
            chart.scrollbarX = new am4core.Scrollbar();
            chart.data = [];

            console.log(data);

            Object.keys(data).forEach(function(k){
                chart.data.push({
                    name: data[k].date,
                    loot: data[k].loot
                });
            });



            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "name";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 300;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 110;

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.minWidth = 50;

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.sequencedInterpolation = true;
            series.dataFields.valueY = "loot";
            series.dataFields.categoryX = "name";
            //series.tooltipText = "[{nameX}: bold]{valueY}[/]";
            series.tooltipText = "Date: {name}\nValue: {valueY}";
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

});