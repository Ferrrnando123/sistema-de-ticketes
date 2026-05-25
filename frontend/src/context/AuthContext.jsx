import { createContext, useContext, useState, useEffect } from 'react';
import { apiFetch } from '../services/api';

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const userStorageKey = 'udb_ticket_user';
  const tokenStorageKey = 'udb_ticket_token';

  useEffect(() => {
    checkSession();
  }, []);

  const checkSession = async () => {
    try {
      const response = await apiFetch('check_session');
      if (response.success && response.data) {
        setUser(response.data);
        localStorage.setItem(userStorageKey, JSON.stringify(response.data));
      } else {
        setUser(null);
        localStorage.removeItem(userStorageKey);
        localStorage.removeItem(tokenStorageKey);
      }
    } catch (error) {
      setUser(null);
      localStorage.removeItem(userStorageKey);
      localStorage.removeItem(tokenStorageKey);
    } finally {
      setLoading(false);
    }
  };

  const login = async (email, password) => {
    const response = await apiFetch('procesar_login', {
      method: 'POST',
      body: { usuario: email, password }
    });
    
    if (response.success && response.data) {
      setUser(response.data);
      localStorage.setItem(userStorageKey, JSON.stringify(response.data));
      if (response.data.access_token) {
        localStorage.setItem(tokenStorageKey, response.data.access_token);
      }
      return { success: true };
    }
    return { success: false, message: response.message };
  };

  const logout = async () => {
    try {
      await apiFetch('logout');
      setUser(null);
      localStorage.removeItem(userStorageKey);
      localStorage.removeItem(tokenStorageKey);
    } catch (error) {
      console.error('Error logging out', error);
    }
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
