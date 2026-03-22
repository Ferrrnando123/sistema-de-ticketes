import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './Login.css';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setIsLoading(true);

    try {
      const result = await login(email, password);
      if (result.success) {
        navigate('/dashboard');
      } else {
        setError(result.message || 'Credenciales incorrectas');
      }
    } catch (err) {
      setError('Ocurrió un error de conexión');
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <div className="login-header">
          <h2>TicketSystem</h2>
          <p>Inicia sesión para continuar</p>
        </div>
        
        {error && <div className="alert-error">{error}</div>}
        
        <form onSubmit={handleSubmit} className="login-form">
          <div className="form-group">
            <label htmlFor="usuario">Usuario (Correo UDB)</label>
            <input
              type="text"
              id="usuario"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              placeholder="ejemplo@alumno.udb.edu.sv"
              required
            />
          </div>
          
          <div className="form-group">
            <label htmlFor="password">Contraseña</label>
            <input
              type="password"
              id="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="••••••••"
              required
            />
          </div>
          
          <button type="submit" className="btn btn-primary login-btn" disabled={isLoading}>
            {isLoading ? <span className="loader"></span> : 'Ingresar'}
          </button>
        </form>
        
        <div className="login-footer">
          <p>¿No tienes cuenta? Contacta al administrador.</p>
        </div>
      </div>
    </div>
  );
};

export default Login;
