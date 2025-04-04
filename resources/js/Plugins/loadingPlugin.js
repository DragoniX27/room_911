import loadingState from '@/loadingState.js';
export default {
    install(app) {
      app.config.globalProperties.$showLoading = function() {
        loadingState.isLoading = true;
      };

      app.config.globalProperties.$hideLoading = function() {
        loadingState.isLoading = false;
      };
    }
  };
