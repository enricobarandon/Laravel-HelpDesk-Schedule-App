require('./bootstrap');

import { createApp } from 'vue';
import router from './router';
import SchedulesIndex from './components/schedules/SchedulesIndex';
import SchedulesCreate from './components/schedules/SchedulesCreate';

createApp({
    components: {
        SchedulesIndex,
        SchedulesCreate
    }
}).use(router).mount('#app');