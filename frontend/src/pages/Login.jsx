import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { BlurFade } from '../components/magicui/blur-fade';
import './Login.css'; // Mantenemos el CSS original para el formulario compacto

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
    <div className="min-h-screen w-full flex bg-[#f4f6f8] font-sans selection:bg-[#003366] selection:text-white">
      {/* 
        SECCIÓN IZQUIERDA (Estilo Moderno Split-Screen) 
        El banner que te gustó, mantenido intacto.
      */}
      <div className="hidden lg:flex w-1/2 bg-[#003366] relative overflow-hidden items-center justify-center">
        <div className="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.08)_0%,transparent_50%)]" />
        <div className="absolute bottom-[-15%] right-[-10%] w-[600px] h-[600px] rounded-full bg-[#ffcc00] opacity-10 blur-[100px]" />

        <BlurFade delay={0.2} inView className="z-10 text-left px-16 max-w-2xl">
          <h1 className="text-4xl xl:text-5xl font-extrabold text-white mb-6 tracking-tight leading-tight">
            Universidad<br className="mb-2" />Don Bosco
          </h1>
          <p className="text-blue-100 text-lg opacity-90 leading-relaxed max-w-md">
            Plataforma centralizada y moderna para la gestión de tickets de soporte técnico institucional.
          </p>
        </BlurFade>
      </div>

      {/* 
        SECCIÓN DERECHA (El formulario original pero alineado a la derecha) 
      */}
      <div className="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-12 relative bg-[#f4f6f8]">

        {/* Título sólo para móviles */}
        <div className="text-center mb-6 lg:hidden">
          <h1 className="text-2xl font-bold text-[#003366]">Universidad Don Bosco</h1>
        </div>

        {/* 
          Formulario Original: ¡Utilizando explícitamente las clases de Login.css!
          Le quitamos el shadow y el border general para que se acople limpio al fondo 
          o se lo dejamos como una tarjeta sutil, según tu CSS original.
        */}
        <div className="login-card" style={{ width: '100%', maxWidth: '380px', margin: '0 auto', boxShadow: '0 4px 12px rgba(0,0,0,0.05)', border: 'none' }}>
          <div className="login-header">
            <h2 style={{ color: '#003366', fontWeight: 'bold' }}>Bienvenido</h2>
            <p>Inicia sesión para continuar</p>
          </div>

          {error && <div className="alert-error" style={{ fontSize: '0.85rem' }}>{error}</div>}

          <form onSubmit={handleSubmit} className="login-form">
            <div className="form-group">
              <label htmlFor="usuario">Usuario (Correo Institucional)</label>
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

            <button
              type="submit"
              className="btn btn-primary login-btn"
              disabled={isLoading}
              style={{ backgroundColor: '#003366', borderColor: '#003366', marginTop: '10px' }}
            >
              {isLoading ? <span className="loader"></span> : 'Iniciar Sesión'}
            </button>
          </form>

          <div className="login-footer" style={{ marginTop: '20px' }}>
            <p>¿No tienes cuenta? Contacta al administrador.</p>
          </div>
        </div>

        {/* Footer pequeño en la derecha para reemplazar el enorme bloque azul de abajo */}
        <div className="mt-8 text-center">
          <p className="text-xs text-gray-400 font-medium tracking-wide">
            © 2026 Depto. Sistemas Informáticos
          </p>
        </div>

      </div>
    </div>
  );
};

export default Login;
