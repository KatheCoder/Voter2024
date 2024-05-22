<template>
    <div class="card">
        <div class="card-body">
            <div v-if="Object.keys(meanRatings).length">
                <div class="chart-container">
                    <p class="scroll-instruction">Swipe left or right to view the chart.</p>
                    <highcharts :options="chartOptions" ref="chart"></highcharts>
                </div>
            </div>
            <div v-else>
                <h5>No data to display.</h5>
            </div>
        </div>
    </div>
</template>

<script>
import {Chart} from 'highcharts-vue';

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
        },
        chartKey: {
            type: String, // Adjust the type according to your needs
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
                credits: {
                    enabled: false
                },
                title: {
                    text: this.heading
                },
                xAxis: {
                    categories: Object.values(this.categoryStatements),
                    title: {
                        text: 'Statements'
                    }
                },
                yAxis: {
                    min: 0,
                    max: 10, // Set the maximum value to 10
                    title: {
                        text: 'Mean Ratings'
                    },
                    labels: {
                        format: '{value}'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2, // Adjust the space between columns
                        groupPadding: 0.1, // Adjust the space between groups of columns
                        dataLabels: {
                            enabled: true,
                            crop: false,
                            overflow: 'none',
                            allowOverlap: true, // Allow data labels to overlap
                            style: {
                                fontSize: '10px' // Adjust font size here
                            },
                            formatter: function () {
                                return this.y; // Display the value on top of the bar
                            }
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    useHTML: true,
                    formatter: function () {
                        let tooltip = `<span style="font-size: 10px">${this.x}</span><br/>`;
                        this.points.forEach(point => {
                            tooltip += `<span style="color:${point.color}">${point.series.name}</span>: <b>${point.y}</b><br/>`; // Include the value in parentheses
                        });
                        return tooltip;
                    }
                },
                series: [],
                exporting: {
                    sourceWidth: 1800, // Set the width of the exported chart
                    sourceHeight: 600, // Set the height of the exported chart
                    scale: 1 // Adjust the scale of the exported chart
                }
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

.chart-container {
    width: 100%; /* Make the container full width */
    overflow-x: auto; /* Enable horizontal scrolling */
    padding-bottom: 20px; /* Optional: add some padding to improve appearance */
}

.scroll-instruction {
    display: none;
    text-align: center;
    margin-bottom: 10px;
    font-size: 14px;
    color: #555;
}

@media (max-width: 992px) {
    .chart-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Enable smooth scrolling on iOS */
    }

    .chart-container > div {
        width: 500%; /* Set a larger width to ensure scrolling is needed */
    }

    .scroll-instruction {
        display: block;
    }
}
</style>
