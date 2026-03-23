import { createContext, useContext, useState, useEffect } from 'react';
import { apiFetch } from '../services/api';

const AuthContext = createContext();

export const useAuth = () => useContext(AuthContext);

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    checkSession();
  }, []);

  const checkSession = async () => {
    try {
      const response = await apiFetch('check_session');
      if (response.success && response.data) {
        setUser(response.data);
      } else {
        setUser(null);
      }
    } catch (error) {
      setUser(null);
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
      return { success: true };
    }
    return { success: false, message: response.message };
  };

  const logout = async () => {
    try {
      await apiFetch('logout');
      setUser(null);
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
