<template>
    <navigation-menu></navigation-menu>
    <section class="py-3 py-md-5 py-xl-8">
        <div class="container">
            <button class="btn btn-primary" @click="resetFilters">Reset Filters</button>

            <!-- Filter options -->
            <div class="row align-items-center">
                <div class="col-md-3 mb-3 col-6">
                    <label class="form-label">Gender:</label>
                    <select class="form-select" v-model="selectedGender" @change="fetchData">
                        <option value="">All</option>
                        <option v-for="gender in genders" :value="gender">{{ gender }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 col-6">
                    <label class="form-label">Age:</label>
                    <select class="form-select" v-model="selectedAge" @change="fetchData">
                        <option value="">All</option>
                        <option v-for="age in ages" :value="age">{{ age }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 col-6">
                    <label class="form-label">Race:</label>
                    <select class="form-select" v-model="selectedRace" @change="fetchData">
                        <option value="">All</option>
                        <option v-for="race in races" :value="race">{{ race }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 col-6">
                    <label class="form-label">Municipality:</label>
                    <select class="form-select" v-model="selectedMunicipality" @change="fetchData">
                        <option value="">All</option>
                        <option v-for="municipality in municipalities" :value="municipality">{{ municipality }}</option>
                    </select>
                </div>

            </div>

        </div>
        <div class="container-fluid" v-if="nationalChartData">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <h2>If general elections were taking place today, which party would you vote for?</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="legend-card">
                        <div class="legend-items card">
                            <div class="card-body">
                                <line-chart-plot title="National" :chartData="this.nationalChartData" :dates="this.nationalDates"/>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="legend-card">
                        <div class="legend-items card">
                            <div class="card-body">
                                <line-chart-plot title="Provincial" :chartData="this.provincialChartData" :dates="this.provincialDates"/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</template>

<script>
import NavigationMenu from "../partials/NavigationMenu";
import LineChartPlot from "../partials/LineChartPlot";

import axios from "axios";

export default {
    name: "DashboardC",
    components: {LineChartPlot, NavigationMenu},
    data() {
        return {
            filters: {
                selectedGender: this.selectedGender,
                selectedAge: this.selectedAge,
                selectedRace: this.selectedRace,
                selectedMunicipality: this.selectedMunicipality
            },
            selectedGender: '',
            selectedAge: '',
            selectedRace: '',
            selectedMunicipality: '',
            national: [],
            provincial: [],
            genders: [],
            ages: [],
            races: [],
            nationalFilteredData: [],
            nationalFilteredColors: [],
            provincialFilteredData: [],
            provincialFilteredColors: [],
            nationalLabelsFilteredData: [],
            provincialLabelsFilteredData: [],
            municipalities: [],
            filterData: {},
            highlightedValue: null, // Store highlighted value
            provincialChartData :[],
            provincialDates :[],
            nationalChartData :[],
            nationalDates :[],


        }
    },
    methods: {
        highlightDataPoint(value) {
            this.highlightedValue = value;
        },
        resetFilters() {
            this.selectedGender = '';
            this.selectedAge = '';
            this.selectedRace = '';
            this.selectedMunicipality = '';
            this.filters.selectedGender = '';
            this.filters.selectedAge = '';
            this.filters.selectedRace = '';
            this.filters.selectedMunicipality = '';
            this.fetchData();
        },
        fetchFilters() {
            axios.get('/api/filters')
                .then(response => {
                    this.genders = response.data.genders;
                    this.ages = response.data.age_groups;
                    this.races = response.data.races;
                    this.municipalities = response.data.municipality;
                })
                .catch(error => {
                    console.error('Error fetching filters:', error);
                });
        },
        fetchData() {
            this.fetchNationalChartData();
            this.fetchProvincialChartData();
        },
        fetchNationalChartData() {
            axios
                .get('/api/line', {
                    params: {
                        type: 'national',
                        gender: this.selectedGender,
                        age_groups: this.selectedAge,
                        race: this.selectedRace,
                        municipality: this.selectedMunicipality
                    }
                })
                .then((response) => {
                    this.nationalChartData = response.data.results;
                    this.nationalDates = response.data.dates;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        fetchProvincialChartData() {
            axios
                .get('/api/line', {
                    params: {
                        type: 'provincial',
                        gender: this.selectedGender,
                        age_groups: this.selectedAge,
                        race: this.selectedRace,
                        municipality: this.selectedMunicipality
                    }
                })
                .then((response) => {
                    this.provincialChartData = response.data.results;
                    this.provincialDates = response.data.dates;
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    },
    mounted() {
        this.fetchFilters();
        this.fetchData();
    },
}

</script>
<style>
.chart-container {
    width: 1500px; /* Set the desired width */
    overflow-x: auto; /* Enable horizontal scrolling */
}
</style>
