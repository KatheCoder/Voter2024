<template>
    <div>
        <chart :options="chartOptions"></chart>
        <legend-card :title="title" :legend-data="data"></legend-card>

    </div>
</template>

<script>
import { Chart } from 'highcharts-vue';
import Highcharts from 'highcharts';
import Exporting from 'highcharts/modules/exporting';
import LegendCard from "./LegendCard";
Exporting(Highcharts);

export default {
    name: 'ColumnChart',
    components: {
        Chart,LegendCard
    },
    props: {
        title: {
            type: String,
            default: 'Title here'
        },
        data: {
            type: Array,
            required: true,
        },
        highlightedValue: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'column',
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: this.title,
                },
                xAxis: {
                    categories: [], // Will be updated dynamically
                },
                yAxis: {
                    title: {
                        text: '%GT Sum of Weight',
                    },
                },
                showInLegend: true,
                legend: {
                    enabled: false,
                    labelFormat: '{name}: {y} %',
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return `${this.y}%`;
                            },

                            // rotation: -90,
                        },
                        colorByPoint: true ,
                        tooltip: {
                            pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}%</b><br/>'
                        }
                    }
                },
                series: [{
                    name: '',
                    data: this.formatData(this.data), // Format the data with colors
                }],
            },
        };
    },
    watch: {
        highlightedValue(newValue) {
            console.log(newValue)
            this.highlightDataPoint(newValue); // Highlight data point
        },
        data: {
            handler(newData) {
                // Update chart data when data prop changes
                this.chartOptions.series[0].data = this.formatData(newData);

                // Update xAxis categories dynamically
                this.chartOptions.xAxis.categories = newData.map(item => item.abbr_name);
            },
            immediate: true,
        },
    },
    methods: {
        formatData(data) {
            // Extracting necessary fields from JSON data and formatting for Highcharts
            return data.map((item, index) => ({
                name: item.name,
                y: parseFloat(item.value), // Assuming value is a string representing a number
                color: item.color,
                category: item.abbr_name, // Adding a category from formatted data
            }));
        },
    },
};
</script>
