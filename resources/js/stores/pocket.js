import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const usePocketStore = defineStore("pocket", {
  state: () => ({
    balance: 0,
  }),
  actions: {
    async get() {
      try {
        // get token from auth store
        const auth = useAuthStore();

        // Set default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${auth.token}`;

        const response = await axios.get("/pocket");

        if(response.data.success) {
          this.balance = response.data.amount;
        }

        return response;

      } catch (error) {
        throw error;
      }
    }
  }
});
