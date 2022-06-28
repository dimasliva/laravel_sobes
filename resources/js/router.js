import Vue from 'vue';
import VueRouter from 'vue-router';
import Index from "./views/Index"
import SingleEquipment from "./views/SingleEquipment";

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: "Home",
        component: Index
    },
    {
        path: '/equipment/:id',
        name: "SingleEquipment",
        component: SingleEquipment
    },

]
export default new VueRouter({
    mode: "history",
    routes
});
