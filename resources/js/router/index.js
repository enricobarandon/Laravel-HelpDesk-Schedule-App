import {createRouter, createWebHistory} from 'vue-router';

import SchedulesIndex from '../components/schedules/SchedulesIndex';
import SchedulesCreate from '../components/schedules/SchedulesCreate';
import SchedulesEdit from '../components/schedules/SchedulesEdit';
import GroupsActive from '../components/groups/GroupsActive';
import GroupsDeactivated from '../components/groups/GroupsDeactivated';

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
    },
    {
        path: '/groups/active',
        name: 'groups.active',
        component: GroupsActive
    },
    {
        path: '/groups/deactivated',
        name: 'groups.deactivated',
        component: GroupsDeactivated
    }
]

export default createRouter({
    history: createWebHistory(),
    routes
})