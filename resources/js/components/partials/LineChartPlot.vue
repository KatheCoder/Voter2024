<template>
    <div class="chart-container">
        <highcharts ref="chart" :options="chartOptions"></highcharts>
    </div>
</template>

<script>
import { Chart } from 'highcharts-vue';
import Highcharts from 'highcharts';

export default {
    name: "LineChartPlot",
    components: {
        highcharts: Chart
    },
    props: {
        chartData: {
            type: Array,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        dates: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'line',
                    height: 400
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: this.title
                },
                xAxis: {
                    categories: this.dates,
                    title: {
                        text: 'Date'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Percentage'
                    },
                    labels: {
                        format: '{value}%'
                    },
                    min: 0 // Start y-axis at 0
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let totalSampleCount = 0;

                        // Calculate the total sample count for the current index across all series
                        this.points.forEach((point) => {
                            totalSampleCount += point.series.options.sampleCounts[point.point.index];
                        });

                        let tooltip = `<span style="font-size: 10px">${this.x} ( Sample : ${totalSampleCount} )</span><br/>`;
                        this.points.forEach((point) => {
                            const sampleCount = point.series.options.sampleCounts[point.point.index]; // Get the sample count for the current point
                            tooltip += `<span style="color:${point.series.color}">${point.series.name}</span>: <b>${point.y}%</b> (${sampleCount})<br/>`;
                        });

                        return tooltip;
                    }
                },
                legend: {
                    labelFormatter: function() {
                        const series = this.chart.series.find(s => s.name === this.name);
                        const totalSampleCount = series.userOptions.sampleCounts.reduce((total, count) => total + count, 0);
                        if (this.yData) {
                            const total = this.yData.reduce((sum, value) => sum + value, 0);
                            const average = total / this.yData.length;
                            // return `${this.name} : ${average.toFixed(2)}% ( ${totalSampleCount} )`;
                            return `${this.name}`;
                        }
                        return `${this.name} (Total Sample: ${totalSampleCount})`;
                    }
                },
                series: this.chartData.map((data,index) => ({
                    name: data.name,
                    data: data.data,
                    color: data.color,
                    sampleCounts: data.sampleCounts || [], // Ensure sampleCounts is defined
                }))
            }
        };
    },
    mounted() {
        // Wait until the component is mounted before updating the chart
        this.$nextTick(() => {
            if (this.$refs.chart && this.$refs.chart.chart) {
                this.updateChart();
            }
        });
    },
    watch: {
        chartData: {
            handler() {
                this.updateChart();
            },
            deep: true
        },
        dates: {
            handler() {
                this.updateChart();
            },
            deep: true
        }
    },
    methods: {
        updateChart() {
            if (this.$refs.chart && this.$refs.chart.chart) {
                this.chartOptions.series = this.chartData.map((data,index) => ({
                    name: data.name,
                    data: data.data,
                    color: data.color,
                    sampleCounts: data.sampleCounts || [], // Ensure sampleCounts is defined
                }));
                this.chartOptions.xAxis.categories = this.dates;

                // Redraw the chart
                this.$refs.chart.chart.update(this.chartOptions);
            }
        }
    }
};
</script>

<style scoped>
.chart-container {
    position: relative;
    width: 100%;
    height: 400px;
}
</style>
