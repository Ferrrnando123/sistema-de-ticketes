import { Outlet, Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { Bell, LogOut, Home, Ticket, HelpCircle, LayoutDashboard } from 'lucide-react';
import { AnimatedThemeToggler } from './magicui/animated-theme-toggler';
import { BlurFade } from './magicui/blur-fade';
import { apiFetch } from '../services/api';
import { useEffect, useMemo, useRef, useState } from 'react';
import './Layout.css';

const Layout = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [notifOpen, setNotifOpen] = useState(false);
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [notifs, setNotifs] = useState([]);
  const notifWrapRef = useRef(null);
  const unreadCount = useMemo(() => notifs.filter(n => !n.leida).length, [notifs]);

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  const loadNotifs = async () => {
    try {
      const resp = await apiFetch('notificaciones_listar', { method: 'GET' });
      if (resp.success) setNotifs(resp.data || []);
    } catch {
      // silencioso
    }
  };

  useEffect(() => {
    loadNotifs();
    const t = setInterval(() => loadNotifs(), 25000);
    return () => clearInterval(t);
  }, []);

  useEffect(() => {
    const onClickOutside = (event) => {
      if (!notifOpen) return;
      if (notifWrapRef.current && !notifWrapRef.current.contains(event.target)) {
        setNotifOpen(false);
      }
    };

    document.addEventListener('mousedown', onClickOutside);
    return () => document.removeEventListener('mousedown', onClickOutside);
  }, [notifOpen]);

  useEffect(() => {
    const onResize = () => {
      if (window.innerWidth > 768) setMobileMenuOpen(false);
    };
    window.addEventListener('resize', onResize);
    return () => window.removeEventListener('resize', onResize);
  }, []);

  const closeMobileMenu = () => setMobileMenuOpen(false);

  const markRead = async (id) => {
    try {
      await apiFetch('notificaciones_marcar_leida', { method: 'PATCH', body: { id } });
      setNotifs((prev) => prev.map((n) => (n.id === id ? { ...n, leida: true } : n)));
    } catch {
      // noop
    }
  };

  return (
    <div className="layout-container">
      <aside className={`sidebar ${mobileMenuOpen ? 'open' : ''}`}>
        <div className="sidebar-header">
          <h2>UDB Tickets</h2>
          <span className="badge">{user?.rol === 'admin' ? 'Admin' : 'Alumno'}</span>
        </div>
        
        <nav className="sidebar-nav">
          <Link to="/dashboard" className="nav-link" onClick={closeMobileMenu}>
            <Home size={20} /> Dashboard
          </Link>
          <Link to="/mis-tickets" className="nav-link" onClick={closeMobileMenu}>
            <Ticket size={20} /> Mis Tickets
          </Link>
          <Link to="/nuevo-ticket" className="nav-link" onClick={closeMobileMenu}>
            <span className="plus-icon">+</span> Nuevo Ticket
          </Link>
          <Link to="/ayuda" className="nav-link" onClick={closeMobileMenu}>
            <HelpCircle size={20} /> Ayuda
          </Link>
          
          {user?.rol === 'admin' && (
            <Link to="/panel-soporte" className="nav-link admin-link" onClick={closeMobileMenu}>
              <LayoutDashboard size={20} /> Panel Soporte
            </Link>
          )}
        </nav>
        
        <div className="sidebar-footer">
          <div className="user-info user-info-row">
            <div className="user-main">
              <div className="avatar">{user?.nombre?.charAt(0).toUpperCase()}</div>
              <div className="user-details">
                <strong>{user?.nombre}</strong>
                <small>{user?.email}</small>
              </div>
            </div>
            <div className="user-right-controls">
              <div className="notif-wrap" ref={notifWrapRef}>
                <button
                  type="button"
                  className="notif-btn"
                  onClick={() => {
                    setNotifOpen(v => !v);
                    if (!notifOpen) loadNotifs();
                  }}
                  aria-label="Notificaciones"
                >
                  <Bell size={18} />
                  {unreadCount > 0 && <span className="notif-badge">{unreadCount > 9 ? '9+' : unreadCount}</span>}
                </button>

                {notifOpen && (
                  <BlurFade delay={0.02} yOffset={6} inView>
                    <div className="notif-panel">
                      <div className="notif-title">
                        <span>Notificaciones</span>
                        <button type="button" className="notif-link" onClick={loadNotifs}>
                          Actualizar
                        </button>
                      </div>

                      {notifs.length === 0 ? (
                        <div className="notif-empty">Sin notificaciones por ahora.</div>
                      ) : (
                        <div className="notif-list">
                          {notifs.map((n) => (
                            <button
                              key={n.id}
                              type="button"
                              className={`notif-item ${n.leida ? 'read' : 'unread'}`}
                              onClick={() => {
                                if (!n.leida) markRead(n.id);
                                if (n.ticket_id) navigate(`/tickets/${n.ticket_id}`);
                                setNotifOpen(false);
                              }}
                            >
                              <div className="notif-item-title">{n.titulo}</div>
                              <div className="notif-item-body">{n.cuerpo}</div>
                            </button>
                          ))}
                        </div>
                      )}
                    </div>
                  </BlurFade>
                )}
              </div>

              <AnimatedThemeToggler />
            </div>
          </div>
          <button onClick={handleLogout} className="logout-btn" style={{ marginTop: '0.75rem' }}>
            <LogOut size={18} /> Salir
          </button>
        </div>
      </aside>
      
      {mobileMenuOpen && <button className="mobile-overlay" onClick={closeMobileMenu} aria-label="Cerrar menu" />}

      <main className="main-content">
        <header className="mobile-header">
          <button
            type="button"
            className="mobile-menu-btn"
            onClick={() => setMobileMenuOpen((v) => !v)}
            aria-label="Abrir menu"
          >
            ☰
          </button>
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
