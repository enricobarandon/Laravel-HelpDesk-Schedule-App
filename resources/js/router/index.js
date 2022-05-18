import {createRouter, createWebHistory} from 'vue-router';

import SchedulesIndex from '../components/schedules/SchedulesIndex';
import SchedulesCreate from '../components/schedules/SchedulesCreate';
import SchedulesEdit from '../components/schedules/SchedulesEdit';

const routes = [
    {
        path: '/schedules',
        name: 'schedules.index',
        component: SchedulesIndex
    },
    {
        path: '/schedules/create',
        name: 'schedules.create',
        component: SchedulesCreate
    },
    {
        path: '/schedules/edit',
        name: 'schedules.edit',
        component: SchedulesEdit,
        props: true
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})