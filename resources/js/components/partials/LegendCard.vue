<template>
    <div class="legend-card">
        <h3>{{ title }}</h3>
        <div class="legend-items card">
            <div class="card-body">
                <table class="legend-table table table-hover">
                    <thead>
                    <tr>
                        <th>Abbr.</th>
                        <th>Party Name</th>
                        <th>% GT sum of weight</th>
                        <th>Total Weight</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(value, key) in legendData" :key="key" @mouseover="highlight(value.key)">
                        <td class="legend-text">
                            <p>{{ value.key }}</p>
                        </td>
                        <td class="legend-text">
                            <p>{{ value.value }}</p>
                        </td>
                        <td class="legend-text">
                            <p>{{ value.data }}%</p>
                        </td>
                        <td class="legend-text">
                            <p>{{ value.sum.total_weight }}</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    name: "LegendCard",
    props: {
        title: {
            type: String,
            required: true
        },
        legendData: {
            type: Object,
            required: true
        }
    },
    methods: {
        highlight(value) {
            this.$emit('highlight', value);
        }
    }
}
</script>

<style scoped>
.legend-card {
    margin-top: 20px;
}

.legend-items {
    display: flex;
    flex-wrap: wrap;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-right: 20px;
}

/*.legend-color {*/
/*    width: 10px;*/
/*    height: 10px;*/
/*    margin-right: 5px;*/
/*    border-radius: 50%;*/
/*}*/

.legend-label {
    font-size: 14px;
}
</style>
