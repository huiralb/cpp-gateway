// create pinia storage payment
import axios from "axios";
import { defineStore } from "pinia";
import { useAuthStore } from "./auth";

export const usePaymentStore = defineStore("payment", {
  state: () => ({
    amount: null,
    type: null,
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

    async get() {
      try {
        // get token from auth store
        const auth = useAuthStore();

        // Set default Authorization header
        axios.defaults.headers.common["Authorization"] = `Bearer ${auth.token}`;

        const response = await axios.get("/transactions");

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
