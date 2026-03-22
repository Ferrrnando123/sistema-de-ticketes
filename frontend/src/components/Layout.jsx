import { Outlet, Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { LogOut, Home, Ticket, HelpCircle, LayoutDashboard } from 'lucide-react';
import './Layout.css';

const Layout = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="layout-container">
      <aside className="sidebar">
        <div className="sidebar-header">
          <h2>UDB Tickets</h2>
          <span className="badge">{user?.rol === 'admin' ? 'Admin' : 'Alumno'}</span>
        </div>
        
        <nav className="sidebar-nav">
          <Link to="/dashboard" className="nav-link">
            <Home size={20} /> Dashboard
          </Link>
          <Link to="/mis-tickets" className="nav-link">
            <Ticket size={20} /> Mis Tickets
          </Link>
          <Link to="/nuevo-ticket" className="nav-link">
            <span className="plus-icon">+</span> Nuevo Ticket
          </Link>
          <Link to="/ayuda" className="nav-link">
            <HelpCircle size={20} /> Ayuda
          </Link>
          
          {user?.rol === 'admin' && (
            <Link to="/panel-soporte" className="nav-link admin-link">
              <LayoutDashboard size={20} /> Panel Soporte
            </Link>
          )}
        </nav>
        
        <div className="sidebar-footer">
          <div className="user-info">
            <div className="avatar">{user?.nombre?.charAt(0).toUpperCase()}</div>
            <div className="user-details">
              <strong>{user?.nombre}</strong>
              <small>{user?.email}</small>
            </div>
          </div>
          <button onClick={handleLogout} className="logout-btn">
            <LogOut size={18} /> Salir
          </button>
        </div>
      </aside>
      
      <main className="main-content">
        <header className="mobile-header">
           <h2>UDB Tickets</h2>
        </header>
        <div className="content-wrapper">
          <Outlet />
        </div>
      </main>
    </div>
  );
};

export default Layout;
