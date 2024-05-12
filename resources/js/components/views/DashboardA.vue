<template>
    <navigation-menu></navigation-menu>
        <section class="py-3 py-md-5 py-xl-8 mt-5">

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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4" v-if="decidedFilteredData">
                        <pie-chart title='Have you already decided who you will vote for?' :data="decidedFilteredData" />
                    </div>
                    <div class="col-md-4" v-else><h3 class="text-center">No data to display</h3></div>
                    <div class="col-md-4" v-if="likelyFilteredData" >
                        <pie-chart title='How likely are you to vote on 29 May?' :data="likelyFilteredData" />
                    </div>
                    <div class="col-md-4" v-else><h3 class="text-center">No data to display</h3></div>
                    <div class="col-md-4"  v-if="statusFilteredData">
                        <pie-chart title='Which of the following best describes your voting status?' :data="statusFilteredData" />
                    </div>
                    <div class="col-md-4" v-else><h3 class="text-center">No data to display</h3></div>

                </div>
            </div>
        </section>
</template>

<script>
import NavigationMenu from "../partials/NavigationMenu";
import axios from 'axios';
import PieChart from "../partials/PieChart";


export default {
    name: "DashboardA",
    components: {
        NavigationMenu,
        PieChart
    },
    data() {
        return {
            loading: true,
            selectedGender: '',
            selectedAge: '',
            selectedRace: '',
            selectedMunicipality: '',
            genders: [],
            ages: [],
            races: [],
            municipalities: [],
            chartOptions: null,
            decidedFilteredData: [],
            likelyFilteredData: [],
            statusFilteredData: [],
            overallRespondents:''

        }
    },
    methods: {
        resetFilters() {
            this.selectedGender = '';
            this.selectedAge = '';
            this.selectedRace = '';
            this.selectedMunicipality = '';
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
            axios.get('/api/filtered-data', {
                params: {
                    gender: this.selectedGender,
                    age_groups: this.selectedAge,
                    race: this.selectedRace,
                    municipality: this.selectedMunicipality
                }
            })
                .then(response => {
                    this.decidedFilteredData = response.data.data?.decided;
                    this.likelyFilteredData = response.data.data?.likely;
                    this.statusFilteredData = response.data.data?.voting_status;
                    this.overallRespondents = response.data?.overall;


                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
    },
    mounted() {
        this.fetchFilters();
        this.fetchData();
    },
}

</script>
