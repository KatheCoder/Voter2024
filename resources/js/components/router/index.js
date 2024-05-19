import { createWebHistory, createRouter } from "vue-router"
import Home from "../views/Home"
import DashboardA from "../views/DashboardA"
import SecondView from "../views/SecondView"
import DashboardC from "../views/DashboardC"
import DashboardD from "../views/DashboardD"
import NotFound from "../views/PageNotFound"


const routes = [
    // {
    //     path: "/Home",
    //     name: "Home",
    //     component: Home,
    // },
    {
        path: "/dashboard/A",
        component:DashboardA,
        name:"DashboardA",
    },
    {
        path: "/dashboard/D",
        component:DashboardD,
        name:"ThirdView",
    },
    {
        path: "/dashboard/B",
        component:DashboardC,
        name:"DashboardB",
    },
    {
        path: "/logout",
        redirect: "/"
    },
    {
        path: "/:pathMatch(.*)*",
        component:NotFound,
        name:"NotFound",
    },
]



const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
