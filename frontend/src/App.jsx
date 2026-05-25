import { useState, useEffect } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import './index.css';

// Placeholder Pages
import Login from './pages/Login';
import Dashboard from './pages/Dashboard';
import NuevoTicket from './pages/NuevoTicket';
import MisTickets from './pages/MisTickets';
import PanelSoporte from './pages/PanelSoporte';
import Ayuda from './pages/Ayuda';
import TicketDetalle from './pages/TicketDetalle';

// Main Layout with Navbar
import Layout from './components/Layout';

const ProtectedRoute = ({ children, requireAdmin }) => {
  const { user, loading } = useAuth();
  
  if (loading) {
    return <div className="loader-container">Cargando...</div>;
  }
  
  if (!user) {
    return <Navigate to="/login" replace />;
  }
  
  if (requireAdmin && user.rol !== 'admin') {
    return <Navigate to="/dashboard" replace />;
  }
  
  return children;
};

function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      
      <Route path="/" element={
        <ProtectedRoute>
          <Layout />
        </ProtectedRoute>
      }>
        <Route index element={<Navigate to="/dashboard" replace />} />
        <Route path="dashboard" element={<Dashboard />} />
        <Route path="nuevo-ticket" element={<NuevoTicket />} />
        <Route path="mis-tickets" element={<MisTickets />} />
        <Route path="tickets/:id" element={<TicketDetalle />} />
        <Route path="ayuda" element={<Ayuda />} />
        
        {/* Admin only route */}
        <Route path="panel-soporte" element={
          <ProtectedRoute requireAdmin={true}>
            <PanelSoporte />
          </ProtectedRoute>
        } />
      </Route>
      
      {/* Catch all */}
      <Route path="*" element={<Navigate to="/dashboard" replace />} />
    </Routes>
  );
}

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <AppRoutes />
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
