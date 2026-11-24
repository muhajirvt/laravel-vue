import axios from "axios";
import { ref } from "vue";

export const LOGIN_PATH = "/login";
export const DASHBOARD_PATH = "/dashboard";

export function useAuthSession() {
  const isAuthenticated = ref(false);
  const isChecking = ref(true);
  const adminUser = ref(null);

  const redirectTo = (path) => {
    if (window.location.pathname !== path) {
      window.location.replace(path);
    }
  };

  const getStoredToken = () => {
    const savedToken = localStorage.getItem("access_token");
    if (savedToken) return savedToken;

    const sessionToken = sessionStorage.getItem("access_token");
    if (sessionToken) {
      localStorage.setItem("access_token", sessionToken);
      sessionStorage.removeItem("access_token");
    }

    return sessionToken;
  };

  const applyToken = (token) => {
    localStorage.setItem("access_token", token);
    sessionStorage.removeItem("access_token");
    axios.defaults.headers.common.Authorization = `Bearer ${token}`;
    isAuthenticated.value = true;
  };

  const clearSession = (redirect = true) => {
    localStorage.removeItem("access_token");
    sessionStorage.removeItem("access_token");
    delete axios.defaults.headers.common.Authorization;
    adminUser.value = null;
    isAuthenticated.value = false;

    if (redirect) {
      redirectTo(LOGIN_PATH);
    }
  };

  const authenticate = (token) => {
    applyToken(token);
    redirectTo(DASHBOARD_PATH);
  };

  const logout = async () => {
    try {
      await axios.post("/api/auth/logout");
    } catch {
      window.alert("The server could not revoke this token. Your local session has been cleared.");
    } finally {
      clearSession();
    }
  };

  const checkSession = async () => {
    const token = getStoredToken();

    if (!token) {
      clearSession(false);
      isChecking.value = false;
      redirectTo(LOGIN_PATH);
      return;
    }

    applyToken(token);

    try {
      const response = await axios.get("/api/auth/current-user");
      adminUser.value = response.data.data;
      isChecking.value = false;
      redirectTo(DASHBOARD_PATH);
    } catch {
      clearSession(false);
      isChecking.value = false;
      redirectTo(LOGIN_PATH);
    }
  };

  return {
    isAuthenticated,
    isChecking,
    adminUser,
    authenticate,
    checkSession,
    clearSession,
    logout,
  };
}
