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
                    <div class="col-md-12" v-if="!loading">
                        <datatable heading="Factors influencing your voting choice? (On a scale of 1 to 10, where 1 means least important and 10 means most important)."
                                   :categoryStatements="factorsCategoryStatements"
                                   :meanRatings="filteredData" ></datatable>

                    </div>
                    <div class="col-md-12"   v-if="!secondLoading">

                            <datatable heading="Reasons for not voting or being unsure about voting ? (On a scale of 1 to 10, where 1 means a Weak Reason and 10 means a Strong Reason)."
                                       :categoryStatements="likelyCategoryStatements"
                                       :meanRatings="secondTable" ></datatable>

                    </div>
                </div>

            </div>
        </section>
</template>

<script>
import NavigationMenu from "../partials/NavigationMenu";
import axios from 'axios';
import Datatable from "../partials/Datatable";


export default {
    name: "DashboardD",
    components: {
        NavigationMenu,
        Datatable
    },
    data() {
        return {
            loading: true,
            secondLoading: true,
            selectedGender: '',
            selectedAge: '',
            selectedRace: '',
            selectedMunicipality: '',
            genders: [],
            ages: [],
            races: [],
            municipalities: [],
            chartOptions: null,
            filteredData: [],
            secondTable: [],
            likelyFilteredData: [],
            statusFilteredData: [],
            parties: [],
            overallRespondents:'',
            meanPercentageRatings: {},
            factorsCategoryStatements: {
                economy: 'State of the economy (jobs, economic growth, standard of living)',
                infrastructure: 'Infrastructure (roads, healthcare facilities, power and water supply, quality buildings and public spaces)',
                transportation: 'Transportation (reliability, safety, affordability, extensive network coverage, accessibility)',
                safety: 'Safety (effective policing, arrest and conviction rates, trust in law enforcement, community freedom and safety)',
                combating_corruption: 'Combating corruption and promoting moral and ethical values',
                service_delivery: 'Performance and service delivery (efficiency, decisiveness, problem-solving capabilities)',
                historical_success: 'Historical success and track record of a party',
                brand_values: 'Brand values and tradition',
                personal_liking: 'Gut feeling and personal liking for the party'
            },
            likelyCategoryStatements: {
                lost_faith: 'Lost faith in the elections, government, and ruling party',
                empty_promises: 'Tired of empty promises ',
                changes: 'Previous votes did not affect any change ',
                unaddressed_by_government: 'Critical issues such as loadshedding and unemployment continue to go unaddressed by government ',
                always_wins: 'Same party always wins ',
                good_enough: 'No one is good enough ',
                queues: 'The queues are too long',
                too_busy: 'I am too busy',
            }

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
            this.loading = true;
            axios.get('/api/means', {
                params: {
                    gender: this.selectedGender,
                    age_groups: this.selectedAge,
                    race: this.selectedRace,
                    municipality: this.selectedMunicipality
                }
            })
                .then(response => {
                    this.filteredData = response.data.meanRatings;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.loading = false;
                });
            this.secondLoading = true;
            axios.get('/api/meansT', {
                params: {
                    gender: this.selectedGender,
                    age_groups: this.selectedAge,
                    race: this.selectedRace,
                    municipality: this.selectedMunicipality
                }
            })
                .then(response => {
                    this.secondTable = response.data.meanRatings;
                    this.secondLoading = false;
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    this.secondLoading = false;
                });
        }
    },
    mounted() {
        this.fetchFilters();
        this.fetchData();
    },
}

</script>
