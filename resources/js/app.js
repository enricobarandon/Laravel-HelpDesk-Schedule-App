require('./bootstrap');

import { createApp } from 'vue';
import router from './router';
import SchedulesIndex from './components/schedules/SchedulesIndex';
import SchedulesCreate from './components/schedules/SchedulesCreate';
import VueSweetalert2 from 'vue-sweetalert2';

createApp({
    components: {
        SchedulesIndex,
        SchedulesCreate
    }
}).use(router).use(VueSweetalert2).mount('#app');