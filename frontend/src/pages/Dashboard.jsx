import { useAuth } from '../context/AuthContext';
import { Link } from 'react-router-dom';
import { Ticket, PlusCircle, HelpCircle } from 'lucide-react';
import { BlurFade } from '../components/magicui/blur-fade';
import './Dashboard.css';

const Dashboard = () => {
  const { user } = useAuth();
  
  return (
    <div className="dashboard-container">
      <header className="page-header" style={{ paddingBottom: '1rem', borderBottom: 'none' }}>
        <BlurFade delay={0.15} yOffset={12}>
          <h1 className="text-3xl md:text-4xl font-extrabold text-[#003366] tracking-tight mb-2">
            Bienvenido, <span className="text-[#003366] font-black">{user?.nombre?.split(' ')[0]}</span>
          </h1>
        </BlurFade>
        <BlurFade delay={0.3} yOffset={12}>
          <p className="text-gray-500 font-medium text-lg">¿En qué podemos ayudarte el día de hoy?</p>
        </BlurFade>
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
