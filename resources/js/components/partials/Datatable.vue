<template>
    <div class="legend-card">
        <h5>{{ heading }}</h5>
        <div class="table-responsive card">
            <div class="legend-items">
                <div class="card-body">
                    <div v-if="Object.keys(meanRatings).length">
                        <table class="legend-table table table-hover">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th v-for="(party, index) in parties" :key="index">{{ party }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(category, categoryName) in categoryStatements" :key="categoryName">
                                <td>{{ category }}</td>
                                <td v-for="(party, index) in parties" :key="index">{{
                                        meanRatings[party][categoryName]
                                    }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else>
                        <h5>No data to display.</h5>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'Datatable',
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
            parties: []
        };
    },
    computed: {
        categories() {
            return Object.keys(this.meanRatings[Object.keys(this.meanRatings)[0]]).filter(
                key => key !== 'average'
            );
        }
    },
    created() {
        this.parties = Object.keys(this.meanRatings);
    }
};
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
