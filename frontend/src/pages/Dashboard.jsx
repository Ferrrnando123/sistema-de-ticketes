import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import { Ticket, PlusCircle, HelpCircle } from 'lucide-react';
import './Dashboard.css';

const Dashboard = () => {
  const { user } = useAuth();
  
  return (
    <div className="dashboard-container">
      <header className="page-header">
        <h1>Bienvenido, {user?.nombre?.split(' ')[0]} 👋</h1>
        <p>¿En qué podemos ayudarte el día de hoy?</p>
      </header>
      
      <div className="action-cards">
        <Link to="/nuevo-ticket" className="action-card primary">
          <div className="card-icon"><PlusCircle size={32} /></div>
          <h3>Crear Ticket</h3>
          <p>Abre una nueva solicitud de soporte institucional.</p>
        </Link>
        
        <Link to="/mis-tickets" className="action-card">
          <div className="card-icon blue"><Ticket size={32} /></div>
          <h3>Mis Tickets</h3>
          <p>Consulta el estado de tus solicitudes enviadas.</p>
        </Link>
        
        <Link to="/ayuda" className="action-card">
          <div className="card-icon green"><HelpCircle size={32} /></div>
          <h3>Centro de Ayuda</h3>
          <p>Guías y respuestas a preguntas frecuentes.</p>
        </Link>
      </div>
    </div>
  );
};

export default Dashboard;
