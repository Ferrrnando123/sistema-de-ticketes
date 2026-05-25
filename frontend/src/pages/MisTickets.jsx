import { useState, useEffect } from 'react';
import { apiFetch } from '../services/api';
import { useNavigate } from 'react-router-dom';
import './MisTickets.css';

const MisTickets = () => {
  const navigate = useNavigate();
  const [tickets, setTickets] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchTickets();
  }, []);

  const fetchTickets = async () => {
    try {
      const resp = await apiFetch('mis-tickets');
      if (resp.success) {
        setTickets(resp.data || []);
      } else {
        setError(resp.message);
      }
    } catch {
      setError('No se pudieron cargar los tickets.');
    } finally {
      setLoading(false);
    }
  };

  const formatearFecha = (fechaISO) => {
    const fecha = new Date(fechaISO);
    return fecha.toLocaleDateString('es-ES', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const getStatusBadge = (estado) => {
    const statusClass = `status-badge status-${estado}`;
    const text = estado.toUpperCase();
    return <span className={statusClass}>{text}</span>;
  };

  return (
    <div className="page-container list-container animate-fade">
      <div className="flex-between">
        <div>
          <h1 className="page-title">Mis Tickets</h1>
          <p className="page-subtitle">Historial de todas tus solicitudes de soporte.</p>
        </div>
        <button onClick={fetchTickets} className="btn btn-outline" disabled={loading}>
          {loading ? 'Actualizando...' : 'Actualizar'}
        </button>
      </div>

      {error && <div className="alert-error">{error}</div>}

      <div className="tickets-grid">
        {loading && tickets.length === 0 ? (
          <div className="loader-container">Cargando tickets...</div>
        ) : tickets.length === 0 ? (
          <div className="empty-state">
            <h3>No tienes tickets creados aún.</h3>
            <p>Si tienes un problema, no dudes en crear un nuevo ticket.</p>
          </div>
        ) : (
          tickets.map((ticket) => (
            <div key={ticket.id} className="ticket-card">
              <div className="ticket-header">
                <span className="ticket-id">#{ticket.id}</span>
                {getStatusBadge(ticket.estado)}
              </div>
              <h3 className="ticket-asunto">{ticket.asunto}</h3>
              <p className="ticket-desc">{ticket.descripcion}</p>

              {ticket.foto_url && (
                <div className="ticket-evidence">
                  <a href={ticket.foto_url} target="_blank" rel="noopener noreferrer">
                    <img src={ticket.foto_url} alt="Evidencia" className="evidence-preview" />
                  </a>
                </div>
              )}

              <div className="ticket-footer" style={{ gap: '0.6rem' }}>
                <div className="ticket-meta">
                  <span className="meta-label">Ubicación: </span>
                  <strong>{ticket.ubicacion}</strong>
                </div>
                <div className="ticket-meta">
                  <span className="meta-label">Fecha: </span>
                  {formatearFecha(ticket.created_at)}
                </div>
                <div style={{ marginTop: '0.65rem', display: 'flex', justifyContent: 'flex-end' }}>
                  <button className="btn btn-outline" onClick={() => navigate(`/tickets/${ticket.id}`)}>
                    Ver detalle / Chat
                  </button>
                </div>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
};

export default MisTickets;

