import { defineStore } from "pinia";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        user: null,
        token: localStorage.getItem("token") || null,
    }),

    actions: {
        test() {
            axios.get("/").then((response) => {
                console.log(response);
            });
        },
        async login(credentials) {
            try {
                await axios.get('/sanctum/csrf-cookie');
                // Login via Laravel Sanctum API
                const response = await axios.post("/login", credentials);

                // Store token
                this.token = response.data.token;
                localStorage.setItem("token", this.token);

                // Fetch user details
                await this.fetchUser();

                return response;
            } catch (error) {
                // Clear any existing token
                this.logout();
                throw error;
            }
        },

        async fetchUser() {
            try {
                // Set default Authorization header
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;

                // Fetch user details
                const response = await axios.get("/user");
                this.user = response.data;
                return response.data;
            } catch (error) {
                this.logout();
                throw error;
            }
        },

        logout() {
            // Remove token from local storage
            localStorage.removeItem("token");

            // Clear user and token in store
            this.user = null;
            this.token = null;

            // Remove Authorization header
            delete axios.defaults.headers.common["Authorization"];
        },
    },

    getters: {
        isAuthenticated() {
            return !!this.user;
        },
    },
});
