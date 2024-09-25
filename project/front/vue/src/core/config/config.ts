
interface RootConfig {
  environment: string;
  isProduction: boolean;
  nombreAPP: string;
  version: string;
  apiURL: string;
  loginURL: string;
  logoutURL: string;
  baseURL: string;
  initURL: string;
  prefijo: string;
}


export const rootConfig: RootConfig = {
  nombreAPP: import.meta.env.VITE_NOMBRE_APP,
  baseURL: import.meta.env.BASE_URL,
  apiURL: import.meta.env.VITE_API_URL,
  loginURL: import.meta.env.VITE_LOGIN_URL,
  logoutURL: import.meta.env.VITE_LOGOUT_URL,
  initURL: import.meta.env.VITE_INIT_URL,
  prefijo: import.meta.env.VITE_PRE_FIJO,
  environment: "",
  isProduction: false,
  version: ""
};
