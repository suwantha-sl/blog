// Importing required modules
import { createStore } from 'vuex' // Vuex's createStore function to create a store
import router from './app'; // Importing router to control routing in actions


// Creating a Vuex store
export default createStore({
// State object contains all the reactive data that we want to share across components
state: {
// Initialize isLoggedIn from localStorage to persist login status across page refreshes
isLoggedIn: !!localStorage.getItem('token'),
isSuperAdmin: localStorage.getItem('userType') === 'SA'

},
/*getters: {
    isSuperAdmin: state => {
      return state.isLoggedIn && localStorage.getItem('userType') === 'SA';
    },
},*/
// Mutations are functions that directly mutate the state.
// Each mutation handler gets the entire state tree as the first argument.
mutations: {
// Mutation to set isLoggedIn to true
LOGIN(state) {
state.isLoggedIn = true;
state.isSuperAdmin = localStorage.getItem('userType') === 'SA';
},
// Mutation to set isLoggedIn to false
LOGOUT(state) {
state.isLoggedIn = false;
state.isSuperAdmin = false;
}
},
// Actions are functions that cause side effects and can involve
// asynchronous operations. Actions can also commit mutations.
actions: {
// Login action that commits LOGIN mutation
login({ commit }) {
// login logic here, then:
commit('LOGIN')
},
// Logout action that commits LOGOUT mutation and dispatches navigateToLogin action
logout({ commit, dispatch }) {
commit('LOGOUT');
dispatch('navigateToLogin');
},
// Action to navigate to login route using Vue Router
navigateToLogin() {
    // Clear the token and userType from local storage after successful logout
    localStorage.removeItem('token');
    localStorage.removeItem('userType');
    router.push({ path: '/userlogin' });
}
}
})