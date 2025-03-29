// create pinia storage payment
import axios from "axios";
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const usePaymentStore = defineStore("payment", {
  state: () => ({
    amount: null,
    type: null,
    items: null,
    summary: null
  }),
  actions: {
    async send({ amount, type }) {
      try {
        // get token from auth store
        const auth = useAuthStore();

        // Set default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${auth.token}`;

        const response = await axios.post("/transactions", {
          amount: amount,
          type: type,
        });

        return response;

      } catch (error) {
        throw error;
      }
    },

    async get(params = {page: 1}) {
      try {
        // get token from auth store
        const auth = useAuthStore();

        // Set default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${auth.token}`;

        const response = await axios.get("/transactions", {
          params: params
        });

        if(response.data.success) {
          this.items = response.data.items;
          this.summary = response.data.summary;
        }

        return response;

      } catch (error) {
        throw error;
      }
    },

    async status({ order_id }) {
      try {
        // get token from auth store
        const auth = useAuthStore();

        // Set default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${auth.token}`;

        const response = await axios.post("/transactions/status", {
          order_id: order_id
        });

        return response;

      } catch (error) {
        throw error;
      }
    }
  },
});
