<template>
    <div>
        <chart :options="chartOptions"></chart>
        <legend-card :title="title" :legend-data="legendData"></legend-card>
    </div>
</template>

<script>
import { Chart } from 'highcharts-vue';
import Highcharts from 'highcharts';
import Exporting from 'highcharts/modules/exporting';
import LegendCard from "../partials/LegendCard";

Exporting(Highcharts);

export default {
    name: 'ColumnChart',
    components: {
        Chart,
        LegendCard
    },
    props: {
        title: {
            type: String,
            default: 'Title here'
        },
        legendData: {
            type: Array,
        },
        labels: {
            type: Array,
            required: true,
        },
        colors: {
            type: Array,
            required: true,
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
                    categories: this.labels,
                },
                yAxis: {
                    title: {
                        text: '%GT Sum of Weight',
                    },
                },
                showInLegend: true,
                legend: {
                    enabled: true,
                    labelFormat: '{name}: {y}',
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return `${this.y}%`;
                            }
                        },
                        colorByPoint: true // Enable color by point
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
        labels: {
            handler(newLabels) {
                this.chartOptions.xAxis.categories = newLabels;
            },
            immediate: true,
        },
        data: {
            handler(newData) {
                this.chartOptions.series[0].data = this.formatData(newData); // Format the new data with colors
            },
            immediate: true,
        },
    },
    methods: {
        formatData(data) {
            // Assign colors to each data point
            return data.map((item, index) => ({
                y: item,
                color: this.colors[index % this.colors.length], // Use color from the 'colors' prop
            }));
        },
    },
};
</script>
