<template>
    <div class="card">
        <div class="card-body">
            <div v-if="Object.keys(meanRatings).length">
                <highcharts :options="chartOptions" ref="chart"></highcharts>
            </div>
            <div v-else>
                <h5>No data to display.</h5>
            </div>
        </div>
    </div>
</template>

<script>
import { Chart } from 'highcharts-vue';

export default {
    name: 'Datatable',
    components: {
        highcharts: Chart
    },
    props: {
        meanRatings: {
            type: Object,
            required: true
        },
        categoryStatements: {
            type: Object,
            required: true
        },
        heading: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    type: 'column',
                    height: 400
                },
                title: {
                    text: this.heading
                },
                xAxis: {
                    categories: Object.values(this.categoryStatements),
                    title: {
                        text: 'Categories'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Mean Ratings'
                    },
                    labels: {
                        format: '{value}'
                    }
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px' // Adjust font size here
                            },
                            formatter: function() {
                                return this.y; // Display the value on top of the bar
                            }
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function() {
                        let tooltip = `<span style="font-size: 10px">${this.x}</span><br/>`;
                        this.points.forEach(point => {
                            tooltip += `<span style="color:${point.color}">${point.series.name}</span>: <b>${point.y}</b><br/>`; // Include the value in parentheses
                        });
                        return tooltip;
                    }
                },
                series: []
            }
        };
    },
    watch: {
        meanRatings: {
            handler() {
                this.updateChart();
            },
            deep: true
        },
        categoryStatements: {
            handler() {
                this.updateChart();
            },
            deep: true
        }
    },
    methods: {
        updateChart() {
            if (this.$refs.chart && this.$refs.chart.chart) {
                const seriesData = Object.entries(this.meanRatings).map(([party, data]) => ({
                    name: party,
                    data: Object.values(data).slice(0, -1), // Exclude the color from the data
                    color: data.color || null // Set the color based on the party color if available
                }));

                this.chartOptions.series = seriesData;
                this.$refs.chart.chart.update(this.chartOptions);
            }
        }
    },
    mounted() {
        this.updateChart(); // Call updateChart once the component is mounted
    }
};
</script>

<style scoped>
.card {
    margin-top: 20px;
}
</style>
