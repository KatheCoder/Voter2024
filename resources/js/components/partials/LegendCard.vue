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
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(value, key) in legendData" :key="key" @mouseover="highlight(value.key)">
                        <td class="legend-text">
                            <p>{{ value.abbr_name }}</p>
                        </td>
                        <td class="legend-text">
                            <p>{{ value.name }}</p>
                        </td>
                        <td class="legend-text">
                            <p>{{ value.value }}%</p>
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




</style>
