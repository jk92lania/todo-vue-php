import { createRouter, createWebHistory } from "vue-router";
import HomeView from '@/pages/HomeView'
import CreateView from '@/pages/CreateView'
import UpdateView from '@/pages/UpdateView'
import DeleteView from '@/pages/DeleteView'
import ListView from '@/pages/ListView'
import AboutView from '@/pages/AboutView'

const router = createRouter({
    history : createWebHistory(),
    routes : [
        {
            path : '/',
            name : 'Home',
            component : HomeView
        },
        {
            path : '/create',
            name : 'Create',
            component : CreateView
        },
        {
            path : '/update',
            name : 'Update',
            component : UpdateView
        },
        {
            path : '/delete',
            name : 'Delete',
            component : DeleteView
        },
        {
            path : '/list',
            name : 'List',
            component : ListView
        },
        {
            path : '/about',
            name : 'About',
            component : AboutView
        }
    ],
});

export default router;