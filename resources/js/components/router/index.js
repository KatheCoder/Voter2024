import { createWebHistory, createRouter } from "vue-router"
import Home from "../views/Home"
import DashboardA from "../views/DashboardA"
import DashboardB from "../views/DashboardB"
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
    },   {
        path: "/dashboard/B",
        component:DashboardB,
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
