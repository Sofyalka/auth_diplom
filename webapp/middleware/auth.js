export default function ({ route, redirect, store }) {
  // залогинен ли пользователь
  if(!store.state.auth.loggedIn){
    // console.log(store.state.auth.access_token);
    return redirect({ name: 'login', params: route.params });
  }
}
