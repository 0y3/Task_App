import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle';

import { createApp } from 'vue';
import App from './src/App.vue';
import router from './src/router/index';

const app = createApp(App);
app.use(router);
// app.mount('#app');

router.isReady().then(() => {
    app.mount('#app');
});
