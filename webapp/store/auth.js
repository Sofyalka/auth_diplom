const state = () => ({
  loggedIn: false, // true | false
  access_token: null,
});

const getters = {
  loggedIn: state => state.loggedIn,
};

const mutations = {
  'access_token:set'(state, { access_token, expired }) {
    state.access_token = access_token;
  },
  'loggedIn:set'(state, loggedIn) {
    state.loggedIn = loggedIn;
  },
};

const actions = {
  async login({ commit, dispatch, state }, { username, password }) {
    return this.$axios.get('/api/auth/register/login').then((resp) => {
      commit('loggedIn:set', true);
      commit('access_token:set', 'token');
    });
  },

  async register({ commit, dispatch, state }, { username, email }) {
    return this.$axios.post('/api/auth/register', {login: username, email: email}).then((resp) => {
      console.log(resp);
    });
  },

  async complete({ commit, dispatch }, data) {
    const resp = this.$axios.get('/api/auth/register/complete');
    console.log(resp);
  },

  async refresh({ commit, dispatch }, data) {
    const resp = this.$axios.get('/api/auth/token/refresh');
    console.log(resp);
  },

  async logout({ commit }){
    commit('loggedIn:set', false);
    return this.$axios.get('/logout');
  }

};

export default {
  mutations,
  actions,
  getters,
  state,
};
