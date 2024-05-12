<template>
    <div>
        <chart v-if="chartOptions" :options="chartOptions"></chart>
        <div v-else>
            <p>Loading...</p>
        </div>
    </div>
</template>

<script>
import {Chart} from 'highcharts-vue';
import HighchartsVue from 'highcharts-vue';
import Highcharts from 'highcharts';
import Exporting from 'highcharts/modules/exporting';

Exporting(Highcharts);

export default {
    name: "PieChart",
    components: {
        Chart,
        HighchartsVue,
    },
    props: {
        title: {
            type: String,
            default: ''
        },
        data: {
            type: Array,
            required: true
        },

    },
    data() {
        return {
            loading: true,
            chartOptions: null
        };
    },
    watch: {
        data: {
            immediate: true,
            handler(newVal) {
                this.updateChart(newVal);
            },
        },
        title: {
            immediate: true,
            handler(newValue) {
                this.updateChart(this.data);
            }
        }
    },
    methods: {
        updateChart(data) {
            this.chartOptions = {
                chart: {
                    type: 'pie',

                    height: 450 // Adjust height as needed
                },
                credits: {
                    enabled: false
                },

                legend: {
                    itemStyle: {
                        color: '#000'
                    }
                },
                title: {
                    text: this.title || 'Responses to the Survey' // Use prop title or default
                },
                series: [
                    {
                        showInLegend:true,
                        legend: {
                            enabled: true,
                            labelFormat: '{name}: {y}',
                        },
                        name: 'Responses',
                        colorByPoint: true,
                        colors: this.colors, // Assign colors array
                        data: data.map(item => ({
                            name: item.response || 'No response',
                            y: item.count,
                            color: item.color || null, // Use color associated with response name
                            dataLabels: {
                                enabled: true,
                                format: '{point.y} ({point.percentage:.1f}%)'
                            }
                        }))
                    }
                ]
            };
        },
        destroyChart() {
            if (this.$refs.chart) {
                this.$refs.chart.destroy();
            }
        },
    }
}
</script>

<style scoped>

</style>
